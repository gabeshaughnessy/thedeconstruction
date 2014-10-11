<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_sharing-meta-data',
		'title' => 'Sharing Meta Data',
		'fields' => array (
			array (
				'key' => 'field_52f197cc66b9f',
				'label' => 'Youtube Video ID',
				'name' => 'decon_video_id',
				'type' => 'text',
				'instructions' => 'Just the video ID of a video on Youtube, for eaxample \'_SA_kvN3pVk\'. It\'s the part after watch?v= in the URL for the video on Youtube.',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => 20,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
?>