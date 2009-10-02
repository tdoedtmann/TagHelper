<?php
$a = Tag::createTag('a');
$a->setContent('www.Timo-Strotmann.de')
	->addAttribute(AttributeFactory::createAttribute('href', 'http://www.timo-strotmann.de', $a->getName()))
	->addAttribute(AttributeFactory::createAttribute('shape', 'circle', $a->getName()))
	->addAttribute(AttributeFactory::createAttribute('target', '_blank', $a->getName()))
	->addAttribute(AttributeFactory::createAttribute('style', 'color:white; text-decoration:none;', $a->getName()));

echo $a;
