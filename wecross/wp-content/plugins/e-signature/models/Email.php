<?php

class WP_E_Email extends WP_E_Model {
	
	
	public function __construct()
    {
        parent::__construct();
        
       
      
    }
    
   
   public function esig_register_mail_option()
   {
   			
		$esig_options_default = array(
			'enable' 		=> 'no',
			'from_email_field' 		=> '',
			'from_name_field'   		=> '',
			'smtp_settings'     		=> array( 
				'host'               	=> 'smtp.example.com',
				'type_encryption'	=> 'none',
				'port'              	=> 25,
				'authentication'		=> 'yes',
				'username'		=> 'yourusername',
				'password'          	=> 'yourpassword'
			)
		);

		/* install the default plugin options */
                if ( ! get_option( 'esig_mail_options' ) ){
                    add_option( 'esig_mail_options', $esig_options_default, '', 'yes' );
                }
	
   }
   
   /**
   * Retrurn e-signature mail settings password 
   * 
   * @return
   */
   
   public function esig_mail_get_password() {
            $esig_options = get_option( 'esig_mail_options' );
            $temp_password = $esig_options['smtp_settings']['password'];
            $password = "";
            $decoded_pass = base64_decode($temp_password);
            if (base64_encode($decoded_pass) === $temp_password) {  //it might be encoded
                if(false === mb_detect_encoding($decoded_pass)){  //could not find character encoding.
                    $password = $temp_password;
                }
                else{
                    $password = base64_decode($temp_password); 
                }               
            }
            else{ //not encoded
                $password = $temp_password;
            }
            return $password;
	}
	
	
public function mailType($content_type){
		return 'text/html';
}

 public function default_mail($to_email, $subject, $message,$headers,$attachments=false)
 {
 	
 		add_filter( 'wp_mail_content_type', array($this, 'mailType') );
 		if($attachments)
 		{
			$mailsent = wp_mail($to_email, $subject,$message, $headers,$attachments);
		}
		else
		{
			$mailsent = wp_mail($to_email, $subject,$message, $headers);
		}
		
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
		return $mailsent ; 
 	
 }
	
 public function esig_mail( $from_name='',$from_email='',$to_email, $subject, $message,$attachments=false) {
  	
		$errors = '';

		$esig_options = get_option( 'esig_mail_options' );
		// if from name is not set 
			 if($from_name=='')
			 {
			 	$from_name  = utf8_decode($esig_options['from_name_field']);
		
			 }
			 // if from email is not set 
			 if($from_email == '')
			 {
			 	$from_email = $esig_options['from_email_field']; 
			 }
		
		if($esig_options['enable'] == 'no')
		{
			
				$headers = array(
				"From: ". $from_name ." <{$from_email}>",
				"Reply-To: {$from_name} <{$from_email}>"
				);
			
			 $mailsent = $this->default_mail($to_email, $subject, $message,$headers,$attachments);
			 return $mailsent ; 
		}

		require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
		
		$mail = new PHPMailer();
		
		
		$mail->IsSMTP();
		
		/* If using smtp auth, set the username & password */
		if( 'yes' == $esig_options['smtp_settings']['autentication'] ){
			$mail->SMTPAuth = true;
			$mail->Username = $esig_options['smtp_settings']['username'];
			$mail->Password = $this->esig_mail_get_password() ; 
		}
		
		/* Set the SMTPSecure value, if set to none, leave this blank */
		if ( $esig_options['smtp_settings']['type_encryption'] !== 'none' ) {
			$mail->SMTPSecure = $esig_options['smtp_settings']['type_encryption'];
		}
		
		/* Set the other options */
		$mail->Host = $esig_options['smtp_settings']['host'];
		$mail->Port = $esig_options['smtp_settings']['port']; 
		$mail->SetFrom( $from_email, $from_name );
		$mail->isHTML( true );
		$mail->Subject = utf8_decode($subject);
		$mail->MsgHTML( $message );
		$mail->AddAddress( $to_email );
		
		// adding attachment if there is attachment 
		if($attachments)
		{
			$mail->addAttachment($attachments);
		}
		
		$mail->SMTPDebug = 0;

		/* Send mail and return result */
		if ( ! $mail->Send() )
			$errors = $mail->ErrorInfo;
		
		$mail->ClearAddresses();
		$mail->ClearAllRecipients();
			
		if ( ! empty( $errors ) ) {
			return $errors;
		}
		else{
			return true;
		}
	}
	
  public function esig_test_mail( $to_email, $subject, $message ) {
  	
		$errors = '';

		$esig_options = get_option( 'esig_mail_options' );

		require_once( ABSPATH . WPINC . '/class-phpmailer.php' );
		
		$mail = new PHPMailer();
		
		$from_name  = utf8_decode($esig_options['from_name_field']);
		$from_email = $esig_options['from_email_field']; 
		
		$mail->IsSMTP();
		
		/* If using smtp auth, set the username & password */
		if( 'yes' == $esig_options['smtp_settings']['autentication'] ){
			$mail->SMTPAuth = true;
			$mail->Username = $esig_options['smtp_settings']['username'];
			$mail->Password = $this->esig_mail_get_password() ; 
		}
		
		/* Set the SMTPSecure value, if set to none, leave this blank */
		if ( $esig_options['smtp_settings']['type_encryption'] !== 'none' ) {
			$mail->SMTPSecure = $esig_options['smtp_settings']['type_encryption'];
		}
		
		/* Set the other options */
		$mail->Host = $esig_options['smtp_settings']['host'];
		$mail->Port = $esig_options['smtp_settings']['port']; 
		$mail->SetFrom( $from_email, $from_name );
		$mail->isHTML( true );
		$mail->Subject = utf8_decode($subject);
		$mail->MsgHTML( $message );
		$mail->AddAddress( $to_email );
		$mail->SMTPDebug = 0;

		/* Send mail and return result */
		if ( ! $mail->Send() )
			$errors = $mail->ErrorInfo;
		
		$mail->ClearAddresses();
		$mail->ClearAllRecipients();
			
		if ( ! empty( $errors ) ) {
			return $errors;
		}
		else{
			return 'Test mail was sent';
		}
	}
	
}

