<?php

class WP_E_Notice extends WP_E_Model {
    
   
    
    /** @var array $_data  */
	protected $_esigdata = array();
	/** @var bool $_dirty When something changes */
	protected $_change = false;
	
	private $session_id='';
    
    public function __construct()
    {
        parent::__construct();
        
        $this->session_id= $this->esig_session_id();
    }
    
    
    /**
     * Set a session variable
     *
     * @param string $key
     * @param mixed $value
     */
    
    public function set( $key, $value ) 
    {
        
        if ( $value !== $this->get( $key ) ) 
        {
            $this->_esigdata[ sanitize_key( $key ) ] = maybe_serialize( $value );
            $this->_change = true;
            
            $this->save_data(); 
        }
    }
    
    /**
     * save_data function.
     */
    public function save_data() {
        // Dirty if something changed - prevents saving nothing new
        if ( $this->_change ) {
    
            if ( false === get_transient('esig-'.$this->session_id ) )
             {
                 set_transient('esig-'.$this->session_id,$this->_esigdata,12*60);  
            } else {
                delete_transient('esig-'.$this->session_id);
                set_transient('esig-'.$this->session_id,$this->_esigdata,12*60);  
            }
            // Mark session clean after saving
            $this->_change = false;
        }
    }
    
    /**
     * Get a session variable
     *
     * @param string $key
     * @param  mixed $default used if the session variable isn't set
     * @return mixed value of session variable
     */
    public function get( $key) {
        
        $key = sanitize_key( $key );
        
        if(isset( $this->_esigdata[ $key ] ))
        {
            return maybe_unserialize( $this->_esigdata[ $key ] )  ; 
        }
        else 
        {
            $notices = get_transient('esig-'.$this->session_id  );
            
            return $notices[$key];
        }
        
    }
    
    /**
     * Get a session variable
     *
     * @param string $key
     * @param  mixed $default used if the session variable isn't set
     * @return mixed value of session variable
     */
    public function get_all() 
    {
    
        if(!empty( $this->_esigdata))
        {
            echo "here";
            exit ;
            return maybe_unserialize( $this->_esigdata)  ;
        }
        else
        {
            
            $notices = get_transient('esig-'.$this->session_id  );
           
            return $notices;
        }
    
    }
    
    public function esig_print_notice()
    {
          
          $all_notice = $this->get_all(); 
          $alert_msg ='';
          if(empty($all_notice))
          {
              return false ; 
          }
          foreach ($all_notice as $key =>$value)
          {
              
              $view = new WP_E_View();
              
              $data = array(
                    "alert-type"=> $key ,
                  "alert-msg"=> $value ,
                  );
              
            $alert_msg .= $view->renderPartial('alert-msg',$data) ;   
          }
           // deleting transient after printing notice 
          delete_transient('esig-'.$this->session_id);
          return $alert_msg ;
        
    }
    /**
	 * Generate a unique customer ID for guests, or return user ID if logged in.
	 *
	 * Uses Portable PHP password hashing framework to generate a unique cryptographically strong ID.
	 *
	 * @return int|string
	 */
    
	public function esig_session_id() 
	{
		if ( is_user_logged_in() ) {
			return get_current_user_id();
		} else {
		    if ( empty( $_COOKIE['esig_session_id'] ) ) {
		      
        			require_once( ABSPATH . 'wp-includes/class-phpass.php');
        			$hasher = new PasswordHash( 8, false );
        			
        			$esig_session_id =md5( $hasher->get_random_bytes( 32 ) );
        			$this->esig_setcookie('esig_session_id',$esig_session_id);
        			return $esig_session_id;
			
			}
			else 
			{
			     return $_COOKIE['esig_session_id'];
			}
		}
	}
    
	
	public function esig_setcookie( $name, $value, $expire = 0, $secure = false ) 
	{
	    if ( ! headers_sent() ) {
	        setcookie( $name, $value,60 * 60 * 1, COOKIEPATH, COOKIE_DOMAIN, $secure );
	    } elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	        headers_sent( $file, $line );
	        trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
	    }
	}
	
    
}