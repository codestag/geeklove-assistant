<?php
/**
 * Wedding RSVP Widget.
 */
class Stag_Wedding_Rsvp extends Stag_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_id          = 'stag_wedding_rsvp';
		$this->widget_cssclass    = 'wedding-rsvp';
		$this->widget_description = __( 'Display RSVP form.', 'geeklove-assistant' );
		$this->widget_name        = __( 'Section: RSVP Form', 'geeklove-assistant' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __( 'Are you attending? RSVP here!', 'geeklove-assistant' ),
				'label' => __( 'Title:', 'geeklove-assistant' ),
			),
			'subtitle' => array(
				'type'  => 'text',
				'std'   => __( 'Please select the options below and click the button in order to RSVP!', 'geeklove-assistant' ),
				'label' => __( 'Sub Title:', 'geeklove-assistant' ),
			),
			'guests' => array(
				'type'  => 'number',
				'label' => __( 'Max number of Guests:', 'geeklove-assistant' ),
				'std'   => '5',
				'min'   => '1',
				'max'   => '20',
				'step'  => '1',
			),
			'rsvp_label' => array(
				'type'  => 'text',
				'std'   => __( 'I Am Attending', 'geeklove-assistant' ),
				'label' => __( 'RSVP Button Label:', 'geeklove-assistant' ),
			),
			'attending_message' => array(
				'type'  => 'text',
				'std'   => __( 'Thanks for attending, we will see you at our wedding.', 'geeklove-assistant' ),
				'label' => __( 'Attending Message:', 'geeklove-assistant' ),
			),
			'not_attending_message' => array(
				'type'  => 'text',
				'std'   => __( 'Sad to hear that you won’t make it to the wedding.  But thanks for letting us know!', 'geeklove-assistant' ),
				'label' => __( 'Not Attending Message:', 'geeklove-assistant' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$title         = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$subtitle      = $instance['subtitle'];
		$guests        = $instance['guests'];
		$rsvp_label    = $instance['rsvp_label'];
		$attending     = @$instance['attending_message'];
		$not_attending = @$instance['not_attending_message'];

		echo $before_widget;

		?>

		<!-- BEGIN #rsvp -->
		<section id="rsvp" class="section-block">

			<div class="inner-block">
				<h2 class="section-title"><?php echo $title; ?></h2>
				<?php if($subtitle != '') echo "<h4 class='sub-title'>$subtitle</h4>"; ?>

				<form class="grids" id="rsvp-form" method="post" action="<?php the_permalink(); ?>">
					<div class="grid-4">
						<label for="attendee_name"><?php _e('Your Name', 'geeklove-assistant'); ?></label>
						<input type="text" id="attendee_name" name="attendee_name" required>
					</div>

					<div class="grid-4">
						<label for="attendee_count"><?php _e('Number of Guests', 'geeklove-assistant') ?></label>
						<select id="attendee_count" name="attendee_number" class="custom-dropdown">
							<?php
							$max_guests = (int) ( ( isset( $guests ) && ! empty( $guests ) ) ? $guests : 5 );

							for ($i = 1; $i < $max_guests + 1 ; $i++) {
								echo "<option value='$i'>$i</option>";
							}

							?>
						</select>
					</div>

					<?php $all_events = stag_all_wedding_events(); ?>

					<?php if ( ! empty( $all_events ) ) : ?>
					<div class="grid-4">
						<label for="attendee_event"><?php _e( 'You will attend&hellip;', 'geeklove-assistant' ) ?></label>
						<select id="attendee_event" name="attendee_event" class="custom-dropdown">

							<?php if ( count($all_events) > 1 ) : ?>
							<option value="all-events"><?php _e( 'All Events', 'geeklove-assistant' ); ?></option>
							<?php endif; ?>

							<?php foreach ( $all_events as $title ) : ?>
							<option value="<?php echo esc_attr( stag_to_slug( $title ) ); ?>"><?php echo esc_html($title); ?></option>
							<?php endforeach; ?>

							<option value="no-events"><?php _e( 'Not attending', 'geeklove-assistant' ); ?></option>

						</select>
					</div>
					<?php endif; ?>

					<div class="submit grid-12">
						<?php $label = ( isset( $rsvp_label ) && !empty($rsvp_label) ) ? $rsvp_label : __( 'I Am Attending', 'geeklove-assistant' ); ?>
						<input type="submit" id="submit_rsvp" value="<?php echo esc_attr( $label ); ?>" name="submit_rsvp"
								data-attending="<?php echo esc_attr( $attending ); ?>"
								data-not-attending="<?php echo esc_attr( $not_attending ); ?>" />
						<input type="hidden" name="action" value="post" />
						<?php wp_nonce_field( 'new-post' ); ?>
					</div>

				</form>

				<!-- END .inner-block -->

			</div>

			<!-- END #rsvp -->
		</section>

		<?php

		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	/**
	 * Registers the widget with the WordPress Widget API.
	 *
	 * @return void.
	 */
	public static function register() {
		register_widget( __CLASS__ );
	}
}

add_action( 'widgets_init', array( 'Stag_Wedding_Rsvp', 'register' ) );

