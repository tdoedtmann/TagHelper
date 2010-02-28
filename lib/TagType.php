<?php
require_once 'Attribute.php';

define('HTML_VARIANTS',									'strict, frameset, transitional');
define('HTML_VARIANT',									'transitional');

define('XHTML_TAGS',										'a, abbr, acronym, address, applet, area, b, base, basefont, bdo, big, blockquote, body, br, button, caption, center, cite, code, col, colgroup, dd, del, dfn, dir, div, dl, dt, em, fieldset, font, form, frame, frameset, h1, h2, h3, h4, h5, h6, head, hr, html, i, iframe, img, input, ins, isindex, kbd, label, legend, li, link, map, menu, meta, noframes, noscript, object, ol, optgroup, option, p, param, pre, q, s, samp, script, select, small, span, strike, strong, style, sub, sup, table, tbody, td, textarea, tfoot, th, thead, title, tr, tt, u, ul, var');

define('STRICT_BLOCK_ELEMENTS',					'address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul');
define('STRICT_INLINE_ELEMENTS',				'a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var');

define('FRAMESET_BLOCK_ELEMENTS',				'address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul');
define('FRAMESET_INLINE_ELEMENTS',			'a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var');

define('TRANSITIONAL_BLOCK_ELEMENTS',		'address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul');
define('TRANSITIONAL_INLINE_ELEMENTS',	'a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var');

define('STANDALONE_TAGS', 							'area, base, basefont, br, col, frame, hr, img, input, isindex, link, meta, param');

define('ENCODING',											'UTF-8');


function trimExplode($string, $delim=',', $onlyNonEmptyValues=true)    {
  $temp = explode($delim,$string);
  $newtemp = array();
  while (list($key, $val) = each($temp))      {
    if (!$onlyNonEmptyValues || strcmp('', trim($val)))      {
      $newtemp[] = trim($val);
    }
  }
  reset($newtemp);
  return $newtemp;
} 

function viewArray($array)  {
  if (!is_array($array)) {
    return false;
  }

  if (!count($array)) {
    $tableContent.= Tag::createTag('tr')->setContent(
    Tag::createTag('td')->setContent(
    Tag::createTag('strong')->setContent(htmlspecialchars("EMPTY!"))));

  } else {
    while (list($key, $val) = each($array)) {
      $td1 = Tag::createTag('td', array('valign'=>'top'))->setContent(htmlspecialchars((string)$key));

      $tdValue = (is_array($array[$key])) ? viewArray($array[$key]) : Tag::createTag('span', array('style'=>'color:red;'))->setContent(nl2br(htmlspecialchars((string)$val)) . Tag::createTag('br'));
      $td2 = Tag::createTag('td', array('valign'=>'top'))->setContent($tdValue);
       
      $tableContent.= Tag::createTag('tr')->setContent($td1 . $td2);
    }
  }
   
  $tableAttr = array(
    'cellpadding' => '1',
    'cellspacing' => '0',
    'border'      => '1'
  );
  
   return Tag::createTag('table', $tableAttr)->setContent($tableContent);
} 



/**********************************************************************
 * Exception-Klassen
 **********************************************************************/

/**
 * Exception Klasse für AbstractTag-Fehler
 *
 * @author Timo Strotmann
 */
class AbstractTagException extends Exception {
} 

/**
 * Exception Klasse für TagHtmlVariant-Fehler
 *
 * @author Timo Strotmann
 */
class TagHtmlVariantException extends Exception {
} 

/**
 * Exception Klasse für TagInlineElement-Fehler
 *
 * @author Timo Strotmann
 */
class TagInlineElementException extends Exception {
} 

/**
 * Exception Klasse für UnknownTag-Fehler
 *
 * @author Timo Strotmann
 */
class UnknownTagException extends Exception {
} 

/**
 * Exception Klasse für TagFactory-Fehler
 *
 * @author Timo Strotmann
 */
class TagTypeException extends Exception {
} 

/**
 * Exception Klasse für StandaloneTag-Fehler
 *
 * @author Timo Strotmann
 */
class StandaloneTagException extends Exception {
} 



/**
 *
 * @author Timo Strotmann
 */
interface TagInterface {
  public function getName();
  public function getAttributes();
  public function getAttribute($name);
  public function isInlineTag();
  public function setName($name);
  public function addAttribute(Attribute $value);
  public function addAttributes($value);
  public function removeAttribute(Attribute $value);
  public function display();
} 



/**
 *
 * @author Timo Strotmann
 */
class AbstractTag implements TagInterface {

  protected $name = null;
  //protected $parent = null;
  protected $attributes = null;
  protected $isInlineTag = true;
  protected $isBlockTag  = true;

  protected $displayContentWithHtmlEntities = false;

  /**
   * 
   * @var array
   */
  protected $allowedParent = array(
    'a' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, td'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, td'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, td, body'),
    ),
    'abbr' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'acronym' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'address' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'applet' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'area' =>  array(
      'strict'        => array('map'),
      'frameset'      => array('map'),
      'transitional'  => array('map'),
    ),
    
    'b' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'base' =>  array(
      'strict'        => array('head'),
      'frameset'      => array('head'),
      'transitional'  => array('head'),
    ),
    'basefont' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'bdo' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'big' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'blockquote' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'body' =>  array(
      'strict'        => array('html'),
      'frameset'      => array('html'),
      'transitional'  => array('html, noframes'),
    ),
    'br' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'button' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    
    'caption' =>  array(
      'strict'        => array('table'),
      'frameset'      => array('table'),
      'transitional'  => array('table'),
    ),
    'center' =>  array(
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'cite' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'code' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'col' =>  array(
      'strict'        => array('colgroup, table'),
      'frameset'      => array('colgroup, table'),
      'transitional'  => array('colgroup, table'),
    ),
    'colgroup' =>  array(
      'strict'        => array('table'),
      'frameset'      => array('table'),
      'transitional'  => array('table'),
    ),
    
    'dd' =>  array(
      'strict'        => array('dl'),
      'frameset'      => array('dl'),
      'transitional'  => array('dl'),
    ),
    'del' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, body'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, body'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'dfn' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'dir' =>  array(
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'div' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'dl' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'dt' =>  array(
      'strict'        => array('dl'),
      'frameset'      => array('dl'),
      'transitional'  => array('dl'),
    ),
    
    'em' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    
    'fieldset' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'font' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'form' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'frame' =>  array(
      'frameset'      => array('frameset'),
    ),
    'frameset'      =>  array(
      'frameset'      => array('html'),
    ),
    
    'h1' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'h2' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'h3' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'h4' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'h5' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'h6' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'head' =>  array(
      'strict'        => array('html'),
      'frameset'      => array('html'),
      'transitional'  => array('html'),
    ),
    'hr' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'html' =>  array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    
    'i' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'iframe' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'img' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'input' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'ins' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, body'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, body'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'isindex' =>  array(
      'frameset'      => array('applet, blockquote, body, center, dd, del, div, fieldset, form, head, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, center, dd, del, div, fieldset, form, head, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    
    'kbd' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    
    'label' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'legend' =>  array(
      'strict'        => array('fieldset'),
      'frameset'      => array('fieldset'),
      'transitional'  => array('fieldset'),
    ),
    'li' =>  array(
      'strict'        => array('dir, menu, ol, ul'),
      'frameset'      => array('dir, menu, ol, ul'),
      'transitional'  => array('dir, menu, ol, ul'),
    ),
    'link' =>  array(
      'strict'        => array('head'),
      'frameset'      => array('head'),
      'transitional'  => array('head'),
    ),
    
    'map' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'menu' =>  array(
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'meta' =>  array(
      'strict'        => array('head'),
      'frameset'      => array('head'),
      'transitional'  => array('head'),
    ),
    
    'noframes' =>  array(
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, frameset, iframe, ins, li, map, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, frameset, iframe, ins, li, map, noscript, object, td, th'),
    ),
    'noscript' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    
    'object' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, head'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, head'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, head'),
    ),
    'ol' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'optgroup' =>  array(
      'strict'        => array('select'),
      'frameset'      => array('select'),
      'transitional'  => array('select'),
    ),
    'option' =>  array(
      'strict'        => array('select, optgroup'),
      'frameset'      => array('select, optgroup'),
      'transitional'  => array('select, optgroup'),
    ),
    
    'p' =>  array(
      'strict'        => array('address, applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('address, applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('address, applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'param' =>  array(
      'strict'        => array('applet, object'),
      'frameset'      => array('applet, object'),
      'transitional'  => array('applet, object'),
    ),
    'pre' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    
    'q' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    
    's' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'samp' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'script' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, head, body'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, head, body'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, head, body'),
    ),
    'select' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'small' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'span' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'strike' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'strong' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'style' =>  array(
      'strict'        => array('head'),
      'frameset'      => array('head'),
      'transitional'  => array('head'),
    ),
    'sub' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'sup' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    
    'table' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    'tbody' =>  array(
      'strict'        => array('table'),
      'frameset'      => array('table'),
      'transitional'  => array('table'),
    ),
    'td' =>  array(
      'strict'        => array('tr'),
      'frameset'      => array('tr'),
      'transitional'  => array('tr'),
    ),
    'textarea' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'tfoot' =>  array(
      'strict'        => array('table'),
      'frameset'      => array('table'),
      'transitional'  => array('table'),
    ),
    'th' =>  array(
      'strict'        => array('tr'),
      'frameset'      => array('tr'),
      'transitional'  => array('tr'),
    ),
    'thead' =>  array(
      'strict'        => array('table'),
      'frameset'      => array('table'),
      'transitional'  => array('table'),
    ),
    'title' =>  array(
      'strict'        => array('head'),
      'frameset'      => array('head'),
      'transitional'  => array('head'),
    ),
    'tr' =>  array(
      'strict'        => array('table, tbody, tfoot, thead'),
      'frameset'      => array('table, tbody, tfoot, thead'),
      'transitional'  => array('table, tbody, tfoot, thead'),
    ),
    'tt' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    
    'u' =>  array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
    'ul' =>  array(
      'strict'        => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'frameset'      => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
      'transitional'  => array('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th'),
    ),
    
    'var' =>  array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, body'),
    ),
  );
  

  /**
   * 
   * @var array
   */
  protected $allowedChild = array(
    'a' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'abbr' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'acronym' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'address' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, p'),
    ),
    'applet' => array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, param'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, param'),
    ),
    'area' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    
    'b' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'base' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'basefont' => array(
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'bdo' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'big' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'blockquote' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, script'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'body' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, script'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'br' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'button' => array(
      'strict'        => array('abbr, acronym, address, applet, b, basefont, bdo, big, blockquote, br, center, cite, code, dfn, dl, dir, div, em, font, h1-6, hr, i, img, kbd, map, menu, noframes, noscript, object, ol, p, pre, q, samp, script, small, span, strong, sub, sup, table, tt, ul, var'),
      'frameset'      => array('abbr, acronym, address, applet, b, basefont, bdo, big, blockquote, br, center, cite, code, dfn, dl, dir, div, em, font, h1-6, hr, i, img, kbd, map, menu, noframes, noscript, object, ol, p, pre, q, samp, script, small, span, strong, sub, sup, table, tt, ul, var'),
      'transitional'  => array('abbr, acronym, address, applet, b, basefont, bdo, big, blockquote, br, center, cite, code, dfn, dl, dir, div, em, font, h1-6, hr, i, img, kbd, map, menu, noframes, noscript, object, ol, p, pre, q, samp, script, small, span, strong, sub, sup, table, tt, ul, var'),
    ),
    
    'caption' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'center' => array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'cite' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'code' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'col' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'colgroup' => array(
      'strict'        => array('col'),
      'frameset'      => array('col'),
      'transitional'  => array('col'),
    ),
    
    'dd' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'del' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'dfn' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    /****************************************************************************
     * :TODO:
     * dir::childs(Muss (ein- oder mehrmal): li (Hinweis: li darf in diesem Zusammenhang keine Block-Elemente enthalten)) 
     */
    'dir' => array(
      'frameset'      => array('li'),
      'transitional'  => array('li'),
    ),
    'div' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'dl' => array(
      'strict'        => array('dd, dt'),
      'frameset'      => array('dd, dt'),
      'transitional'  => array('dd, dt'),
    ),
    'dt' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    'em' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    /****************************************************************************
     * :TODO:
     * fieldset::childs(#PCDATA legend, gefolgt von: [Block-Elemente], [Inline-Elemente]) 
     */
    'fieldset' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'font' => array(
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'form' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, script'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'frame' => array(
      'frameset'      => array(''),
    ),
    'frameset' => array(
      'frameset'      => array('frame, frameset, noframes'),
    ),
    
    'h1' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'h2' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'h3' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'h4' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'h5' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'h6' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    /****************************************************************************
     * :TODO:
     * head::childs(1. MUSS: title
                    2. KANN:
                    2.1. nach  HTML Strict: base, isindex, link, meta, object, script, style
                    2.2. nach  HTML Transitional: isindex) 
     */
    'head' => array(
      'strict'        => array('title, base, isindex, link, meta, object, script, style'),
      'frameset'      => array('title'),
      'transitional'  => array('title, isindex'),
    ),
    'hr' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    /****************************************************************************
     * :TODO:
     * html::childs(1. Strict, Transitional: head, gefolgt von body
                    2. Frameset: head, gefolgt von frameset)
     */
    'html' => array(
      'strict'        => array('head, body'),
      'frameset'      => array('head, frameset'),
      'transitional'  => array('head, body'),
    ),
    
    'i' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'iframe' => array(
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'img' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'input' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    /****************************************************************************
     * :TODO:
     * ins::childs(#PCDATA [Block-Elemente] bei Verwendung als Block-Element, [Inline-Elemente]) 
     */
    'ins' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'isindex' => array(
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    
    'kbd' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    'label' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'legend' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    /****************************************************************************
     * :TODO:
     * li::childs(#PCDATA dir | menu | ol | ul
                  1. bei dir und menu: [Inline-Elemente]
                  2. bei ol und ul:    [Block-Elemente] | [Inline-Elemente]) 
     */
    'li' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'link' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    
    'map' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, area'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, area'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, area'),
    ),
    /****************************************************************************
     * :TODO:
     * menu::childs(li (Hinweis: li darf in diesem Zusammenhang keine Block-Elemente enthalten)) 
     */
    'menu' => array(
      'frameset'      => array('li'),
      'transitional'  => array('li'),
    ),
    'meta' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    
    'noframes' => array(
      'frameset'      => array('body'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'noscript' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    'object' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, param'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var, param'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var, param'),
    ),
    'ol' => array(
      'strict'        => array('li'),
      'frameset'      => array('li'),
      'transitional'  => array('li'),
    ),
    'optgroup' => array(
      'strict'        => array('option'),
      'frameset'      => array('option'),
      'transitional'  => array('option'),
    ),
    'option' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    
    'p' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'param' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'pre' => array(
      'strict'        => array('a, abbr, acronym, applet, b, bdo, br, button, cite, code, dfn, em, i, input, iframe, kbd, label, map, q, samp, script, select, span, strong, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, applet, b, bdo, br, button, cite, code, dfn, em, i, input, iframe, kbd, label, map, q, samp, script, select, span, strong, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, bdo, br, button, cite, code, dfn, em, i, input, iframe, kbd, label, map, q, samp, script, select, span, strong, textarea, tt, var'),
    ),
    
    'q' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    's' => array(
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'samp' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'script' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'select' => array(
      'strict'        => array('optgroup, option'),
      'frameset'      => array('optgroup, option'),
      'transitional'  => array('optgroup, option'),
    ),
    'small' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'span' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'strike' => array(
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'strong' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'style' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'sub' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'sup' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    /****************************************************************************
     * :TODO:
     * table::childs(Darf folgende anderen HTML-Elemente (in dieser Reihenfolge) enthalten: caption (optional), col oder colgroup (optional), thead (optional), tfoot (optional), tbody (ein oder mehrere - wenn nur einmal benötigt, darf tbody auch entfallen, weshalb die herkömmliche Konstruktion, wonach table direkt aus tr-Elementen besteht, ebenfalls zulässig ist)) 
     */
    'table' => array(
      'strict'        => array('caption, col, colgroup, thead, tfoot, tbody, tr'),
      'frameset'      => array('caption, col, colgroup, thead, tfoot, tbody, tr'),
      'transitional'  => array('caption, col, colgroup, thead, tfoot, tbody, tr'),
    ),
    'tbody' => array(
      'strict'        => array('tr'),
      'frameset'      => array('tr'),
      'transitional'  => array('tr'),
    ),
    'td' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'textarea' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'tfoot' => array(
      'strict'        => array('tr'),
      'frameset'      => array('tr'),
      'transitional'  => array('tr'),
    ),
    'th' => array(
      'strict'        => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul, a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul, a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'thead' => array(
      'strict'        => array('tr'),
      'frameset'      => array('tr'),
      'transitional'  => array('tr'),
    ),
    'title' => array(
      'strict'        => array(''),
      'frameset'      => array(''),
      'transitional'  => array(''),
    ),
    'tr' => array(
      'strict'        => array('td, th'),
      'frameset'      => array('td, th'),
      'transitional'  => array('td, th'),
    ),
    'tt' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    
    'u' => array(
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
    'ul' => array(
      'strict'        => array('li'),
      'frameset'      => array('li'),
      'transitional'  => array('li'),
    ),
    
    'var' => array(
      'strict'        => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'frameset'      => array('a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var'),
      'transitional'  => array('a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var'),
    ),
  );
  
  /**
   * 
   * @var array
   */
  protected $allowedPCData = array(
    'a' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'abbr' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'acronym' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'address' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'applet' =>  array(
      'frameset'      => false,
      'transitional'  => false,
    ),
    'area' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    'b' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'base' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'basefont' =>  array(
      'frameset'      => false,
      'transitional'  => false,
    ),
    'bdo' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'big' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'blockquote' =>  array(
      'strict'        => false,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'body' =>  array(
      'strict'        => false,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'br' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'button' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'caption' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'center' =>  array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'cite' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'code' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'col' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'colgroup' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    'dd' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'del' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'dfn' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'dir' =>  array(
      'frameset'      => false,
      'transitional'  => false,
    ),
    'div' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'dl' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'dt' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'em' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'fieldset' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'font' =>  array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'form' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'frame' =>  array(
      'frameset'      => false,
    ),
    'frameset'      =>  array(
      'frameset'      => false,
    ),
    
    'h1' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'h2' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'h3' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'h4' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'h5' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'h6' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'head' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'hr' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'html' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    
    'i' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'iframe' =>  array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'img' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'input' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'ins' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'isindex' =>  array(
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    'kbd' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'label' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'legend' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'li' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'link' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    'map' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'menu' =>  array(
      'frameset'      => false,
      'transitional'  => false,
    ),
    'meta' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    'noframes' =>  array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'noscript' =>  array(
      'strict'        => false,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'object' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'ol' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'optgroup' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'option' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'p' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'param' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'pre' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'q' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    's' => array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'samp' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'script' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'select' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'small' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'span' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'strike' => array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'strong' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'style' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'sub' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'sup' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    
    
    
    
    

    'table' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'tbody' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'td' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'textarea' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'tfoot' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'th' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'thead' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'title' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    'tr' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    'tt' =>  array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
    
    'u' => array(
      'frameset'      => true,
      'transitional'  => true,
    ),
    'ul' =>  array(
      'strict'        => false,
      'frameset'      => false,
      'transitional'  => false,
    ),
    
    'var' => array(
      'strict'        => true,
      'frameset'      => true,
      'transitional'  => true,
    ),
  );

  
  /**
   * Konstruktor von AbstractTag.
   *
   * @param string $name Name des zu erstellenden Tags
   * @return void
   */
  public function __construct($name) {
    $this->name = $name;
    $attributes = array();

    if (false === array_search(HTML_VARIANT, trimExplode(HTML_VARIANTS))) {
      throw new TagHtmlVariantException('Die angegeben HTML-Variante existiert nicht');
    }

    switch(HTML_VARIANT) {
      case 'strict':
        $this->isInlineTag = in_array($this->name, trimExplode(STRICT_INLINE_ELEMENTS));
        $this->isBlockTag  = in_array($this->name, trimExplode(STRICT_BLOCK_ELEMENTS));
        break;

      case 'frameset':
        $this->isInlineTag = in_array($this->name, trimExplode(FRAMESET_INLINE_ELEMENTS));
        $this->isBlockTag  = in_array($this->name, trimExplode(FRAMESET_BLOCK_ELEMENTS));
        break;

      case 'transitional':
        $this->isInlineTag = in_array($this->name, trimExplode(TRANSITIONAL_INLINE_ELEMENTS));
        $this->isBlockTag  = in_array($this->name, trimExplode(TRANSITIONAL_BLOCK_ELEMENTS));
        break;
    }
  } 

  /**
   * __toString()-Methode zum ausgeben des Tags (siehe display()-Methode).
   *
   * @return string
   */
  public function __toString() {
    return $this->display();
  } 

  /**
   * Gibt den Namen des Tags zurück.
   *
   * @return string
   */
  public function getName() {
    return $this->name;
  } 

  // :TODO: Wenn man das Parent-Element (-Tag) mit abspeichert, kann man besser validieren, denn es ist ja nicht jedes Tag überall erlaubt!
  //	public function getParent() {
  //		return $this->parent;
  //	}

  /**
   * Gibt ein Array mit den Attributen zu diesem Tags zurück.
   *
   * @return array
   */
  public function getAttributes() {
    return $this->attributes;
  } 

  /**
   * Gibt ein Array mit den Attributen zu diesem Tags zurück.
   *
   * @return array
   */
  public function getAttribute($name) {
    if (array_key_exists($name, $this->attributes)) {
      return $this->attributes[$name];
    }
    return false;
  } 

  /**
   * Gibt TRUE zurück, wenn es sich bei dem Tag um ein "Inline-Tag", wie z.B. <br />, <hr />, <span> etc., zurück.
   *
   * @return boolean
   */
  public function isBlockTag() {
    return (boolean)$this->isBlockTag;
  } 

  /**
   * Alias-Methode für isBlockTag()
   *
   * @return boolean
   */
  public function isBlockElement() {
    return $this->isBlockTag();
  } 

  /**
   * Alias-Methode für isBlockTag()
   *
   * @return boolean
   */
  public function isBlock() {
    return $this->isBlockTag();
  } 

  /**
   * Gibt TRUE zurück, wenn es sich bei dem Tag um ein "Inline-Tag", wie z.B. <br />, <hr />, <span> etc., zurück.
   *
   * @return boolean
   */
  public function isInlineTag() {
    return (boolean)$this->isInlineTag;
  } 

  /**
   * Alias-Methode für isInlineTag()
   *
   * @return boolean
   */
  public function isInlineElement() {
    return $this->isInlineTag();
  } 

  /**
   * Alias-Methode für isInlineTag()
   *
   * @return boolean
   */
  public function isInline() {
    return $this->isInlineTag();
  } 

  /**
   * Setzt den Namen des Tags.
   *
   * @return Tag $this
   */
  public function setName($value) {
    $this->name = $value;
    return $this;
  } 

  /**
   * Fügt dem Tag ein Attribute hinzu.
   *
   * @return Tag $this
   */
  public function addAttribute(Attribute $value) {
    $this->attributes[$value->getName()] = $value;
    return $this;
  } 

  /**
   * Fügt dem Tag die übergebenen Attribute hinzu.
   *
   * @return Tag $this
   */
  public function addAttributes($value) {
    if (is_array($value) && !empty($value)) {
      foreach($value as $attribute) {
        if ($attribute instanceof Attribute) {
          $this->addAttribute($attribute);
        } else {
          throw new AbstractTagException('Die Methode \'addAttributes()\' erwartet ein Array mit Attribute-Objekten!');
        }
      }
    }

    return $this;
  } 

  /**
   * Entfernt dem Tag das übergebene Attribute.
   *
   * @return Tag $this
   */
  public function removeAttribute(Attribute $value) {
    if (array_key_exists($value->getName(), $this->attributes)) {
      unset($this->attributes[$value->getName()]);
    }
    
    return $this;
  } 

  /**
   * Ist dieser Wert gesetzt, so wird der Inhalt, der innerhalb eines ModularTags (z.B. <p>-Tag) steht, mit der htmlentities()-Funktion aufgerufen.
   *
   * @return Tag $this
   */
  public function setHtmlentities($value) {
    $this->displayContentWithHtmlEntities = (boolean)$value;
    return $this;
  } 

  /**
   * Gibt TRUE zurück, wenn der Inhalt des Tags mit der htmlentities()-Funktion von PHP aufgerufen werden soll, andernfalls FALSE.
   *
   * @return boolean
   */
  public function getHtmlentities() {
    return $this->displayContentWithHtmlEntities;
  } 

  /**
   * Rendern den Anfang des Tags.
   * Dies behinhaltend das öffnenden des Tags und die Auflistung der Attribute, z.B. '<a href="http://www.timo-strotmann.de" target="_blank"'.
   * Zu beachten ist, das das öffnede Tag nicht nicht geschlossen wird, dies liegt daran, das ein StandaloneTag mit ' />' und ein ModularTag mit '>' geschlossen wird!
   *
   * @return string
   */
  public function display() {
    $str = '<'.$this->name;
    if (!empty($this->attributes)) {
      foreach($this->attributes as $attr) {
        $str.= $attr;
      }
    }

    return $str;
  }
  
} 


/**
 * Hierbei handelt es sich um Tags, die kein abschließdes Tag haben, wie z.B. <br />, <hr />, <input /> etc.
 *
 * @author Timo Strotmann
 */
class StandaloneTag extends AbstractTag {

  protected $content = '';
  protected $contentAfter = true;

  /**
   * Gibt den "Content", der für diesen Tag hinterlegt ist, zurück.
   *
   * @return mixed $content
   */
  public function getContent() {
    return $this->content;
  } 

  /**
   * Setzt den "Content" (z.B. die "Beschriftung" eines <input>-Tags), der vor oder hinter dem Tag erscheinen soll.
   *
   * @param string $content
   * @return Tag $this
   */
  public function setContent($content) {
    if (is_string($content)) {
      $this->content = $content;
    } else {
      throw new StandaloneTagException('Der in $content hinterlegte Wert muss ein String sein!');
    }
    return $this;
  } 

  /**
   * Bestimmt, ob der "Content" (z.B. die "Beschriftung" eines <input>-Tags) vor oder hinter dem Tag stehen soll.
   *
   * @param boolean $value
   * @return Tag $this
   */
  public function setContentAfter($value = true) {
    $this->contentAfter = (boolean)$value;
    return $this;
  } 

  /**
   * Bestimmt, ob der "Content" (z.B. die "Beschriftung" eines <input>-Tags) vor oder hinter dem Tag stehen soll.
   *
   * @param boolean $value
   * @return Tag $htis
   */
  public function setContentBefore($value = false) {
    $this->setContentAfter(!(boolean)$value);
    return $this;
  } 

  /**
   * Vervollständigung der AbstragTag::display()-Methode
   *
   * @return string
   */
  public function display() {
    $tag = parent::display() . ' />';

    $content = '';
    if (is_array($this->content)) {
      foreach($this->content as $value) {
        $content.=$value;
      }
    } else {
      $content = $this->content;
    }

    if ($this->contentAfter) {
      $str = $tag . $content;
    } else {
      $str = $content . $tag;
    }
    
    return $str;
  } 
  
} 


/**
 * Hierbei handelt es sich um Tags, die sowhol aus einem öffnedem und schließendem Tag bestehen, wie z.B. <p>, <span>, <textarea> etc.
 *
 * @author Timo Strotmann
 */
class ModularTag extends AbstractTag {

  protected $content = '';

  /**
   * Gibt den "Content", der z.B. zwischen dem öffneden und schließendem Tag steht (z.B. 'Hello World!' für <p>Hello World!</p>) zurück.
   *
   * @return mixed $content
   */
  public function getContent() {
    return $this->content;
  } 

  /**
   * Setzt den "Content", der zwischen dem öffneden und schließendem Tag steht (z.B. 'Hello World!' für <p>Hello World!</p>).
   *
   * @param string $content
   * @return Tag $this
   */
  public function setContent($content) {
    $this->content = $content;
    return $this;
  } 

  /**
   * Vervollständigung der AbstragTag::display()-Methode
   *
   * @return string
   */
  public function display() {
    $tag = parent::display();

    $content = '';
    if (is_array($this->content)) {
      foreach($this->content as $value) {
        $content.= $value;
      }
    } else {
      $content = $this->content;
    }
    
    if ($this->displayContentWithHtmlEntities) {
      $content = htmlentities($content, ENT_NOQUOTES, ENCODING);
    }
    
    return "{$tag}>{$content}</{$this->name}>";
  } 
  
  
} 
