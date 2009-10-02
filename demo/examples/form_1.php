<?php 
$br = Tag::createTag('br');
$hr = Tag::createTag('hr');

$items_test = array(
  'tickets_01' => 'Single-Ticket',
  'tickets_02' => 'Group-Ticket',
  'tickets_03' => array(
    'value' => 'Family-Ticket',
    'attributes' => array(
      'checked' => true,
      'onclick' => array(
        'value'   => "alert('Hallo Du Da! Du hast mich angeklickt.');", 
        'options' => array(
          'addSlashes' => false
        )
      )
    )
  )
);

foreach(Tag::createChoiceTag($items_test, 'tickets', 'checkbox') as $item) {
  $itemContent.=$item;
}
$itemContent.= Tag::createInputTag('submit', 'submit', 'Submit');

echo $formTag = Tag::createFormTag($_SERVER['PHP_SELF'], $itemContent);
echo $br.$hr.$br;
echo $h2 = Tag::createTag('h2')->setContent('POST');
echo $preTag = Tag::createTag('pre')->setContent(var_export($_POST,1));
