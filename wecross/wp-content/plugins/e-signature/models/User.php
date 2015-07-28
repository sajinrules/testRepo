<?php
/**
 * User Model
 * 
 * @since 0.1.0
 */
class WP_E_User extends WP_E_Model{

	public $userData;

	public function __construct(){
		parent::__construct();
		$this->table = $this->table_prefix . "users";

		$this->signature = new WP_E_Signature;
		$this->settings = new WP_E_Setting();
	}
	
	/***
	 *  get E-signature user full name 
	 *  return string 
	 *  Since 1.0.3 
	 * */
	
	public function getUserFullName($id=null){
	
		$id = !$id ? $this->getCurrentUserID() : $id;
		return $this->wpdb->get_var("SELECT first_name FROM " . $this->table . " WHERE user_id=" . $id);
	}
	
	
	public function getUserLastName($id=null){
	
		$id = !$id ? $this->getCurrentUserID() : $id;
		return $this->wpdb->get_var("SELECT last_name FROM " . $this->table . " WHERE user_id=" . $id);
	}
	
	public function getUserID($email=null){
	
		//$id = !$id ? $this->getCurrentUserID() : $id;
		return $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT user_id FROM " . $this->table . " WHERE user_email = %s", $email
			));
	}
	

public function wp_user_not_exists($email=null){
	
		//$id = !$id ? $this->getCurrentUserID() : $id;
		$wp_user_id= $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT wp_user_id FROM " . $this->table . " WHERE user_email = %s", $email
			));
			
	     if($wp_user_id == 0)
	     {
		 	return false ;
		 }
		 else 
		 {
		 	return $wp_user_id ; 
		 }
	}
	/**
	 * Asserts whether or not a user has signed a particular document
	 * 
	 * @param $document_id [Integer] 
	 * @param $document_id [Integer]
	 * @param $user_id [Integer]
	 * @return Boolean
	 * @since 0.1.0
	 */
	public function hasSignedDocument($user_id, $document_id){
		return $this->signature->userHasSignedDocument($user_id, $document_id);
	}

	public function getUserEmail($id=null){
	
		$id = !$id ? $this->getCurrentUserID() : $id;
		
		return $this->wpdb->get_var("SELECT user_email FROM " . $this->table . " WHERE user_id=" . $id);
	}
	

	public function getUserTotal(){
		return $this->wpdb->get_var("SELECT COUNT(*) FROM " . $this->table);
	}
	
	public function UserEmail_exists($user_email)
	{
		return $this->wpdb->get_var($this->wpdb->prepare(
				"SELECT COUNT(*) as cnt FROM " . $this->table . " WHERE user_email=%s",$user_email
				) );
		//return $this->wpdb->get_var("SELECT COUNT(*) FROM " . $this->table . " WHERE user_email=" . $user_email);
	}

	public function getUserBy($field, $strvalue){
		$user = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE $field = '%s'", $strvalue
			)
		);
		if(!empty($user[0])) return $user[0];
		else return false;
	}
	
	public function getUserdetails($user_id, $document_id){
	
		
		$user = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE user_id= '%s'", $user_id
			)
		);
		
		if(!empty($user[0])) {
		
		if($this->settings->get_generic("esign_user_meta_id_". $user_id ."_name_document_id_".$document_id)){
		
		       $user[0]->first_name =stripslashes_deep( $this->settings->get_generic("esign_user_meta_id_". $user_id ."_name_document_id_".$document_id)) ; 
				
				if($this->settings->get_generic("esign_user_meta_email_". $user_id ."_name_document_id_".$document_id)){
					$user[0]->user_email = $this->settings->get_generic("esign_user_meta_email_". $user_id ."_name_document_id_".$document_id) ; 
				}
			}
			else {
				$user[0]->first_name =stripslashes_deep( $user[0]->first_name) . " " . stripslashes_deep($user[0]->last_name);
			}
			return $user[0];
		}
		else { return false; }
	}
	
	
	public function  get_esig_signer_name($user_id, $document_id)
	{
	    
	    $new_name = $this->settings->get_generic('esign_signed_'. $user_id .'_name_document_id_'.$document_id);
	    	
	    if($new_name)
	    {
	        $signer_name = $new_name ;
	    }
	    else
	    {
	        $user= $this->getUserdetails($user_id,$document_id);
	        $signer_name = $user->first_name;
	    }
	    
	    return stripslashes_deep($signer_name) ; 
	}
	
	/**
	 * This is method getUserByWPID
	 *  this method return already setup with wp user id . 
	 * @param mixed $id This is a user id 
	 * @return bolean
	 *
	 */	
	public function getUserByWPID($id){

		$user = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE wp_user_id = %d", $id
			)
		);
		
		if(!empty($user[0])) return $user[0];
		else return false;
	}


	/**
	 * This is method getUserByID
	 *  this method return user details by user id . 
	 * @param mixed $id This is a description
	 * @return mixed This is the return value description
	 *
	 */
		
	public function getUserByID($id){
		$user = $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE user_id = %d", $id
			)
		);
		if(!empty($user[0])) return $user[0];
		else return false;
	}

	/***
	 * checking administrative user access . 
	 * Since 1.0.13 
	 * return bolean 
	 * */
	
	public function checkEsigAdmin($user_id){
		        
		
       
			$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
			if($user_id == $admin_user_id){
				return true ; 
			}else {
            
                $esig_access=apply_filters('esig_plugin_access_control',''); // define a filter for esignature plugin access 
				
				if($esig_access == "allow"){
					return true ;
				}else {
					
					return false ; 
				}
			
				return false ; 
			}
		
	}
    
    /***
	 * return super admin id 
	 * Since 1.0.13 
	 * */
	public function esig_get_super_admin_id(){
		
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		return  $admin_user_id;	
	}
	
	
	
	/***
	 * return administrator display name 
	 * Since 1.0.13 
	 * */
	public function esig_get_administrator_displayname(){
		
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		$user_details=get_userdata( $admin_user_id );
		
		return  stripslashes_deep($user_details->display_name) ;
		
	}
    
	/***
	 * return administrator E-mail address . 
	 * Since 1.0.13 
	 * */
	public function esig_get_administrator_email(){
		
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		$user_details=get_userdata( $admin_user_id );
		return  $user_details->user_email ;
		
	}
	
	/**
	 * Insert User row 
	 * 
	 * @since 1.0.1
	 * @param Array $user
	 * @return Int user_id
	 */
	public function insert($user){
	   
		$user_id = $this->wpdb->get_var(
			$this->wpdb->prepare(
					"SELECT user_id FROM " . $this->table . " WHERE user_email='%s'", 
					$user['user_email']
					)
				);
		// User already exists. Update
		if(!empty($user_id)){
			
			if(!isset($user['last_name'])) $user['last_name'] = '';
			
			$user_fname=$this->getUserFullName($user_id);
			
			if($user_fname != $user['first_name']){
				
				$user_name=$user['first_name'] ; 
				
				
				$this->settings->set("esign_user_meta_id_".$user_id ."_name_document_id_".$user['document_id'],$user['first_name']);
				
			}
			if(!empty($user['document_id'])){
				$userID = $this->getCurrentUserID();
				
				$user_name = $this->getUserFullName($userID) . " " . $this->getUserLastName($userID) ;
				$this->settings->set("esign_user_meta_id_".$userID ."_name_document_id_".$user['document_id'],$user_name);
				$user_email = $this->getUserEmail($userID);
				$this->settings->set("esign_user_meta_email_".$userID ."_name_document_id_".$user['document_id'],$user_email);
			}
			
			return $user_id;
		} 
		
		
		include ESIGN_PLUGIN_PATH . DS . "lib" . DS . "UUID.php";
		$uuid = UUID::v4();
			if(!empty($user['wp_user_id'])) { $user_wp_user_id=$user['wp_user_id'] ; } else {$user_wp_user_id='';}
			if(!empty($user['last_name'])) { $user_last_name=$user['last_name'] ; } else {$user_last_name='';}
			if(!empty($user['user_title'])) { $user_user_title=$user['user_title'] ; } else {$user_user_title='';}
		$this->wpdb->query(
			$this->wpdb->prepare(
				"INSERT INTO " . $this->table . " VALUES(null,%d,%s,%s,%s,%s,%s)",
				$user_wp_user_id,
				$uuid,
				$user['user_email'],
				$user_user_title,
				$user['first_name'],
				$user_last_name
			)
		);
		
		$last_user_id=$this->wpdb->insert_id;
	
		return $last_user_id;
	}

	/**
	 * Update User row 
	 * 
	 * @since 1.0.1
	 * @param Array $user
	 * @return Int user_id
	 */
	public function update($user){
		
		$wp_user_id = array_key_exists('wp_user_id',$user)? $user['wp_user_id']:'';
		return $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET 
				wp_user_id='%s',
				user_title='%s',
				user_email='%s',
				first_name='%s',
				last_name='%s' WHERE user_id=%d",
				$wp_user_id,
				$user['user_title'],
				$user['user_email'],
				$user['first_name'],
				$user['last_name'],
				$user['user_id']
			)
		);
	}

	public function updateField($user_id, $field, $value){
		return $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE $this->table SET $field='%s' WHERE user_id=%d", $value, $user_id
			)
		);
	}

	public function fetchAll(){

		return $this->wpdb->get_results("SELECT * FROM " . $this->table);
	}

	/** !! D E P R E C A T E D !! **/
	public function getUserData($id=null){

		$id = isset($id) ? $id : get_current_user_id();
		return get_userdata($id);
	}

	public function getCurrentUserID(){
		$wp_user_id = $this->getCurrentWPUserID();

		return $this->wpdb->get_var("SELECT user_id FROM " . $this->table . " WHERE wp_user_id=" . $wp_user_id);
	}

	public function getCurrentWPUserID(){
		return get_current_user_id();
	}
	
}