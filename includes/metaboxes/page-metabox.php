<?php
add_action( 'add_meta_boxes', 'stag_metabox_page' );

function stag_metabox_page(){
	$meta_box = array(
		'id'          => 'stag_metabox_page',
		'title'       => __('Page Settings', 'geeklove'),
		'description' => __('Add a subtitle and page cover.', 'geeklove'),
		'page'        => 'post',
		'context'     => 'normal',
		'priority'    => 'high',
		'fields'      => array(
			array(
				'name' => __('Page Subtitle', 'geeklove'),
				'desc' => __('Enter the sub title for the page.', 'geeklove'),
				'id'   => '_stag_page_subtitle',
				'type' => 'text',
				'std'  => ''
			),
			array(
				'name' => __('Page Cover', 'geeklove'),
				'desc' => __('Choose a background cover for this page, ideal size 1260px x 260px.', 'geeklove'),
				'id'   => '_stag_page_cover',
				'type' => 'file',
				'std'  => ''
			),
		)
	);

    $meta_box['page'] = 'page';
	stag_add_meta_box($meta_box);

	$meta_box['page'] = 'gallery';
	$meta_box['description'] = __('Add a subtitle and cover for Gallery single page.', 'geeklove');
	$meta_box['fields'][0]['name'] = __('Gallery Subtitle', 'geeklove');
	$meta_box['fields'][1]['name'] = __('Gallery Cover', 'geeklove');
	stag_add_meta_box($meta_box);
}
