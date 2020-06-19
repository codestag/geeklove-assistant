<?php
/**
 * Contact Form Shortcode
 *
 * Displays a contact form.
 *
 * @package Geeklove Assistant
 * @subpackage Geeklove
 */
function geeklove_contact_form_sc() {
	$nameError         = __( 'Please enter your name.', 'geeklove-assistant' );
	$emailError        = __( 'Please enter your email address.', 'geeklove-assistant' );
	$emailInvalidError = __( 'You entered an invalid email address.', 'geeklove-assistant' );
	$commentError      = __( 'Please enter a message.', 'geeklove-assistant' );

	$errorMessages = array();

	if ( isset( $_POST['submit_message'] ) ) {
		$errorMessages['nameError'] = $nameError;
		if ( trim( $_POST['contact_name'] ) === '' ) {
			$hasError = true;
		} else {
			$name = trim( $_POST['contact_name'] );
		}

		if ( trim( $_POST['contact_email'] ) === '' ) {
			$errorMessages['emailError'] = $emailError;
			$hasError                    = true;
		} elseif ( ! is_email( $_POST['contact_email'] ) ) {
			$errorMessages['emailInvalidError'] = $emailInvalidError;
			$hasError                           = true;
		} else {
			$email = trim( $_POST['contact_email'] );
		}

		if ( trim( $_POST['contact_message'] ) === '' ) {
			$errorMessages['commentError'] = $commentError;
			$hasError                      = true;
		} else {
			$comments = stripslashes( trim( $_POST['contact_message'] ) );
		}

		if ( ! isset( $hasError ) ) {
			$emailTo = geeklove_get_thememod_value( 'geeklove_contact_email' );
			if ( ! isset( $emailTo ) || ( $emailTo == '' ) ) {
				$emailTo = get_option( 'admin_email' );
			}

			$subject = '[Contact Form] From ' . $name;

			$body  = "Name: $name \n\nEmail: $email \n\nMessage: $comments \n\n";
			$body .= "--\n";
			$body .= 'This mail is sent via contact form on ' . get_bloginfo( 'name' ) . "\n";
			$body .= home_url();

			$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;

			wp_mail( $emailTo, $subject, $body, $headers );
			$emailSent = true;
		}
	}
	?>

	<section id="event" class="section-block contact">

	<div class="inner-block">

		<form class="contact-form" action="<?php the_permalink(); ?>" method="post">
			<div class="grids">
				<div class="grid-4">
					<label for="contact_name"><?php _e( 'Your Name', 'geeklove-assistant' ); ?>*</label>
					<input type="text" id="contact_name" name="contact_name" >
					<?php if ( isset( $errorMessages['nameError'] ) ) { ?>
						<span class="error"><?php echo $errorMessages['nameError']; ?></span>
					<?php } ?>
				</div>

				<div class="grid-4">
					<label for="contact_email"><?php _e( 'Your Email', 'geeklove-assistant' ); ?>*</label>
					<input type="email" id="contact_email" name="contact_email" >
					<?php if ( isset( $errorMessages['emailError'] ) ) { ?>
						<span class="error"><?php echo $errorMessages['emailError']; ?></span>
					<?php } ?>
					<?php if ( isset( $errorMessages['emailInvalidError'] ) ) { ?>
						<span class="error"><?php echo $errorMessages['emailInvalidError']; ?></span>
					<?php } ?>
				</div>
				<div class="grid-4">
					<label for="contact_phone"><?php _e( 'Your Phone', 'geeklove-assistant' ); ?></label>
					<input type="text" id="contact_phone" name="contact_phone">
				</div>
			</div>

			<div class="grids textarea-wrap">
				<div class="grid-12">
					<label for="contact_message"><?php _e( 'Your Message', 'geeklove-assistant' ); ?>*</label>
					<textarea name="contact_message" id="contact_message" cols="30" rows="10" ></textarea>
					<?php if ( isset( $errorMessages['commentError'] ) ) { ?>
						<span class="error"><?php echo $errorMessages['commentError']; ?></span>
					<?php } ?>
				</div>
			</div>

			<?php if ( isset( $emailSent ) && $emailSent == true ) : ?>
				<div class="thanks">
					<p><?php _e( 'Thanks, your message was sent successfully.', 'geeklove-assistant' ); ?></p>
				</div>
			<?php endif; ?>

			<div class="submit">
				<input type="submit" class="" value="<?php _e( 'Send Message', 'geeklove-assistant' ); ?>" name="submit_message">
			</div>
		</form>

		<!-- END .inner-block -->
	</div>

	<!-- END #event -->
	</section>
	<?php
}
add_shortcode( 'geeklove_contact_form', 'geeklove_contact_form_sc' );
