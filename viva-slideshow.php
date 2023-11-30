<?php
/*
Plugin Name: VIVA Content Slideshow
Plugin URI:  http://vivapro.net
Description: Using this Plugin you can present your products, services or content in beautiful style. Crate slideshow within a few minutes. Just select content item or fill the form and click - Add. All you do displays immediately in the preview without saving and reloading.
Version:     1.0.0
Author:      VIVA PRO
Author URI:  http://vivapro.net/about/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access and exit

define('VIVA_SL_ASSETS', plugin_dir_url(__FILE__) . 'assets/');

class Viva_Slideshow {

    /**
     * triggered on initialization
     */
    public function __construct() {
        add_action( 'plugins_loaded', array($this, 'load_textdomain') ); //add plugin textdomain
        add_action('init', array($this, 'register_content_type')); //register cards content type
        add_action('add_meta_boxes', array($this, 'add_meta_boxes')); //add meta boxes
        add_action('save_post_viva_slideshow', array($this, 'save_item')); //save cards
        add_action('init', array($this,'register_shortcodes')); //shortcodes
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles')); //admin scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles')); //public scripts and styles
        add_action('admin_menu', array($this, 'add_submenu'));//add setting page
        add_filter('the_content', array($this, 'prepend_meta')); //gets our meta data and dispayed it before the content
        add_filter( 'script_loader_tag', array($this, 'add_type_attribute'), 10, 3 );
        register_activation_hook(__FILE__, array($this, 'plugin_activate')); //activate hook
        register_deactivation_hook(__FILE__, array($this, 'plugin_deactivate')); //deactivate hook
        add_filter( 'admin_body_class', array($this,'vsl_admin_class') );//add extra spesial class to body
        //add columns in list table
        add_filter( 'manage_viva_slideshow_posts_columns', array($this, 'add_columns'));
        add_action( 'manage_viva_slideshow_posts_custom_column', array($this, 'manage_columns'), 10, 2 );
        
    }

    /**
     * add extra columns to list table
     *
     * @param [type] $columns
     * @return void
     */
    public function add_columns( $columns ) {
        $shortcode = [
            'shortcode'    => __('Shortcode'),
        ];
        $thumbnail = [
            'image' => __('Thumbnail'),
        ];

        return array_slice( $columns, 0, 1 ) + $thumbnail + $columns + $shortcode;
    }

    /**
     * render extra columns in list table
     *
     * @param [type] $column_name
     * @param [type] $post_ID
     * @return void
     */
    public function manage_columns( $column_name, $post_ID ) {
        if ( $column_name === 'shortcode' ) {
           echo '<input type="text" value="[viva_slideshow id=' . esc_attr( $post_ID ) . ']"><span class="dashicons dashicons-admin-page"></span>';
        }

        if ( $column_name === 'image' ) {
            // $this->do_pr(get_current_screen());//dev
            $str = get_post_meta( $post_ID, 'viva_slideshow_data', true );
            // regular expression to extract the first image URL
            if (preg_match('/"img":"(.*?)"/', $str, $matches)) {
                $first_img = $matches[1];
                echo '<a href="' . esc_url( get_edit_post_link() )  . '"><img src="' . esc_url( $first_img ) . '" alt="thumbnail"></a>';
            } else {
                echo "No image found.";
            }
        }
    }


    /**
     * Add plugin textdomain
     *
     * @return void
     */
    public function load_textdomain() {
         load_plugin_textdomain( 'viva-slideshow', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * add setting page
     *
     * @return void
     */
    public function add_submenu() {

        add_submenu_page(
            'edit.php?post_type=viva_slideshow',
            'Settings', // Page title
            'Settings', // Menu title
            'manage_options', // Capability required to access the page
            'vsl-settings', // Page slug
            array( $this, 'viva_slideshow_settings_page') // Callback public function to render the page
        );

        //call register settings function
        add_action( 'admin_init', array($this,'register_vsh_settings' ));
    }

    /**
     * display seeting page
     *
     * @return void
     */
    public function viva_slideshow_settings_page() {
        ?>
        <div class="wrap">
        <h2>Slideshows Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'viva-slideshow-opt-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th class="v_help" scope="row" style="cursor:help">
                        <?php esc_html_e('Description Lenght', 'viva-slideshow') ?>
                        <span class="dashicons dashicons-editor-help">
                        <span class="v_help_win" style="width: 400px; padding: 10px; position: fixed; top: 42px; right: 20px; background: #404550; color:#fff; 5px;border-radius: 5px;display:none">
                            <p><?php esc_html_e('Description Length(words) by default, maximum 55. This refers only to fetched text from content items. Length of custom text as you wish. Also you can add or shorten fetched text for a senseful description. Default is 20', 'viva-slideshow') ?></p>
                        </span>
                </th>
                <td><input type="number" name="length" min="5" max="55" step="1" value="<?php echo esc_attr( get_option('length') ); ?>" /></td>
                </tr>
                <tr valign="top">
                <th class="v_help" scope="row">
                    <?php esc_html_e('Read More Text', 'viva-slideshow') ?>
                    <span class="dashicons dashicons-editor-help os_help_readmore">
                    <div class="v_help_win" style="width: 400px; padding: 10px; position: fixed; top: 42px; right: 20px; background: #404550; color:#fff; 5px;border-radius: 5px;display:none">
                        <p><?php esc_html_e('Default text is "Read More", you can change it as you wish or write your own if your site language is not English', 'viva-slideshow') ?></p>
                        <p><?php esc_html_e('Note: Also you can change it for every item when add or edit ', 'viva-slideshow') ?></p>
                    </div>
                    </th>
                <td><input type="text" name="read_more" value="<?php echo esc_attr( get_option('read_more') ); ?>" placeholder="Default is Read More"/></td>
                </tr>
                <tr valign="top">
                <th class="v_help" scope="row">
                    <?php esc_html_e('Admin Layout', 'viva-slideshow') ?>
                    <span class="dashicons dashicons-editor-help ">
                    <div class="v_help_win" style="width: 400px; padding: 10px; position: fixed; top: 42px; right: 20px; background: #404550; color:#fff; 5px;border-radius: 5px;display:none">
                        <p><?php esc_html_e('Select admin layout for better preview and management. ', 'viva-slideshow') ?></p>
                        <p><?php esc_html_e('Note: Check it on edit page and make sure it do not hide any necessary extra modules if you have any, before set it as default.', 'viva-slideshow') ?></p>
                    </div>
                    </th>
                <td>
                    <select name="admin_style">
                        <option value=""<?php if (get_option('admin_style') === '') echo ' selected'; ?>>Classic</option>
                        <option value="v_layout_viva"<?php if (get_option('admin_style') === 'v_layout_viva') echo ' selected'; ?>>VIVA Layout(BETA)</option>
                    </select> 
                </td>
                </tr>
            </table>
            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('.v_help').hover(function() {
                        $('.v_help_win',this).fadeIn(300);
                    }, function() {
                        $('.v_help_win',this).fadeOut(300);
                    });
            });
        </script>
        <?php
    }

    
    /**
     * register our settings
     *
     * @return void
     */
    public function register_vsh_settings() {      
        register_setting( 'viva-slideshow-opt-group', 'length', array($this, 'sanitize_callback'));
        register_setting( 'viva-slideshow-opt-group', 'read_more', array($this, 'sanitize_callback'));
        register_setting( 'viva-slideshow-opt-group', 'admin_style', array($this, 'sanitize_callback'));
    }     

    /**
     * get plugin settings -default data
     *
     * @return void
     */
    public function get_settings(){   
        $length = 20;  
        $read_more = 'Read More';  
        if (get_option('length')) {
            $length = get_option('length');
        }
        if (get_option('read_more')) {
            $read_more = get_option('read_more');
        }
        return array(
            'length' => $length,
            'read_more' => $read_more,
            // 'admin_style' => get_option('admin_style'),//todo
        );
    }    

    /**
     * sanitize data
     *
     * @param [type] $options
     * @return void
     */
    public function sanitize_callback( $options ){
        $options = sanitize_text_field( $options );
        return $options;
    }

    /**
     * add special admin class
     *
     * @param [type] $classes
     * @return void
     */
    function vsl_admin_class( $classes ) {
        // if activated in saettings return special class
        if ( !empty(get_option('admin_style'))) {
            $classes .= ' ' . get_option('admin_style') . ' ';
        } 
        return $classes;
    }

    /**
     * register the content type
     *
     * @return void
     */
    public function register_content_type() {
        //Labels for post type
        $labels = array(
            'name' => 'VIVA Slideshows',
            'singular_name' => 'VIVA Slideshow',
            'menu_name' => 'VIVA Slideshows',
            'name_admin_bar' => 'VIVA Slideshow',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Slideshow',
            'new_item' => 'New Slideshow',
            'edit_item' => 'Edit Slideshow',
            'view_item' => 'View Slideshow',
            'all_items' => 'All Slideshows',
            'search_items' => 'Search Slideshows',
            'parent_item_colon' => 'Parent Slideshow:',
            'not_found' => 'No Slideshows found.',
            'not_found_in_trash' => 'No Slideshows found in Trash.',
        );
        //arguments for post type
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav' => true,
            'query_var' => true,
            'hierarchical' => false,
            'supports' => array('title'),//editor disable by css as not used for now, but Gutenberg layout doesnt work without it
            'has_archive' => true,
            'menu_position' => 20,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,//set true if need to add to menu as page
            'menu_icon' => 'dashicons-images-alt2',
            'rewrite' => array('slug' => 'vslideshow', 'with_front' => 'true'),
            'capability_type' => 'post',
            'show_in_rest' => false,    // for Gutenberg
        );
        //register post type
        register_post_type('viva_slideshow', $args);
    }

    /**
     * adding meta boxes for the content type
     *
     * @return void
     */
    public function add_meta_boxes() {
        add_meta_box(
            'viva_slideshow_meta_box', //id
            'Edit Slideshows & Preview', //name
            array($this, 'display_card_metabox'), //display function
            'viva_slideshow', //post type
            'normal', //location
            'default' //priority
        );
        add_meta_box('extra_fields', 'Shortcode', array($this, 'show_extra_box'), 'viva_slideshow', 'side', 'low');
    }

    /**
     * display shortcode box
     *
     * @param [type] $post
     * @return void
     */
    public function show_extra_box($post)
    {
        echo '<input class="osc_copy_shortcode" type="text" value="[viva_slideshow id=' . esc_attr( $post->ID ) . ']"> ';
    }
    
    /**
     * display function used for our meta box
     *
     * @param [type] $post
     * @return void
     */
    public function display_card_metabox($post)
    {
        //set nonce field
        wp_nonce_field('viva_slideshow_nonce', 'viva_slideshow_nonce_field');
        //collect variables
        $viva_slideshow_data = get_post_meta($post->ID, 'viva_slideshow_data', true);
        ?>
        <div class="field-container">
        <?php
        //before main form elementst hook
        do_action('viva_slideshow_admin_form_start');
        ?>
        <div id="app-slideshow" class="oss-cards-admin">
            <div id="osti_show_data">
                <span id="close_data" v-on:click="editElMain(9), closeModals()"><span class="dashicons dashicons-admin-page"></span></span>
                <div class="oss_copy_data_click">
                <span class="dashicons dashicons-admin-page"></span> <?php esc_html_e('Copy To Clipboard', 'viva-slideshow') ?>
                    <span class="oss_copyed_alert_data"><?php esc_html_e('Data Is Copied', 'viva-slideshow') ?></span>
                </div>
                <?php 
                /*
                toString() is js object converted to JSON string where there's all card's data and its parameters - no html or js tags(inc/funcs/functions line 93)
                 */
                ?>
                <textarea id="viva_slideshow_data" name="viva_slideshow_data" cols="100" rows="10">{{toString()}}</textarea>
            </div>
            <?php
            include 'inc/preview.php';
            include 'inc/sidebar.php';
            echo '</div>'; //end app
            //after main form elementst hook
            do_action('viva_slideshow_admin_form_end');
            ?>
        </div>
    <?php
    }

    /**
     * triggered on activation of the plugin (called only once)
     *
     * @return void
     */
    public function plugin_activate() {
        //call our custom content type function
        $this->register_content_type();
        //flush permalinks //
        flush_rewrite_rules();
    }

    /**
     * trigered on deactivation of the plugin (called only once)
     *
     * @return void
     */
    public function plugin_deactivate() {
        //flush permalinks
        flush_rewrite_rules();
    }

    /**
     * show item(meta data) for WP preview
     *
     * @param [type] $content
     * @return void
     */
    public function prepend_meta($content)
    {
        global $post, $post_type;
        //display meta only on our content type
        if ($post_type == 'viva_slideshow') {
            //collect variables
            $viva_slideshow_data = get_post_meta($post->ID, 'viva_slideshow_data', true);
            //display
            if ( ! empty($viva_slideshow_data)) {
                ob_start();
                $data = json_decode($viva_slideshow_data, true);
                include 'inc/frontend.php';
                $cards = ob_get_clean();
                return $cards . $content;
            }
        }else{
            return $content;
        }
    }

    /**
     * register shortcode
     *
     * @return void
     */
    public function register_shortcodes(){
        add_shortcode('viva_slideshow', array($this,'display_shortcode'));
    }

    /**
     * shortcode display
     *
     * @param [type] $atts
     * @return void
     */
    public function display_shortcode($atts){
        $arguments = shortcode_atts( 
            array(
                'id' => ''
            ), 
            $atts 
        );
        $html = $this->get_cards_output($arguments);
        return $html;
    } 

    /**
     * main function for displaying cards
     *
     * @param string $arguments
     * @return void
     */
    public function get_cards_output($arguments = "")
    {
        $card_object = '';
        //default args
        $default_args = array(
            'id' => '',
        );
        //update default args if we passed in new args
        if ( ! empty($arguments)) {
            $cards_id = $arguments['id'];
            $viva_slideshow_data = get_post_meta($cards_id, 'viva_slideshow_data', true);
            //display if there's data & status published
            if ( ! empty($viva_slideshow_data) && get_post_status( $cards_id ) === 'publish') {
                ob_start();
                $data = json_decode($viva_slideshow_data, true);
                include 'inc/frontend.php';
                $card_object = ob_get_clean();
            }
        }
        return $card_object;
    }

    /**
     * triggered when adding or editing
     *
     * @param [type] $post_id
     * @return void
     */    
    public function save_item($post_id)
    {
        //check for nonce
        if (!isset($_POST['viva_slideshow_nonce_field']) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['viva_slideshow_nonce_field'] ) ) , 'viva_slideshow_nonce' ) ) {
            return $post_id;
        }

        //check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        /*
        `viva_slideshow_data is the only data passed to post meta - it's a JSON string of the js object off all cards and its parameters, sanitized before post
         */
        $viva_slideshow_data = isset($_POST['viva_slideshow_data']) ? sanitize_textarea_field($_POST['viva_slideshow_data']) : '';
        //update data
        update_post_meta($post_id, 'viva_slideshow_data', $viva_slideshow_data);

        //save hook
        do_action('viva_slideshow_admin_save', $post_id);
    }

    /**
     * add tag module to main js script
     *
     * @param [type] $tag
     * @param [type] $handle
     * @param [type] $src
     * @return void
     */
    public function add_type_attribute($tag, $handle, $src) {
        // if not your script, do nothing and return original $tag
        if ( 'osscards-app' !== $handle ) {
            return $tag;
        }
        // remove the current type if there is one
        $tag = preg_replace( '/ type=([\'"])[^\'"]+\1/', '', $tag ); 

        // add type
        $tag = str_replace( 'src=', 'type="module" src=', $tag );

        return $tag;
    }

    /**
     * enqueus scripts and stles for the backend
     *
     * @return void
     */
    public function enqueue_admin_scripts_and_styles() {
        if ( get_current_screen()->id == 'edit-viva_slideshow' ) {
            wp_enqueue_style('vsl-list', plugin_dir_url(__FILE__) . 'assets/css/list.css', array(), '202302');
            wp_enqueue_script('vsl-list', plugin_dir_url(__FILE__) . 'assets/js/list.js', array('jquery'), '1.0');
        }

        //calls if post type is viva_slideshow only and edit or new page
        if ( get_current_screen()->base == 'post' && get_post_type() ==='viva_slideshow' ) {
            wp_enqueue_style('vsl-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), '202302');
            wp_enqueue_style('vsl-frontend-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
            wp_enqueue_style('uikit-css', plugin_dir_url(__FILE__) . 'assets/css/uikit.min.css', array(), '202302');
            wp_enqueue_media();//requires for images
            wp_enqueue_script('vsl-admin', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery'), '1.0');
            wp_enqueue_script('uikit-js', plugin_dir_url(__FILE__) . 'assets/js/uikit.min.js', array(), '3.0');
            wp_enqueue_script('vsl-vue', plugin_dir_url(__FILE__) . 'assets/js/vue.js', array(), '2.0');
            wp_enqueue_script('vsl-vue-color', plugin_dir_url(__FILE__) . 'assets/js/vue-color.min.js', array(), '2.0');
            wp_enqueue_script('vsl-color', plugin_dir_url(__FILE__) . 'assets/js/color.js', array(), '2.0');           
            wp_enqueue_script('vsl-axios', plugin_dir_url(__FILE__) . 'assets/js/axios.min.js', array(), '2.0');           
            //main script as type module & localize_script
            wp_enqueue_script('osscards-app', plugin_dir_url(__FILE__) . 'inc/app.js', array(), null, true);
            global $post, $post_type;        
            $viva_slideshow_data = get_post_meta( $post->ID, 'viva_slideshow_data', true );
            if ( $viva_slideshow_data ) {
                wp_add_inline_script( 'osscards-app', 'const OSDATA = ' . wp_json_encode( $viva_slideshow_data ), 'before' );
            }
            wp_add_inline_script( 'osscards-app', 'const OSDEFAULT = ' . wp_json_encode( $this->get_settings() ), 'before' );
        }
    }

    /**
     * enqueues scripts and styled for the frontend
     *
     * @return void
     */
    public function enqueue_public_scripts_and_styles() {
        wp_enqueue_style('vsl-uikit-css', plugin_dir_url(__FILE__) . 'assets/css/uikit.min.css', array(), '202302');
        wp_enqueue_style('vsl-public-styles', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '202302');
        wp_enqueue_script('vsl-uikit-js', plugin_dir_url(__FILE__) . 'assets/js/uikit.min.js', array(), '2.0');
        wp_enqueue_script('vsl-js', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '2.0');
    }
}

$viva_slideshow = new Viva_Slideshow;
//include widget
include(plugin_dir_path(__FILE__) . 'inc/widget.php');
?>