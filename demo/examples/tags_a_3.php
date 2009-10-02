<?php
$attributes = array(
	'target' => '_blank',
	'style'  => 'color: green;'
);

echo Tag::createATag('http://www.timo-strotmann.de', 'www.Timo-Strotmann.de', $attributes);
