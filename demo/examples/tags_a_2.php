<?php
$a = Tag::a('http://www.timo-strotmann.de', 'www.Timo-Strotmann.de');
echo $a->addAttribute(AttributeFactory::createAttribute('shape', 'circle', $a->getName()))
	->addAttribute(AttributeFactory::createAttribute('target', '_blank', $a->getName()))
	->addAttribute(AttributeFactory::createAttribute('style', 'color:white; text-decoration:none;', $a->getName()));
