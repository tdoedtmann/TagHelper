<?php

$listItems = array(
  array(
    'dt' => 'Item 1',
    'dd' => 'Description for Item 1'
  ),
  array(
    'dt' => 'Item 2',
    'dd' => 'Description for Item 2'
  ),
  array(
    'dt' => 'Item 3',
    'dd' => 'Description for Item 3'
  ),
);
echo Tag::dl($listItems);
