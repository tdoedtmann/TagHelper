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


  <!-- SyntaxHighlighter -->  
  <script type="text/javascript" src="js/sh/shCore.js"></script>
  <!--
  <script type="text/javascript" src="js/sh/shBrushBash.js"></script>
  <script type="text/javascript" src="js/sh/shBrushCpp.js"></script>
  <script type="text/javascript" src="js/sh/shBrushCSharp.js"></script>
  -->
  <script type="text/javascript" src="js/sh/shBrushCss.js"></script>
  <!--
  <script type="text/javascript" src="js/sh/shBrushDelphi.js"></script>
  <script type="text/javascript" src="js/sh/shBrushDiff.js"></script>
  <script type="text/javascript" src="js/sh/shBrushGroovy.js"></script>
  <script type="text/javascript" src="js/sh/shBrushJava.js"></script>
  <script type="text/javascript" src="js/sh/shBrushJScript.js"></script>
  -->
  <script type="text/javascript" src="js/sh/shBrushPhp.js"></script>
  <!--
  <script type="text/javascript" src="js/sh/shBrushPlain.js"></script>
  <script type="text/javascript" src="js/sh/shBrushPython.js"></script>
  <script type="text/javascript" src="js/sh/shBrushRuby.js"></script>
  <script type="text/javascript" src="js/sh/shBrushScala.js"></script>
  <script type="text/javascript" src="js/sh/shBrushSql.js"></script>
  <script type="text/javascript" src="js/sh/shBrushVb.js"></script>
  -->
  <script type="text/javascript" src="js/sh/shBrushXml.js"></script>
  <link type="text/css" rel="stylesheet" href="css/sh/shCore.css" />
  <link type="text/css" rel="stylesheet" href="css/sh/shThemeDefault.css" />
  <script type="text/javascript">
  /*<![CDATA[*/
      SyntaxHighlighter.config.clipboardSwf = 'js/sh/clipboard.swf';
      SyntaxHighlighter.all();
  /*]]>*/
  </script>

</head>

<body>
  <!-- ----------------------------------------
  ---- HEADER ---------------------------------
  -----------------------------------------  -->
  <div id="bg1">
    <div id="header">
      <h1><a href="#">(X)HTML-Tag-Classes<sup>0.1</sup></a></h1>
      <h2>By <a href="http://www.timo-strotmann.de/">Timo Strotmann</a></h2>
    </div>
  </div>
  
  
  <!-- ----------------------------------------
  ---- MENU -----------------------------------
  -----------------------------------------  -->
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
  
  
  <div id="bg3">
    <div id="bg4">
      <div id="bg5">
        <div id="page">
        
          <!-- ----------------------------------------
          ---- CONTENT --------------------------------
          -----------------------------------------  -->
          <div id="content">

<?php 

/*
+----+----+----+
| A1 | A2 | A3 | 
+----+----+----+
| B1 | B2 | B3 | 
+----+----+----+
| C1 | C2 | C3 | 
+----+----+----+
| D1 | D2 | D3 | 
+----+----+----+
$table_01 = array(
  array('A1', 'A2', 'A3'),
  array('B1', 'B2', 'B3'),
  array('C1', 'C2', 'C3'),
  array('D1', 'D2', 'D3')
);


+----+----+----+----+
| A1 |         | A4 |
+----+         +----+
| B1 |   A2    | B4 |
+----+         +----+
| C1 |         | C4 |
+----+----+----+----+
| D1 |   D2    | D4 |
+----+----+----+----+
$table_02[0][0]['content'] = 'A1';
$table_02[0][1]['content'] = 'A2';
$table_02[0][1]['attr'] = array('rowspan'=>3, 'colspan'=>2);
$table_02[0][2]['content'] = 'A3';
$table_02[0][3]['content'] = 'A4';

$table_02[1][0]['content'] = 'B1';
$table_02[1][1]['content'] = 'B4';

$table_02[2][0]['content'] = 'C1';
$table_02[2][1]['content'] = 'C4';

$table_02[3][0]['content'] = 'D1';
$table_02[3][1]['content'] = 'D2';
$table_02[3][1]['attr'] = array('colspan'=>2);
$table_02[3][2]['content'] = 'D4';
*/
/*
$table_03 = array(
  0 => array(
    0 => array('content' => 'A1'),
    1 => array(
      'content' => 'A2',
      'attr'    => array('rowspan'=>3, 'colspan'=>2)
    ),
    2 => array('content' => 'A3'),
    3 => array('content' => 'A4'),
  ),
  
  1 => array(
    0 => array('content' => 'B1'),
    1 => array('content' => 'B4'),
  ),
  
  2 => array(
    0 => array('content' => 'C1'),
    1 => array('content' => 'C4'),
  ),
  
  3 => array(
    0 => array('content' => 'C1'),
    1 => array(
      'content' => 'C2',
      'attr'    => array('colspan'=>2)
    ),
    2 => array('content' => 'A4'),
  )
);
*/


?>          
          
          
            <?php include_once 'includeExampleFiles.php'?>
          </div>

          <!-- ----------------------------------------
          ---- SUBMENU --------------------------------
          -----------------------------------------  -->
          <div id="sidebar">
            <ul>
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


  <!-- ----------------------------------------
  ---- FOOTER ---------------------------------
  -----------------------------------------  -->
  <div id="footer">
    <p>&copy; 2009 <a href="http://www.timo-strotmann.de/">Timo Strotmann</a></p>
  </div>
  <!-- end #footer -->
</body>
</html>



<?php 
//var_dump(get_html_translation_table(HTML_ENTITIES));
/*

$br = Tag::createTag('br');
$hr = Tag::createTag('hr');

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
$preTag = Tag::createTag('pre');
$data = array(
  'width'=>'10',
  'style'=>'color:#333'
);
echo $preTag->addAttributes(AttributeFactory::createAttributes($preTag->getName(), $data));
*/
?>          
