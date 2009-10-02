<?php
$a = Tag::createTag('a');
$a->addAttribute(new Attribute('href', 'http://www.timo-strotmann.de/', $a->getName()))
  ->addAttribute(new Attribute('shape', 'rect', $a->getName()))
  ->addAttribute(new Attribute('target', '_blank', $a->getName()))
  ->setContent('www.Timo-Strotmann.de');

  echo $a->setHtmlentities(false);
