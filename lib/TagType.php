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




  /*
   * a::parent([Block-Elemente] | [Inline-Elemente] (außer a | button) | td | body, (body nur bei  HTML Transitional))
   * abbr::parent([Block-Elemente] | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * acronym::parent([Block-Elemente] | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * address::parent(applet | blockquote | body | button | center | dd | del | div | fieldset | form | iframe | ins | li | map | noframes | noscript | object | td | th) 
   * applet::parent([Block-Elemente] (außer pre) | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * area::parent(map) 
   * b::parent([Block-Elemente] | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * base::parent(head) 
   * basefont::parent([Block-Elemente] (außer pre) | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * bdo::parent([Block-Elemente] | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * big::parent([Block-Elemente] (außer pre) | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * blockquote::parent(applet | blockquote | body | button | center | dd | del | div | fieldset | form | iframe | ins | li | map | noframes | noscript | object | td | th) 
   * body::parent(html | noframes (noframes nur bei  HTML Frameset)) 
   * br::parent([Block-Elemente] | [Inline-Elemente] | body (body nur bei  HTML Transitional)) 
   * button::parent([Block-Elemente] | [Inline-Elemente] (außer button)) 
   * caption::parent() 
   * center::parent() 
   * cite::parent() 
   * code::parent() 
   * col::parent() 
   * colgroup::parent() 
   * dd::parent() 
   * del::parent() 
   * dfn::parent() 
   * dir::parent() 
   * div::parent() 
   * dl::parent() 
   * dt::parent() 
   * em::parent() 
   * fieldset::parent() 
   * font::parent() 
   * form::parent() 
   * frame::parent() 
   * frameset::parent() 
   * h1::parent() 
   * h2::parent() 
   * h3::parent() 
   * h4::parent() 
   * h5::parent() 
   * h6::parent() 
   * head::parent() 
   * hr::parent() 
   * html::parent() 
   * i::parent() 
   * iframe::parent() 
   * img::parent() 
   * input::parent() 
   * ins::parent() 
   * isindex::parent() 
   * kbd::parent() 
   * label::parent() 
   * legend::parent() 
   * li::parent() 
   * link::parent() 
   * map::parent() 
   * menu::parent() 
   * meta::parent() 
   * noframes::parent() 
   * noscript::parent() 
   * object::parent() 
   * ol::parent() 
   * optgroup::parent() 
   * option::parent() 
   * p::parent() 
   * param::parent() 
   * pre::parent() 
   * q::parent() 
   * s::parent() 
   * samp::parent() 
   * script::parent() 
   * select::parent() 
   * small::parent() 
   * span::parent() 
   * strike::parent() 
   * strong::parent() 
   * style::parent() 
   * sub::parent() 
   * sup::parent() 
   * table::parent() 
   * tbody::parent() 
   * td::parent() 
   * textarea::parent() 
   * tfoot::parent() 
   * th::parent() 
   * thead::parent() 
   * title::parent() 
   * tr::parent() 
   * tt::parent() 
   * u::parent() 
   * ul::parent() 
   * var::parent()
   * 
   * 
   * 
   * 
   * a::childs(#PCDATA, [Inline-Elemente] (außer a))
   * abbr::childs(#PCDATA [Inline-Elemente]) 
   * acronym::childs(#PCDATA [Inline-Elemente]) 
   * address::childs(#PCDATA [Inline-Elemente] | p (p nur bei  HTML Transitional)) 
   * applet::childs([Block-Elemente] | [Inline-Elemente] | param) 
   * area::childs(__LEER__) 
   * b::childs(#PCDATA [Inline-Elemente]) 
   * base::childs(__LEER__) 
   * basefont::childs(__LEER__ ) 
   * bdo::childs(#PCDATA [Inline-Elemente]) 
   * big::childs(#PCDATA [Inline-Elemente]) 
   * blockquote::childs(1. nach  HTML Strict:       [Block-Elemente] | script
                        2. nach  HTML Transitional: #PCDATA und [Block-Elemente] | [Inline-Elemente]) 
   * body::childs(1. nach  HTML Strict: [Block-Elemente] | script
                  2. nach  HTML Transitional: #PCDATA und [Block-Elemente] | [Inline-Elemente]) 
   * br::childs(__LEER__ ) 
   * button::childs(#PCDATA abbr | acronym | address | applet | b | basefont | bdo | big | blockquote | br | center | cite | code | dfn | dl | dir | div | em | font | h1-6 | hr | i | img | kbd | map | menu | noframes | noscript | object | ol | p | pre | q | samp | script | small | span | strong | sub | sup | table | tt | ul | var) 
   * caption::childs() 
   * center::childs() 
   * cite::childs() 
   * code::childs() 
   * col::childs() 
   * colgroup::childs() 
   * dd::childs() 
   * del::childs() 
   * dfn::childs() 
   * dir::childs() 
   * div::childs() 
   * dl::childs() 
   * dt::childs() 
   * em::childs() 
   * fieldset::childs() 
   * font::childs() 
   * form::childs() 
   * frame::childs() 
   * frameset::childs() 
   * h1::childs() 
   * h2::childs() 
   * h3::childs() 
   * h4::childs() 
   * h5::childs() 
   * h6::childs() 
   * head::childs() 
   * hr::childs() 
   * html::childs() 
   * i::childs() 
   * iframe::childs() 
   * img::childs() 
   * input::childs() 
   * ins::childs() 
   * isindex::childs() 
   * kbd::childs() 
   * label::childs() 
   * legend::childs() 
   * li::childs() 
   * link::childs() 
   * map::childs() 
   * menu::childs() 
   * meta::childs() 
   * noframes::childs() 
   * noscript::childs() 
   * object::childs() 
   * ol::childs() 
   * optgroup::childs() 
   * option::childs() 
   * p::childs() 
   * param::childs() 
   * pre::childs() 
   * q::childs() 
   * s::childs() 
   * samp::childs() 
   * script::childs() 
   * select::childs() 
   * small::childs() 
   * span::childs() 
   * strike::childs() 
   * strong::childs() 
   * style::childs() 
   * sub::childs() 
   * sup::childs() 
   * table::childs() 
   * tbody::childs() 
   * td::childs() 
   * textarea::childs() 
   * tfoot::childs() 
   * th::childs() 
   * thead::childs() 
   * title::childs() 
   * tr::childs() 
   * tt::childs() 
   * u::childs() 
   * ul::childs() 
   * var::childs() 
   * 
   */  



  $allowedParent = array(
    'a'     => '',
  );






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
      return $this->attributes[$value->getName()];
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
