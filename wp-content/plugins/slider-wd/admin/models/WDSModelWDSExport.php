<?php

class WDSModelWDSExport {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function export_one() {
    global $wpdb;
    $slider_id = WDW_S_Library::get('current_id', 0);
    $sliders_to_export = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider where id="%d"', $slider_id));
    foreach ($sliders_to_export as $slider) {
      $slider->music_url = str_replace('{site_url}', site_url(), $slider->music_url);
      $slider->built_in_watermark_url = str_replace('{site_url}', site_url(), $slider->built_in_watermark_url);
      $slider->right_butt_url = str_replace('{site_url}', site_url(), $slider->right_butt_url);
      $slider->left_butt_url = str_replace('{site_url}', site_url(), $slider->left_butt_url);
      $slider->right_butt_hov_url = str_replace('{site_url}', site_url(), $slider->right_butt_hov_url);
      $slider->left_butt_hov_url = str_replace('{site_url}', site_url(), $slider->left_butt_hov_url);
      $slider->bullets_img_main_url = str_replace('{site_url}', site_url(), $slider->bullets_img_main_url);
      $slider->bullets_img_hov_url = str_replace('{site_url}', site_url(), $slider->bullets_img_hov_url);
      $slider->play_butt_url = str_replace('{site_url}', site_url(), $slider->play_butt_url);
      $slider->play_butt_hov_url = str_replace('{site_url}', site_url(), $slider->play_butt_hov_url);
      $slider->paus_butt_url = str_replace('{site_url}', site_url(), $slider->paus_butt_url);
      $slider->paus_butt_hov_url = str_replace('{site_url}', site_url(), $slider->paus_butt_hov_url);
      $slides = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslide WHERE slider_id="%d"', $slider->id));
      
      if ($slides) {
        foreach ($slides as $slide) {
          $slide->image_url = str_replace('{site_url}', site_url(), $slide->image_url);
          $slide->thumb_url = str_replace('{site_url}', site_url(), $slide->thumb_url);
          
          $slidelayers = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdslayer WHERE slide_id="%d"', $slide->id));
          foreach ($slidelayers as $layer) {
            $layer->image_url = str_replace('{site_url}', site_url(), $layer->image_url);
          }
          $slide->slidelayers = $slidelayers;
        }
      }
      $slider->slides = $slides;
    }
    return $sliders_to_export;
  }

  public function export_full() {
    global $wpdb;
    $slider_ids_string = WDW_S_Library::get('slider_ids', 0);
    $slider_ids_string = rtrim($slider_ids_string, ",");
    $slider_ids = explode(',', $slider_ids_string);
    $sliders_to_export = array();
    if ($slider_ids_string == 'all') {
      $sliders = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wdsslider');
    }
    else {
      $sliders = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE id IN (' . $slider_ids_string . ')');
    }
    foreach ($sliders as $slider) {
      array_push($sliders_to_export, $slider);
    }
    foreach ($sliders_to_export as $slider) {
      $slider->music_url = str_replace('{site_url}', site_url(), $slider->music_url);
      $slider->built_in_watermark_url = str_replace('{site_url}', site_url(), $slider->built_in_watermark_url);
      $slider->right_butt_url = str_replace('{site_url}', site_url(), $slider->right_butt_url);
      $slider->left_butt_url = str_replace('{site_url}', site_url(), $slider->left_butt_url);
      $slider->right_butt_hov_url = str_replace('{site_url}', site_url(), $slider->right_butt_hov_url);
      $slider->left_butt_hov_url = str_replace('{site_url}', site_url(), $slider->left_butt_hov_url);
      $slider->bullets_img_main_url = str_replace('{site_url}', site_url(), $slider->bullets_img_main_url);
      $slider->bullets_img_hov_url = str_replace('{site_url}', site_url(), $slider->bullets_img_hov_url);
      $slider->play_butt_url = str_replace('{site_url}', site_url(), $slider->play_butt_url);
      $slider->play_butt_hov_url = str_replace('{site_url}', site_url(), $slider->play_butt_hov_url);
      $slider->paus_butt_url = str_replace('{site_url}', site_url(), $slider->paus_butt_url);
      $slider->paus_butt_hov_url = str_replace('{site_url}', site_url(), $slider->paus_butt_hov_url);
      $slides = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslide WHERE slider_id="%d"', $slider->id));
      
      $slides = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslide WHERE slider_id="%d"', $slider->id));
      if ($slides) {
        foreach ($slides as $slide) {
          $slide->image_url = str_replace('{site_url}', site_url(), $slide->image_url);
          $slide->thumb_url = str_replace('{site_url}', site_url(), $slide->thumb_url);
          
          $slidelayers = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdslayer WHERE slide_id="%d"', $slide->id));
          foreach ($slidelayers as $layer) {
            $layer->image_url = str_replace('{site_url}', site_url(), $layer->image_url);
          }
          $slide->slidelayers = $slidelayers;
        }
      }
      $slider->slides = $slides;
    }
    return $sliders_to_export;
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}