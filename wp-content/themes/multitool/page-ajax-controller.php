<?php
/**
 * Template Name: We Cross Ajax - Controller 
 *
 * The template and functionality for controlling ajax calls
 * @package WordPress
 * @subpackage Multitool
 * @since We Cross 1.0
 */
//get_header();

include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
include_once('../../../wp-includes/wp-db.php');

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'lu_ajax' : lu_ajax();break;
        case 'blah' : blah();break;
        // ...etc...
    }
}
//do_action( 'login_init' );

  echo $user_id = get_current_user_id();
 
function lu_ajax() {
    $pass='';
    //print_r($_POST);
   // $box= $_POST['single_user_password-16'];
    foreach ($_POST as $x) {
    $pass=$x[1]['value'];
    }
     echo $pass;
   echo $username=$wp_user->user_login;


// echo $hash = wp_hash_password($password);
/* $user = get_user_by( 'login', $username );
if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID) ) {
   
}else{
    echo 'Password is not correct';
}*/

}

?>