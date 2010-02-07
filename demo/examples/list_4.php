<?php

$listItems = array(
  array(
    'dt' => array(
      'content' => 'Item 1',
      'attributes' => array('style'=>'font-weight:bold;'),
    ),
    'dd' => array(
      'content'    => 'Description for Item 1',
      'attributes' => array('class'=>'firstItem', 'style'=>'padding-left: 2em;'),
    ),
  ),
  array(
    'dt' => array(
      'content' => 'Item 2',
      'attributes' => array('style'=>'font-weight:bold;'),
    ),
    'dd' => array(
      'content'    => 'Description for Item 2',
      'attributes' => array('class'=>'secondItem', 'style'=>'padding-left: 2em;'),
    ),
  ),
  array(
    'dt' => array(
      'content' => 'Item 3',
      'attributes' => array('style'=>'font-weight:bold;'),
    ),
    'dd' => array(
      'content'    => 'Description for Item 3',
      'attributes' => array('class'=>'thirdItem', 'style'=>'padding-left: 2em;'),
    ),
  ),
);
echo Tag::dl($listItems, array('style' => 'font-size:1.4em; font-family:Verdana,Arial,sans-serif'));