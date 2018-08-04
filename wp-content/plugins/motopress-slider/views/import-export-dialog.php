<?php if (!defined('ABSPATH')) exit;?>
<div id="mpsl-import-export-wrapper">
    <div class="mpsl-import-wrapper">
        <h4><?php _e('Import', MPSL_TEXTDOMAIN ) ?></h4>
        <form id="mpsl-import-form" action="<?php echo admin_url( 'admin.php?import=mpsl-importer&step=2'); ?>" method="post" enctype="multipart/form-data" >
            <p><?php printf(__( 'To import sliders select %s Export file that you downloaded before then click import button.', MPSL_TEXTDOMAIN ), $mpsl_settings['product_name']); ?></p>
            <?php wp_nonce_field('mpsl-import', 'mpsl-import-nonce'); ?>
            <input type="hidden" name="mpsl-import-type" value="manual" />
            <label for="mpsl-import-file"><?php _e('Import File: ', MPSL_TEXTDOMAIN);?></label>
            <input type="file" name="mpsl-import-file" id="mpsl-import-file" required="required"/>
            <br/><br/>
            <input type="checkbox" name="mpsl_http_auth" id="mpsl_http_auth" value="true" autocomplete="off" />
            <label for="mpsl_http_auth"><?php _e('Enable HTTP Auth', MPSL_TEXTDOMAIN);?></label>                                            
            <br/>
            <div class="need-mpsl_http_auth" style="display: none;">
                <label for="mpsl_http_auth_login"><?php _e('Login:', MPSL_TEXTDOMAIN);?></label>
                <input type="text" name="mpsl_http_auth_login" id="mpsl_http_auth_login" disabled="disabled" autocomplete="off"/>                                                               
            </div>
            <div class="need-mpsl_http_auth" style="display: none;">
                <label for="mpsl_http_auth_password"><?php _e('Password:', MPSL_TEXTDOMAIN);?></label>
                <input type="password" name="mpsl_http_auth_password" id="mpsl_http_auth_password" disabled="disabled" autocomplete="off"/>                        
            </div>
            <br/>
            <button type="submit" class="button-primary"><?php esc_attr_e( 'Import', MPSL_TEXTDOMAIN ); ?></button>
            <br/>            
        </form>      
    </div>
    <br/>
    <hr/>
    <?php if (!empty($sliders)) { ?>    
        <div class="mpsl-export-wrapper">
            <h4><?php _e('Export', MPSL_TEXTDOMAIN ) ?></h4>
            <form id="mpsl-export-form" action="<?php echo $this->getSlidersExportUrl(); ?>" method="post">            
                <table class="mpsl-export-table widefat fixed">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="export-check-all" class="export-check-all" />
                            </th>
                            <th><?php _e('ID', MPSL_TEXTDOMAIN); ?></th>
                            <th><?php _e('Name', MPSL_TEXTDOMAIN); ?></th>     
                            <th><?php _e('Alias', MPSL_TEXTDOMAIN); ?></th>     
                        </tr>
                    </thead>
                    <tbody>
                    <?php                
                    foreach ( $sliders as $slider ) {
                    ?>
                        <tr>
                            <th><input type="checkbox" name="ids[]" class="mpsl-export-id-checkbox" value="<?php echo $slider['id']; ?>" /></th>
                            <th><?php echo $slider['id']; ?></th>
                            <th><?php echo $slider['title']; ?></th>                        
                            <th><?php echo $slider['alias']; ?></th>                        
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <fieldset>
                        <?php wp_nonce_field('export-mpsl-sliders'); ?>
                        <input type="hidden" name="mpsl-export" value="1">
                        <p><?php  _e( 'Downloads an export file that contains your selected sliders to import on your new site.', MPSL_TEXTDOMAIN ); ?></p>
                        <button id="mpsl-export-btn" class="button-primary"><?php _e('Export', MPSL_TEXTDOMAIN); ?></button>                        
                </fieldset>                
            </form>
        </div>
    <br/>
    <?php } ?> 

</div>