<?php
$time = microtime();
$table = array(
  'thead' => array(
    0 => array(
      0 => 'Kopfzeile 1 => Zelle 1',
      1 => 'Kopfzeile 1 => Zelle 2',
      2 => 'Kopfzeile 1 => Zelle 3',
    ),
    1 => array(
      0 => array(
        'content'    => 'Kopfzeile 2 => Zelle 1',
        'attributes' => array('colspan'=>3, 'style'=>'background-color:yellow; color:black;'),
      ),
    ),
  ),
  'tfoot' => array(
    array(
      'Fusszeile 1 => Zelle 1',
      'Fusszeile 1 => Zelle 2',
      'Fusszeile 1 => Zelle 3',
    ),
  ),
  'tbody' => array(
    array(
      'Zeile 1 => Zelle 1',
      'Zeile 1 => Zelle 2',
      'Zeile 1 => Zelle 3',
    ),
    array(
      array(
        'content'    => Tag::i('Zeile 2 => Zelle 1'),
        'attributes' => array(),
      ),
      Tag::b('Zeile 2 => Zelle 2'),
      'Zeile 2 => Zelle 3',
    ),
  ),
);

echo Tag::table($table, array('border'=>1, 'cellpadding'=>0, 'cellspacing'=>0, 'width'=>'100%'));
echo Tag::br().(microtime() - $time);