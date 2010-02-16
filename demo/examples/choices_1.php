<?php
$items = array(
  'choices_1_value_attr_1' => 'Item 1',
  'choices_1_value_attr_2' => 'Item 2',
  'choices_1_value_attr_3' => 'Item 3',
);

echo Tag::radio($items, 'name_attr');