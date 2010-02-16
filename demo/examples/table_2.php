<?php
$time = microtime();
$table = array(
  0 => array(
    0 => 'Zeile 1 => Zelle 1',
    1 => 'Zeile 1 => Zelle 2',
    2 => 'Zeile 1 => Zelle 3',
  ),
  1 => array(
    0 => array(
      'content'    => 'Zeile 2 => Zelle 1',
      'attributes' => array(),
    ),
    1 => array(
      'content'    => 'Zeile 2 => Zelle 2',
      'attributes' => array(),
    ),
    2 => array(
      'content'    => 'Zeile 2 => Zelle 3',
      'attributes' => array(),
    ),
  ),
  2 => array(
    0 => array(
      'content'    => 'Zeile 3 => Zelle 1',
      'attributes' => array(),
    ),
    1 => array(
      'content'    => 'Zeile 3 => Zelle 2',
      'attributes' => array('colspan'=>2, 'style'=>'background-color:yellow; color:black;'),
    ),
  )
);

echo Tag::table($table, array('border'=>1, 'cellpadding'=>0, 'cellspacing'=>0));
echo Tag::br().(microtime() - $time);