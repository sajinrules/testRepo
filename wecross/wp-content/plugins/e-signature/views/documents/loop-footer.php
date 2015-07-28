<?php 

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

?>		
		
		</tbody>
	</table>
</div>



<div class="header_left">

<?php 

 $esig_common = new WP_E_Common();
 
  echo $esig_common->esig_bulk_action_form();
  
?>

</div>
<div class="header_right">
<a href="admin.php?page=esign-about-general" class="esig-feedback"><span class="esig-feedback-span"></span><?php _e('We\'d Love to hear Your Feedback!', 'esig'); ?></a>
</div>
</form>
<!--<p align="right" style="font-weight:500;"><a href="admin.php?page=esign-about-general" class="esig-feedback"><span class="esig-feedback-span"></span><?php _e('We\'d Love to hear Your Feedback!', 'esig'); ?></a></p>-->
<?php echo $data['loop_tail']; ?>