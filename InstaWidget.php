<?php
/**
 * Plugin Name: InstaWidget
 * Description: A simple Widget to display your latest Instagram image. 
 * Version: 1.0
 * Author: Robert Thompson
 * Author URI: http://robgt.us
 */

/**
 * Add function to widgets_init that'll load our widget.
 */

require_once 'class.insta.php';

add_action( 'widgets_init', 'InstaWidget_load_widgets' );

function InstaWidget_load_widgets() {
	register_widget( 'InstaWidget_widget' );
}

 
class InstaWidget_widget extends WP_Widget {
/**
	 * Widget setup.
	 */
	function InstaWidget_widget() {
		/* Widget settings. */
		$widget_options = array( 
		 'classname' => 'simple_instagram_widget', 
		 'description' => __('A Simple widget that displays your most recent instagram image.') );

		/* Widget control settings. */
		$control_options = array( 
		'width' => 300, 
		'height' => 350, 
		'id_base' => 'simple_instagram_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'simple_instagram_widget', 'InstaWidget', $widget_options, $control_options );
	
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
                $instagram = new Instagram(array(
                'apiKey'      => $instance['key']));

 
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
                if ( $title )
			echo $before_title . $title . $after_title;
                echo " \n";
	 
                $imageinsta = $instagram->getPopularMedia();
                //$imageinsta = $instagram->getUserMedia($instance['user'],1);
	        //var_dump($imageinsta);
                //foreach($imageinsta->data as $d)
                //{
                foreach ($imageinsta->data as $data) { 
                $link = $data->link; 
                $thumbnail = $data->images->thumbnail->url; 
                }

                //$markup='<div">'. 
		//	'<a href="'.$d->link . '"><img src="'.$d->images->low_resolution->url . '"></a></div>';
                //exit;
                //}
                $markup='<div">'. 
			'<a href="'.$link . '"><img src="'.$thumbnail . '"></a></div>';  

                 
		echo nl2br($markup);
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}
 

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
       		$instance['key'] = strip_tags( $new_instance['key'] );
 		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
		'title' => __('Instagram', 'Instagram'), 
		'city' => __('New York', 'New York')
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
                
                <!-- Title Input -->
                <p>
		<label for="<?php echo $this->get_field_id( 'Title' ); ?>"><?php _e('Title:', 'title'); ?></label>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<!-- Key Input -->
		<p>
		<label for="<?php echo $this->get_field_id( 'key' ); ?>"><?php _e('Key:', 'key'); ?></label>
		<input id="<?php echo $this->get_field_id( 'key' ); ?>" name="<?php echo $this->get_field_name( 'key' ); ?>" value="<?php echo $instance['key']; ?>" style="width:100%;" />
		</p>
	<?php
	}
}
?>