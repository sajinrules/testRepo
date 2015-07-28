<?php
class WP_E_Invite extends WP_E_Model {

	private $table;

	public function __construct(){
		parent::__construct();

		$this->table = $this->prefix . "invitations";
		
	}

	
	public function insert($invitation){

		$this->wpdb->query(
			$this->wpdb->prepare(
				"INSERT INTO " . $this->table . " (user_id, document_id, invite_hash, invite_message, invite_sent) VALUES(%d,%d,'%s','%s',0)", 
				$invitation['recipient_id'],
				$invitation['document_id'],
				$invitation['hash'],
				'' // TODO: Get rid of this column, `invite_message`
			)
		);
		return $this->wpdb->insert_id;
	}

	/**
	 * Records when an invite is sent
	 * 
	 * @since 1.0.1
	 * @param Int ($invitation_id), String ($date_sent) formatted as 0000-00-00 00:00:00
	 * @return void
	 */
	
	public function recordSent($invitation_id, $date_sent = null){
		
		$date_sent = $date_sent ? $date_sent : date("Y-m-d H:i:s");
		
		$this->wpdb->show_errors();
		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"UPDATE " . $this->table . " SET " . 
					"invite_sent='1', invite_sent_date='%s', sender_ip='%s' ".
					"WHERE invitation_id=%d",
				$date_sent,
				$_SERVER['REMOTE_ADDR'],
				$invitation_id
			)
		);
	
		return $result;
	}

	public function getInvitations($documentID){

		return $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " i 
				 INNER JOIN " . $this->prefix . "users u 
				 ON i.user_id = u.user_id AND i.document_id=%d", $documentID
			)
		);
	}
	
	public function get_all_Invitations_userID($user_id){

		return $this->wpdb->get_results(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE user_id = %d", $user_id
				)
			);
	}

	public function getInviteHash($invitationID){
		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT invite_hash FROM " . $this->table . " WHERE invitation_id = %d", $invitationID
			)
		);
	}
	
	public function getInviteHash_By_documentID($documentID){
		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT invite_hash FROM " . $this->table . " WHERE document_id = %d", $documentID
			)
		);
	}
	
	public function getuserid_By_invitehash($invite_hash){
		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT user_id FROM " . $this->table . " WHERE invite_hash = %d", $invite_hash
				)
			);
	}
	
	public function getdocumentid_By_invitehash($invite_hash){
		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT document_id FROM " . $this->table . " WHERE invite_hash = %d", $invite_hash
				)
			);
	}
    
    public function getInviteID_By_userID_documentID($user_id,$documentID){
		return $this->wpdb->get_var(
			$this->wpdb->prepare(
				"SELECT invitation_id FROM " . $this->table . " WHERE user_id=%d and document_id = %d", $user_id,$documentID
			)
		);
	}

	public function getInviteBy($field, $strvalue){
	 return	$invite = $this->wpdb->get_row(
			$this->wpdb->prepare(
				"SELECT * FROM " . $this->table . " WHERE $field = '%s'", $strvalue
			)
		);
		//return $invite[0];
	}	
	
	/**
	 * Deletes all invitations for a given document
	 */
	public function deleteDocumentInvitations($doc_id){
		return $this->wpdb->delete($this->table, array('document_id' => $doc_id), '%d');
	}
	
	
	/**
	 * Return Total invitation Row Count
	 *
	 * @since 0.1.0
	 * @param null
	 * @return Int
	 */
	public function getInvitationTotal($userid,$doc_id){

		return $this->wpdb->get_var("SELECT COUNT(*) FROM " . $this->table . " WHERE user_id=". $userid ." and document_id=". $doc_id ."");

	}
    
    public function getInvitationCount($doc_id){
    
                if(empty($doc_id)){
                   return false;
                }
		return $this->wpdb->get_var("SELECT COUNT(*) FROM " . $this->table . " WHERE document_id=". $doc_id ."");

	}
	
	public function getInvitationExists($doc_id){

		return $this->wpdb->get_var("SELECT COUNT(*) FROM " . $this->table . " WHERE document_id=". $doc_id ."");

	}

	public function fetchAll(){
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM " . $this->table);
	}
	
    
	public function send_invitation($invitation_id,$signer_id,$document_id){
		
		$this->user = new WP_E_User();
		$this->document = new WP_E_Document();
		$this->setting = new WP_E_Setting();
		$this->view = new WP_E_View();
        $this->mail = new WP_E_Email();
		// Prepare invitation message
		$invite_template = file_get_contents(ESIGN_PLUGIN_PATH . DS . 'views' . DS . 'invitations' . DS . 'invite.php');

		$pageID = $this->setting->get('default_display_page');

		$invite_hash = $this->getInviteHash($invitation_id);
        $invite_checksum =$this->document->document_checksum_by_id($document_id);

		$invitationURL = esc_url(add_query_arg(array('invite'=>$invite_hash, 'csum'=>$invite_checksum), get_permalink($pageID)));
		
		$users= $this->user->getUserBy('user_id',$signer_id);
        
		$document=$this->document->getDocument($document_id);
        
		$user_id = $users->user_id ; 
		$user_details=$this->user->getUserdetails($user_id, $document_id);
		$admin_user = $this->user->getUserByWPID(get_current_user_id());
		$sender_name = $admin_user->first_name . " " . $admin_user->last_name;
		
        // adding required filter 
        
        $esig_logo = apply_filters('esig_invitation_logo_filter','');
        if(empty($esig_logo)){
             $esig_logo = sprintf( __( '<a href="http://www.approveme.me/?ref=1" target="_blank"><span class="wp-e-signature-logo"></span></a> ', 'esig'), ESIGN_ASSETS_DIR_URI )  ; 
        }
        
        $esig_header_tagline = apply_filters('esig_invitation_header_tagline_filter','');
        if(empty($esig_header_tagline)){
             $esig_header_tagline = __( 'Sign Legally Binding Documents using a WordPress website', 'esig')  ; 
        }
        
        $esig_footer_head = apply_filters('esig_invitation_footer_head_filter','');
        if(empty($esig_footer_head)){
             $esig_footer_head = __( 'What is WP E-Signature?', 'esig')  ; 
        }
        
        $esig_footer_text = apply_filters('esig_invitation_footer_text_filter','');
        if(empty($esig_footer_text)){
            $esig_footer_text = __( 'WP E-Signature by Approve Me is the
                                fastest way to sign and send documents
                                using WordPress. Save a tree (and a
                                stamp).  Instead of printing, signing
                                and uploading your contract, the
                                document signing process is completed
                                using your WordPress website. You have
                                full control over your data - it never
                                leaves your server. <br>
                                <b>No monthly fees</b> - <b>Easy to use
                                  WordPress plugin.</b><a style="color:#368bc6;text-decoration:none" href="http://www.approveme.me/wp-digital-e-signature/?ref=1" target="_blank"> Learn more</a> ', 'esig');
        }
        
        $admin_user = $this->user->getUserByWPID(get_current_user_id());
         $sender= $admin_user->first_name . " " .  $admin_user->last_name ;
        
        $sender =apply_filters('esig-sender-name-filter',$sender);
        
		$template_data = array(
            'esig_logo'=> $esig_logo , 
            'esig_header_tagline'=>$esig_header_tagline,
            'esig_footer_head'=>$esig_footer_head,
            'esig_footer_text'=>$esig_footer_text,
			'user_email' => $users->user_email,
			'user_full_name' =>$sender,
			'recipient_name' =>$user_details->first_name,
			'document_title' => $document->document_title,
			'document_checksum' => $document->document_checksum,
			'invite_url' => $invitationURL,
			'assets_dir' => ESIGN_ASSETS_DIR_URI,
			);
		
		
		

		$invite_message = $this->view->renderPartial('invite', $template_data, false, 'invitations');
		
		$subject = $document->document_title . " - Signature requested by " .  $sender ;
		// send Email
		
		$mailsent =  $this->mail->esig_mail($sender,$admin_user->user_email,$users->user_email, $subject, $invite_message);
		
		// Record event: Document sent
		$this->recordSent($invitation_id);

		return $mailsent;
	}
    
   
    
}