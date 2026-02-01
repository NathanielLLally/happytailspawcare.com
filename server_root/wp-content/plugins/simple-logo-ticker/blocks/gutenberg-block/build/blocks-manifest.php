<?php
// This file is generated. Do not modify it manually.
return array(
	'gutenberg-block' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/gutenberg-block',
		'version' => '0.1.0',
		'title' => 'Simple logo ticker',
		'category' => 'widgets',
		'description' => 'simple logo ticker',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'postId' => array(
				'type' => 'number',
				'default' => 0
			),
			'shortcodeId' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'gutenberg-block',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
