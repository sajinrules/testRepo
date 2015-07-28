<?php $projects = ( $projects ) ? $projects : array(); ?>
<?php $products = ( $products ) ? $products : array(); ?>
<?php $option_value = is_array( $option_value ) ?  $option_value : array(); 

?>

<div class="cpmw-magic-project">
    <div class="cpmw-error"></div>
    <h2><?php _e( 'WooCommerce Order Project', 'cpmw' ) ; ?></h2>
    <form action="" id="cpmw-product-project" method="post">
        <input type="hidden" name="action" value="product_project_action">
        <?php echo wp_nonce_field( 'cpmw_nonce' ); ?>
        <ul class="cpmw-form-fields">
            <?php 
            if( count( $option_value ) ) {
                foreach( $option_value as $key => $field ) { 

                    echo $this->settings_field( $key, $field, $projects,  $products );
                } 
            } else { 
                echo $this->settings_field( $key=0, $field=array(), $projects,  $products );
            }

            ?>

        </ul>
        <input type="submit" value="Save Change" class="button button-primary"/>

        <a href="#" class="cpmw-add-more button"><?php _e( 'Add new', 'cpmw' ); ?></a>
        <div class="cpmw-spinner" style="display: none;"><?php _e( 'Saving....', 'cpmw' ); ?></div>
        
    </form>
</div>


<div class="cpmw-form-clone-wrap" style="display: none;">
    <li class="cpmw-clone-area">
        <div class="cpmw-delete-li"><span><?php _e( 'Delete', 'cpmw'); ?></span></div>
        <div class="cpmw-type-wrap">
            <label for=""><?php _e( 'Type', 'cpmw' ); ?></label>
            <select class="cpmw-type" name="type[]">
                <option value="duplicate"><?php _e( 'Duplicate', 'cpmw'); ?></option>
                <option value="create"><?php _e( 'Create', 'cpmw'); ?></option>
            </select>
        </div>

        <div class="cpmw-product-wrap">
            <label for=""><?php _e( 'Product', 'cpmw' ); ?></label>
            <select name="product_id[]">
                <?php
                    echo $this->get_product_option( $product_id = null, $products );
                ?>
                
            </select>
        </div>
        <div class="cpmw-project-fields-wrap">
            <div class="cpmw-project-fields">
                <label for=""><?php _e( 'Project', 'cpmw' ); ?></label>

                <select name="project_id[]">
                    <?php
                        echo $this->get_project_option( $project_id = null, $projects );
                    ?>
                </select>
            </div>
        </div>

        <div class="cpmw-clear"></div> 
        
        <div class="cpmw-role-wrap" style="display: none;">
            <div class="cpm-form-item cpmw-project-role">
                <table>
                
                </table>
            </div>

            <label for=""><?php _e( 'Co-workers', 'cpmw' ); ?></label>
            <input class="cpmw-project-coworker" type="text" name="" placeholder="<?php esc_attr_e( 'Add co-workers...', 'cpm' ); ?>" size="45">
        </div>
    </li>
</div>