<?php
$items = array(
  'choices_4_value_attr_1' => 'Item 1',
  'choices_4_value_attr_2' => 'Item 2',
  'choices_4_value_attr_3' => 'Item 3',
  'choices_4_value_attr_4' => array(
    'value' => 'Item 4',
    'attributes' => array(
      'checked' => TRUE,
      'style'   => 'font-size: 5em;',
    )
  ),
  'choices_4_value_attr_5' => array(
    'value' => 'Item 5',
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
foreach(Tag::checkbox($items, 'checkbox_items', array(), FALSE) as $item) {
  echo $item.Tag::br();
}