<?php
$tags = array(
	0 => array(
		'list'   => array(
			'type'       => 'ul',
			'attributes' => array('style'=>'list-style:none; color:red; margin:0; padding:0;'),
			'content'    => array('Item A', 'Item B', 'Item C'),
		),
	),
	
	1 => array('br' => ''),
	2 => array('hr' => ''),
	3 => array(
		'choice' => array(
			'type'       => 'radio',
			'name'       => 'some_choices',
			'attributes' => array(),
			'content'    => array(
				'tickets_01' => 'Single-Ticket',
				'tickets_02' => 'Group-Ticket',
				'tickets_03' => array(
					'value' => 'Family-Ticket',
					'attributes' => array(
						'checked' => true,
					),
				),
			),
		),
	),
		
	4 => array('br' => ''),
	5 => array('hr' => ''),
	6 => array(
	'input'  => array(
		'type'  => 'submit',
		'name'  => 'submit',
		'value' => 'Submit this!'
		),
	),
	
	7 => array('hr' => ''),
	8 => array(
		'p'      => array(
			'attributes' => array('class'=>'notice', 'style'=>'color:#ccc;'),
			'content' => array(
				0 => 'Etwas Text vor dem Link ',
				1 => array(
					'a' => array(
						'href'       => 'http://www.timo-strotmann.de',
						'content'    => 'Timo Strotmann',
						'attributes' => array(
							'target' => '_blank',
							'style'  => 'color: green;'
						),
					),
				),
				2 => ' und etwas Text nach dem Link.',
			),
		),
	),
);

$createdTags = Tag::createTagsByArray($tags);
echo $createdTags;