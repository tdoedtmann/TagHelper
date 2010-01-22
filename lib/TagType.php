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


function trimExplode($string, $delim=',', $onlyNonEmptyValues=0)    {
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
				$this->isInlineTag = (false !== array_search($this->name, trimExplode(STRICT_INLINE_ELEMENTS))) ? true : false;
				break;
				
			case 'frameset':
				$this->isInlineTag = (false !== array_search($this->name, trimExplode(FRAMESET_INLINE_ELEMENTS))) ? true : false;
				break;
					
			case 'transitional':
				$this->isInlineTag = (false !== array_search($this->name, trimExplode(TRANSITIONAL_INLINE_ELEMENTS))) ? true : false;
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
	public function isInlineTag() {
		return (boolean)$this->isInlineTag;
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
		$str = "\n".'<'.$this->name;
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
		
		if ($this->contentAfter) {
			$str = $tag . $this->content;
		} else {
			$str = $this->content . $tag;
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

		if ($this->displayContentWithHtmlEntities) {
      return "{$tag}>" . htmlentities($this->content, ENT_NOQUOTES, ENCODING) . "</{$this->name}>";
    } else {
      return "{$tag}>{$this->content}</{$this->name}>";
    }
	} 
} 



