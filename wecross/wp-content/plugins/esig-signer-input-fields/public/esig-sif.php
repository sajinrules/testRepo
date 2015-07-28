<?php
/**
 * 
 * @package ESIG_SIF
 * @author  Michael Medaglia <mm@michaelmedaglia.com>
 */

if (! class_exists('ESIG_SIF')) :
class ESIG_SIF {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1
	 *
	 * @var     string
	 */
	const VERSION = '1.0.11';
	
	private $inputs_table = 'esign_documents_signer_field_data';

	/**
	 *
	 * Unique identifier for plugin.
	 *
	 * @since     0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'esig-sif';

	/**
	 * Instance of this class.
	 *
	 * @since     0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array($this, 'load_plugin_textdomain') );
		add_action( 'esig_signature_saved', array($this, 'save_signer_inputs'), 10, 1);

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array($this, 'activate_new_site') );

		// Load public-facing style sheet and JavaScript.
		add_action( 'esig_footer', array($this, 'enqueue_styles') );
		add_action( 'esig_head', array($this, 'enqueue_scripts') );
		
		// Register Shortcodes
		add_shortcode( 'esigtextfield', array($this, 'render_shortcode_textfield') );
		add_shortcode( 'esigtodaydate', array($this, 'render_shortcode_todaydate') );
        add_shortcode( 'esigdatepicker', array($this, 'render_shortcode_datepicker') );
		add_shortcode( 'esigradio', array($this, 'render_shortcode_radio') );
		add_shortcode( 'esigcheckbox', array($this, 'render_shortcode_checkbox') );
		
	}


	/**
	 * Returns the plugin slug.
	 *
	 * @since     0.1
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Returns an instance of this class.
	 *
	 * @since     0.1
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since     0.1
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		self::single_activate();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since     0.1
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		self::single_deactivate();
	}

	/**
	 * Textfield Shortcode
	 * Usage: [esigtextfield label="First Name" required=""]
	 */
	public function render_shortcode_textfield($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'name' => 'textfield',
			'label' => 'Text', //foo is a default value
			'required' => '',
			'verifysigner'=>'',
            'size'=>'',
			), $atts, 'esigtextfield'));

		$name = preg_replace('/[^a-zA-Z\d-]/', "", $name);
        
		if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		
		$document_id=isset($_GET['did'])?$esig->shortcode->document->document_id_by_csum($_GET['did']): $_GET['document_id'] ;	
		// Admins 
		if(isset($document_id) && intval($document_id)){
			
			// Already signed	
			
			if($this->check_signature($document_id,$verifysigner)){
				
				$this->populate_field($esig, $document_id, $name, $value,$verifysigner);
				 
				return $this->text_to_html($label, $name, $value, $required, true,$verifysigner,$size);

			// Not signed
			} else {
				return $this->text_to_html($label, $name, '', $required, false,$verifysigner,$size);
			}

		// Recipient
		} else if($this->get_user_info($esig, $invitation, $recipient)){	
				
			$doc_id = $invitation->document_id;

			// Already signed
			if($this->check_signature($doc_id,$verifysigner,$invitation->user_id)){
				
				$this->populate_field($esig, $doc_id, $name, $value,$verifysigner);
				return $this->text_to_html($label, $name, $value, $required, true,$verifysigner,$size);

			// Not signed
			} else {
			     
				return $this->text_to_html($label, $name, '', $required, false,$verifysigner,$size);
			}
			
		// Public page. Just show the empty field
		} else {
		 
        
		if(isset($_POST[$name])){
					$document_id=$esig->shortcode->document->document_max();
		           $this->populate_field($esig,$document_id,$name,$_POST[$name],$verifysigner);
			return $this->text_to_html($label, $name,$_POST[$name], $required, true,$verifysigner,$size);
			}
			else {
			
			return $this->text_to_html($label, $name, '', $required, false,$verifysigner,$size);
			}
		}
	}


	/**
	 * Converts an label to text input html. $value will override.
	 */		
	private function text_to_html($placeholder, $name, $value = '', $is_required = false, $signed = false,$verifysigner='undefined',$size='undefined'){
		
		if($signed){
			
			if($verifysigner !='undefined' and $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify=' title="This element is assigned to '. $this->get_signer_name($verifysigner) . '"';			
			}
			return '<span class="esig-sif-textfield signed" '. $verify .'>'. htmlspecialchars(stripslashes($value),ENT_QUOTES) .'</span>';
		} else {
			
			$required = ($is_required == 1) ? 'required':'';
			$verify='';
			if($verifysigner !='undefined' || $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify='readonly title="This element is assigned to '. $this->get_signer_name($verifysigner) . '" class="sifreadonly"';			
			}
            $inputsize='';
            if($size !='undefined' ){
            
                $inputsize = 'style="width:'. $size .'px;"' ;
            }
			$required=(!empty($verify)) ? '' : $required = 'class="esig-sif-textfield" ' . $required;
			return '<input   placeholder="'.$placeholder.
				'" type="text" '. $verify .' name="'.$name.'" value="'.$value.'"  '. $inputsize .' '.$required.' />';
		}

	}
	
	private function get_signer_name($verifysigner)
	{
		if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = $esig ? $esig : WP_E_Sig();
		$value = '';
		
		$pieces = explode("ud", $verifysigner);
		
		$user_id =$pieces[0];
		$document_id = $pieces[1];
		$userdetails=$esig->shortcode->user->getUserdetails($user_id,$document_id);
		return $userdetails->first_name ; 
	 }
	
	/**
	* Checking sif signature . 
	*
	* Since 1.0.4 
	*
	*/
	
	private function check_signature($document_id,$verifysigner,$user_invite=0){
			global $wpdb;
		if(!function_exists('WP_E_Sig'))
					return ;
				
		$esig = $esig ? $esig : WP_E_Sig();
		$value = '';
		
		if($verifysigner !='undefined' and $verifysigner !='null'){
		
		$pieces = explode("ud", $verifysigner);
		
		$user_id =$pieces[0];
		$document_id = $pieces[1];
			
		 if($esig->shortcode->signature->GetSignatureId($user_id,$document_id))  
						return true ; 
		  
		} else {
		
		   if($user_invite>0){
			  if($esig->shortcode->signature->userHasSignedDocument($user_invite,$document_id)){
				return true ;
				}else {
				return false ;
				}						
		   }
			if($esig->shortcode->signature->documentHasSignature($document_id))  
							return true ; 
		}
	}
	
	private function populate_field(&$esig, $document_id, $name, &$value,$verifysigner){
		
		global $wpdb;
		if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = $esig ? $esig : WP_E_Sig();
		$value = '';
		
		if($verifysigner !='undefined' and $verifysigner !='null'){
		
		$pieces = explode("ud", $verifysigner);
		
		$user_id =$pieces[0];
		$document_id = $pieces[1];
		
		 $signature_id=$esig->shortcode->signature->GetSignatureId($user_id,$document_id) ; 
		 
		$result = $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}{$this->inputs_table} " .
			"WHERE signature_id=%d and document_id = %d ORDER BY date_created DESC",$signature_id,$document_id
		));
		} else {
		
		$result = $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM {$wpdb->prefix}{$this->inputs_table} " .
			"WHERE document_id = %d ORDER BY date_created DESC", $document_id
		));
		}
       
       
		$decrypt_fields=$esig->shortcode->signature->decrypt("esig_sif",$result->input_fields);
		
        $fields =json_decode($decrypt_fields);
		
		if(isset($fields->$name)){
			$value = $fields->$name;
		}
	}
	
	/**
	 * Checks url params and populates recipient and invitation from invite code
	 * 
	 * @return Boolean True if successful. False if bad params.
	 */	
	private function get_user_info(&$esig=null, &$invitation, &$recipient){
		
		// URL is expected to pass an invite hash and document checksum
		$invite_hash = isset($_GET['invite']) ? $_GET['invite'] : null;
		$checksum = isset($_GET['csum']) ? $_GET['csum'] : null;
		
		if(!$invite_hash || !$checksum){
			return false;
		}
		if(! function_exists('WP_E_Sig'))
				return ;
		$esig = $esig ? $esig : WP_E_Sig();

		// Grab invitation and recipient from invite hash
		$invitation = $esig->shortcode->invite->getInviteBy('invite_hash', $invite_hash);
		$recipient = $esig->shortcode->user->getUserBy('user_id',$invitation->user_id);
		
		if($invitation && $recipient){
			return true;
		} else {
			return false;
		}
	}
	

	/**
	 * Today's Date Shortcode
	 * Usage: [esigtodaydate]
	 */
	public function render_shortcode_todaydate($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
				'format' => "m/d/Y",
			), $atts, 'esigtodaydate'));
			
		if(! function_exists('WP_E_Sig'))
				return ;
                
		$esig = WP_E_Sig();
        
        $date = date($format);
        $name = 'esig-sif-todaydate';
		 // Essentially like a textfield but always the same name
		
		$document_id=isset($_GET['did'])?$esig->shortcode->document->document_id_by_csum($_GET['did']): $_GET['document_id'] ;	
		// Admins
       
		if(isset($document_id) && intval($document_id)){
						
			// Already signed	
			if($esig->shortcode->signature->getDocumentSignatures($document_id)){
				$this->populate_field($esig, $document_id, $name, $value,$verifysigner='null');
				
				return $this->date_to_html($value,true);

			// Not signed
			} else {
          
				return $this->date_to_html($date);
			}

		// Recipient
		} else if($this->get_user_info($esig, $invitation, $recipient)){
			
			$doc_id = $invitation->document_id;

			// Already signed
			if($esig->shortcode->user->hasSignedDocument($recipient->user_id, $doc_id)){
				$this->populate_field($esig, $doc_id, $name, $value,$verifysigner='null');
				
				return $this->date_to_html($value,true);

			// Not signed
			} else {
				return $this->date_to_html($date);
			}
			
		// Public-facing page (Stand Alone Doc)
		} else {
			if(isset($_POST[$name])){
					$document_id=$esig->shortcode->document->document_max();
		           $this->populate_field($esig,$document_id,$name,$value,$verifysigner='null');
					return $this->date_to_html($_POST[$name],true);
			}
			else {
				
				return $this->date_to_html($date);
                
			}
			
		}
		
	}


	/**
	 * Renders a date. $value will override.
	 */		
	private function date_to_html($value = '', $signed = false){
		
		if($signed){
			return  '<span class="esig-sif-textfield signed" >'.$value.'</span>';
		} else {
			
			return  '<input class="esig-sif-todaydate" type="text" name="esig-sif-todaydate" value="'.$value.'" readonly />';
		}

	}
    
    /**
	 * Today's Date Shortcode
	 * Usage: [esigtodaydate]
	 */
	public function render_shortcode_datepicker($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
				'name' =>'', 
                'verifysigner'=>'',
			), $atts, 'esigdatepicker'));
			
		if(! function_exists('WP_E_Sig'))
				return ;
                
		$esig = WP_E_Sig();
        
        $date = date($format);
        
        if(empty($verifysigner)){
            $verifysigner='undefined';
        }
      
		 // Essentially like a textfield but always the same name
		$document_id=isset($_GET['did'])?$esig->shortcode->document->document_id_by_csum($_GET['did']): $_GET['document_id'] ;	
		// Admins
        
		if(isset($document_id) && intval($document_id)){
						
			// Already signed	
			if($esig->shortcode->signature->getDocumentSignatures($document_id)){
            
				$this->populate_field($esig, $document_id, $name, $value,$verifysigner);
				
				return $this->datepicker_to_html($value,$name,true,$verifysigner);

			// Not signed
			} else {
          
				return $this->datepicker_to_html($date,$name,false,$verifysigner);
			}

		// Recipient
		} else if($this->get_user_info($esig, $invitation, $recipient)){
			
			$doc_id = $invitation->document_id;
          
			// Already signed
			if($esig->shortcode->user->hasSignedDocument($recipient->user_id, $doc_id)){
				$this->populate_field($esig, $doc_id, $name, $value,$verifysigner);
				
				return $this->datepicker_to_html($value,$name,true,$verifysigner);

			// Not signed
			} else {
				return $this->datepicker_to_html($date,$name,false,$verifysigner);
			}
			
		// Public-facing page (Stand Alone Doc)
		} else {
        
			if(isset($_POST[$name])){
					$document_id=$esig->shortcode->document->document_max();
		           $this->populate_field($esig,$document_id,$name,$value,$verifysigner);
					return $this->datepicker_to_html($_POST[$name],$name,true,$verifysigner);
			}
			else {
				
				return $this->datepicker_to_html($date,$name,false,$verifysigner);
                
			}
			
		}
		
	}


	/**
	 * Renders a date. $value will override.
	 */		
	private function datepicker_to_html($value = '',$name, $signed = false,$verifysigner='undefined'){
		
           
		if($signed){
            $verify=null;
           
			if($verifysigner !='undefined' || $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify='title="This element is assigned to '. $this->get_signer_name($verifysigner) . '" class="sifreadonly"';			
			}
           
			return '<span class="esig-sif-textfield signed" '. $verify .'>'.$value.'</span>';
		} else {
			$verify=null;
           
			if($verifysigner !='undefined' || $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify='readonly title="This element is assigned to '. $this->get_signer_name($verifysigner) . '" class="sifreadonly"';			
			}
			return '<script type="text/javascript">
        jQuery(document).ready(function () {
		//alert("datepicker"); 
    jQuery( "#'. $name .'" ).datepicker();
    });
              </script>  <input class="esig-sif-datepicker" '. $verify .' placeholder="Select Date" id="'. $name .'" type="text" name="'. $name .'" value="'.$value.'"  /> ';
		}

	}


	/**
	 * Similar to php parse_str but will include whitespace in array keys
	 * 
	 */
	private function parse_str($input, &$vars){
				
		$input = str_replace('&amp;', '&', $input);
		$pairs = explode("&", $input);

		foreach ($pairs as $pair) {
			$nv = explode("=", $pair);
			$name = urldecode($nv[0]);
			$nameSanitize = preg_replace('/([^\[]*)\[.*$/','$1',$name);
			$vars[$nameSanitize] = $nv[1]?$nv[1]:'';
		}
	}

	/**
	 * Radio Button Shortcode
	 * Usage: [esigradio]
	 */
	public function render_shortcode_radio($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'name' => 'radios',
            'label' =>'Text', 
			'labels' => 'Text', //foo is a default value
			'required' => '',
			'display'=>'',
			'verifysigner'=>''
			), $atts, 'esigradio'));
			
		$name = preg_replace('/[^a-zA-Z\d-]/', "", $name);
		
		if(! function_exists('WP_E_Sig'))
				return ;
		$esig = WP_E_Sig();		
		$this->parse_str($labels, $radios);
		$html = '';

		$document_id=isset($_GET['did'])?$esig->shortcode->document->document_id_by_csum($_GET['did']): $_GET['document_id'] ;	
		// Admins
		if( isset($document_id) && intval($document_id)){
						
			// Already signed	
			if($this->check_signature($document_id,$verifysigner)){
				
				$this->populate_field($esig, $document_id, $name, $value,$verifysigner);
				$html = $this->radios_to_html($radios, $name, $value,'',$verifysigner,$display);

			// Not signed
			} else {			
				$html = $this->radios_to_html($radios, $name,'',$required,$verifysigner,$display,$label);
			}

		// Recipient
		} else if($this->get_user_info($esig, $invitation, $recipient)){
			
			$doc_id = $invitation->document_id;

			// Already signed
			if($this->check_signature($doc_id,$verifysigner,$invitation->user_id)){

				$this->populate_field($esig, $doc_id, $name, $value,$verifysigner);
				$html = $this->radios_to_html($radios, $name, $value,'',$verifysigner,$display);

			// Not signed
			} else {
				$html = $this->radios_to_html($radios, $name,'',$required,$verifysigner,$display,$label);
			}
			
		// Public-facing page (Stand Alone Doc)
		} else {
		
		if(isset($_POST[$name])){
					$document_id=$esig->shortcode->document->document_max();
		           $this->populate_field($esig,$document_id,$name,$_POST[$name],$verifysigner);
					$html = $this->radios_to_html($radios, $name,$_POST[$name],'',$verifysigner,$display);
			}
			else {
			
			$html = $this->radios_to_html($radios, $name,'',$required,$verifysigner,$display,$label);
			}
			
		}
		
		return $html;
		
	}

	/**
	 * Converts an array of radios to html. $checked will override which radio is checked.
	 */	
	public function radios_to_html($radios, $name, $checked_value=null,$is_required = false,$verifysigner='undefined',$display='vertical',$label=false){
		
		$html = '';
		$html = '';
		if($label){
        
                  $html .= '<span> '. $label .' </span>';
                }
		foreach($radios as $key => $checked){
			$checked = $checked ? 'CHECKED':'';
			$value = sanitize_title_for_query($key);
			
			// Use the signer value, not the default value
			if($checked_value){
			$verify='';	
			if($verifysigner !='undefined' and $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify=' title="This element is assigned to '. $this->get_signer_name($verifysigner) . ' "';				
			}
				$checked = ($checked_value == $value) ? 'checked=CHECKED':'';
		        if($display =="vertical"){
                  $html .= '<div class="radio"><label class="esig-sif-radio">'.
				            '<input type="radio" onclick="javascript: return false;" '. $verify .' '.$checked.' name="' .$name.'" value="'.$value.'" /> '.$key.'</label></div>';
                }
                else {
                    $html .= '<div class="radio-horizental"><label class="esig-sif-radio">'.
				            '<input type="radio" onclick="javascript: return false;" '. $verify .' '.$checked.' name="' .$name.'" value="'.$value.'" /> '.$key.'</label></div>';
                 
                }
				
			}
			else {
			$required = ($is_required == 1) ? 'required':'';
			$verify='';	
			if($verifysigner !='undefined' and $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify='onclick="this.checked=false;" title="This element is assigned to '. $this->get_signer_name($verifysigner) . '" class="sifreadonly"';				
			}
			
			$required=(!empty($verify)) ? '' : $required;
			$class=(!empty($verify)) ? 'class="esig-sif-none"' : 'class="esig-sif-radio"';
			
			if($display =="vertical"){
			
				if(empty($verify)) 			
						$html .= '<div id="radios">';
					
			$html .= '<span class="radio"><label '.$class.'>'.
				'<input type="radio" '. $verify .' name="' .$name.'" '. $required .' value="'.$value.'" '.$checked.
				' /> '.$key.'</label></span>';
				if(empty($verify))
						$html .='</div>';
			
			}
			 elseif($display =="horizontal"){	 
					
					$html .= '<span class="radio-horizental"><label '.$class.'>'.
						'<input type="radio" '. $verify .' name="' .$name.'" '. $required .' value="'.$value.'" '.$checked.
						' /> '.$key.'</label></span>';
					
			        }	 
				}
		}
		
		return $html;
	}

/**
	*  Checking sif display or not 
	*
	* Since 1.0.4
	**/
	private function check_sif_display($verifysigner){
	
		  $pieces = explode("ud", $verifysigner);
		
		$user_id =$pieces[0];
		$document_id = $pieces[1];
		
		if(!$user_id || !$document_id){
				return false;
		}
		
		if(!function_exists('WP_E_Sig'))
				return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
		
		$invite_hash = isset($_GET['invite']) ? $_GET['invite'] : null;
		$checksum = isset($_GET['csum']) ? $_GET['csum'] : null;
		if(!$invite_hash || !$checksum){
				return true;
		}
		$invitation = $api->invite->getInviteBy('invite_hash', $invite_hash);
		 
		$recipient = $api->user->getUserBy('user_id',$invitation->user_id);
		
		if($recipient->user_id == $user_id) {
			
					return false;
		} else {
		 return true;
		}
	  
	}
	/**
	 * Checkbox Shortcode
	 * Usage: [esigcheckbox]
	 */
	public function render_shortcode_checkbox($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'name' => 'checkboxes',
            'label'=>'Text',
			'boxes' => '', //foo is a default value
			'verifysigner'=>'',
			'display'=>'',
			'required' => '',
			), $atts, 'esigcheckbox'));

			$name = preg_replace('/[^a-zA-Z\d-]/', "", $name);
			if(! function_exists('WP_E_Sig'))
				return ;
			$esig = WP_E_Sig();
			$this->parse_str($boxes, $boxes_arr);
			$html = '';
           
		$document_id=isset($_GET['did'])?$esig->shortcode->document->document_id_by_csum($_GET['did']): $_GET['document_id'] ;	
			// Admins
		if(isset($document_id) && intval($document_id)){
						
				// Already signed	
			if($this->check_signature($document_id,$verifysigner)){
					
				$this->populate_field($esig, $document_id, $name, $value,$verifysigner);
					
					$html = $this->checkboxes_to_html($boxes_arr, $name, $value,'',$verifysigner,$display);

				// Not signed
				} else {
					$html = $this->checkboxes_to_html($boxes_arr, $name,$value,$required,$verifysigner,$display,$label);
				}

			// Recipient
			} else if($this->get_user_info($esig, $invitation, $recipient)){
			
				$doc_id = $invitation->document_id;

				// Already signed
				if($this->check_signature($doc_id,$verifysigner,$invitation->user_id)){

					$this->populate_field($esig, $doc_id, $name, $value,$verifysigner);
					$html = $this->checkboxes_to_html($boxes_arr, $name, $value,'',$verifysigner,$display);

				// Not signed
				} else {
					$html = $this->checkboxes_to_html($boxes_arr, $name,$value,$required,$verifysigner,$display,$label);
				}
			
			// Public facing page (like a Stand Alone Doc)
			} else {
			if(isset($_POST[$name])){
					$document_id=$esig->shortcode->document->document_max();
		           $this->populate_field($esig,$document_id,$name,$_POST[$name],$verifysigner);
					$html = $this->checkboxes_to_html($boxes_arr, $name,$_POST[$name],'',$verifysigner,$display);
			}
			else {
			
			$html = $this->checkboxes_to_html($boxes_arr, $name,"",$required,$verifysigner,$display,$label);
			}
				
			}
		
			return $html;
	}
	

	/**
	 * Converts an array of checkboxes to html. $checked will override which boxes are checked.
	 */	
	public function checkboxes_to_html($boxes, $name, $checked_value=null,$is_required = false,$verifysigner='undefined',$display='vertical',$label=false){
		
		$html = '';
		if($label){
                  $html .= '<span> '. $label .' </span>';
                }
                
		foreach($boxes as $key => $checked){
			$checked = $checked ? 'CHECKED':'';
			$value = sanitize_title_for_query($key);
			
			// Use the signer value, not the default value
			if($checked_value){
			$verify='';
			if($verifysigner !='undefined' and $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify='title="This element is assigned to '. $this->get_signer_name($verifysigner) . '"';			
			}
				$checked = in_array($value, $checked_value) ? 'checked=CHECKED':'';
               
               
                if($display =="vertical"){
                   $html .= '<div class="checkbox"><label class="esig-sif-checkbox">'.
				    '<input type="checkbox" onclick="javascript: return false;" '. $verify .' name="' .$name.'[]" '. $checked .'  value="'.$value.'"  />'.$key.'</label></div>' ;
                }
                else {
                     $html .= '<span class="checkbox-horizental"><label class="esig-sif-checkbox">'.
				    '<input type="checkbox" onclick="javascript: return false;" '. $verify .' name="' .$name.'[]" '. $checked .'  value="'.$value.'"  />'.$key.'</label></span>' ;
                }
                
				
			
            }
			else {
			$verify='';
			if($verifysigner !='undefined' and $verifysigner !='null') { 
				if($this->check_sif_display($verifysigner))
							$verify='onclick="return false;" title="This element is assigned to '. $this->get_signer_name($verifysigner) . '" class="sifreadonly"';			
			}
            
			
		
			$required = ($is_required == 1) ? 'required':'';
			$required=(!empty($verify)) ? '' :$required;
			$class=(!empty($verify)) ? 'class="esig-sif-none"' : 'class="esig-sif-checkbox"';
			
			if($display =="vertical"){
           
			if(empty($verify))
					$html .= '<div id="checkboxes">';
			$html .= '<span class="checkbox"><label '. $class .'>'.
				'<input  type="checkbox" name="' .$name.'[]" '.$verify.' value="'.$value.'"'. $required . " " .$checked.
				' /> '.$key.'</label></span>';
			if(empty($verify))
					$html .='</div>' ;
			 }
			 elseif($display =="horizontal"){
                 
					$html .= '<span class="checkbox-horizental"><label '. $class .'>'.
						'<input  type="checkbox" name="' .$name.'[]" '.$verify.' value="'.$value.'"'. $required . " " .$checked.
						' /> '.$key.'</label></span>';
					
			 }
					
			}
		}
		
		return $html;
	}



	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since     0.1
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since     0.1
	 */
	private static function single_activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$table = $wpdb->prefix . 'esign_documents_signer_field_data';
        
        if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) 
        {
		    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "`(
			    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			    `signature_id` int(11) NOT NULL,
			    `document_id` int(11) NOT NULL,
			    `input_fields` longtext NOT NULL,
			    `date_created` datetime NOT NULL,
			    `date_modified` datetime NOT NULL) ENGINE = INNODB";
		    //dbDelta($sql);
             $wpdb->query($sql);
        }
        
        if(get_option('WP_ESignature__Signer_Input_Fields_documentation'))
        {
            update_option('WP_ESignature__Signer_Input_Fields_documentation','https://www.approveme.me/wp-digital-signature-plugin-docs/article/how-to-add-signer-input-fields/');
            
        }
        else
        {
           
           add_option('WP_ESignature__Signer_Input_Fields_documentation','https://www.approveme.me/wp-digital-signature-plugin-docs/article/how-to-add-signer-input-fields/');
        }
        
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since     0.1
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     0.1
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since     0.1
	 */
	public function enqueue_styles() {
		
			$current_page = get_queried_object_id();
			
			global $wpdb;
			if(!function_exists('WP_E_Sig'))
					return ;
								
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
			$default_page=array();
			if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
			$default_page= $wpdb->get_col("SELECT page_id FROM {$table}");
			}
			
			$default_normal_page=$api->setting->get_generic('default_display_page');
			
		// If we're on a stand alone page
			if( is_page($current_page) && in_array($current_page,$default_page)){
			echo "<link rel='stylesheet' id='esig-sif-plugin-styles-css'  href='". plugins_url() ."/esig-signer-input-fields/public/assets/css/public.css?ver=". self::VERSION ."' type='text/css' media='all' />";
			}
			if( is_page($current_page) && $current_page == $default_normal_page){
			echo "<link rel='stylesheet' id='esig-sif-plugin-styles-css'  href='". plugins_url() ."/esig-signer-input-fields/public/assets/css/public.css?ver=". self::VERSION ."' type='text/css' media='all' />";
			}
            
           echo "<link rel='stylesheet' id='jquery-style-css'  href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css?ver=4.0' type='text/css' media='all' />";
         
        echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/jquery.js?ver=1.11.1'></script>";
        echo "<script type='text/javascript' src='". includes_url() ."/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>";
		$esig_scripts=new WP_E_Esigscripts();
		$esig_scripts->display_ui_scripts(array('core.min','datepicker.min'));
			
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since     0.1
	 */
	public function enqueue_scripts() {
		$current_page = get_queried_object_id();
			global $wpdb;
			if(!function_exists('WP_E_Sig'))
				return ;
								
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
			$default_page=array();
			if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
			$default_page= $wpdb->get_col("SELECT page_id FROM {$table}");
			}
			
			
					$default_normal_page=$api->setting->get_generic('default_display_page');
		// If we're on a stand alone page
			if( is_page($current_page) && in_array($current_page,$default_page)){
			echo "<script type='text/javascript' src='". plugins_url() . "/esig-signer-input-fields/public/assets/js/public.js?ver=". self::VERSION ."'></script>";
			}
			else if( is_page($current_page) && $current_page == $default_normal_page){
			echo "<script type='text/javascript' src='". plugins_url() . "/esig-signer-input-fields/public/assets/js/public.js?ver=". self::VERSION ."'></script>";
			}
        
          //  wp_enqueue_script('jquery-ui-datepicker');
          
	}

	/**
	 * Saves the user input fields.
	 *
	 * @since     0.1
	 */
	public function save_signer_inputs($args){
		
		global $wpdb;
		
		if(!function_exists('WP_E_Sig'))
						return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		$post = $args['post_fields'];
		$invitation = $args['invitation'];
		
		$document_id = $invitation->document_id;
		
		$input_fields = array();
        
		foreach($post as $var => $value){
			if(preg_match("/^esig-sif-/", $var)){
				
				$input_fields[$var] =$value; 
               
			}
		}

		if(!count($input_fields)){
			return;
		}
       
		$data = array(
			"document_id" => $document_id,
			"signature_id" => $args['signature_id'],
			"input_fields" =>$api->signature->encrypt("esig_sif",json_encode($input_fields)),
			"date_created" => date("Y-m-d H:i:s"),
			"date_modified" => date("Y-m-d H:i:s")
		);
		
		$wpdb->insert($table = $wpdb->prefix . $this->inputs_table,$data);
	}
    
  public function get_sif_meta($sif_meta_key){
  
        global $wpdb;
        $value = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s LIMIT 1" , $sif_meta_key) );
        if($value !=null){
             return $value ;  
        } 
        return false ;        
  }


}
endif;
