<?php
add_action('add_meta_boxes', 'stag_metabox_gallery');

function stag_metabox_gallery(){
  $meta_box = array(
    'id' => 'stag_metabox_gallery',
    'title' => __('Gallery', 'geeklove-assistant'),
    'description' => __('Upload images for this gallery', 'geeklove-assistant'),
    'page' => 'page',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => __('Gallery Pics', 'geeklove-assistant'),
            'desc' => __('Choose bulk images for gallery.', 'geeklove-assistant'),
            'id' => '_stag_gallery_pics',
            'type' => 'images',
            'std' => __('Upload Images', 'geeklove-assistant'),
            'multiple' => 'true',
        ),
      )
    );

    $meta_box['page'] = 'gallery';
    stag_add_meta_box($meta_box);
}
