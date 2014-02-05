<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_sharing-meta-data',
		'title' => 'Sharing Meta Data',
		'fields' => array (
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
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
?>