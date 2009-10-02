<?php 
require_once '../lib/Tag.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : WidgetLike
Description: A two-column, fixed-width design featuring black content area background.
Version    : 1.0
Released   : 20081016

-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>XHTML-Tag-Classes</title>

	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />	
	<link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />	
	<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />	
	
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery_ui-core.js"></script>
	<script type="text/javascript" src="js/jquery_ui-tabs.js"></script>
	<script type="text/javascript" src="js/toggle.js"></script>
	<script type="text/javascript" src="js/tabs.js"></script>
</head>

<body>
	<!-- ----------------------------------------
	---- HEADER ---------------------------------
	-----------------------------------------	-->
	<div id="bg1">
		<div id="header">
			<h1><a href="#">(X)HTML-Tag-Classes<sup>1.0</sup></a></h1>
			<h2>By <a href="http://www.timo-strotmann.de/">Timo Strotmann</a></h2>
		</div>
	</div>
	
	
	<!-- ----------------------------------------
	---- MENU -----------------------------------
	-----------------------------------------	-->
	<div id="bg2">
		<div id="header2">
			<div id="menu">
				<ul>
					<li><a href="#">Class: Attritbute</a></li>
					<li><a href="#">Class: Tag</a></li>
					<li><a href="#">Examples</a></li>
				</ul>
			</div>
			
			<div id="search">
				<form method="get" action="#">
					<fieldset>
					<input type="text" name="q" value="search keywords" id="q" class="text" />
					<input type="submit" value="Search" class="button" />
					</fieldset>
				</form>
			</div>
		</div>
	</div>
	
	
	
<!-- end #bg2 -->
<div id="bg3">
	<div id="bg4">
		<div id="bg5">
			<div id="page">
				<div id="content">
					<?php include_once 'includeExampleFiles.php'?>
				</div>
				<!-- end #content -->
				<div id="sidebar">
					<ul>
						<li>
							<h2>Tempus aliquam</h2>
							<p>Sed vel quam. Vestibulum pellentesque. Morbi sit amet magna ac lacus dapibus interdum. Donec pede nisl, gravida iaculis, auctor vitae, bibendum sit amet aliquam. <a href="#">Read more&hellip;</a></p>
						</li>
						<li>
							<h2>Examples</h2>
							<?php include_once 'includeExampleMenu.php'?>
						</li>
					</ul>
				</div>
				<!-- end #sidebar -->
				<div style="clear: both; height: 40px;">&nbsp;</div>
			</div>
			<!-- end #page -->
		</div>
	</div>
</div>

<!-- end #bg3 -->
<div id="footer">
	<p>&copy; 2009 <a href="http://www.timo-strotmann.de/">Timo Strotmann</a></p>
</div>
<!-- end #footer -->
</body>
</html>








<?php 
/*****************************************************************************************************
 *****************************************************************************************************
 ************************************   T E S T A U S G A B E N   ************************************
 *****************************************************************************************************
 *****************************************************************************************************/

//var_dump(get_html_translation_table(HTML_ENTITIES));

$br = Tag::createTag('br');
$hr = Tag::createTag('hr');


/*
$for = 'diesunddas';
$content =' Nase';
$label = Tag::createLabelTag($for, $content);
$input = Tag::createInputTag('checkbox', 'Name', 'nase', array('id'=>$for, 'class'=>'nix'));
echo $br.$hr.'Checkbox'.$br;
echo $label . $input;
echo $hr;


$f = Tag::createLabeledInputTag('Super-Label', 'checkbox', 'createLabeledInputTag', 'createLabeledInputTag-Value', $inputAttributes=array(), $labelAttributes=array());
echo $f;
echo $hr;
echo $preTag->setContent($f);
echo $hr;
*/


/*
$a = Tag::createTag('a');
$a->addAttribute(new Attribute('href', 'http://www.saltation.de', $a->getName()))
  ->addAttribute(new Attribute('shape', 'rect', $a->getName()))
  ->addAttribute(new Attribute('target', '_blank', $a->getName()))
  ->setContent($hr.'Blähbauch'.$hr);
echo $br.$a;
var_dump($a->display());

$a->setHtmlentities(false);
echo $br.$a;
var_dump($a->display());


$preTag = Tag::createTag('pre');
$data = array(
	array('name'=>'width', 'value'=>'10'),
	array('name'=>'style', 'value'=>'color:#333')
);
$preTag->addAttributes(AttributeFactory::createAttributes($preTag->getName(), $data));




$preTag->setContent("=======================\n= Ein anderes <a>-Tag =\n=======================\n");
echo $br.$br.$preTag.$br.$br;



$aTag = Tag::createTag('a');
$aTag->setContent('Me, Myself & I')
     ->addAttribute(AttributeFactory::createAttribute('href', 'http://www.timo-strotmann.de', $aTag->getName()))
     ->addAttribute(AttributeFactory::createAttribute('shape', 'circle', $aTag->getName()))
     ->addAttribute(AttributeFactory::createAttribute('target', '_blank', $aTag->getName()))
     ->addAttribute(AttributeFactory::createAttribute('style', 'color:black; text-decoration:none;', $aTag->getName()));
echo $aTag;
var_dump($aTag->display());

$aTag->setHtmlentities(false);
echo $br.$aTag;
var_dump($aTag->display());

*/

?>					
