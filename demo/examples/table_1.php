<?php
// 'a' => array([Block-Elemente] | [Inline-Elemente] (außer a | button) | td | body, (body nur bei  HTML Transitional)),
//$a = exclude(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS.',td', 'a,button');
//echo Tag::pre(var_export($a, 1));

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