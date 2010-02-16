<?php
$time = microtime();
$table = array(
  0 => array(
    0 => 'Zeile 1 => Zelle 1',
    1 => 'Zeile 1 => Zelle 2',
    2 => 'Zeile 1 => Zelle 3',
  ),
  1 => array(
    0 => array('content' => 'Zeile 2 => Zelle 1'),
    1 => array('content' => 'Zeile 2 => Zelle 2'),
    2 => array('content' => 'Zeile 2 => Zelle 3'),
  ),
);
echo Tag::table($table, array('border'=>'1'));
echo Tag::br().(microtime() - $time);