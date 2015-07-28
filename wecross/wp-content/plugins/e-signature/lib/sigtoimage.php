<?php
require_once('../../../../wp-load.php');

if ( file_exists( ABSPATH . 'wp-config.php') ) {

	/** The config file resides in ABSPATH */
	require_once( ABSPATH . 'wp-config.php' );

} elseif ( file_exists( dirname(ABSPATH) . '/wp-config.php' ) && ! file_exists( dirname(ABSPATH) . '/wp-settings.php' ) ) {

	/** The config file resides one level above ABSPATH but is not part of another install */
	require_once( dirname(ABSPATH) . '/wp-config.php' );

}

function imageto($json){
require_once 'signature-to-image.php';

$img = sigJsonToImage($json);


// Output to browser
header('Content-Type: image/png');
imagepng($img);

// Destroy the image in memory when complete
imagedestroy($img);
}

$userid=isset($_GET['uid'])?$_GET['uid']:$_GET['owner_id'];


$doc_id=$_GET['doc_id'];

$esig_nonce = $_GET['esig_verify'];

if(!wp_verify_nonce($esig_nonce, $userid.$doc_id))
{
  exit ;	
}
  

 if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$signature = new WP_E_Signature; 
		$document = new WP_E_Document;
	$doc_id =$document->document_id_by_csum($doc_id);


		
		//$owner_id =$_GET['owner_id']; 
		if(isset($_GET['owner_id']) && !empty($_GET['owner_id'])){
		
		$json =$signature->getUserSignature($userid);
	
		
        }else {
        
            $json =$signature->getDocumentSignature($userid,$doc_id);
         }

 imageto($json);
    
?>

