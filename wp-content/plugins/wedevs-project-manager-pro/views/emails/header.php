<?php
$logo_path   = cpm_get_option( 'logo' );
$date        = cpm_date2mysql( current_time( 'mysql' ) ); 
$custom_date = date( 'l, d F Y', strtotime( $date ) );
$calendar    = CPM_URL . '/assets/images/calendar.png';

?>
<table cellspacing=0 width=600 style="padding: 0; border-top: 2px solid #3598db;">
	<tr>
		<td style="background: #ededed; height:82px; padding-left: 50px; width:50%;">
			<?php
			if ( $logo_path ) {
				?>
					<img style="max-height: 82px; max-width: 200px;"  src="<?php echo $logo_path; ?>"/>
				<?php
			}
			?>
		</td>
		<td style="background: #ededed; height:82px; text-align: right; padding-right: 50px;">
			<span style="float: right; padding-top: 4px; font-size: 13px;"><?php echo $custom_date; ?></span>
			<img style="float: right; margin-right: 10px;" src="<?php echo $calendar; ?>">
			<span style="clear: both;"></span>
		</td>
	</tr>
</table>                                                                         