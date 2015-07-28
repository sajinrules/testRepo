<?php 

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

?>
<?php
	// To default a var, add it to an array
	$vars = array(
		'messages', // will default $data['messages']
		'form_head', 
		'more_options', 
		'pdf_options', 
		'form_tail'
	);
	$this->default_vals($data, $vars);
?>

<div id="page-loader-admin" class="cf" style="display:none;">
                 <div class="span">
                <h2 class="cf"> <?php 
                $user = new WP_E_User();
                $admin_user = $user->getUserByWPID(get_current_user_id());  
                echo $admin_user->first_name ;
                ?> , <?php _e('we\'re preparing your document... sip some coffee (or tea) while you wait.','esig') ; ?> </h2>
                    <div class="coffee_cup"></div>
                </div>
   </div>

	
<form  method="post" class="document-form" action="" id="document_form" enctype="multipart/form-data">	

<h2 class="esign-form-header"><?php _e('1. Enter your documents name', 'esig'); ?></h2>

<?php if (array_key_exists('messages', $data)) { echo $data['messages']; } ?>



	
	<?php echo $data['form_head']; ?>
    <?php $document_id = isset($_GET['document_id'])?$_GET['document_id']:null ; 
            if(empty($document_id))
                      {
                        
                         $document_id = $this->model->document_max()+1;
                      }
    ?>
    
    <input type="hidden" name="document_id" id="document_id" value="<?php echo $document_id; ?>" />
	<input type="hidden" name="document_action" id="document_action" value="add" />
	
				<div id="titlediv">
					<div id="titlewrap">
						<input type="text"  name="document_title" id="title" size="30" placeholder="Enter the document's name here" class="text-input span4" value="<?php echo $data['document_title']; ?>" />
						
					</div>
				</div>

				<div class="add_form_matt">				
					<h2 class="esign-form-header"><?php _e('2. What needs to be signed?', 'esig'); ?> <span class="settings-title"><?php _e('(copy and paste the content below)', 'esig'); ?></span></h2>
					
					<?php   echo $data['document_editor']; ?>
					
				</div>
				
				
			<div class="esign-form-panel basic_esign">
				
					<span class="invitations-container">	
					
					
					<div class="invitations-container-title">
					<div class="need_to_sign">
				<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" width="20px" align="left" />
					<span>
					<?php _e('Each signer is sent a signer specific URL and email invite to sign your document.', 'esig'); ?>
					</span>
					</a>
					</div>
					<div class="need_to_sign_right">
					<h2 class="esign-form-header"><?php _e('3. Who needs to sign this document?', 'esig'); ?></h2></div>
					</div>
					
					<div class="af-inner-doc">
						<div id="recipient_emails_ajax">
								<?php echo $data['recipient_emails']; ?>
						</div>
						<div class="esig-signer-container">
                             <span class="esig-signer-left"> <?php echo apply_filters('esig-signer-order-filter',''); ?>  <a href="#" id="standard_view"><?php _e('+ Add Signer', 'esig'); ?></a></span>
                             <span class="esig-signer-right-document"></span>
                        </div>
					</div>
					
					</span>
				
				<span class="settings-title"></span>
			</div>  
				
			<div class="esign-form-document-panel">	
				
				<!-- Start of Meta Box -->
	<div id="postimagediv" class="postbox">
       <h3 class="hndle"><span><?php _e('Document Options', 'esig'); ?></span></h3>
       <div class="inside">
       
       			<p>
					
					<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" width="20px" align="left" />
					<span>
					<?php _e('Receive an email notification everytime a signature is added to this document.', 'esig'); ?>
					</span>
					</a>
					
					<input type="checkbox" <?php echo $data['notify_check']; ?> id="notify" name="notify"> <?php _e('Notify me when a signature is added', 'esig'); ?>
				</p>

				<p>
					<a href="#" class="tooltip">
					<img src="<?php echo $data['ESIGN_ASSETS_DIR_URI']; ?>/images/help.png" height="20px" width="20px" align="left" />
					<span>
					<?php _e('Selecting this option will automatically attach your signature from the WP E-Signature admin panel to this document.', 'esig'); ?>
					</span>
					</a>
				
					<input type="checkbox" id="add_signature" <?php echo $data['add_signature_select']; ?> name="add_signature"> <?php echo $data['document_add_signature_txt']; ?>
				</p>
				
				<?php echo $data['more_options']; ?>
				
				<?php echo $data['more_contents']; ?>
				
				<p id="esignadvanced"><a href="#"><?php _e('(+) Advanced document settings', 'esig'); ?></a></p>
				<p id="esignadvanced-hide" style="display:none"><a href="#"><?php _e('(-) Hide Advanced document settings', 'esig'); ?></a></p>
				
				<span id="advanced-settings">
					<p>
						<h4><?php _e('Print Document button advanced options:', 'esig'); ?></h4>
						<select name="esig_print_option" data-placeholder="Choose a Option..." class="esig-select2" style="width:500px;" tabindex="2">
							<option value=""></option>                
							<option value="1" <?php echo $data['selected1']; ?>><?php _e('Only display \'Print Document\' button when document is signed by everyone', 'esig'); ?></option>
							<option value="2" <?php echo $data['selected2']; ?>><?php _e('Hide \'Print Document\' button always, no matter what.','esig'); ?></option>
							<option value="3" <?php echo $data['selected3']; ?>><?php _e('Display \'Print Document\' button always, no matter what.', 'esig'); ?></option>
							<option value="4" <?php echo $data['selected4']; ?>><?php _e('Only Display \'Print Document\' while document waiting for signature.', 'esig'); ?></option>
						</select>
					</p>
				
					<?php if(array_key_exists('pdf_options',$data)){ echo $data['pdf_options'];} ?>
					
					<?php if(array_key_exists('advanced_more_options',$data)){ echo $data['advanced_more_options'];} ?>
				</span>
       	</div>
     </div>
	 </div>
	
	<?php 
	
	
	 echo apply_filters('esig-add-document-form-meta-box',''); 
	
	?>
	
<!-- End of meta box -->


				<div id="esig_submit_section" class="esig-colum-conainer">
				   
                    <span class="esig-left">
					<input type="submit" value="Send Document" class="submit button button-primary button-large" id="submit_send"  name="send">
					<input type="submit" value="Save as draft" class="submit button button-secondary button-large" id="submit_save" name="save">
					</span>
                    <span class="esig-right" style="display:none;" id="esig-preview-document">
                    <?php
                      $esig_settings=new WP_E_Setting();
                      $default_page=$esig_settings->get_generic('default_display_page');
                      
                    ?>
                     <a href="<?php echo site_url(); ?>?page_id=<?php echo $default_page;  ?>&esigpreview=1&document_id=<?php echo $document_id; ?>&mode=1" target="_blank" class="submit button esig-preview button-large"> Preview Document </a>
                    </span>
                    
				</div>
                
                
                
				</form>	
				<?php if (array_key_exists('form_tail', $data)) { echo $data['form_tail']; } ?>
				
				
				<div class="af-inner_edit" id="standard_view_popup_edit" style="display:none;">
							 
			<span class="invitations-container_ajax">	
				<div align="center"><img src="<?php if (array_key_exists('ESIGN_ASSETS_DIR_URI', $data)) { echo $data['ESIGN_ASSETS_DIR_URI'];} ?>/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
					<h2 class="esign-form-header"><?php _e('Who needs to sign this document?', 'esig'); ?></h2>
		
					<div class="af-inner">
						<div id="recipient_emails">
							<?php if (array_key_exists('recipient_emails_ajax', $data)) { echo $data['recipient_emails_ajax'];} ?>
						</div><!-- [data-group=recipient-emails] -->
                        
                       <div class="esig-signer-container">
                             <span class="esig-signer-left"> <?php if (array_key_exists('esig-signer-order', $data)) { echo $data['esig-signer-order']; } ?>  &nbsp;</span>
                             <span class="esig-signer-right"><a href="#" id="addRecipient"><?php _e('+ Add Signer', 'esig'); ?></a></span>
                        </div> 
					
					</div>
			</span>
            <p align="center">
			<input type="button" value="Save Changes" class="submit button button-primary button-large" id="submit_signer_save"  name="signersave">
            
            
			</p>
			</div>		
			
		
		 <!-- Report bug form include here -->
    <?php 
    
    $common =new WP_E_Common();
    
    $common->esig_report_bug_form();
      
    ?>

