<?php 
class WpTagCloud extends WP_Widget {
	function WpTagCloud() {
		// widget actual processes
		parent::WP_Widget(false, $name = 'SEO Intelligent Tag Cloud');	
	}

	function form($instance) {
		// outputs the options form on admin
	    $title = esc_attr($instance['title']);
	    $min_posts = esc_attr($instance['min_posts']);
	    ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('min_posts'); ?>"><?php _e('Numero minimo di post:'); ?></label> 
			<input class="widefat" size="2" id="<?php echo $this->get_field_id('min_posts'); ?>" name="<?php echo $this->get_field_name('min_posts'); ?>" type="text" value="<?php echo $min_posts; ?>" />
		</p>
	    <?php 
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['min_posts'] = strip_tags( $new_instance['min_posts'] );
        return $instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $min_posts = $instance['min_posts'];
        $tags = get_tags();
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                  <?php 
                  	echo "<div id=\"wptagcloud_class\">";
                  	foreach ( $tags as $tag ){
                  		if( $tag->count >= $min_posts ){
							$tag_link = get_tag_link( $tag->term_id );
							echo "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>{$tag->name}</a> ";
						}
					}
                  	echo "</div>";
                  ?>
              <?php echo $after_widget; ?>
        <?php

	}

}
?>