<?php
/**
 * Template Name: We Cross Account - Metric 
 *
 * The template and functionality for displaying an account
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
get_header(); 

//echo "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
 
$wp_user = wp_get_current_user(); 
um_fetch_user($wp_user->ID);
function show_post($path) {
  $post = get_page_by_path($path);
  $content = apply_filters('the_content', $post->post_content);
  echo $content;
}

function my_content($content) {
    $page = get_page_by_title('social-connect');
    
    if ( is_page($page->ID) )
		echo 'df';
        $content = "Hello World!";
        return $content;
}
  $url=$_SERVER["REQUEST_URI"];
/*if($_SERVER["REQUEST_URI"] ="/account/privacy/?updated=account"){
	
	wp_redirect( get_site_url(), 301 ); exit;
}
echo $url=$_SERVER["REQUEST_URI"];*/

 //print_r($_POST);

if (!empty($_POST)){
  if($_POST['SubmitButton']){
	header('Cache-Control: no-cache, must-revalidate');
	header('Location: '.$_SERVER['REQUEST_URI']);
	echo '<div class="alert alert-success alert-dismissible" role="alert">
	 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Profile image updated successfully</div>';
   //print_r($_POST);exit;
  }elseif($_POST['changePwd']){
  //define('WP_CACHE', true);
 $userid = $_POST['userId'];
 //$pass='$P$BZB33Z5wnaou1rOXephsJ2tnDM7Brd/';
 //$pass='EvvX37uUa%D#-J4s';
 $pass=$_POST['cur_pwd'];
 $username=$wp_user->user_login;
// echo $hash = wp_hash_password($password);
 $user = get_user_by( 'login', $username );
if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID) ) {
  global $wpdb;
  $password=$_POST['new_pwd'];
$newPassword = ltrim($password);
$hash = wp_hash_password($newPassword);
//echo $hash;exit;
$updatePwd=$wpdb->update('wp_users', array('user_pass' => $hash, 'user_activation_key' => ''), array('ID' => $user->ID) );
if($updatePwd){
    $user = get_user_by( 'id', $userid ); 
if( $user ) {
    wp_set_current_user( $userid, $user->user_login );
    wp_set_auth_cookie( $userid );
  //  do_action( 'wp_login', $user->user_login );
}
	echo '<div class="alert alert-success alert-dismissible" role="alert">
	 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Password changed successfully</div>';

 
	//wp_set_current_user( $userid, $user->user_login );
   // wp_set_auth_cookie( $userid );
    //do_action( 'wp_login', $user->user_login );
}
//wp_cache_delete($user_id, 'users');

}else{
  echo '<div class="alert alert-danger alert-dismissible" role="alert">
	 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Current password is not correct</div>';
}
 


if($check){
  
}
  }elseif($_POST['um_account_submits'] =='Vernieuw privacy'){
	//header('Cache-Control: no-cache, must-revalidate');
	header('Location: '.$_SERVER['REQUEST_URI']);
	 //   $url=$_SERVER["REQUEST_URI"];
	
	// wp_redirect($url, 301 ); exit;	
	 
	 
	 echo '<div class="alert alert-success alert-dismissible" role="alert">
	 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	Profile settings changed successfully</div>';
	
	echo "<script>setTimeout(\"location.href = window.location.href ;\",1500);</script>";
	 
	   
  }else{}
/* change password ----end */
// Do operations here...
}
/* change password */
 
?>
<div class="page-content-wrapper">
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Modal title</h4>
					</div>
					<div class="modal-body">
						 Widget settings form goes here
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue">Save changes</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<?php // include('menu-options.php'); ?>
		<div class="page-head">
			
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php the_title(); ?></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
		<div class="page-breadcrumb breadcrumb">
		<?php
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb();
			}
		?>
		</div>
		<div class="row">
			<div class="col-md-12 left-sidepanel">
					<!-- BEGIN PROFILE SIDEBAR -->
					<div class="profile-sidebar" style="width:250px;">
						<!-- PORTLET MAIN -->
						<div class="portlet light profile-sidebar-portlet">
							<!-- SIDEBAR USERPIC -->
							<div class="profile-userpic">
							  
							  <?php
							  //$imageURL="http://wecross.dev.wecross.nl/wp-content/uploads/ultimatemember/{echo get_userimg_url($wp_user->ID);}/profile_photo-190.jpg?1436793679";
							 // if (getimagesize($imageURL) !== false) {
							  // display image
							 // }
							  ?>
							  <?php // echo $wp_user->ID;
							  //
							  
							   
							  ?>
							 <!-- <img src="http://wecross.dev.wecross.nl/wp-content/uploads/ultimatemember/2/profile_photo-190.jpg?1436793679" class="gravatar avatar avatar-96 um-avatar" width="96" height="96" alt="">-->
								<!--<img src="<?php //echo get_userimg_url($wp_user->ID); ?>" class="img-responsive" alt="">-->
								<img src="http://wecross.dev.wecross.nl/wp-content/uploads/ultimatemember/<?php echo $wp_user->ID; ?>/profile_photo-190.jpg?1436793679" class="img-responsive" alt="">
							</div>
							<!-- END SIDEBAR USERPIC -->
							<!-- SIDEBAR USER TITLE -->
							<div class="profile-usertitle">
								<div class="profile-usertitle-name">
									<?php echo um_user('display_name'); ?>
								</div>
								<div class="profile-usertitle-job">
									<?php echo um_user('functie'); ?>
								</div>
							</div>
							<!-- END SIDEBAR USER TITLE -->
							<!-- SIDEBAR BUTTONS -->
							<div class="profile-userbuttons">
								<button type="button" class="btn btn-circle green-haze btn-sm">Follow</button>
								<button type="button" class="btn btn-circle btn-danger btn-sm">Message</button>
							</div>
							<!-- END SIDEBAR BUTTONS -->
							<!-- SIDEBAR MENU -->
							<?php
							  $urlDashboard=$_SERVER["REQUEST_URI"];
							//if($url == '/account-2/'){$active == "active";
							//}
							//$active=($uri =='/account-2/') ? "active" : ""; 
							?>
							<div class="profile-usermenu">
								<ul class="nav">
									<li class='<?php echo ($urlDashboard =='/account-2/') ? "active" : " ";  ?>' >
										<a href="/account-2">
										<i class="icon-home"></i>
										Overview </a>
									</li>
									<li class='<?php echo ($urlDashboard =='/account-2/?profiletab=main&um_action=edit') ? "active" : "";  ?>'>
										<a href="?profiletab=main&um_action=edit">
										<i class="icon-settings"></i>
										Account Settings </a>
									</li>
										<li class='<?php echo ($urlDashboard =='/account-2/?profiletab=notify&um_action=edit') ? "active" : "";  ?>'>
										<a href="?profiletab=notify&um_action=edit">
										<i class="icon-settings"></i>
										Notifications </a>
									</li>
									<li class='<?php echo ($urlDashboard =='/tasks/') ? "active" : "";  ?>'>
										<a href="page_todo.html" target="_blank">
										<i class="icon-check"></i>
										Tasks </a>
									</li>
									<li class='<?php echo ($urlDashboard =='/help/') ? "active" : "";  ?>'>
										<a href="extra_profile_help.html">
										<i class="icon-info"></i>
										Help </a>
									</li>
								</ul>
							</div>
							<!-- END MENU -->
						</div>
						<!-- END PORTLET MAIN -->
						<!-- PORTLET MAIN -->
						<div class="portlet light">
							<!-- STAT -->
							<div class="row list-separated profile-stat">
								<div class="col-md-4 col-sm-4 col-xs-6">
									<div class="uppercase profile-stat-title">
										 37
									</div>
									<div class="uppercase profile-stat-text">
										 Projects
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-6">
									<div class="uppercase profile-stat-title">
										 51
									</div>
									<div class="uppercase profile-stat-text">
										 Tasks
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-6">
									<div class="uppercase profile-stat-title">
										 61
									</div>
									<div class="uppercase profile-stat-text">
										 Uploads
									</div>
								</div>
							</div>
							<!-- END STAT -->
							<div>
								<h4 class="profile-desc-title">About Marcus Doe</h4>
								<span class="profile-desc-text"> Lorem ipsum dolor sit amet diam nonummy nibh dolore. </span>
								<div class="margin-top-20 profile-desc-link">
									<i class="fa fa-globe"></i>
									<a href="http://www.keenthemes.com">www.keenthemes.com</a>
								</div>
								<div class="margin-top-20 profile-desc-link">
									<i class="fa fa-twitter"></i>
									<a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
								</div>
								<div class="margin-top-20 profile-desc-link">
									<i class="fa fa-facebook"></i>
									<a href="http://www.facebook.com/keenthemes/">keenthemes</a>
								</div>
							</div>
						</div>
						<!-- END PORTLET MAIN -->
					</div>
					<!-- END BEGIN PROFILE SIDEBAR -->
		<?php if($urlDashboard =='/account-2/?profiletab=main&um_action=edit' || $urlDashboard =='/account-2/' ) {?>			
					
					<!-- BEGIN PROFILE CONTENT -->
					<div class="profile-content">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption caption-md">
											<i class="icon-globe theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
</div>
  <ul class="nav nav-tabs">
  <?php //echo $curUrl;?>
	<li class="active">
	  <a href="#tab_1_1" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=personal';return false" id="personalInfo">Personal Info</a>
	</li>
	<li>
	  <a href="#tab_1_2" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=avatar';return false" id="avatar">Change Avatar</a>
	</li>
	<li>
	  <a href="#tab_1_3" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=password';return false" id="password">Change Password</a>
	</li>
	<li>
	  <a href="#tab_1_4" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=privacy';return false" id="privacy">Privacy Settings</a>
	</li>
	<li>
	  <a href="#tab_1_5" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=social';return false" id="social">Social Connect</a>
	</li>
	<li>
	  <a href="#tab_1_6" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=delete';return false" id="delete">Delete Account</a>
	</li>
  </ul>
</div>
<div class="portlet-body">
<?php
// For fetching the contents from the page
// Fetching data from the shortcodes
	if(have_posts()) {
		while (have_posts()) {
		  the_post();
		  the_content(); 
		}
	}
?>
<div class="tab-content">
	<!-- PERSONAL INFO TAB . Default content loading -->
	<div class="tab-pane active" id="tab_1_1">
	<script type="text/javascript">											
	  $(".um-header").css("display","none");
	</script>
	</div>
  <!-- END PERSONAL INFO TAB -->
  <!-- CHANGE AVATAR TAB -->
	<div class="tab-pane" id="tab_1_2">
	  <div class="clearfix margin-top-10"></div>
		Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
	  <div class="clearfix margin-top-20"></div>
		<?php do_action('um_profile_header', $args ); //loading change avatar pluign action ?>
	  <form action="" method="post">
		<div class="margin-top-10">
		  <input type="submit" name="SubmitButton"  class="btn green-haze"/> 
			<a href="javascript:;" class="btn default">Cancel </a>
			<a href="javascript:;" class="btn default fileinput-exists" class="um-reset-profile-photo" data-dismiss="fileinput" data-user_id="2">Remove </a>
		</div>
	  </form>									
	  <div class="clearfix margin-top-20">
	  <span class="label label-danger">NOTE! </span>
	  <span> &nbsp; Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
	  </div> 
	</div>
  <!-- END CHANGE AVATAR TAB -->
  <!-- CHANGE PASSWORD TAB -->
	<div class="tab-pane" id="tab_1_3">
	  <?php $user_ID = get_current_user_id();?> 
												<form action="#" method="POST" id="resetPassword">
													<div class="form-group">
														<label class="control-label" id="curpwd_label">Current Password</label>
														<input type="password" class="form-control" name="cur_pwd" id="cur_pwd">
													</div>
													<div class="form-group">
														<label class="control-label" id="newpwd_label">New Password</label>
														<input type="password" class="form-control" name="new_pwd" id="new_pwd">
													</div>
													<div class="form-group">
														<label class="control-label" id="repwd_label">Re-type New Password</label>
														<input type="password" class="form-control" name="re_pwd" id="re_pwd">
													</div>
													<div class="margin-top-10">
													  <input type="button" id="resetPwd"  class="btn green-haze" name="save_data" value="Change Password " />
														
														<a href="javascript:;" class="btn default" id="cancelset">
														Cancel </a>
													</div>
													<input type="hidden" name="userId" value = "<?php echo $user_ID; ?>" />
													<input type="hidden" name="changePwd" value = "change" />
												</form>
											</div>
											<!-- END CHANGE PASSWORD TAB -->
											<!-- PRIVACY SETTINGS TAB -->
											<div class="tab-pane" id="tab_1_4">
											
									<div class="table table-light table-hover">		
							 <div class="um-form">
	
		<form method="post" action="#" id="um_privacy_form">
			
			<?php do_action('um_account_page_hidden_fields', $args ); ?>
			
			<?php //do_action('um_account_user_photo_hook__mobile', $args ); ?>
			
			<div class="um-account-side uimob340-hide uimob500-hide">
			
				<?php //do_action('um_account_user_photo_hook', $args ); ?>
				
				<?php //do_action('um_account_display_tabs_hook', $args ); ?>

			</div>
			
			<div class="um-account-main" data-current_tab="<?php echo $ultimatemember->account->current_tab; ?>">
			
				<?php
				
				$current_tab=$ultimatemember->account->current_tab='privacy';
				
				//echo $current_tab = $ultimatemember->account->current_tab;
				do_action('um_before_form', $args);
				do_action('um_account_tab__privacy', $args );
				//echo '<pre>';print_r($ultimatemember->account->tabs);
				/*foreach( $ultimatemember->account->tabs as $k => $arr ) {

					foreach( $arr as $id => $info ) { extract( $info );
					
						$current_tab = $ultimatemember->account->current_tab;

						if ( isset($info['custom']) || um_get_option('account_tab_'.$id ) == 1 || $id == 'general' ) {

							?>
							
							<div class="um-account-nav uimob340-show uimob500-show"><a href="#" data-tab="<?php echo $id; ?>" class="<?php if ( $id == $current_tab ) echo 'current'; ?>"><?php echo $title; ?>
								<span class="ico"><i class="<?php echo $icon; ?>"></i></span>
								<span class="arr"><i class="um-faicon-angle-down"></i></span>
							</a></div>
							
							<?php
							
							echo '<div class="um-account-tab um-account-tab-'.$id.'" data-tab="'.$id.'">';

								do_action("um_account_tab__{$id}", $info );
							
							echo '</div>';
						
						}
						
					}
					
				}*/
				
				?>
				
			</div><div class="um-clear"></div>
			<div class="um-cancel-privacy">
		
			<a href="#" class="btn default" id="cancel-privacy">
			Cancel </a>
			</div>
			
		</form>
		
	<?php do_action('um_after_account_page_load'); ?>
	
</div>

</div>	
	 		
										<!--	
												<form action="#">
													<table class="table table-light table-hover">
													<tbody><tr>
														<td>
															 Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus..
														</td>
														<td>
															<label class="uniform-inline">
															<div class="radio"><span><input type="radio" name="optionsRadios1" value="option1"></span></div>
															Yes </label>
															<label class="uniform-inline">
															<div class="radio"><span class="checked"><input type="radio" name="optionsRadios1" value="option2" checked=""></span></div>
															No </label>
														</td>
													</tr>
													<tr>
														<td>
															 Enim eiusmod high life accusamus terry richardson ad squid wolf moon
														</td>
														<td>
															<label class="uniform-inline">
															<div class="checker"><span><input type="checkbox" value=""></span></div> Yes </label>
														</td>
													</tr>
													<tr>
														<td>
															 Enim eiusmod high life accusamus terry richardson ad squid wolf moon
														</td>
														<td>
															<label class="uniform-inline">
															<div class="checker"><span><input type="checkbox" value=""></span></div> Yes </label>
														</td>
													</tr>
													<tr>
														<td>
															 Enim eiusmod high life accusamus terry richardson ad squid wolf moon
														</td>
														<td>
															<label class="uniform-inline">
															<div class="checker"><span><input type="checkbox" value=""></span></div> Yes </label>
														</td>
													</tr>
													</tbody></table>-->
													<!--end profile-settings-->
												 <!--	<div class="margin-top-10">
														<a href="javascript:;" class="btn green-haze">
														Save Changes </a>
														<a href="javascript:;" class="btn default">
														Cancel </a>
													</div>
												</form> -->
											</div>
											<!-- END PRIVACY SETTINGS TAB -->
<!-- SOCIAL CONNECT TAB -->
<div class="tab-pane" id="tab_1_5">
 
 <div class="um-form">
			
		 
		
		
		<?php do_action('um_account_page_hidden_fields', $args ); ?>
			 
<div class="um-account-main" data-current_tab="<?php echo $ultimatemember->account->current_tab; ?>">
			
				<?php
				
				$current_tab=$ultimatemember->account->current_tab='social';
				
				//echo $current_tab = $ultimatemember->account->current_tab;
				do_action('um_before_form', $args);
				do_action('um_account_tab__social', $args );
				?>
				</div>
			 <div class="um-clear"></div>
  				<!--
				<div  class="um-social-wecross col-md-6">
						<h3>Connect to Facebook</h3>
						<a href="https://www.facebook.com/v2.2/dialog/oauth?client_id=APP_ID&redirect_uri=http%3A%2F%2Fwecross.dev.wecross.nl%2F%3Ffacebook_auth%3Dtrue&state=f0b14267ccd1723d7520f848281b07de&sdk=php-sdk-4.0.16&scope=public_profile%2C+email" title="Sign in with Facebook" class="um-button um-alt um-button-social um-button-facebook"><i class="um-faicon-facebook" style="margin-right: 8px;"></i><span>Sign in with Facebook</span></a></div>
								
				<div  class="um-social-wecross col-md-6">
						<h3>Connect to Twitter</h3>
						<a href="https://api.twitter.com/oauth/authenticate?" title="Sign in with Twitter" class="um-button um-alt um-button-social um-button-twitter"><i class="um-faicon-twitter" style="margin-right: 8px;"></i><span>Sign in with Twitter</span></a></div>
								
				<div  class="um-social-wecross col-md-6">
						<h3>Connect to Google +</h3>
						<a href="https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri=http%3A%2F%2Fwecross.dev.wecross.nl%2F&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&access_type=online&approval_prompt=auto" title="Sign in with Google+" class="um-button um-alt um-button-social um-button-google"><i class="um-faicon-google-plus" style="margin-right: 8px;"></i><span>Sign in with Google+</span></a></div>
								
				<div  class="um-social-wecross col-md-6">
						<h3>Connect to LinkedIn</h3>
						<a href="" title="Sign in with LinkedIn" class="um-button um-alt um-button-social um-button-linkedin"><i class="um-faicon-linkedin" style="margin-right: 8px;"></i><span>Sign in with LinkedIn</span></a></div>
					-->			
				
  </div>
  
  
  <style type="text/css">
		
			div#um-shortcode-social-160 div.um-field {padding: 0}
			
			div#um-shortcode-social-160 a.um-button.um-button-social {
				font-size: 15px;
				padding: 16px 20px !important;
			}
			
			div#um-shortcode-social-160 a.um-button.um-button-social i {
				font-size: 18px;
				width: 18px;
				top: auto;
				vertical-align: baseline !important;
				margin-right: 0;
			}
			
						
						
						
			div#um-shortcode-social-160 a.um-button.um-button-social {
				display: inline-block !important;
				float: none !important;
				margin-bottom: 10px !important;
				width: auto;
							}
			
			div#um-shortcode-social-160 div.um-field {text-align: center}
			
						
						
			div#um-shortcode-social-160 a.um-button.um-button-facebook {background-color: #3b5998!important}
			div#um-shortcode-social-160 a.um-button.um-button-facebook:hover {background-color: #324D84!important}
			div#um-shortcode-social-160 a.um-button.um-button-facebook {color: #fff!important}
			
						
			div#um-shortcode-social-160 a.um-button.um-button-twitter {background-color: #55acee!important}
			div#um-shortcode-social-160 a.um-button.um-button-twitter:hover {background-color: #4997D2!important}
			div#um-shortcode-social-160 a.um-button.um-button-twitter {color: #fff!important}
			
						
			div#um-shortcode-social-160 a.um-button.um-button-google {background-color: #dd4b39!important}
			div#um-shortcode-social-160 a.um-button.um-button-google:hover {background-color: #BE4030!important}
			div#um-shortcode-social-160 a.um-button.um-button-google {color: #fff!important}
			
						
			div#um-shortcode-social-160 a.um-button.um-button-linkedin {background-color: #0976b4!important}
			div#um-shortcode-social-160 a.um-button.um-button-linkedin:hover {background-color: #07659B!important}
			div#um-shortcode-social-160 a.um-button.um-button-linkedin {color: #fff!important}
			
			
.um-social-wecross {
  padding: 15px 0;
  margin: 0 auto;
  text-align: center;
}
			.um-button{
				border-radius: 25px!important;
			}
			div#um-shortcode-social-160 a.um-button.um-button-social{
				  padding: 10px 20px !important;
			}
		  .um-social-wecross h3{
				font-size: 15px;
				font-weight: bold;
				padding-bottom: 15px;
		  }
		</style>

	
	
	
	
											</div>
											<!-- END SOCIAL CONNECT TAB -->
<!-- DELETE ACCOUNT TAB -->
<div class="tab-pane" id="tab_1_6">
 
    
	   <div class="um-form">
			
		   <form method="post" action="#" id="um_delete_form">
		<?php do_action('um_account_page_hidden_fields', $args ); ?>
			 
<div class="um-account-main" data-current_tab="<?php echo $ultimatemember->account->current_tab; ?>">
			
				<?php
				
				$current_tab=$ultimatemember->account->current_tab='delete';
				
				//echo $current_tab = $ultimatemember->account->current_tab;
				do_action('um_before_form', $args);
				do_action('um_account_tab__delete', $args );
				?>
				</div>
			 <div class="um-clear"></div>
  				</form>	
  </div>

	  
	  
	  
	<script type="text/javascript">											
	  $(".um-header").css("display","none");
	</script>
	 		
								 
											 
  <div class="clearfix margin-top-20"></div>
  
  
  <style type="text/css">
		
			 
 
			.um-button{
				border-radius: 25px!important;
			}
			 
		  
		  #tab_1_6 .um-account-main label, .um-field-single_user_password .um-field-area {
			float: left !important;
			clear: both;
		  }
		  .um-field-single_user_password input.um-form-field {
			border: 1px solid #ccc;
		  }
		</style>

	
	
	
	
											</div>
											<!-- END DELETE ACCOUNT TAB -->											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END PROFILE CONTENT -->
					<?php }elseif($urlDashboard =='/account-2/?profiletab=notify&um_action=edit'){ ?>
					
					<!-- NOTIFICATION CONTENTS -->
					
					
				  <div class="profile-content">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light">
									<div class="portlet-title tabbable-line">
										<div class="caption caption-md">
											<i class="icon-globe theme-font hide"></i>
											<span class="caption-subject font-blue-madison bold uppercase">Profile Account</span>
</div>
  <ul class="nav nav-tabs">
  <?php //echo $curUrl;?>
	<li class="active">
	  <a href="#tab_1_1" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=personal';return false" id="personalInfo">Email Notifications</a>
	</li>
	<li>
	  <a href="#tab_1_2" data-toggle="tab" onclick="location.href = $(this).attr('href')+'?q=avatar';return false" id="avatar">Web Notifications</a>
	</li> 
  </ul>
</div>
<div class="portlet-body">
<?php
// For fetching the contents from the page
// Fetching data from the shortcodes
	if(have_posts()) {
		while (have_posts()) {
		  the_post();
		  the_content(); 
		}
	}
?>
<div class="tab-content">
	<!-- PERSONAL INFO TAB . Default content loading -->
	<div class="tab-pane active" id="tab_1_1">
	  
	  
	   <div class="um-form">
			
		 <form method="post" action="#" id="um_notify_form">
		
		
		<?php do_action('um_account_page_hidden_fields', $args ); ?>
			 
<div class="um-account-main" data-current_tab="<?php echo $ultimatemember->account->current_tab; ?>">
			
				<?php
				
				$current_tab=$ultimatemember->account->current_tab='notifications';
				
				//echo $current_tab = $ultimatemember->account->current_tab;
				do_action('um_before_form', $args);
				do_action('um_account_tab__notifications', $args );
				?>
				</div>
			 <div class="um-clear"></div>
  				</form>	
				
  </div>

	  <div class="clearfix margin-top-20"></div>
	  
	  
	<script type="text/javascript">											
	  $(".um-header").css("display","none");
	</script>
	</div>
  <!-- END PERSONAL INFO TAB -->
  <!-- CHANGE AVATAR TAB -->
	<div class="tab-pane" id="tab_1_2">
 
		 
		 	   <div class="um-form">
			
		 <form method="post" action="#" id="um_webnotify_form" name="um_webnotify_form">
		
		
		<?php do_action('um_account_page_hidden_fields', $args ); ?>
			 
<div class="um-account-main" data-current_tab="<?php echo $ultimatemember->account->current_tab; ?>">
			
				<?php
				
				$current_tab=$ultimatemember->account->current_tab='webnotifications';
				
				//echo $current_tab = $ultimatemember->account->current_tab;
				do_action('um_before_form', $args);
				do_action('um_account_tab__webnotifications', $args );
				?>
				</div>
			 <div class="um-clear"></div>
  				</form>	
				
  </div>

		 
		 
		 
	</div>
  <!-- END CHANGE AVATAR TAB -->
  <!-- CHANGE PASSWORD TAB -->
											<!-- END CHANGE PASSWORD TAB -->
											<!-- PRIVACY SETTINGS TAB -->
											<!-- END PRIVACY SETTINGS TAB -->
<!-- SOCIAL CONNECT TAB -->
											<!-- END SOCIAL CONNECT TAB -->
<!-- DELETE ACCOUNT TAB -->
											<!-- END DELETE ACCOUNT TAB -->											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- END NOTIFICATION CONTENTS -->
					
					
					
					<?php } ?>
					
				</div>
		</div>
		
		
	</div>
</div>
<!--
 Code for tab based view
-->
<script type="text/JavaScript">
  $(document).ready(function(){
		$("a").click(function(event) {
		  var id=$(this).attr('id');
		  //console.log(id);
		  if (id =='personalInfo') {
			$(".tab-pane, .um-col-alt").css("display","block");
			$(".um-header").css("display","none");
			$("#tab_1_2, #tab_1_3, #tab_1_4, #tab_1_5, #tab_1_6").css("display","none"); 		  
		  }else if(id =='avatar'){
			$(".tab-pane, .um-col-alt, #tab_1_4").css("display","none");
			$(".um-header, #tab_1_2").css("display","block");
		  }else if(id =='password'){ 
			$(".tab-pane, .um-header, .um-col-alt, #tab_1_2, #tab_1_4, #tab_1_5, #tab_1_6").css("display","none");
			$("#tab_1_3").css("display","block");													  
		 }else if(id =='privacy'){
			$(".tab-pane, .um-header, .um-col-alt, #tab_1_2, #tab_1_3, #tab_1_5, #tab_1_6").css("display","none");
			$("#tab_1_4").css("display","block");
		 }else if(id =='social'){
			$(".tab-pane, .um-header, .um-col-alt,#tab_1_1, #tab_1_3, #tab_1_2, #tab_1_4, #tab_1_6").css("display","none");
			$("#tab_1_5").css("display","block");
		 }else if(id =='delete'){
			$(".tab-pane, .um-header, .um-col-alt,#tab_1_1, #tab_1_3, #tab_1_2, #tab_1_4,#tab_1_5").css("display","none");
			$("#tab_1_6").css("display","block");
		 }
		 
			//console.log($(this).attr('id'));
		});
		
		
		$("#resetPwd").click(function()
    {
	  validateForm();
	
	/*  $("#resetPassword").submit(function(){
		  alert('Submitted');
		  return true;
		  }); */
	 
	 $(document).on('submit','#resetPassword',function(){
   // code
   // alert('submitted');
});
	 
        $("#resetPassword").submit(function(){
		   
			// e.preventDefault();  //prevent form from submitting
        var data = $("#resetPassword :input").serializeArray();
        console.log(data); //use the console for debugging, F12 in Chrome, not alerts
		   
		  });
 
    });
		
		
		
		   
		/**
		 * Form validation and action for password reset
		 **/
		 $('#resetPwd').click(function(e) {
		 // alert('Clicked');
		 
        //
    
		e.preventDefault();
    });
		 function validateForm(){


    var nameReg = /^[A-Za-z]+$/;
    var numberReg =  /^[0-9]+$/;
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

    var oldPassword = $('#cur_pwd').val();
    var newPassword = $('#new_pwd').val();
    var rePassword = $('#re_pwd').val();
  //  var telephone = $('#telInput').val();
   // var message = $('#messageInput').val();

    var inputVal = new Array(oldPassword, newPassword, rePassword);
 
    var inputMessage = new Array("current password", "new password", "retype new password", "Password doesn't match!", "message");

     $('.error').hide();
//alert(inputVal[0]);
        if(inputVal[0] == ""){ 
            $('#curpwd_label').after('<span class="alert alert-danger" id="alertpw"> Please enter your ' + inputMessage[0] + '</span>');
       // } 
       // else if(!nameReg.test(oldPassword)){
           // $('#control-label').after('<span class="alert alert-danger"> Letters only</span>');
        }else if(inputVal[1] == ""){
            $('#newpwd_label').after('<span class="alert alert-danger" id="alertnpw"> Please enter your ' + inputMessage[1] + '</span>');
        }else if(inputVal[2] == ""){
		  $('#alertmismatchpw').hide();
            $('#repwd_label').after('<span class="alert alert-danger" id="alertrepw"> Please  ' + inputMessage[2] + '</span>');
        } else{
		  if(inputVal[1] != inputVal[2]){
			$('#alertrepw').hide();
			$('#repwd_label').after('<span class="alert alert-danger" id="alertmismatchpw"> ' + inputMessage[3] + '</span>');
			return false;
		  }  
		   //alert('old');
		 // alert('Ok');
		 // console.log($(this));
		  //$('.form-group').closest('#alertpw').remove();
			$('#alertpw').hide();
			 $('#alertnpw').hide();
		    $('#alertrepw').hide();
		  //$('#curpwd_label').remove('btn default');
		  $("#resetPassword").submit();
		  return true;
		
		}
              
}  
		 $("#cancelset").click(function() {
		   $('#resetPassword')[0].reset();
		  });
		 
		 
  
  $("#avatar_form").click(function(){
  $('#avatarForm').submit();
	
  });
  
 
  
     $("#um_account_submit").live('click', function(){
	//  alert('Clicked');
		
  $('#um_privacy_form').submit(function(e){
	// alert('privacy');
	 var data = $('form#um_privacy_form').serialize();
	 $.ajax({
                url : '<?php bloginfo('url'); ?>/account/privacy/', // or whatever
                type:'POST',
                data: data,
                success:function(data) {
//alert('ok');
                       // localStorage.email = data[0];

                },
                error:function(data) {
					//alert('Error');
                }
            });
	
	 
	
	
  });
	 $('#um_notify_form').submit(function(e){
	 //alert('notify');
	  var data = $('form#um_notify_form').serialize();
	  $.ajax({
                url : '<?php bloginfo('url'); ?>/account/notifications/', // or whatever
                type:'POST',
                data: data,
                success:function(data) {
//alert('ok');
                       // localStorage.email = data[0];

                },
                error:function(data) {
					//alert('Error');
                }
            });
	  
	 });
	 
	$('#um_delete_form').submit(function(e){
	 //alert('delete');
	  var data = $('form#um_delete_form').serialize();
	  $.ajax({
                url : '<?php bloginfo('url'); ?>/account/delete/', // or whatever
                type:'POST',
                data: data,
                success:function(data) {
//alert('ok');
                       // localStorage.email = data[0];

                },
                error:function(data) {
					//alert('Error');
                }
            });
	  
	 });
  });
  
  $("#um_account_tab-webnotifications").click(function(){
	
	  $('#um_webnotify_form').submit(function(e){
	 //alert('notify');
	  var data = $('form#um_webnotify_form').serialize();
	  $.ajax({
                url : '<?php bloginfo('url'); ?>/account/webnotifications/', // or whatever
                type:'POST',
                data: data,
                success:function(data) {
//alert('ok');
                       // localStorage.email = data[0];

                },
                error:function(data) {
					//alert('Error');
                }
            });
	  
	 });
  });
  
  
   $("#cancel-privacy").click(function() {
	   location.href = window.location.href;
	   location.reload();
	   //window.location= window.location.href;
		   //$('#um_privacy_form')[0].reset();
		  });
		 
  
  
  /*if ($("form").submit() == TRUE) {
	//code
	alert('Submitted');
  }*/
   });
		
</script>
<style>
  .error{color: red;}
  .nulls{display: none;}
  .um-col-alt.um-col-alt-b{display:block !important;}
  .um-account-main{
width:100% !important;
  padding: 10px;
  border: 1px solid #D2D2D2;
}
.um-field-area {  
float: right;
 width: 35%;
  max-width: 320px;
}

.um-field-checkbox, .um-field-radio{
  margin-top: -10px;
}

#tab_1_1 .um-field-checkbox, .um-field-radio{
  margin-top: -20px;
}


.um-field-label{
  float: left;
}
.um-field{
 clear: both;
}
.um-left {
  clear: both;
}
.um-account-main label {
  font-size: 14px;
}
.um-account-main label {
  font-size: 14px !important;
  font-weight: normal;
}

#um_account_submit, #um_account_tab-webnotifications {
  color: #FFFFFF;
  background-color: #44b6ae;
  border-width: 0;
  padding: 7px 14px !important;
  font-size: 14px;
  outline: none !important;
  background-image: none !important;
  filter: none;
  box-shadow: none;
  text-shadow: none;
  border-radius: 4px !important;
  position: absolute;
  margin: 30px 0;
  max-width: 145px;
}
.portlet.light {
  padding: 12px 20px 50px 20px;
}
.um-provider{
  display: inline-block;
  width: 45%;
  text-align: center;
}
.um-provider-conn .um-social-btn{
  width: auto !important;
}
.um-cancel-privacy{
  margin-left: 164px;
  margin-top: 12px;
}
#cur_pwd, #new_pwd, #re_pwd{
  width:35% !important;
}
.um-provider-conn{
  text-align: center !important;
}
.um-provider-user-photo{
  float: none !important;
}
#tab_1_6 .um-account-main{
  border: 0 !important;
}
#tab_1_2 .um-account-main .um-field-area{
  clear: both;
  float: left;
  width: 100%;
  max-width: none;
}
#tab_1_2 .um-account-main label{
  width: 45%;
  display: inline-table;
}
#tab_1_2 .um-account-main .um-field .um-field-label strong{
  font-size:14px;
  font-weight: bold;
}
.um-field-checkbox-option, .um-field-radio-option{
  color: #333 !important;
}
#tab_1_1 .um-account-main .um-field-label label{
   font-weight: bold !important;
}
#tab_1_1 .um-account-heading, #tab_1_4 .um-account-heading, #tab_1_5 .um-account-heading, #tab_1_5 .um-account-heading{
  display: none;
}
</style>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>