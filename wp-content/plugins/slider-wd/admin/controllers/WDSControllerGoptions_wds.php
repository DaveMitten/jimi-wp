<?php
class WDSControllerGoptions_wds {

  public function __construct() {
  }

  public function execute() {
    $task = WDW_S_Library::get('task');
    $id = WDW_S_Library::get('current_id', 0);
    $message = WDW_S_Library::get('message');
    echo WDW_S_Library::message_id($message);
    if (method_exists($this, $task)) {
      check_admin_referer('nonce_wd', 'nonce_wd');
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WDS()->plugin_dir . "/admin/models/WDSModelGoptions_wds.php";
    $model = new WDSModelGoptions_wds();

    require_once WDS()->plugin_dir . "/admin/views/WDSViewGoptions_wds.php";
    $view = new WDSViewGoptions_wds($model);
    $view->display($this->get_sliders());
  }

  public function save_font_family() {
    $wds_global_options = json_decode(get_option("wds_global_options"), true);
    $possib_add_ffamily = (isset($_REQUEST['possib_add_ffamily']) ? esc_html($_REQUEST['possib_add_ffamily']) : '');
    $possib_add_ffamily_google = (isset($_REQUEST['possib_add_ffamily_google']) ? esc_html($_REQUEST['possib_add_ffamily_google']) : '');
    
    $wds_global_options['possib_add_ffamily'] = $possib_add_ffamily;
    $wds_global_options['possib_add_ffamily_google'] = $possib_add_ffamily_google;
    $global_options = json_encode($wds_global_options);
    update_option("wds_global_options", $global_options);
    
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array( 'page'    => $page,
                                                        'task'    => 'display',
                                                        'message' => 1,
                                                 ), admin_url('admin.php')));
  }

  public function save() {
    $register_scripts = (isset($_REQUEST['register_scripts']) ? (int) $_REQUEST['register_scripts'] : 0);
    $loading_gif = (isset($_REQUEST['loading_gif']) ? esc_html($_REQUEST['loading_gif']) : 0);
    $default_layer_fweight = (isset($_REQUEST['default_layer_fweight']) ? esc_html($_REQUEST['default_layer_fweight']) : '');
    $default_layer_start = (isset($_REQUEST['default_layer_start']) ? esc_html($_REQUEST['default_layer_start']) : 0);
    $default_layer_effect_in = (isset($_REQUEST['default_layer_effect_in']) ? esc_html($_REQUEST['default_layer_effect_in']) : '');
    $default_layer_duration_eff_in = (isset($_REQUEST['default_layer_duration_eff_in']) ? esc_html($_REQUEST['default_layer_duration_eff_in']) : 0);
    $default_layer_infinite_in = (isset($_REQUEST['default_layer_infinite_in']) ? esc_html($_REQUEST['default_layer_infinite_in']) : 1);
    $default_layer_end = (isset($_REQUEST['default_layer_end']) ? esc_html($_REQUEST['default_layer_end']) : 0);
    $default_layer_effect_out = (isset($_REQUEST['default_layer_effect_out']) ? esc_html($_REQUEST['default_layer_effect_out']) : '');
    $default_layer_duration_eff_out = (isset($_REQUEST['default_layer_duration_eff_out']) ? esc_html($_REQUEST['default_layer_duration_eff_out']) : 0);
    $default_layer_infinite_out = (isset($_REQUEST['default_layer_infinite_out']) ? esc_html($_REQUEST['default_layer_infinite_out']) : 1);
    $default_layer_add_class = (isset($_REQUEST['default_layer_add_class']) ? esc_html($_REQUEST['default_layer_add_class']) : '');
    $default_layer_ffamily = (isset($_REQUEST['default_layer_ffamily']) ? esc_html($_REQUEST['default_layer_ffamily']) : '');
    $default_layer_google_fonts = (isset($_REQUEST['default_layer_google_fonts']) ? esc_html($_REQUEST['default_layer_google_fonts']) : 0);
    $spider_uploader = (isset($_REQUEST['spider_uploader']) ? esc_html($_REQUEST['spider_uploader']) : 0);
    $possib_add_ffamily = (isset($_REQUEST['possib_add_ffamily']) ? esc_html($_REQUEST['possib_add_ffamily']) : '');
    $possib_add_ffamily_google = (isset($_REQUEST['possib_add_ffamily_google']) ? esc_html($_REQUEST['possib_add_ffamily_google']) : '');
    $global_options = array(
      'default_layer_fweight'          => $default_layer_fweight,
      'default_layer_start'            => $default_layer_start,
      'default_layer_effect_in'        => $default_layer_effect_in,
      'default_layer_duration_eff_in'  => $default_layer_duration_eff_in,
      'default_layer_infinite_in'      => $default_layer_infinite_in,
      'default_layer_end'              => $default_layer_end,
      'default_layer_effect_out'       => $default_layer_effect_out,
      'default_layer_duration_eff_out' => $default_layer_duration_eff_out,
      'default_layer_infinite_out'     => $default_layer_infinite_out,
      'default_layer_add_class'        => $default_layer_add_class,
      'default_layer_ffamily'          => $default_layer_ffamily,
      'default_layer_google_fonts'     => $default_layer_google_fonts,
      'register_scripts'               => $register_scripts,
      'loading_gif'                    => $loading_gif,
      'spider_uploader'                => $spider_uploader,
      'possib_add_ffamily'             => $possib_add_ffamily,
      'possib_add_ffamily_google'      => $possib_add_ffamily_google,
    );
    $global_options = json_encode($global_options);
    update_option("wds_global_options", $global_options);
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array( 'page'    => $page,
                                                        'task'    => 'display',
                                                        'message' => 1,
                                                 ), admin_url('admin.php')));
  }


  public function change_layer_options() {
    $choose_slider_id = (isset($_REQUEST["choose_slider"]) ? esc_html($_REQUEST['choose_slider']) : '');
    $default_layer_ffamily_check = (isset($_REQUEST["default_layer_ffamily_check"]) ? esc_html($_REQUEST['default_layer_ffamily_check']) : 0);
    $default_layer_fweight_check = (isset($_REQUEST["default_layer_fweight_check"]) ? esc_html($_REQUEST['default_layer_fweight_check']) : 0);
    $default_layer_effect_in_check = (isset($_REQUEST["default_layer_effect_in_check"]) ? esc_html($_REQUEST['default_layer_effect_in_check']) : 0);
    $default_layer_effect_out_check = (isset($_REQUEST["default_layer_effect_out_check"]) ? esc_html($_REQUEST['default_layer_effect_out_check']) : 0);
    $default_layer_add_class_check = (isset($_REQUEST["default_layer_add_class_check"]) ? esc_html($_REQUEST['default_layer_add_class_check']) : 0);

    $default_array = array();
    if ($default_layer_ffamily_check) {
      $default_layer_ffamily = (isset($_REQUEST['default_layer_ffamily']) ? esc_html($_REQUEST['default_layer_ffamily']) : '');
      $default_layer_google_fonts = (isset($_REQUEST['default_layer_google_fonts']) ? esc_html($_REQUEST['default_layer_google_fonts']) : 0);
      array_push($default_array, '`ffamily`="' . $default_layer_ffamily . '"', '`google_fonts`="' . $default_layer_google_fonts . '"');
    }
    if ($default_layer_fweight_check) {
      $default_layer_fweight = (isset($_REQUEST['default_layer_fweight']) ? esc_html($_REQUEST['default_layer_fweight']) : '');
      array_push($default_array, '`fweight`="' . $default_layer_fweight . '"');
    }
    if ($default_layer_effect_in_check) {
      $default_layer_start = (isset($_REQUEST['default_layer_start']) ? esc_html($_REQUEST['default_layer_start']) : 0);
      $default_layer_effect_in = (isset($_REQUEST['default_layer_effect_in']) ? esc_html($_REQUEST['default_layer_effect_in']) : '');
      $default_layer_duration_eff_in = (isset($_REQUEST['default_layer_duration_eff_in']) ? esc_html($_REQUEST['default_layer_duration_eff_in']) : 0);
      $default_layer_infinite_in = (isset($_REQUEST['default_layer_infinite_in']) ? esc_html($_REQUEST['default_layer_infinite_in']) : 1);
      array_push($default_array, '`start`=' . $default_layer_start, '`layer_effect_in`="' . $default_layer_effect_in . '"', '`duration_eff_in`=' . $default_layer_duration_eff_in, '`infinite_in`=' . $default_layer_infinite_in);
    }
    if ($default_layer_effect_out_check) {
      $default_layer_end = (isset($_REQUEST['default_layer_end']) ? esc_html($_REQUEST['default_layer_end']) : 0);
      $default_layer_effect_out = (isset($_REQUEST['default_layer_effect_out']) ? esc_html($_REQUEST['default_layer_effect_out']) : '');
      $default_layer_duration_eff_out = (isset($_REQUEST['default_layer_duration_eff_out']) ? esc_html($_REQUEST['default_layer_duration_eff_out']) : 0);
      $default_layer_infinite_out = (isset($_REQUEST['default_layer_infinite_out']) ? esc_html($_REQUEST['default_layer_infinite_out']) : 1);
      array_push($default_array, '`end`=' . $default_layer_end, 'layer_effect_out="' . $default_layer_effect_out . '"', 'duration_eff_out=' . $default_layer_duration_eff_out, '`infinite_out`=' . $default_layer_infinite_out);
    }
    if ($default_layer_add_class_check) {
      $default_layer_add_class = (isset($_REQUEST['default_layer_add_class']) ? esc_html($_REQUEST['default_layer_add_class']) : '');
      array_push($default_array, '`add_class`="' . $default_layer_add_class . '"');
    }
    global $wpdb;
    $where = '';
    if ($choose_slider_id != '') {
      $slide_id_arr = $wpdb->get_col($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "wdsslide WHERE slider_id='%d'", $choose_slider_id));
      $where = ' WHERE slide_id IN ('. implode(',', $slide_id_arr) .')';
    }
    $set = $wpdb->query('UPDATE ' . $wpdb->prefix . 'wdslayer SET ' . implode(',', $default_array) . $where);
    $message = $wpdb->last_error ? 2 : 22;
    $page = WDW_S_Library::get('page');
    WDW_S_Library::spider_redirect(add_query_arg(array(
                                                   'page' => $page,
                                                   'task' => 'display',
                                                   'message' => $message,
                                                 ), admin_url('admin.php')));
  }

  public function get_sliders() {
    global $wpdb;
    $sliders = $wpdb->get_results("SELECT id, name FROM " . $wpdb->prefix . "wdsslider ORDER BY `name` ASC", OBJECT_K);
    if ($sliders) {
      $sliders[0] = new stdclass();
      $sliders[0]->id = '';
      $sliders[0]->name = __('All sliders', WDS()->prefix);
    }
    else {
      $sliders[0] = new stdclass();
      $sliders[0]->id = 0;
      $sliders[0]->name = __('-Select-', WDS()->prefix);
    }

    ksort($sliders);

    return $sliders;
  }
}