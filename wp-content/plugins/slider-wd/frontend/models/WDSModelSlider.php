<?php
class WDSModelSlider {

  public function get_slide_rows_data($id, $order_dir = 'asc') {
    global $wpdb;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'wdsslide WHERE published=1 AND slider_id="'. $id .'" AND image_url<>"" AND image_url NOT LIKE "%images/no-image.png%" ORDER BY `order` ' . esc_sql($order_dir));
    foreach ($rows as $row) {
      $title_dimension = json_decode($row->title);
      if ($title_dimension) {
        $row->att_width = $title_dimension->att_width;
        $row->att_height = $title_dimension->att_height;
        $row->title = $title_dimension->title;
      }
      else {
        $row->att_width = 0;
        $row->att_height = 0;
      }
      $row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
      $row->thumb_url = str_replace('{site_url}', site_url(), $row->thumb_url);
    }
    return $rows;
  }

  public function get_slider_row_data($id) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wdsslider WHERE id="%d"', $id));
    if ($row) {
      if ($row->music_url != '' && file_exists(str_replace('{site_url}', ABSPATH, $row->music_url))) {
        $row->music_url = str_replace('{site_url}', site_url(), $row->music_url);
      }
      else {
        $row->music_url = '';
      }
      $row->right_butt_url = str_replace('{site_url}', site_url(), $row->right_butt_url);
      $row->left_butt_url = str_replace('{site_url}', site_url(), $row->left_butt_url);
      $row->right_butt_hov_url = str_replace('{site_url}', site_url(), $row->right_butt_hov_url);
      $row->left_butt_hov_url = str_replace('{site_url}', site_url(), $row->left_butt_hov_url);
      $row->bullets_img_main_url = str_replace('{site_url}', site_url(), $row->bullets_img_main_url);
      $row->bullets_img_hov_url = str_replace('{site_url}', site_url(), $row->bullets_img_hov_url);
      $row->play_butt_url = str_replace('{site_url}', site_url(), $row->play_butt_url);
      $row->play_butt_hov_url = str_replace('{site_url}', site_url(), $row->play_butt_hov_url);
      $row->paus_butt_url = str_replace('{site_url}', site_url(), $row->paus_butt_url);
      $row->paus_butt_hov_url = str_replace('{site_url}', site_url(), $row->paus_butt_hov_url);
    }
    return $row;
  }

  public function get_layers_row_data($slide_id, $id) {
    global $wpdb;
	$sql_query = "SELECT layer.* FROM " . $wpdb->prefix . "wdslayer as layer INNER JOIN " . $wpdb->prefix . "wdsslide as slide on layer.slide_id=slide.id INNER JOIN " . $wpdb->prefix . "wdsslider as slider on slider.id=slide.slider_id WHERE layer.slide_id = %d OR (slider.id=%d AND layer.static_layer=1) ORDER BY layer.`depth` ASC";
    $rows = $wpdb->get_results($wpdb->prepare($sql_query, $slide_id, $id));
    foreach ($rows as $row) {
      $title_dimension = json_decode($row->title);
      if ($title_dimension) {
        $row->attr_width = $title_dimension->attr_width;
        $row->attr_height = $title_dimension->attr_height;
        $row->title = $title_dimension->title;
      }
      else {
        $row->attr_width = 0;
        $row->attr_height = 0;
      }
      $row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
    }
    return $rows;
  }

  public function get_layers_by_slider_id_slide_ids($slider_id, $slide_ids) {
    global $wpdb;
	$sql_query = 'SELECT
						`layer`.*
					FROM
						`'. $wpdb->prefix .'wdslayer` AS `layer`
					INNER JOIN `'. $wpdb->prefix .'wdsslide` AS `slide` ON `layer`.`slide_id` = `slide`.`id`
					INNER JOIN `'. $wpdb->prefix .'wdsslider` AS `slider` ON `slider`.`id` = `slide`.`slider_id`
					WHERE
						`layer`.`slide_id` IN ('. implode( $slide_ids, ',' ) .')
					OR (
						`slider`.`id` = '. $slider_id .' AND
						`layer`.`static_layer` = 1
					)
					ORDER BY
						`layer`.`depth` ASC
					';
	$rows = $wpdb->get_results($sql_query);
	$layers = array();
	if ( !empty($rows) ) {
		foreach ($rows as $row) {
			$row->attr_width = 0;
			$row->attr_height = 0;
			$title_dimension = json_decode($row->title);
			if ($title_dimension) {
				$row->title = $title_dimension->title;
			}

			$row->image_url = str_replace('{site_url}', site_url(), $row->image_url);
			$layers[$row->slide_id][] = $row;
		}
	}
    return $layers;
  }
}