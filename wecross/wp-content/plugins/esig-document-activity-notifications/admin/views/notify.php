<?php 

// To default a var, add it to an array
	$vars = array(
		'esig_logo', // will default $data['esig_logo']
		'esig_header_tagline', 
		'document_title',
		'document_checksum',
		'owner_first_name',
		'signer_name',
		'signer_email',
		'view_url',
		'assets_dir',
		
	);
	$this->default_vals($data, $vars);
?>


<div id=":zs" class="ii gt m1436f203bed358e3 adP adO">
  <div id=":zr" style="overflow: hidden;">
    <div class="adM"> </div>
    <div
style="background-color:#efefef;margin:0;padding:0;font-family:'HelveticaNeue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;width:100%;min-width:680px">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
          <tr style="border-collapse:collapse">
            <td style="font-family:'HelveticaNeue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;border-collapse:collapse"
              align="center" bgcolor="#efefef">
              <table border="0" cellpadding="20" cellspacing="0"
                width="640">
                <tbody>
                  <tr style="border-collapse:collapse">
                    <td style="font-family:'HelveticaNeue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;border-collapse:collapse"
                      align="left" width="640">
                      <div style="margin:0 0 20px 0">
                        <div style="text-align:left">
                          <?php echo $data['esig_logo']; ?>
                        </div>
                        <p style="margin:5px 0 0 0;color:#666">
                          <?php echo $data['esig_header_tagline']; ?><br>
                        </p>
                      </div>
                      
                      <table width="640">
                        <tbody>
                          <tr>
                            <td
                              style="background-color:#ffffff;border:1px
                              solid #ccc;padding:40px 40px 30px 40px"
                              bgcolor="FFFFFF">
                              <h1 style="font-size:18px;margin:0 0 10px
                                0;font-weight:bold">Document Viewed: <?php echo $data['document_title']; ?>
                              </h1>
                              Document ID: (<?php echo $data['document_checksum']; ?>)
                              
                              <hr
style="color:#cccccc;background-color:#cccccc;min-height:1px;border:none">
                              <p
                                style="line-height:1.4em;font-size:14px;margin:10px
                                0px">Hi <?php echo $data['owner_first_name']; ?>,<br>
                                <br>
                                <?php echo $data['signer_name']; ?>(<?php echo $data['signer_email']; ?>) has viewed the document.
							  We'll let you know if they sign it.
                                </p>
								<p
                                style="line-height:1.4em;font-size:14px;margin:10px
                                0px">
                                If you'd like more information, you can visit the 
								documents page below. 
                                </p>
                              <hr
style="color:#cccccc;background-color:#cccccc;min-height:1px;border:none">
                              <div style="margin:20px 0px 20px 0px">
                                <table>
                                  <tbody>
                                    <tr>
                                      <td>
                                      <?php
                                        
                                        $background_color_bg= apply_filters('esig-invite-button-background-color','');
                                       
                                        $background_color = !empty( $background_color_bg) ?  $background_color_bg : '#0083c5' ; 
                                        
                                      ?>
                                        <a style="display:inline-block;padding:9px 16px 9px 16px; font:12px/28px 'ProximaNova-Bold', Arial, Helvetica, sans-serif-webkit-body;text-transform: uppercase;letter-spacing: 1px;line-height: 29px;text-decoration:none;color:#f7fbfd; background:<?php echo  $background_color; ?>" href="<?php echo $data['view_url']; ?>" target="_blank">
                                        View Viewed Document</a></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <hr
style="color:#cccccc;background-color:#cccccc;min-height:1px;border:none">
                              <p style="margin:10px 0px;font-size:14px;line-height:1.4em;color:#ff0000">
                              	Warning: do not forward this email to others or
                                else they will have access to your document (on your behalf).</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <table style="min-width:680px;background:#cccccc;border-top:1px
        solid #999999;border-bottom:1px solid #999999;padding:0 0 30px
        0" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
          <tr style="border-collapse:collapse">
            <td style="font-family:'Helvetica
Neue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;border-collapse:collapse"
              align="center" bgcolor="#cccccc">
              <table style="margin-top:20px" border="0" cellpadding="20"
                cellspacing="0" width="640">
                <tbody>
                  <tr style="border-collapse:collapse">
                    <td style="padding: 16px 12px 0px 0px;vertical-align: top;font-family: 'Helvetica Neue',Arial,Helvetica,sans-serif;font-size: 12px;line-height: 1.5em;border-collapse: collapse;color: #555;"
                      align="left"></td>
                    <td style="padding:0px 12px 0px
                      0px;vertical-align:top;font-family:'Helvetica
Neue',Arial,Helvetica,sans-serif;font-size:12px;line-height:1.4em;border-collapse:collapse;color:#555"
                      align="left"> <br>
                    </td>
                    <td style="padding:0px 0px 0px
                      0px;vertical-align:top;font-family:'Helvetica
Neue',Arial,Helvetica,sans-serif;font-size:12px;line-height:1.4em;border-collapse:collapse;color:#555"
                      align="left"> <a href="http://www.approveme.me/?ref=1" target="_blank"><img
src="<?php echo $data['assets_dir']; ?>/images/approveme-badge.png"
                              alt="WP E-Signature" border="0" style="margin-top: -8px;"
                              height="49" width="154"></a><br>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="adL"> </div>
    </div>
    <div class="adL"> </div>
  </div>
</div>

