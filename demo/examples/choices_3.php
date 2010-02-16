<?php
$items = array(
  'choices_3_value_attr_1' => 'Item 1',
  'choices_3_value_attr_2' => 'Item 2',
  'choices_3_value_attr_3' => 'Item 3',
);

echo Tag::checkbox($items, 'name_attr');