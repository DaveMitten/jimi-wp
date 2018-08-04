<?php

/**
 * Plugin Name: Slider WD
 * Plugin URI: https://web-dorado.com/products/wordpress-slider-plugin.html
 * Description: This is a responsive plugin, which allows adding sliders to your posts/pages and to custom location. It uses large number of transition effects and supports various types of layers.
 * Version: 1.2.13
 * Author: WebDorado
 * Author URI: https://web-dorado.com/wordpress-plugins-bundle.html
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || die('Access Denied');

$wds = 0;
final class WDS {
  /**
   * The single instance of the class.
   */
  protected static $_instance = null;
  /**
   * Plugin directory path.
   */
  public $plugin_dir = '';
  /**
   * Plugin directory url.
   */
  public $plugin_url = '';
  /**
   * Plugin main file.
   */
  public $main_file = '';
  /**
   * Plugin version.
   */
  public $plugin_version = '';
  /**
   * Plugin database version.
   */
  public $db_version = '';
  /**
   * Plugin prefix.
   */
  public $prefix = '';
  public $nicename = '';
  public $nonce = 'nonce_wd';
  public $is_free = TRUE;
  public $upload_dir = '';
  public $free_msg = '';

  /**
   * Main WDS Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return WDS - Main instance.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * WDS Constructor.
   */
  public function __construct() {
    $this->define_constants();
    require_once($this->plugin_dir . '/framework/WDW_S_Library.php');
    $this->add_actions();
  }

  /**
   * Define Constants.
   */
  private function define_constants() {
    $this->plugin_dir = WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__));
    $this->plugin_url = plugins_url(plugin_basename(dirname(__FILE__)));
    $this->main_file = plugin_basename(__FILE__);
    $this->plugin_version = '1.2.13';
    $this->db_version = '1.2.13';
    $this->prefix = 'wds';
    $this->nicename = __('Slider WD', $this->prefix);
    $this->use_home_url();
    $upload_dir = wp_upload_dir();
    $this->upload_dir = str_replace(ABSPATH, '', $upload_dir['basedir']) . '/slider-wd';
    $this->site_url_placeholder = '@#$%';
    $this->site_url_buttons_placeholder = '@##$%';
  }

  private function use_home_url() {
    $home_url = str_replace(array("http://", "https://"), "", home_url());
    $pos = strpos($home_url, "/");
    if ( $pos ) {
      $home_url = substr($home_url, 0, $pos);
    }

    $site_url = str_replace("http://", "", $this->plugin_url);
    $site_url = str_replace("https://", "", $site_url);
    $pos = strpos($site_url, "/");
    if ( $pos ) {
      $site_url = substr($site_url, 0, $pos);
    }

    if ( $site_url != $home_url ) {
      $this->front_url = home_url("wp-content/plugins/" . plugin_basename(dirname(__FILE__)));
    }
    else {
      $this->front_url = $this->plugin_url;
    }
  }

  /**
   * Add actions.
   */
  private function add_actions() {
    add_action('init', array($this, 'init'), 9);
    register_activation_hook(__FILE__, array($this, 'activate'));
    add_action('admin_menu', array( $this, 'admin_menu' ) );

    add_action('admin_notices', array($this, 'topic'), 11);

    if ( !$this->is_free ) {
      add_action('wp_ajax_WDSShare', array($this, 'frontend'));
      add_action('wp_ajax_nopriv_WDSShare', array($this, 'frontend'));
    }

    add_shortcode('wds', array($this, 'shortcode'));
    add_shortcode('SliderPreview', array($this, 'shortcode'));
    add_filter('media_buttons_context', array($this, 'media_button'));

    // Add the Slider button to editor.
    add_action('wp_ajax_WDSShortcode', array($this, 'admin_ajax'));
    add_action('wp_ajax_WDSPosts', array($this, 'admin_ajax'));
    if ( !$this->is_free ) {
      add_action('wp_ajax_WDSExport', array($this, 'admin_ajax'));
      add_action('wp_ajax_WDSImport', array($this, 'admin_ajax'));
    }

    add_action('admin_head', array($this, 'admin_head'));
    // Add images to Slider.
    add_action('wp_ajax_wds_UploadHandler', array($this, 'UploadHandler'));
    add_action('wp_ajax_addImage', array($this, 'filemanager_ajax'));

    // Slider Widget.
    if (class_exists('WP_Widget')) {
      add_action('widgets_init', array($this, 'register_widget'));
    }

    if ((!isset($_GET['action']) || $_GET['action'] != 'deactivate')
      && (!isset($_GET['page']) || $_GET['page'] != 'uninstall_wds')) {
      add_action('admin_init', array($this, 'install'));
    }

    if ( !$this->is_free ) {
      add_action('wp_ajax_wds_addEmbed', array($this, 'add_embed_ajax'));
    }
    // Register scripts/styles.
    add_action('wp_enqueue_scripts', array($this, 'front_end_scripts'));
    add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

    add_filter('set-screen-option', array($this, 'set_option_sliders'), 10, 3);

    add_action('admin_notices', array($this, 'topic'), 11);
    add_filter( 'media_upload_tabs', array($this, 'custom_media_upload_tab_name') );
    add_filter( 'media_view_strings', array($this, 'custom_media_uploader_tabs'), 5 );
    add_action( 'media_upload_wds_posts', array($this, 'media_upload_window') );
    add_action( 'media_upload_wds_embed', array($this, 'media_upload_window') );
    add_action( 'media_upload_wds_custom_uploader', array($this, 'filemanager_ajax') );

    if ( $this->is_free) {
      add_filter('plugin_row_meta', array($this, 'add_plugin_meta_links'), 10, 2);
    }

  	// Enqueue block editor assets for Gutenberg.
    add_filter('tw_get_block_editor_assets', array($this, 'register_block_editor_assets'));
    add_action( 'enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets') );

    // Privacy policy.
    add_action( 'admin_init', array($this, 'add_privacy_policy_content') );
  }

  function add_privacy_policy_content() {
    if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
      return;
    }

    $content = __( 'Your name, email address and IP address are collected and stored in our website database when you comment on and/or rate images on our website.', $this->prefix );

    wp_add_privacy_policy_content(
      $this->nicename,
      wp_kses_post( wpautop( $content, false ) )
    );
  }


  /**
   * Wordpress init actions.
   */
  public function init() {
    ob_start();
    $this->wds_overview();
    add_action('init', array($this, 'language_load'));
    add_action('init', array($this, 'register_post_types'));
  }

  /**
   * Plugin menu.
   */
  function admin_menu() {
    $parent_slug = $this->is_free ? null : 'sliders_wds';
    if( !$this->is_free || get_option( "wds_subscribe_done" ) == 1 ) {
      add_menu_page(__('Slider WD', $this->prefix), __('Slider WD', $this->prefix), 'manage_options', 'sliders_' . $this->prefix, array($this, 'admin_pages_new'), $this->plugin_url . '/images/wd_slider.png');
      $parent_slug = "sliders_wds";
    }

    $sliders_page = add_submenu_page($parent_slug, __('Sliders', $this->prefix), __('Sliders', $this->prefix), 'manage_options', 'sliders_'. $this->prefix, array($this, 'admin_pages_new'));
    add_action('admin_print_styles-' . $sliders_page, array($this, 'admin_styles'));
    add_action('admin_print_scripts-' . $sliders_page, array($this, 'admin_scripts'));
    add_action('load-' . $sliders_page, array($this, 'sliders_per_page_option'));

    $global_options_page = add_submenu_page($parent_slug, __('Options', $this->prefix), __('Options', $this->prefix), 'manage_options', 'goptions_wds', array($this, 'admin_pages'));
    add_action('admin_print_styles-' . $global_options_page, array($this, 'admin_styles'));
    add_action('admin_print_scripts-' . $global_options_page, array($this, 'admin_scripts'));

    if ( $this->is_free ) {
      add_submenu_page($parent_slug, __('Get Pro', $this->prefix), __('Get Pro', $this->prefix), 'manage_options', 'licensing_wds', array($this, 'licensing'));
    }

    $demo_slider = add_submenu_page($parent_slug, __('Import', $this->prefix), __('Import', $this->prefix), 'manage_options', 'demo_sliders_wds', array($this, 'demo_sliders'));
    add_action('admin_print_styles-' . $demo_slider, array($this, 'admin_styles'));
    add_action('admin_print_scripts-' . $demo_slider, array($this, 'admin_scripts'));

    $uninstall_page = add_submenu_page(null, __('Uninstall', $this->prefix), __('Uninstall', $this->prefix), 'manage_options', 'uninstall_wds', array($this, 'admin_pages'));
    add_action('admin_print_styles-' . $uninstall_page, array($this, 'admin_styles'));
    add_action('admin_print_scripts-' . $uninstall_page, array($this, 'admin_scripts'));
  }

  /**
   * Admin pages.
   */
  public function admin_pages_new() {
    $allowed_pages = array(
      'sliders_' . $this->prefix,
    );
    $page = WDW_S_Library::get('page');
    if ( !empty($page) && in_array($page, $allowed_pages) ) {
      $page = WDW_S_Library::clean_page_prefix($page);
      $controller_page = $this->plugin_dir . '/admin/controllers/' . $page . '.php';
      $model_page = $this->plugin_dir . '/admin/models/' . $page . '.php';
      $view_page = $this->plugin_dir . '/admin/views/' . $page . '.php';
      if ( !is_file($controller_page) ) {
        echo wp_sprintf(__('The %s controller file not exist.', $this->prefix), '"<b>' . $page . '</b>"');
        return FALSE;
      }
      if ( !is_file($view_page) ) {
        echo wp_sprintf(__('The %s view file not exist.', $this->prefix), '"<b>' . $page . '</b>"');
        return FALSE;
      }
      // Load page file.
      require_once($this->plugin_dir . '/admin/views/AdminView.php');
      require_once($controller_page);
      if ( is_file($model_page) ) {
        require_once($model_page);
      }
      require_once($view_page);
      $controller_class = $page . 'Controller_' . $this->prefix;
      $model_class = $page . 'Model_' . $this->prefix;
      $view_class = $page . 'View_' . $this->prefix;
      // Checking page class.
      if ( !class_exists($controller_class) ) {
        echo wp_sprintf(__('The %s class not exist.', $this->prefix), '"<b>' . $controller_class . '</b>"');
        return FALSE;
      }
      $Model = new stdClass();
      if ( class_exists($view_class) ) {
        $Model = new $model_class();
      }
      $View = new stdClass();
      if ( class_exists($view_class) ) {
        $View =  new $view_class();
      } else {
        echo wp_sprintf(__('The %s class not exist.', $this->prefix), '"<b>' . $view_class . '</b>"');
        return FALSE;
      }
      $controller = new $controller_class( array(
                                             'model' => $Model,
                                             'view' => $View
                                           ));
      $controller->execute();
    }
  }

  function admin_pages() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    $page = WDW_S_Library::get('page');
    if (($page != '') && (($page == 'sliders_wds') || ($page == 'uninstall_wds') || ($page == 'WDSShortcode') || ($page == 'goptions_wds'))) {
      require_once($this->plugin_dir . '/admin/controllers/WDSController' . (($page == 'WDSShortcode') ? $page : ucfirst(strtolower($page))) . '.php');
      $controller_class = 'WDSController' . ucfirst(strtolower($page));
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * Add pagination to sliders admin pages.
   */
  public function sliders_per_page_option() {
    $option = 'per_page';
    $args = array(
      'default' => 20,
      'option' => 'wds_sliders_per_page',
    );
    add_screen_option($option, $args);
  }

  public function set_option_sliders( $status, $option, $value ) {
    if ( 'wds_sliders_per_page' == $option ) {
      return $value;
    }
    return $status;
  }
  /**
   * Licensing page.
   */
  function licensing() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    wp_register_style('wds_licensing', $this->plugin_url . '/licensing/style.css', array(), $this->plugin_version);
    wp_print_styles('wds_licensing');
    require_once($this->plugin_dir . '/licensing/licensing.php');
  }

  /**
   * Demo slides page.
   */
  function demo_sliders() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    require_once($this->plugin_dir . '/demo_sliders/demo_sliders.php');
    wp_register_style('wds_demo_sliders', $this->plugin_url . '/demo_sliders/style.css', array(), $this->plugin_version);
    wp_print_styles('wds_demo_sliders');
    spider_demo_sliders();
  }

  /**
   * Frontend pages.
   */
  function frontend() {
    $page = WDW_S_Library::get('action');
    if (($page != '') && ($page == 'WDSShare')) {
      require_once($this->plugin_dir . '/frontend/controllers/WDSController' . ucfirst($page) . '.php');
      $controller_class = 'WDSController' . ucfirst($page);
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * Admin ajax.
   */
  function admin_ajax() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    $page = WDW_S_Library::get('action');
    if ($page != '' && (($page == 'WDSShortcode') || ($page == 'WDSPosts') || ($page == 'WDSExport') || ($page == 'WDSImport'))) {
      require_once($this->plugin_dir . '/admin/controllers/WDSController' . ucfirst($page) . '.php');
      $controller_class = 'WDSController' . ucfirst($page);
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * @param $params
   * @return mixed|string|void
   */
  function shortcode($params) {
    if ( is_admin() || isset($_GET['elementor-preview'])) {
      // return ob_get_clean();
      return __('Preview unavailable', $this->prefix);
    }
    else {
      $params = shortcode_atts(array('id' => WDW_S_Library::get('slider_id', 0)), $params);
      ob_start();
      $this->front_end($params['id']);
      return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
    }
  }

  /**
   * @param $id
   * @param int $from_shortcode.
   */
  function front_end($id, $from_shortcode = 1) {
    require_once(WDS()->plugin_dir . '/frontend/controllers/WDSControllerSlider.php');
    $controller = new WDSControllerSlider();
    global $wds;
    $controller->execute($id, $from_shortcode, $wds);
    $wds++;
    return;
  }

  function media_button($context) {
    global $pagenow;
    if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php', 'admin-ajax.php'))) {
      $context .= '
      <a onclick="tb_click.call(this); wds_thickDims(); return false;" href="' . add_query_arg(array('action' => 'WDSShortcode', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) . '" class="wds_thickbox button" style="padding-left: 0.4em;" title="Select slider">
        <span class="wp-media-buttons-icon wds_media_button_icon" style="vertical-align: text-bottom; background: url(' . $this->plugin_url . '/images/wd_slider.png) no-repeat scroll left top rgba(0, 0, 0, 0);"></span>
        Add Slider WD
      </a>';
    }
    return $context;
  }

  function admin_head() {
    ?>
    <script>
      var wds_thickDims, wds_tbWidth, wds_tbHeight;
      wds_tbWidth = 400;
      wds_tbHeight = 200;
      wds_thickDims = function() {
        var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
        w = (wds_tbWidth && wds_tbWidth < W - 90) ? wds_tbWidth : W - 40;
        h = (wds_tbHeight && wds_tbHeight < H - 60) ? wds_tbHeight : H - 40;
        if (tbWindow.size()) {
          tbWindow.width(w).height(h);
          jQuery('#TB_iframeContent').width(w).height(h - 27);
          tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
          if (typeof document.body.style.maxWidth != 'undefined') {
            tbWindow.css({'top':(H-h)/2,'margin-top':'0'});
          }
        }
      };
    </script>
    <?php
  }

  function UploadHandler() {
    WDW_S_Library::verify_nonce('wds_UploadHandler');
    require_once($this->plugin_dir . '/filemanager/UploadHandler.php');
  }

  function filemanager_ajax() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    $page = WDW_S_Library::get('action');
    $tab = WDW_S_Library::get('tab');

    //  $query_url = wp_nonce_url($query_url, 'addImage', $this->nonce);
    if ( (($page != '') && (($page == 'addImage') || ($page == 'addMusic')))
      || $tab == 'wds_custom_uploader' ) {
      if ( $tab != 'wds_custom_uploader' ) {
        WDW_S_Library::verify_nonce($page);
      }
      require_once($this->plugin_dir . '/filemanager/controller.php');
      $controller_class = 'FilemanagerController';
      $controller = new $controller_class();
      $addImages_ajax = WDW_S_Library::get('addImages_ajax');
      if ($addImages_ajax == 'addImages_ajax') {
        $load_count = WDW_S_Library::get('load_count');
        $images_list = $controller->get_images(intval($load_count));
        echo (json_encode($images_list, true));
        die;
      }
      else {
        $controller->execute(true, 1);
      }
    }
  }

  function register_widget() {
    require_once($this->plugin_dir . '/admin/controllers/WDSControllerWidgetSlideshow.php');
    return register_widget("WDSControllerWidgetSlideshow");
  }

  function activate() {
    delete_transient('wds_update_check');
    $this->install();
    $this->register_post_types();
    flush_rewrite_rules();
  }

  function install() {
    $version = get_option("wds_version");
    $new_version = $this->db_version;
    if ($version && version_compare($version, $new_version, '<')) {
      require_once $this->plugin_dir . "/sliders-update.php";
      wds_update($version);
      update_option("wds_version", $new_version);
    }
    elseif (!$version) {
      require_once $this->plugin_dir . "/sliders-insert.php";
      wds_insert();
      add_option("wds_version", $new_version, '', 'no');
      add_option("wds_version_1.0.46", 1, '', 'no');
      if ( $this->is_free ) {
        add_option("wds_theme_version", '1.0.0', '', 'no');
      }
    }
  }

  /**
   * Admin styles.
   */
  function admin_styles() {
    wp_admin_css('thickbox');
    wp_enqueue_style($this->prefix . '_tables');
    wp_enqueue_style('wds_tables_640', $this->plugin_url . '/css/wds_tables_640.css', array(), $this->plugin_version);
    wp_enqueue_style('wds_tables_320', $this->plugin_url . '/css/wds_tables_320.css', array(), $this->plugin_version);
    $google_fonts = WDW_S_Library::get_google_fonts();
    for ($i = 0; $i < count($google_fonts); $i = $i + 150) {
      $fonts = array_slice($google_fonts, $i, 150);
      $query = implode("|", str_replace(' ', '+', $fonts));
      $url = 'https://fonts.googleapis.com/css?family=' . $query . '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic';
      wp_enqueue_style('wds_googlefonts_' . $i, $url, null, null);
    }
    wp_enqueue_style('wds_deactivate-css',  $this->plugin_url . '/wd/assets/css/deactivate_popup.css', array(), $this->plugin_version);
  }

  /**
   * Admin scripts.
   */
  function admin_scripts() {
    $wds_global_options = get_option("wds_global_options", 0);
    $global_options = json_decode($wds_global_options);
    if (!$global_options) {
      $global_options = WDW_S_Library::global_options_defults();
    }
    wp_enqueue_media();
    wp_enqueue_script('thickbox');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('jquery-ui-draggable');
    wp_enqueue_script('jquery-ui-tooltip');
    wp_enqueue_script($this->prefix . '_admin');
    wp_enqueue_script('jscolor', $this->plugin_url . '/js/jscolor/jscolor.js', array(), '1.3.9');
    wp_enqueue_style('wds_font-awesome', $this->plugin_url . '/css/font-awesome/font-awesome.css', array(), '4.6.3');
    wp_enqueue_style('wds_effects', $this->plugin_url . '/css/wds_effects.css', array(), $this->plugin_version);
    if ( !$this->is_free ) {
      wp_enqueue_script('wds_hotspot', $this->plugin_url . '/js/wds_hotspot.js', array(), $this->plugin_version);
      wp_enqueue_script('wds_embed', $this->plugin_url . '/js/wds_embed.js', array(), $this->plugin_version);
    }
    require_once(WDS()->plugin_dir . '/framework/WDW_S_Library.php');
    wp_localize_script('wds_admin', 'wds_object', array(
      "GGF" => WDW_S_Library::get_google_fonts(),
      "FGF" => WDW_S_Library::get_font_families(),
      "LDO" => $global_options,
      "is_free" => $this->is_free,
      'translate' => array(
        'check_at_least' => __('You must check at least one item.', $this->prefix),
        'no_slider' => __('There is no slider.', $this->prefix),
        'min_size' => __('Sets the minimal size of the text. It will be shrunk until the font size is equal to this value.', $this->prefix),
        'font_size' => __('Size:', $this->prefix),
        'please_enter_url_to_embed' => __('Please enter url to embed.', $this->prefix),
        'error_cannot_get_response_from_the_server' => __('Error: cannot get response from the server.', $this->prefix),
        'error_something_wrong_happened_at_the_server' => __('Error: something wrong happened at the server.', $this->prefix),
        'edit_filmstrip_thumbnail' => __('Edit Filmstrip Thumbnail', $this->prefix),
        'you_must_set_watermark_type' => __('You must set watermark type.', $this->prefix),
        'watermark_succesfully_set' => __('Watermark Succesfully Set.', $this->prefix),
        'watermark_succesfully_reset' => __('Watermark Succesfully Reset.', $this->prefix),
        'items_succesfully_saved' => __('Items Succesfully Saved.', $this->prefix),
        'changes_made_in_this_table_should_be_saved' => __('Changes made in this table should be saved.', $this->prefix),
        'selected' => __('Selected', $this->prefix),
        'item' => __('item', $this->prefix),
        's' => __('s', $this->prefix),
        'you_must_select_an_image_file' => __('You must select an image file.', $this->prefix),
        'album_thumb_dimensions' => __('Album thumb dimensions:', $this->prefix),
        'album_thumb_width' => __('Album thumb width:', $this->prefix),
        'edit_thumbnail' => __('Edit Thumbnail', $this->prefix),
        'do_you_want_to_delete_layer' => __('Do you want to delete the layer?', $this->prefix),
        'drag_to_re_order' => __('Drag to re-order', $this->prefix),
        'layer' => __('Layer', $this->prefix),
        'delete_layer' => __('Delete layer', $this->prefix),
        'duplicate_layer' => __('Duplicate layer', $this->prefix),
        'text' => __('Text:', $this->prefix),
        'sample_text' => __('Sample text', $this->prefix),
        'leave_blank_to_keep_the_initial_width_and_height' => __('Leave blank to keep the initial width and height.', $this->prefix),
        'dimensions' => __('Dimensions:', $this->prefix),
        'break_word' => __('Break-word', $this->prefix),
        'edit_image' => __('Edit Image', $this->prefix),
        'set_the_html_attribute_specified_in_the_img_tag' => __('Set the value of alt HTML attribute for this image layer.', $this->prefix),
        'alt' => __('Alt:', $this->prefix),
        'use_http_and_https_for_external_links' => __('Use http:// and https:// for external links.', $this->prefix),
        'link' => __('Link:', $this->prefix),
        'open_in_a_new_window' => __('Open in a new window', $this->prefix),
        'in_addition_you_can_drag_and_drop_the_layerto_a_desired_position' => __('In addition, you can drag the layer and drop it to the desired position.', $this->prefix),
        'position' => __('Position:', $this->prefix),
        'published' => __('Published:', $this->prefix),
        'fixed_step_left_center_right' => __('Fixed step (left, center, right)', $this->prefix),
        'yes' => __('Yes', $this->prefix),
        'no' => __('No', $this->prefix),
        'color' => __('Color:', $this->prefix),
        'hover_color' => __('Hover Color', $this->prefix),
        'size' => __('Size:', $this->prefix),
        'font_family' => __('Font family:', $this->prefix),
        'google_fonts' => __('Google fonts', $this->prefix),
        'default' => __('Default', $this->prefix),
        'font_weight' => __('Font weight:', $this->prefix),
        'padding' => __('Padding:', $this->prefix),
        'value_must_be_between_0_to_100' => __('Value must be between 0 and 100.', $this->prefix),
        'transparent' => __('Transparency:', $this->prefix),
        'border' => __('Border:', $this->prefix),
        'use_css_type_values' => __('Use CSS type values.', $this->prefix),
        'use_css_type_values_e_g_10_10_5_888888' => __('Use CSS type values (e.g. 10px 10px 5px #888888).', $this->prefix),
        'shadow' => __('Shadow', $this->prefix),
        'dimensions' => __('Dimensions:', $this->prefix),
        'set_width_and_height_of_the_image' => __('Set width and height of the image.', $this->prefix),
        'set_width_and_height_of_the_video' => __('Set width and height of the video.', $this->prefix),
        'social_button' => __('Social button', $this->prefix),
        'effect_in' => __('Effect in:', $this->prefix),
        'effect_out' => __('Effect out:', $this->prefix),
        'start' => __('Start', $this->prefix),
        'effect' => __('Effect', $this->prefix),
        'duration' => __('Duration', $this->prefix),
        'iteration' => __('Iteration', $this->prefix),
        'autoplay' => __('Autoplay:', $this->prefix),
        'controls' => __('Controls:', $this->prefix),
        'hotspot_width' => __('Hotspot Width:', $this->prefix),
        'hotspot_background_color' => __('Hotspot Background Color:', $this->prefix),
        'hotspot_border' => __('Hotspot Border:', $this->prefix),
        'hotspot_radius' => __('Hotspot Radius:', $this->prefix),
        'in_addition_you_can_drag_and_drop_the_layer_to_a_desired_position' => __('In addition, you can drag the layer and drop it to the desired position.', $this->prefix),
        'leave_blank_to_keep_the_initial_width_and_height' => __('Leave blank to keep the initial width and height.', $this->prefix),
        'video_loop' => __('Video Loop', $this->prefix),
        'disable_youtube_related_video' => __('Disable youtube related video:', $this->prefix),
        'hotspot_animation' => __('Hotspot Animation:', $this->prefix),
        'add_click_action' => __('Add click action:', $this->prefix),
        'select_between_the_option_of_always_displaying_the_navigation_buttons_or_only_when_hovered' => __('Select between the option of always displaying the navigation buttons or only when hovered.', $this->prefix),
        'show_hotspot_text' => __('Show Hotspot text:', $this->prefix),
        'on_hover' => __('On hover', $this->prefix),
        'on_click' => __('On click', $this->prefix),
        'text_alignment' => __('Text alignment:', $this->prefix),
        'slides_name' => __('Slides name:', $this->prefix),
        'static_layer' => __('Static layer:', $this->prefix),
        'the_layer_will_be_visible_on_all_slides' => __('The layer will be visible on all slides.', $this->prefix),
        'add_edit_image' => __('Add/Edit Image', $this->prefix),
        'add_image_layer' => __('Add Image Layer', $this->prefix),
        'slide' => __('Slide', $this->prefix),
        'duplicate_slide' => __('Duplicate slide', $this->prefix),
        'delete_slide' => __('Delete slide', $this->prefix),
        'add_image_by_url' => __('Add Image by URL', $this->prefix),
        'embed_media' => __('Embed Media', $this->prefix),
        'add_post' => __('Add Post', $this->prefix),
        'delete' => __('Delete', $this->prefix),
        'youtube_related_video' => __('Youtube related video:', $this->prefix),
        'video_loop' => __('Video Loop:', $this->prefix),
        'you_can_set_a_redirection_link_so_that_the_user_will_get_to_the_mentioned_location_upon_hitting_the_slide_use_http_and_https_for_external_links' => __('You can add a URL, to which the users will be redirected upon clicking on the slide. Use http:// and https:// for external links.', $this->prefix),
        'link_the_slide_to' => __('Link the slide to:', $this->prefix),
        'add_text_layer' => __('Add Text Layer', $this->prefix),
        'add_video_layer' => __('Add Video Layer', $this->prefix),
        'embed_media_layer' => __('Embed Media Layer', $this->prefix),
        'add_social_buttons_layer' => __('Add Social Buttons Layer', $this->prefix),
        'add_hotspot_layer' => __('Add Hotspot Layer', $this->prefix),
        'do_you_want_to_delete_slide' => __('Do you want to delete slide?', $this->prefix),
        'sorry_you_are_not_allowed_to_upload_this_type_of_file' => __('Sorry, you are not allowed to upload this type of file.', $this->prefix),
        'you_must_select_at_least_one_item' => __('You must select at least one item.', $this->prefix),
        'do_you_want_to_delete_selected_items' => __('Do you want to delete selected items?', $this->prefix),
        'are_you_sure_you_want_to_reset_the_settings' => __('Are you sure you want to reset the settings?', $this->prefix),
        'choose' => __('Choose', $this->prefix),
        'choose_video' => __('Choose Video', $this->prefix),
        'choose_image' => __('Choose Image', $this->prefix),
        'insert' => __('Insert', $this->prefix),
        'add_class' => __('Add class:', $this->prefix),
        'radius' => __('Radius:', $this->prefix),
        'editor' => __('Editor', $this->prefix),
        'group' => __('Group', $this->prefix),
        'color' => __('Color', $this->prefix),
        'background_color' => __('Background Color:', $this->prefix),
        'none' => __('None', $this->prefix),
        'bounce' => __('Bounce', $this->prefix),
        'flash' => __('Flash', $this->prefix),
        'pulse' => __('Pulse', $this->prefix),
        'shake' => __('Shake', $this->prefix),
        'swing' => __('Swing', $this->prefix),
        'tada' => __('Tada', $this->prefix),
        'wobble' => __('Wobble', $this->prefix),
        'hinge' => __('Hinge', $this->prefix),
        'rubberBand' => __('RubberBand', $this->prefix),
        'lightSpeedIn' => __('LightSpeedIn', $this->prefix),
        'rollIn' => __('RollIn', $this->prefix),
        'bounceIn' => __('BounceIn', $this->prefix),
        'bounceInDown' => __('BounceInDown', $this->prefix),
        'bounceInLeft' => __('BounceInLeft', $this->prefix),
        'bounceInRight' => __('BounceInRight', $this->prefix),
        'bounceInUp' => __('BounceInUp', $this->prefix),
        'fadeIn' => __('FadeIn', $this->prefix),
        'fadeInDown' => __('FadeInDown', $this->prefix),
        'fadeInDownBig' => __('FadeInDownBig', $this->prefix),
        'fadeInLeft' => __('FadeInLeft', $this->prefix),
        'fadeInLeftBig' => __('FadeInLeftBig', $this->prefix),
        'fadeInRight' => __('FadeInRight', $this->prefix),
        'fadeInRightBig' => __('FadeInRightBig', $this->prefix),
        'fadeInUp' => __('FadeInUp', $this->prefix),
        'fadeInUpBig' => __('FadeInUpBig', $this->prefix),
        'flip' => __('Flip', $this->prefix),
        'flipInX' => __('FlipInX', $this->prefix),
        'flipInY' => __('FlipInY', $this->prefix),
        'rotateIn' => __('RotateIn', $this->prefix),
        'rotateInDownLeft' => __('RotateInDownLeft', $this->prefix),
        'rotateInDownRight' => __('RotateInDownRight', $this->prefix),
        'rotateInUpLeft' => __('RotateInUpLeft', $this->prefix),
        'rotateInUpRight' => __('RotateInUpRight', $this->prefix),
        'zoomIn' => __('ZoomIn', $this->prefix),
        'zoomInDown' => __('ZoomInDown', $this->prefix),
        'zoomInLeft' => __('ZoomInLeft', $this->prefix),
        'zoomInRight' => __('ZoomInRight', $this->prefix),
        'zoomInUp' => __('ZoomInUp', $this->prefix),
        'lightSpeedOut' => __('LightSpeedOut', $this->prefix),
        'rollOut' => __('RollOut', $this->prefix),
        'bounceOut' => __('BounceOut', $this->prefix),
        'bounceOutDown' => __('BounceOutDown', $this->prefix),
        'bounceOutLeft' => __('BounceOutLeft', $this->prefix),
        'bounceOutRight' => __('BounceOutRight', $this->prefix),
        'bounceOutUp' => __('BounceOutUp', $this->prefix),
        'fadeOut' => __('FadeOut', $this->prefix),
        'fadeOutDown' => __('FadeOutDown', $this->prefix),
        'fadeOutDownBig' => __('FadeOutDownBig', $this->prefix),
        'fadeOutLeft' => __('FadeOutLeft', $this->prefix),
        'fadeOutLeftBig' => __('FadeOutLeftBig', $this->prefix),
        'fadeOutRight' => __('FadeOutRight', $this->prefix),
        'fadeOutRightBig' => __('FadeOutRightBig', $this->prefix),
        'fadeOutUp' => __('FadeOutUp', $this->prefix),
        'fadeOutUpBig' => __('FadeOutUpBig', $this->prefix),
        'flip' => __('Flip', $this->prefix),
        'flipOutX' => __('FlipOutX', $this->prefix),
        'flipOutY' => __('FlipOutY', $this->prefix),
        'rotateOut' => __('RotateOut', $this->prefix),
        'rotateOutDownLeft' => __('RotateOutDownLeft', $this->prefix),
        'rotateOutDownRight' => __('RotateOutDownRight', $this->prefix),
        'rotateOutUpLeft' => __('RotateOutUpLeft', $this->prefix),
        'rotateOutUpRight' => __('RotateOutUpRight', $this->prefix),
        'zoomOut' => __('ZoomOut', $this->prefix),
        'zoomOutDown' => __('ZoomOutDown', $this->prefix),
        'zoomOutLeft' => __('ZoomOutLeft', $this->prefix),
        'zoomOutRight' => __('ZoomOutRight', $this->prefix),
        'zoomOutUp' => __('ZoomOutUp', $this->prefix),
        'insert_valid_audio_file' => __('Insert valid audio file', $this->prefix),
        'fillmode' => __('Fillmode', $this->prefix),
        'fill' => __('Fill', $this->prefix),
        'Changes_must_be_saved' => __('Changes must be saved', $this->prefix),
        'edit_slide' => __('Edit Slide', $this->prefix),
        'media_library' => __('Media Library'), // This is WP translation.
        'disabled_in_free_version' => __('This functionality is disabled in free version.', $this->prefix),
        'video_disabled_in_free_version' => __('You can`t add video slide in free version', $this->prefix),
      )
    ));

    wp_enqueue_script('wds-deactivate-popup', $this->plugin_url.'/wd/assets/js/deactivate_popup.js', array(), $this->plugin_version, true );
    $admin_data = wp_get_current_user();

    wp_localize_script( 'wds-deactivate-popup', 'wdsWDDeactivateVars', array(
      "prefix" => $this->prefix ,
      "deactivate_class" =>  'wds_deactivate_link',
      "email" => $admin_data->data->user_email,
      "plugin_wd_url" => "https://web-dorado.com/products/wordpress-slider-plugin.html",
    ));
  }

  function language_load() {
    load_plugin_textdomain($this->prefix, FALSE, basename(dirname(__FILE__)) . '/languages');
  }

  /**
   * Front end scripts and styles.
   */
  function front_end_scripts() {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wdslayer ORDER BY `depth` ASC");
    $font_array = array();
    foreach ($rows as $row) {
      if (isset($row->google_fonts) && ($row->google_fonts == 1) && ($row->ffamily != "") && !in_array($row->ffamily, $font_array)) {
        $font_array[] = $row->ffamily;
      }
    }
    $query = implode("|", $font_array);
    if ($query != '') {
      $url = 'https://fonts.googleapis.com/css?family=' . $query . '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic';
    }
    wp_register_style('wds_frontend', $this->front_url . '/css/wds_frontend.css', array(), $this->plugin_version);
    wp_register_style('wds_effects', $this->front_url . '/css/wds_effects.css', array(), $this->plugin_version);
    wp_register_style('wds_font-awesome', $this->front_url . '/css/font-awesome/font-awesome.css', array(), '4.6.3');
    if ($query != '') {
      wp_register_style('wds_googlefonts', $url, null, null);
    }
    wp_register_script('wds_jquery_mobile', $this->front_url . '/js/jquery.mobile.js', array('jquery'), $this->plugin_version);
    wp_register_script($this->prefix . '_frontend', $this->front_url . '/js/wds_frontend.js', array('jquery'), $this->plugin_version, TRUE);
    wp_localize_script( $this->prefix . '_frontend', 'wds_object', array(
      "is_free" => $this->is_free,
      'pause' => __('Pause', $this->prefix),
      'play' => __('Play', $this->prefix),
    ));
    if ( !$this->is_free ) {
      wp_register_script('wds_jquery_featureCarouselslider', $this->front_url . '/js/jquery.featureCarouselslider.js', array( 'jquery' ), $this->plugin_version);
      wp_register_script('wds_hotspot', $this->front_url . '/js/wds_hotspot.js', array( 'jquery' ), $this->plugin_version);
      wp_register_script('wds_youtube', 'https://www.youtube.com/iframe_api');
    }
  }

  function add_embed_ajax() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    require_once($this->plugin_dir . '/framework/WDW_S_LibraryEmbed.php');

    if (!WDW_S_LibraryEmbed::verify_nonce('')) {
      die(WDW_S_LibraryEmbed::delimit_wd_output(json_encode(array("error", "Sorry, your nonce did not verify."))));
    }
    $embed_action = WDW_S_Library::get('action');
    if (($embed_action != '') && ($embed_action == 'wds_addEmbed')) {
      $url_to_embed = WDW_S_Library::get('URL_to_embed');
      $data = WDW_S_LibraryEmbed::add_embed($url_to_embed);
      echo WDW_S_LibraryEmbed::delimit_wd_output($data);
      wp_die();
    }
    die('Nothing to add');
  }

  /**
   * Register slider preview custom post type.
   */
  function register_post_types() {
    $args = array(
      'public' => TRUE,
      'show_in_menu' => FALSE,
      'exclude_from_search' => TRUE,
      'create_posts' => 'do_not_allow',
      'capabilities' => array(
        'create_posts' => FALSE,
        'edit_post' => 'edit_posts',
        'read_post' => 'edit_posts',
        'delete_posts' => FALSE,
      ),
    );
    register_post_type('wds-slider', $args);
  }

  public function wds_overview() {
    if (is_admin() && !isset($_REQUEST['ajax'])) {
      if (!class_exists("DoradoWeb")) {
        require_once($this->plugin_dir . '/wd/start.php');
      }
      global $wds_options;
      $wds_options = array(
        "prefix" => "wds",
        "wd_plugin_id" => 69,
        "plugin_title" => "Slider WD",
        "plugin_wordpress_slug" => "slider-wd",
        "plugin_dir" => $this->plugin_dir,
        "plugin_main_file" => __FILE__,
        "description" => __('Slider WD is a responsive plugin, which allows adding sliders to your posts/pages and to custom location. It uses large number of transition effects and supports various types of layers.', $this->prefix),
        // from web-dorado.com
        "plugin_features" => array(
          0 => array(
            "title" => __("Responsive", $this->prefix),
            "description" => __("Sleek, powerful and intuitive design and layout brings the slides on a new level, for perfect and fast web surfing. Ways that users interact with 100% responsive Slider WD guarantees better and brave experience.", $this->prefix),
          ),
          1 => array(
            "title" => __("SEO Friendly", $this->prefix),
            "description" => __("Slider WD has developed the best practices in SEO field. The plugin supports all functions necessary for top-rankings.", $this->prefix),
          ),
          2 => array(
            "title" => __("Drag & Drop Back-End Interface", $this->prefix),
            "description" => __("Arrange each and every layer via user friendly drag and drop interface in seconds. This function guarantees fast and effective usability of the plugin without any development skills.", $this->prefix),
          ),
          3 => array(
            "title" => __("Touch Swipe Navigation", $this->prefix),
            "description" => __("Touch the surface of your mobile devices and experience smooth finger navigation. In desktop devices you can experience the same navigation using mouse dragging.", $this->prefix),
          ),
          4 => array(
            "title" => __("Navigation Custom Buttons", $this->prefix),
            "description" => __("You can choose among variety of navigation button designs included in the plugin or upload and use your custom ones, based on preferences.", $this->prefix),
          )
        ),
        // user guide from web-dorado.com
        "user_guide" => array(
          0 => array(
            "main_title" => __("Installing the Slider WD", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-slider-wd/installing.html",
            "titles" => array()
          ),
          1 => array(
            "main_title" => __("Adding Images to Sliders", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-slider-wd/adding-images.html",
            "titles" => array()
          ),
          2 => array(
            "main_title" => __("Adding Layers to The Slide", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-slider-wd/adding-layers.html",
            "titles" => array()
          ),
          3 => array(
            "main_title" => __("Changing/Modifying Slider Settings", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-slider-wd/changing-settings.html",
            "titles" => array()
          ),
          4 => array(
            "main_title" => __("Publishing the Created Slider", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-slider-wd/publishing-slider.html",
            "titles" => array()
          ),
          5 => array(
            "main_title" => __("Importing/Exporting Sliders", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-slider-wd/import-export.html",
            "titles" => array()
          ),
        ),
        "video_youtube_id" => "xebpM_-GwG0",  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
        "plugin_wd_url" => "https://web-dorado.com/products/wordpress-slider-plugin.html",
        "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com/slider/",
        "plugin_wd_addons_link" => "",
        "after_subscribe" => admin_url('admin.php?page=sliders_wds'), // this can be plagin overview page or set up page
        "plugin_wizard_link" => '',
        "plugin_menu_title" => "Slider WD",
        "plugin_menu_icon" => $this->plugin_url . '/images/wd_slider.png',
        "deactivate" => ( $this->is_free ? TRUE : FALSE ),
        "subscribe" => ( $this->is_free ? TRUE : FALSE ),
        "custom_post" => 'sliders_wds',
        "menu_position" => null,
      );

      dorado_web_init($wds_options);
    }
  }

  function topic() {
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    $user_guide_link = 'https://web-dorado.com/wordpress-slider-wd/';
    $support_forum_link = 'https://wordpress.org/support/plugin/slider-wd';
    $pro_link = 'https://web-dorado.com/files/fromslider.php';
    $pro_icon = $this->plugin_url . '/images/wd_logo.png';
    $support_icon = $this->plugin_url . '/images/support.png';
    $prefix = $this->prefix;
    switch ($page) {
      case 'sliders_wds': {
        $help_text = 'create, edit and delete sliders';
        $user_guide_link .= 'adding-images.html';
        break;
      }
      case 'goptions_wds': {
        $help_text = 'edit global options for sliders';
        $user_guide_link .= 'adding-images.html';
        break;
      }
      case 'licensing_wds': {
        $help_text = '';
        $user_guide_link .= 'adding-images.html';
        break;
      }
      default: {
        return '';
        break;
      }
    }
    ob_start();
    ?>
    <style>
      .wd_topic {
        background-color: #ffffff;
        border: none;
        box-sizing: border-box;
        clear: both;
        color: #6e7990;
        font-size: 14px;
        font-weight: bold;
        line-height: 44px;
        padding: 0 0 0 15px;
        vertical-align: middle;
        width: 98%;
      }
      .wd_topic .wd_help_topic {
        float: left;
      }
      .wd_topic .wd_help_topic a {
        color: #0073aa;
      }
      .wd_topic .wd_help_topic a:hover {
        color: #00A0D2;
      }
      .wd_topic .wd_support {
        float: right;
        margin: 0 10px;
      }
      .wd_topic .wd_support img {
        vertical-align: middle;
      }
      .wd_topic .wd_support a {
        text-decoration: none;
        color: #6E7990;
      }
      .wd_topic .wd_pro {
        float: right;
        padding: 0;
      }
      .wd_topic .wd_pro a {
        border: none;
        box-shadow: none !important;
        text-decoration: none;
      }
      .wd_topic .wd_pro img {
        border: none;
        display: inline-block;
        vertical-align: middle;
      }
      .wd_topic .wd_pro a,
      .wd_topic .wd_pro a:active,
      .wd_topic .wd_pro a:visited,
      .wd_topic .wd_pro a:hover {
        background-color: #D8D8D8;
        color: #175c8b;
        display: inline-block;
        font-size: 11px;
        font-weight: bold;
        padding: 0 10px;
        vertical-align: middle;
      }
    </style>
    <div class="update-nag wd_topic">
      <?php
      if ($help_text) {
        ?>
        <span class="wd_help_topic">
      <?php echo sprintf(__('This section allows you to %s.', $prefix), $help_text); ?>
          <a target="_blank" href="<?php echo $user_guide_link; ?>">
        <?php _e('Read More in User Manual', $prefix); ?>
      </a>
    </span>
        <?php
      }
      if ( $this->is_free ) {
        $text = strtoupper(__('Upgrade to paid version', $prefix));
        ?>
        <div class="wd_pro">
          <a target="_blank" href="<?php echo $pro_link; ?>">
            <img alt="web-dorado.com" title="<?php echo $text; ?>" src="<?php echo $pro_icon; ?>" />
            <span><?php echo $text; ?></span>
          </a>
        </div>
        <?php
      }
      if (FALSE) {
        ?>
        <span class="wd_support">
      <a target="_blank" href="<?php echo $support_forum_link; ?>">
        <img src="<?php echo $support_icon; ?>" />
        <?php _e('Support Forum', $prefix); ?>
      </a>
    </span>
        <?php
      }
      ?>
    </div>
    <?php
    echo ob_get_clean();
  }

  /**
   * Add custom tabs to media uploader.
   *
   * @param $tabs
   * @return array
   */
  function custom_media_upload_tab_name( $tabs ) {
    $custom_tabs = array( 'wds_posts', 'wds_embed', 'wds_custom_uploader' );

    if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'sliders_wds' )
      || ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], $custom_tabs ) ) ) {
      $newtabs = array(
        'wds_posts' => __( "Posts", $this->prefix ),
        'wds_embed' => __( "Embed Media", $this->prefix ),
      );

      $wds_global_options = get_option("wds_global_options", 0);
      $global_options = json_decode($wds_global_options);
      $spider_uploader = isset($global_options->spider_uploader) ? $global_options->spider_uploader : 0;
      if ( $spider_uploader ) {
        $newtabs['wds_custom_uploader'] = __( "WD Media Uploader", $this->prefix );
      }

      if ( isset($tabs['nextgen']) ) {
        unset($tabs['nextgen']);
      }

      if ( is_array( $tabs ) ) {
        return array_merge( $tabs, $newtabs );
      }
      else {
        return $newtabs;
      }
    }

    return $tabs;
  }

  /**
   * Remove unused tabs from media uploader.
   *
   * @param $strings
   *
   * @return mixed
   */
  function custom_media_uploader_tabs( $strings ) {
    if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'sliders_wds' ) ) {
      // Update strings.
      $strings['insertMediaTitle'] = __( "Images / Videos", $this->prefix );
      $strings['insertIntoPost'] = __( "Add to slider", $this->prefix );

      // Remove options.
      $strings_to_remove = array(
        'createVideoPlaylistTitle',
        'createGalleryTitle',
        'createPlaylistTitle'
      );
      foreach ($strings_to_remove as $string) {
        if (isset($strings[$string])) {
          unset($strings[$string]);
        }
      }
    }

    return $strings;
  }

  /**
   *
   */
  function media_upload_window() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    $tab = WDW_S_Library::get('tab');
    $custom_tabs = array( 'wds_posts', 'wds_embed' );
    if ( in_array($tab, $custom_tabs) ) {
      $tab = str_replace('wds_', '', $tab);
      require_once($this->plugin_dir . '/admin/controllers/' . $tab . '.php');
      $controller_class = 'WDSController' . $tab;
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * Register iframe styles and scripts.
   */
  function register_iframe_scripts() {
    $required_scripts = array( 'jquery' );
    $required_styles = array(
      // 'admin-bar',
      // 'dashicons',
      'wp-admin', // admin styles
      'buttons', // buttons styles
      'media-views', // media uploader styles
      'wp-auth-check', // check all
    );
    wp_register_script($this->prefix . '_admin', $this->plugin_url . '/js/wds.js', $required_scripts, $this->plugin_version);

    wp_register_style($this->prefix . '_tables', $this->plugin_url . '/css/wds_tables.css', $required_styles, $this->plugin_version);

    wp_localize_script( $this->prefix . '_admin', 'wds', array(
      "file_not_supported" => __('This file type is not supported.', $this->prefix),
    ));
  }

  /**
   * Register admin styles and scripts.
   */
  function register_admin_scripts() {
    $required_scripts = array( 'jquery' );
    wp_register_script($this->prefix . '_admin', $this->plugin_url . '/js/wds.js', $required_scripts, $this->plugin_version);
    wp_register_style($this->prefix . '_tables', $this->plugin_url . '/css/wds_tables.css', FALSE, $this->plugin_version);
    wp_localize_script( $this->prefix . '_admin', 'wds', array(
      "file_not_supported" =>  __('This file type is not supported.', $this->prefix),
    ));
  }

  function add_plugin_meta_links($meta_fields, $file) {
    if ( plugin_basename(__FILE__) == $file ) {
      $plugin_url = "https://wordpress.org/support/plugin/slider-wd";
      $prefix = $this->prefix;
      $meta_fields[] = "<a href='" . $plugin_url . "' target='_blank'>" . __('Support Forum', $prefix) . "</a>";
      $meta_fields[] = "<a href='" . $plugin_url . "/reviews#new-post' target='_blank' title='" . __('Rate', $prefix) . "'>
            <i class='wdi-rate-stars'>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "</i></a>";

      $stars_color = "#ffb900";

      echo "<style>"
        . ".wdi-rate-stars{display:inline-block;color:" . $stars_color . ";position:relative;top:3px;}"
        . ".wdi-rate-stars svg{fill:" . $stars_color . ";}"
        . ".wdi-rate-stars svg:hover{fill:" . $stars_color . "}"
        . ".wdi-rate-stars svg:hover ~ svg{fill:none;}"
        . "</style>";
    }

    return $meta_fields;
  }

  public function register_block_editor_assets($assets) {
    $version = '2.0.0';
    $js_path = $this->plugin_url . '/js/tw-gb/block.js';
    $css_path = $this->plugin_url . '/css/tw-gb/block.css';
    if (!isset($assets['version']) || version_compare($assets['version'], $version) === -1) {
      $assets['version'] = $version;
      $assets['js_path'] = $js_path;
      $assets['css_path'] = $css_path;
    }
    return $assets;
  }

	/**
   * Enqueue block editor assets.
   */
	public function enqueue_block_editor_assets() {
		$key = 'tw/' . $this->prefix;
		$plugin_name = $this->nicename;
		$data = WDW_S_Library::get_shortcode_data();
		$icon_url = $this->plugin_url . '/images/wt-gb/wd_slider.svg';
		$icon_svg = $this->plugin_url . '/images/wt-gb/icon.svg';
		?>
		<script>
		  if ( !window['tw_gb'] ) {
		  	window['tw_gb'] = {};
		  }
		  if ( !window['tw_gb']['<?php echo $key; ?>'] ) {
				window['tw_gb']['<?php echo $key; ?>'] = {
				title: '<?php echo $plugin_name; ?>',
        titleSelect: '<?php echo sprintf(__('Select %s', $this->prefix), $plugin_name); ?>',
				iconUrl: '<?php echo $icon_url; ?>',
				iconSvg: { 
					width: '30',
					height: '30',
					src: '<?php echo $icon_svg; ?>'
				},
				data: '<?php echo $data; ?>',
			};
		  }		  
		</script>
		<?php
    // Remove previously registered or enqueued versions
    $wp_scripts = wp_scripts();
    foreach ($wp_scripts->registered as $key => $value) {
      // Check for an older versions with prefix.
      if (strpos($key, 'tw-gb-block') > 0) {
        wp_deregister_script( $key );
        wp_deregister_style( $key );
      }
    }
    // Get the last version from all 10Web plugins.
    $assets = apply_filters('tw_get_block_editor_assets', array());
    // Not performing unregister or unenqueue as in old versions all are with prefixes.
    wp_enqueue_script('tw-gb-block', $assets['js_path'], array( 'wp-blocks', 'wp-element' ), $assets['version']);
    wp_localize_script('tw-gb-block', 'tw_obj', array(
      'nothing_selected' => __('Nothing selected.', $this->prefix),
      'empty_item' => __('- Select -', $this->prefix),
    ));
    wp_enqueue_style('tw-gb-block', $assets['css_path'], array( 'wp-edit-blocks' ), $assets['version']);
	}
}

/**
 * Main instance of WDS.
 *
 * @return WDS The main instance to prevent the need to use globals.
 */
function WDS() {
  return WDS::instance();
}

WDS();

/**
 * PHP Function to use in templates.
 *
 * @param $id
 */
function wd_slider($id) {
  echo WDS()->front_end($id);
}

/**
 * Get sliders for theme developers.
 *
 * @return array
 */
function wds_get_sliders() {
  global $wpdb;
  $results = $wpdb->get_results("SELECT `id`,`name` FROM `" . $wpdb->prefix . "wdsslider`", OBJECT_K);
  $sliders = array();
  foreach ($results as $id => $slider) {
    $sliders[$id] = isset($slider->name) ? $slider->name : '';
  }
  return $sliders;
}

/**
 * Show notice to install Image Optimization plugin
 */
function wds_io_install_notice() {
  // Remove old notice.
  if ( get_option('wds_io_notice_status') !== FALSE ) {
    update_option('wds_io_notice_status', '1', 'no');
  }

  // Show notice only on plugin pages.
  if ( !isset($_GET['page']) || strpos(esc_html($_GET['page']), '_wds') === FALSE ) {
    return '';
  }

  $meta_value = get_option('wds_io_notice_status');
  if ( $meta_value === '' || $meta_value === FALSE ) {
    ob_start();
    $prefix = WDS()->prefix;
    $nicename = WDS()->nicename;
    $url = WDS()->plugin_url;
    $dismiss_url = add_query_arg(array( 'action' => 'wd_io_dismiss' ), admin_url('admin-ajax.php'));
    $install_url = esc_url(wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=image-optimizer-wd'), 'install-plugin_image-optimizer-wd'));
    ?>
    <div class="notice notice-info" id="wd_io_notice_cont">
      <p>
        <img id="wd_io_logo_notice" src="<?php echo $url . '/images/iopLogo.png'; ?>" />
        <?php echo sprintf(__("%s advises: Install brand new %s plugin to optimize your website images quickly and easily.", $prefix), $nicename, '<a href="https://wordpress.org/plugins/image-optimizer-wd/" title="' . __("More details", $prefix) . '" target="_blank">' .  __("Image Optimizer WD", $prefix) . '</a>'); ?>
        <a class="button button-primary" href="<?php echo $install_url; ?>">
          <span onclick="jQuery.post('<?php echo $dismiss_url; ?>');"><?php _e("Install", $prefix); ?></span>
        </a>
      </p>
      <button type="button" class="wd_io_notice_dissmiss notice-dismiss" onclick="jQuery('#wd_io_notice_cont').hide(); jQuery.post('<?php echo $dismiss_url; ?>');"><span class="screen-reader-text"></span></button>
    </div>
    <style>
      @media only screen and (max-width: 500px) {
        body #wd_backup_logo {
          max-width: 100%;
        }
        body #wd_io_notice_cont p {
          padding-right: 25px !important;
        }
      }
      #wd_io_logo_notice {
        height: 32px;
        float: left;
        margin-right: 10px;
      }
      #wd_io_notice_cont {
        position: relative;
      }
      #wd_io_notice_cont a {
        margin: 0 5px;
      }
      #wd_io_notice_cont .dashicons-dismiss:before {
        content: "\f153";
        background: 0 0;
        color: #72777c;
        display: block;
        font: 400 16px/20px dashicons;
        speak: none;
        height: 20px;
        text-align: center;
        width: 20px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
      .wd_io_notice_dissmiss {
        margin-top: 5px;
      }
    </style>
    <?php
    echo ob_get_clean();
  }
}

if ( !is_dir(plugin_dir_path(__DIR__) . 'image-optimizer-wd') ) {
  add_action('admin_notices', 'wds_io_install_notice');
}

if ( !function_exists('wd_iops_install_notice_status') ) {
  // Add usermeta to db.
  function wd_iops_install_notice_status() {
    update_option('wds_io_notice_status', '1', 'no');
  }
  add_action('wp_ajax_wd_io_dismiss', 'wd_iops_install_notice_status');
}
