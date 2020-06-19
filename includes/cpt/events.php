<?php
/**
 * Register Events post type.
 *
 * @return void
 */
function stag_create_post_type_events() {
	$labels = array(
		'name'               => __( 'Events', 'geeklove' ),
		'singular_name'      => __( 'Events', 'geeklove' ),
		'add_new'            => __( 'Add New', 'geeklove' ),
		'add_new_item'       => __( 'Add New Events', 'geeklove' ),
		'edit_item'          => __( 'Edit Events', 'geeklove' ),
		'new_item'           => __( 'New Events', 'geeklove' ),
		'view_item'          => __( 'View Events', 'geeklove' ),
		'search_items'       => __( 'Search Events', 'geeklove' ),
		'not_found'          => __( 'No events found', 'geeklove' ),
		'not_found_in_trash' => __( 'No events found in Trash', 'geeklove' ),
		'parent_item_colon'  => '',
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => array(
			'slug'       => 'events',
			'with_front' => false,
			'feeds'      => true,
			'pages'      => true,
		),
		'show_ui'             => true,
		'query_var'           => true,
		'capability_type'     => 'page',
		'map_meta_cap'        => true,
		'menu_position'       => 33,
		'menu_icon'           => 'dashicons-calendar',
		'has_archive'         => true,
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => false,
	);

	register_post_type( 'events', $args );
}
add_action( 'init', 'stag_create_post_type_events' );

function stag_event_edit_columns( $columns ) {
	$columns = array(
		'cb'         => "<input type=\"checkbox\" />",
		'title'      => __( 'Event Title', 'geeklove' ),
		'event_date' => __( 'Event Date', 'geeklove' ),
		'time'       => __( 'Event Time', 'geeklove' ),
		'date'       => __( 'Date Added', 'geeklove' ),
	);

	return $columns;
}
add_filter( 'manage_edit-events_columns', 'stag_event_edit_columns' );

function stag_event_custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'time':
			echo get_post_meta( $post_id, '_stag_event_time', true );
			break;

		case 'event_date':
			$event_date = get_post_meta( $post_id, '_stag_event_date', true );
			if ( '' === $event_date ) return;

			$date = date_create( $event_date );
			echo esc_html( date_format( $date, get_option( 'date_format' ) ) );
			break;
	}
}
add_action( 'manage_posts_custom_column', 'stag_event_custom_columns', 10, 2 );
