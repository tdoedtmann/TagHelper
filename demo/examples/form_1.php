<?php 
$br = Tag::br();

$items_test = array(
  'tickets_01' => 'Single-Ticket',
  'tickets_02' => 'Group-Ticket',
  'tickets_03' => array(
    'value' => 'Family-Ticket',
    'attributes' => array(
      'checked' => TRUE,
      'onclick' => array(
        'value'   => "alert('Hallo Du Da! Du hast mich angeklickt.');", 
        'options' => array(
          'addSlashes' => FALSE
        )
      )
    )
  )
);
Tag::setPrefixId(basename(__FILE__, '.php'));
$itemContent = '';
foreach(Tag::choices($items_test, 'tickets', 'checkbox', array(), FALSE) as $item) {
  $itemContent.= $item . $br;
}
$itemContent.= Tag::input('submit', 'submit', 'Submit');

echo Tag::form($_SERVER['PHP_SELF'], $itemContent);