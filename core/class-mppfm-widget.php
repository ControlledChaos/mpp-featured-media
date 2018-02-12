<?php

// exit if file access directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MPPFM_Featured_Media_List_widget extends WP_Widget {


	public function __construct( $name = '', $widget_options = array() ) {

		if ( empty( $name ) ) {
			$name = __( '( MediaPress ) Featured Media', 'mpp-featured-media' );
		}

		parent::__construct( false, $name, $widget_options );
	}

	public function widget( $args, $instance ) {

		$component = $instance['component'];
        $component_id = '';

	    if ( 'groups' == $component ) {
	        $component_id = bp_get_current_group_id();
        } elseif( 'members' == $component ) {
            $component_id = ( $instance['user_type'] == 'displayed' ) ? bp_displayed_user_id() : bp_loggedin_user_id();
        }

        if ( empty( $component_id ) ) {
	        return;
        }

		$media_args = array(
			'component'    => $instance['component'],
			'component_id' => $component_id,
			'status'       => $instance['status'],
			'type'         => $instance['type'],
			'order'        => 'DESC',//order
			'orderby'      => 'date',
			'meta_key'     => '_mppfm_featured',
			'meta_value'   => 1,
			'post_status'  => 'inherit',
		);

		$query = new MPP_Media_Query( $media_args );

		echo $args['before_widget'];

		echo $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'];

		?>
		<div class="mpp-container mpp-widget-container mpp-media-widget-container mpp-media-video-widget-container">

			<?php if ( $query->have_media() ) : ?>

				<?php while ( $query->have_media() ): $query->the_media(); ?>

					<div class='mpp-g mpp-item-list mpp-media-list mpp-<?php echo $instance["type"] ?>-list'>

						<div
							class="<?php mpp_media_class( 'mpp-widget-item mpp-widget-' . $instance["type"] . '-item ' . mpp_get_grid_column_class( 1 ) ); ?>">

							<div class='mpp-item-entry mpp-media-entry mpp-photo-entry'>

								<a href="<?php mpp_media_permalink(); ?>" <?php mpp_media_html_attributes( array(
									'class'            => "mpp-item-thumbnail mpp-media-thumbnail mpp-photo-thumbnail",
									'data-mpp-context' => 'widget'
								) ); ?>>
									<img src="<?php mpp_media_src( 'thumbnail' ); ?>"
									     alt="<?php echo esc_attr( mpp_get_media_title() ); ?> "/>
								</a>

							</div>

							<a href="<?php mpp_media_permalink(); ?>" <?php mpp_media_html_attributes( array(
								'class'            => "mpp-item-title mpp-media-title mpp-photo-title",
								'data-mpp-context' => 'widget'
							) ); ?> >
								<?php mpp_media_title(); ?>
							</a>

						</div>

					</div>

				<?php endwhile; ?>

				<?php mpp_reset_media_data(); ?>

			<?php else: ?>

				<?php _e( 'Nothing to show', 'mpp-media-rating' ); ?>

			<?php endif; ?>

		</div>

		<?php echo $args['after_widget']; ?>

		<?php
	}


	public function update( $new_instance, $old_instance ) {

		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['component']   = $new_instance['component'];
		$instance['status']      = $new_instance['status'];
		$instance['type']        = $new_instance['type'];
		$instance['max_to_list'] = $new_instance['max_to_list'];
		$instance['order_by']    = $new_instance['order_by'];
		$instance['order']       = $new_instance['order'];
		$instance['user_type']   = $new_instance['user_type'];

		return $instance;
	}

	public function form( $instance ) {

		$defaults = array(
			'title'       => __( 'Featured', 'mpp-featured-media' ),
			'component'   => 'members',
			'status'      => 'public',
			'type'        => 'photo',
			'max_to_list' => 5,
			'user_type'   => 'displayed',
			'order_by'    => 'title',
			'order'       => 'ASC',
		);

		$instance    = wp_parse_args( (array) $instance, $defaults );
		$title       = strip_tags( $instance['title'] );
		$component   = $instance['component'];
		$status      = $instance['status'];
		$type        = $instance['type'];
		$max_to_list = strip_tags( $instance['max_to_list'] );
		$user_type   = $instance['user_type'];
		$order_by    = $instance['order_by'];
		$order       = $instance['order'];

		?>

		<p>
			<label>
				<?php _e( 'Title:', 'mpp-featured-media' ); ?>
				<input class="mppfm-input" id="<?php echo $this->get_field_id( 'title' ); ?>"
				       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>"/>
			</label>
		</p>

		<p>
			<?php echo __( 'List media of user: ', 'mpp-featured-media' ) ?>

			<?php foreach ( mppfm_show_media_of() as $key => $label ): ?>

				<label>
					<input name="<?php echo $this->get_field_name( 'user_type' ); ?>" type="radio"
					       value="<?php echo $key; ?>" <?php checked( $key, $user_type ); ?>/>
					<?php echo $label; ?>
				</label>

			<?php endforeach; ?>
		</p>


		<p>
			<?php

            $components = mppfm_get_components();

            _e( 'Select Component: ', 'mpp-featured-media' );

            ?>

            <?php if ( ! empty( $components ) ): ?>

                <select name="<?php echo $this->get_field_name( 'component' ); ?>">
                    <?php foreach ( $components as $key => $label ) : ?>
                        <option value="<?php echo $key ?>" <?php selected( $component, $key ) ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>

            <?php else: ?>

                <?php _e( 'No Active component', 'mpp-featured-media' ); ?>

            <?php endif; ?>

		</p>

		<p>

			<?php

            $types = mppfm_get_types();

            _e( 'Select Type: ', 'mpp-featured-media' );

            ?>

			<?php if ( ! empty( $types ) ): ?>

				<select name="<?php echo $this->get_field_name( 'type' ); ?>">

					<?php foreach ( $types as $key => $label ) : ?>

						<option value="<?php echo $key ?>" <?php selected( $type, $key ) ?>>
							<?php echo $label; ?>
						</option>

					<?php endforeach; ?>

				</select>

			<?php else: ?>

				<?php _e( 'No Active Media Type!', 'mpp-featured-media' ); ?>

			<?php endif; ?>

		</p>

		<p>

			<?php

            $active_status = mpp_get_active_statuses();

            _e( 'Select Status: ', 'mpp-featured-media' );

            ?>

			<?php if ( ! empty( $active_status ) ): ?>

				<select name="<?php echo $this->get_field_name( 'status' ); ?>">

					<?php foreach ( $active_status as $key => $label ) : ?>

						<option value="<?php echo $key ?>" <?php selected( $status, $key ) ?>>
							<?php _e( $label->label, 'mpp-featured-media' ); ?>
						</option>

					<?php endforeach; ?>

				</select>

			<?php endif; ?>

		</p>

		<p>

			<label>
				<?php echo __( 'Max media to show', 'mpp-featured-media' ) ?>
				<input type="number" name="<?php echo $this->get_field_name( 'max_to_list' ); ?>"
				       value="<?php echo $max_to_list; ?>"/>
			</label>

		</p>

		<p>

			<label>
				<?php _e( 'Sort Order', 'mpp-featured-media' ); ?>
				<select id="<?php echo $this->get_field_id( 'order' ); ?>"
				        name="<?php echo $this->get_field_name( 'order' ); ?>">
					<option
						value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'mpp-featured-media' ); ?></option>
					<option
						value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'mpp-featured-media' ); ?></option>
				</select>
			</label>

		</p>

		<p>

			<label>
				<?php _e( 'Order By:', 'mpp-featured-media' ); ?>
				<select id="<?php echo $this->get_field_id( 'order_by' ); ?>"
				        name="<?php echo $this->get_field_name( 'order_by' ); ?>">
					<option
						value="title" <?php selected( 'title', $order_by ); ?>><?php _e( 'Alphabet', 'mpp-featured-media' ); ?></option>
					<option
						value="date" <?php selected( 'date', $order_by ); ?>><?php _e( 'Date', 'mpp-featured-media' ); ?></option>
					<option
						value="rand" <?php selected( 'rand', $order_by ); ?>><?php _e( 'Random', 'mpp-featured-media' ); ?></option>
				</select>
			</label>

		</p>

		<?php
	}
}

function mppfm_register_featured_list_media_widgets() {
	register_widget( 'MPPFM_Featured_Media_List_widget' );
}
add_action( 'mpp_widgets_init', 'mppfm_register_featured_list_media_widgets' );