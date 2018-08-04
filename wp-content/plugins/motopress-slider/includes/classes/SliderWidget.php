<?php

class MPSLWidget extends WP_Widget
{

    function __construct() {
        global $mpsl_settings;
        parent::__construct(false, $mpsl_settings['product_name'], array('description' => sprintf(__('Add %s', MPSL_TEXTDOMAIN), $mpsl_settings['product_name'])));
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['alias']))
            echo $args['before_title'] . get_mpsl_slider($instance['alias']) . $args['after_widget'];
    }

    public function form($instance) {
        $sliders = new MPSLSlidersList();
        $list = $sliders->getSliderAliases();
        $alias = isset($instance['alias']) ? $instance['alias'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('alias'); ?>"><?php _e('Select slider:', MPSL_TEXTDOMAIN); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('alias'); ?>"
                    name="<?php echo $this->get_field_name('alias'); ?>">
                <option><?php echo '-- ' . __('SELECT', MPSL_TEXTDOMAIN) . ' --'; ?></option>
                <?php foreach ($list as $value) : ?>

                    <option <?php echo $value['alias'] === $alias ? " selected " : ""; ?>
                        value="<?php echo esc_attr($value['alias']); ?>">
                        <?php echo esc_attr($value['title']); ?>
                    </option>

                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['alias'] = (!empty($new_instance['alias'])) ? strip_tags($new_instance['alias']) : '';
        return $instance;
    }

}