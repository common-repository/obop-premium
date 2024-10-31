<?php 
    class ObopPremiumSettings
    {
        /**
         * Holds the values to be used in the fields callbacks
         */
        private $options;

        /**
         * Start up
         */
        public function __construct()
        {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
            add_action( 'init', array($this, 'loadTranslations'));
        }
        
        /**
         * Add options page
         */
        public function add_plugin_page()
        {
            // This page will be under "Settings"
            add_options_page(
                'Settings Admin', 
                'OBOP Premium', 
                'manage_options', 
                'obop-premium', 
                array( $this, 'create_admin_page' )
            );
        }
        
        public function loadTranslations()
        {
            load_plugin_textdomain('wp-admin-obop-premium', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
        }

        /**
         * Options page callback
         */
        public function create_admin_page()
        {
            // Set class property
            $this->options = get_option( 'my_option_name' );
            ?>
            <div class="wrap">
                <h2><?php echo __('OBOP Parameters', 'wp-admin-obop-premium'); ?></h2>
                
                <form method="post" action="options.php">
                     <?php settings_fields( 'obop-premium-settings-group' ); ?>
                     <?php do_settings_sections( 'obop-premium-settings-group' ); ?>
                     <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php echo __('Text', 'wp-admin-obop-premium'); ?></th>
                            <?php $premiumtext = get_option('premiumtext'); ?>
                            <?php $premiumtext = (empty($premiumtext)) ? __('You must logged in to read this article, you can subscribe by our partner Obop here', 'wp-admin-obop-premium') : $premiumtext; ?>
                            <td><textarea name="premiumtext" rows="10" style="width:100%;"><?php echo $premiumtext; ?></textarea></td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

        /**
         * Register and add settings
         */
        public function page_init()
        {        
            register_setting( 'obop-premium-settings-group', 'premiumtext' );   
        }
    }
?>