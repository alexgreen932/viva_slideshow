<?php
/*
 * Oss Slideshow  Widget
 * Defines the widget to be used to show cards
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access and exit   

class Viva_Slideshow_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'viva_slideshow_widget',
            __('VIVA Slideshows Widget', 'viva-slideshow'),
            array(
                'description' => __('Displays OSS cards based on a specified ID', 'viva-slideshow'),
            )
        );
        add_action('widgets_init', array($this, 'register_viva_slideshow_widget'));
    }

    public function widget($args, $instance)
    {
        $id = !empty($instance['id']) ? $instance['id'] : '';

        if ($id) {
            global $post, $post_type;
            $viva_slideshow_data = get_post_meta($id, 'viva_slideshow_data', true);
            $data = json_decode($viva_slideshow_data, true);
            // Output the widget markup
            echo $args['before_widget'];
            include 'frontend.php';
            echo $args['after_widget'];
        }
    }

    public function form($instance)
    {
        $id = !empty($instance['id']) ? $instance['id'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('id')); ?>"><?php _e('Select Slideshow:', 'viva-slideshow'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('id')); ?>"
                    name="<?php echo esc_attr($this->get_field_name('id')); ?>">
                <?php
                $posts = get_posts(array(
                    'post_type' => 'viva_slideshow',
                    'numberposts' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'post_status' => 'publish',
                ));
                foreach ($posts as $post) {
                    ?>
                    <option value="<?php echo esc_attr($post->ID); ?>"<?php selected($post->ID, $id); ?>><?php echo esc_html($post->post_title); ?></option>
                    <?php
                }
                ?>
            </select>
        </p>
        <?php
    }

    //update widget
    function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['id'] = (!empty($new_instance['id'])) ? strip_tags($new_instance['id']) : '';
        return $instance;
    }

    //Register widget
    public function register_viva_slideshow_widget()
    {
        register_widget('viva_slideshow_widget');
    }


}

$viva_slideshow_widget = new Viva_Slideshow_Widget;
?>