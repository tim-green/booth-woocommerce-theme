<?php
if ( ! class_exists( 'TG_Widget_Latest_Posts' ) ) :

	class TG_Widget_Latest_Posts extends WP_Widget {

		protected $defaults = array(
			'title'     => '',
			'post_type' => 'post',
			'taxonomy'  => 'category',
			'term_id'   => '',
			'random'    => false,
			'count'     => 3,
		);

		public function __construct() {
			$widget_ops  = array( 'description' => esc_html__( 'Displays a number of the latest (or random) posts from a specific post type.', 'booth-woo' ) );
			$control_ops = array();
			parent::__construct( 'tg-latest-posts', __( 'Theme - Latest Posts', 'booth-woo' ), $widget_ops, $control_ops );
		}

		public function widget( $args, $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );

			$id            = isset( $args['id'] ) ? $args['id'] : '';
			$before_widget = $args['before_widget'];
			$after_widget  = $args['after_widget'];

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$post_type = $instance['post_type'];
			$taxonomy  = $instance['taxonomy'];
			$term_id   = $instance['term_id'];
			$random    = $instance['random'];
			$count     = $instance['count'];

			if ( 0 === $count ) {
				return;
			}

			$q_args = array(
				'post_type'           => $post_type,
				'ignore_sticky_posts' => true,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'posts_per_page'      => $count,
			);

			if ( $random ) {
				$q_args['orderby'] = 'rand';
				unset( $q_args['order'] );
			}

			$tax_args = array();

			if ( ! empty( $term_id ) ) {
				$tax_args = array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => 'term_id',
						'terms'    => intval( $term_id ),
					),
				);
			}

			if ( ! empty( $tax_args ) ) {
				$q_args['tax_query'] = $tax_args;
			}

			$q = new WP_Query( $q_args );

			echo wp_kses( $before_widget, booth_woo_get_allowed_sidebar_wrappers() );

			if ( ! empty( $title ) ) {
				echo wp_kses( $args['before_title'] . $title . $args['after_title'], booth_woo_get_allowed_sidebar_wrappers() );
			}

			while ( $q->have_posts() ) {
				$q->the_post();

				get_template_part( 'template-parts/widgets/sidebar-item' );
			}
			wp_reset_postdata();

			echo wp_kses( $after_widget, booth_woo_get_allowed_sidebar_wrappers() );

		} // widget

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']     = sanitize_text_field( $new_instance['title'] );
			$instance['post_type'] = $this->defaults['post_type'];
			$instance['taxonomy']  = $this->defaults['taxonomy'];
			$instance['term_id']   = booth_woo_sanitize_intval_or_empty( $new_instance['term_id'] );
			$instance['random']    = isset( $new_instance['random'] );
			$instance['count']     = absint( $new_instance['count'] );

			return $instance;
		} // save

		public function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, $this->defaults );

			$title    = $instance['title'];
			$taxonomy = $instance['taxonomy'];
			$term_id  = $instance['term_id'];
			$random   = $instance['random'];
			$count    = $instance['count'];
			?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'booth-woo' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat"/></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'term_id' ) ); ?>"><?php esc_html_e( 'Category to display the latest posts from (optional):', 'booth-woo' ); ?></label>
			<?php wp_dropdown_categories( array(
				'taxonomy'          => $taxonomy,
				'show_option_all'   => '',
				'show_option_none'  => ' ',
				'option_none_value' => '',
				'show_count'        => 1,
				'echo'              => 1,
				'selected'          => $term_id,
				'hierarchical'      => 1,
				'name'              => $this->get_field_name( 'term_id' ),
				'id'                => $this->get_field_id( 'term_id' ),
				'class'             => 'postform widefat',
			) ); ?>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'random' ) ); ?>"><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'random' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'random' ) ); ?>" value="1" <?php checked( $random, 1 ); ?> /><?php esc_html_e( 'Show random posts.', 'booth-woo' ); ?></label></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'booth-woo' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" step="1" value="<?php echo esc_attr( $count ); ?>" class="widefat"/></p>
			<?php

		} // e form

	}

endif;