<div id="esig-sif-admin-panel" style="display:none;">
	
	<div class="esig-sif-main-panels">
			
			<?php
              
               $asset_dir = ESIGN_ASSETS_DIR_URI;
            
            ?>
			
			
		<div class="panel esig-sif-panel-textfield" style="display:none;">
		    <div class="sif_popup_container">
            
			<div class="sif_popup_left">	
			
              <div class="sif_text_signer_info"> </div> 
             
              <div class="sif_text_placeholder_Text">
               
              Enter your placeholder text<br>
                
                 <input type="text"  name="textbox" value="" placeholder="Placeholder Text">
              </div> 
            </div>
           
            <div class="sif_popup_right" valign="top">
                        <!-- textbox advanced area -->
                     <div class="sif_advanced_button_area" align="right">
                     <button id="sif_textbox_advanced_button" class="advanced-button icon-settings"><?php _e('Advanced','esig_sif'); ?></button>
                     </div> 
                     <!-- others area start here -->
                     <div class="sif_others_area">
                     <input type="checkbox" class="required" name="required" checked ><?php _e('Required', 'esig-sif'); ?>
			         </div>
			</div>
            
			<div class="popup_submit_area" align="left"><a class="esig-mini-btn esig-blue-btn insert-btn"><?php _e('Insert Text Field', 'esig-sif' );?></a></div>
            
            </div>
            
			<div class="sif_textbox_advanced_content" style="display:none;"> 
            
                  Max Width:  <input type="text" id="maxsize" name="textbox_width" size="4" value="">Px
                   <hr width="100%">
              <span class="sif-advanced-text">    <?php _e('Tips: you can adjust the size of your text field by using 
                  the text box above. See the result in real time','esig'); ?>
               </span>   
               
            </div>
			
            
		</div>
        
        
        <!-- date panel start here  -->
        <div class="panel esig-sif-panel-datepicker" style="display:none;">
		
			<div align="center" class="esig-sif-popup-logo">
			
			<img src="<?php echo ESIGN_ASSETS_DIR_URI ; ?>/images/logo.svg"></p>
					
		</div>
			<p align="center" class="esig-sif-instructions">
						<?php _e('Add a date picker for your signer to fill out.', 'esig-sif' );?>
					</p>
			<div class="sif_popup_main_datepicker">		
			<div class="sif_popup_left">
             Please select a signer :
            </div>
			 </div>
			<p>&nbsp;  </p>
            <p>&nbsp;  </p>
			<p align="center"><a class="esig-mini-btn esig-blue-btn insert-date"><?php _e('Insert Date', 'esig-sif' );?></a></p>
			
		</div>
        
		<!-- radio start here -->
		
		<div class="panel esig-sif-panel-radio" style="display:none;">
        
         <div class="sif_popup_container">
            
			<div class="sif_popup_left">	
			
              <div class="sif_radio_signer_info"> </div> 
              
              <div class="sif_text_placeholder_Text">
                Enter your field label(Optionnal)<br>
                
                 <input type="text"  name="radiolabel" value="">
              </div> 
              <div class="radio_button">
              <ul id="radio_html">
              <li> Add Radio Buttons</li>
              
               <li>
				<input type="radio" name=""/>
				<input type="text" name="label[]" placeholder="Label" value="" />
				<input type="hidden" class="hidden_radio" name="" value="">
                <span class="icon-plus" id="addRadio"></span><span class="icon-minus" id="minusRadio"></span>
                </li>
                </ul>
			 </div>
             
            </div>
           
            <div class="sif_popup_right" valign="top">
                        <!-- textbox advanced area -->
                     <div class="sif_advanced_button_area">
                     <button id="sif_radio_advanced_button" class="advanced-button icon-settings"><?php _e('Advanced','esig_sif'); ?></button>
                     </div> 
                     <!-- others area start here -->
                     <div class="sif_others_area">
                     <input type="checkbox" class="required" name="required" checked ><?php _e('Required', 'esig-sif'); ?>
			         </div>
			</div>
            
			<div class="popup_submit_area" align="left"><a class="esig-mini-btn esig-blue-btn insert-btn"><?php _e('Insert Radio Buttons', 'esig-sif' );?></a></div>
            
            <div class="sif_radio_advanced_content" style="display:none;text-align:justify;"> 
            
                <div class="sif_alignment">
			<input type="radio" name="sif_radio_position" id="radiocheck" checked value="vertical"><?php _e('Display vertically' , 'esig-sif');?> <span class="esig-default"><?php _e('(Default)','esig-sif'); ?></span><br>
			<input type="radio" name="sif_radio_position" id="radiocheck" value="horizontal"><?php _e('Display horizontally', 'esig-sif' );?>
			</div>
                <hr width="100%">
               <span class="sif-advanced-text">   <?php _e('Tips: you can choose for your radio buttons to be displayed 
                  vertically or horizontally on your document here.','esig'); ?>
                </span>
            </div>
            
            </div>
            
        
	
         </div>
         
         
        <!-- checkbox start here -->
		<div class="panel esig-sif-panel-checkbox" style="display:none;">
        
		
		<div class="sif_popup_container">
            
			<div class="sif_popup_left">	
			
              <div class="sif_checkbox_signer_info"> </div> 
              
              <div class="sif_text_placeholder_Text">
                Enter your field label(Optional)<br>
                
                 <input type="text"  name="checkboxlabel" value="">
              </div> 
              
              <div class="checkbox_button">
              <ul id="checkbox_html">
              <li> Add Checkbox Buttons</li>
              
               <li>
				<input type="checkbox" name=""/>
				<input type="text" name="label[]" placeholder="Label" value="" />
				<input type="hidden" class="hidden_checkbox" name="" value="">
                <span class="icon-plus" id="addCheckbox"> </span><span class="icon-minus" id="minusCheckbox"> </span>
                </li>
                </ul>
			 </div>
             
            </div>
           
            <div class="sif_popup_right" valign="top">
                        <!-- textbox advanced area -->
                     <div class="sif_advanced_button_area">
                     <button id="sif_checkbox_advanced_button" class="advanced-button icon-settings"><?php _e('Advanced','esig_sif'); ?></button>
                     </div> 
                     <!-- others area start here -->
                     <div class="sif_others_area">
                     <input type="checkbox" class="required" name="required" checked ><?php _e('Required', 'esig-sif'); ?>
			         </div>
			</div>
            
			<div class="popup_submit_area" align="left"><a class="esig-mini-btn esig-blue-btn insert-btn"><?php _e('Insert Checkboxes Buttons', 'esig-sif' );?></a></div>
            
            <div class="sif_checkbox_advanced_content" style="display:none;"> 
            
                <div class="sif_alignment">
			<input type="radio" name="sif_checkbox_position" id="checkboxcheck" checked value="vertical"><?php _e('Display vertically' , 'esig-sif');?> <span class="esig-default"><?php _e('(Default)','esig-sif'); ?></span> <br>
			<input type="radio" name="sif_checkbox_position" id="checkboxcheck" value="horizontal"><?php _e('Display horizontally', 'esig-sif' );?>
			</div>
                <hr width="100%">
               <span class="sif-advanced-text">   <?php _e('Tips: you can choose for your radio buttons to be displayed 
                  vertically or horizontally on your document here.','esig'); ?>
                </span>
            </div>
            
            </div>
			
			
			
		</div>

	</div>
    
    
    
</div>