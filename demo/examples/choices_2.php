<?php
$items = array(
  'choices_2_value_attr_1' => 'Item 1',
  'choices_2_value_attr_2' => 'Item 2',
  'choices_2_value_attr_3' => 'Item 3',
);
foreach(Tag::radio($items, 'radio_items', array(), false) as $item) {
  echo Tag::p($item);
}