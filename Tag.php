<?php
require_once 'AttributeFactory.php';
require_once 'TagType.php';

/**********************************************************************
 * Exception-Klassen
 **********************************************************************/

/**
 * Exception Klasse für Tag-Fehler
 * 
 * @author Timo Strotmann
 */
class TagException extends Exception {
} 



/**********************************************************************
 * AttributeTypeFactory-Klasse
 * -> zum erstellen/ermitteln eines Tags
 **********************************************************************/

/**
 * 
 * @author Timo Strotmann
 */
class Tag {
	
	static protected $prefixId = ''; 
	static protected $defaultTextareaCols = 20;
	static protected $defaultTextareaRows = 5;
	
	/**
	 * Erstellt Tag und fügt diesem, die übergebenen Attribute hinzu.
	 * Das Array mit den Attributen ist wie folgt aufgebaut:
	 *   $data = array(
	 *     array('name'=>'width', 'value'=>'10'),
	 *     array('name'=>'style', 'value'=>'color:#333', 'options'=>array('addSlashes'=>false))
	 *   );
	 * Wobei die Keys, weggelassen werden können, dann ist die Reihenfolge aber 'name'=0, 'value'=1, 'options'=2!
	 * 
	 * @param string $name Name des Tags, z.B. 'a', 'input', 'br' etc.
	 * @param array $attributes Array mit den zu erstellenden Attributen.
	 * @return Tag
	 */
	static public function createTag($name, $attributes=array()) {
		if (false === array_search($name, trimExplode(STANDALONE_TAGS))) {
			$tag = new MultiTag($name);
		} else {
			$tag = new StandaloneTag($name);
		}
		if (is_array($attributes) && !empty($attributes)) {
			$tag->addAttributes(AttributeFactory::createAttributes($tag->getName(), $attributes));
		}
		
		return $tag;
	} 
	
	/**
	 * Erstellt ein <a>-Tag und fügt dem den übergebenen Inhalt, sowie die übergebenen Attribute hinzu.
	 * 
	 * @param string $href Wert des href-Attribute
	 * @param $content Text, der Links
	 * @param $attributes Zusätliche Attribute für das Tag
	 * @return Tag
	 */
	static public function createATag($href, $content, $attributes=array()) {
		$attributes['href'] = $href;
		$tag = self::createTag('a', $attributes);
		$tag->setContent($content);
		return $tag;
	} 
	
 /**
   * Erstellt ein <form>-Tag
   *
   * @param string $action URL an der das <form> versendet wird
   * @param string $formContent Content zwischen dem <form>- und </form>-Tag
   * @param array $params Additional parameter like 'class' 
   * @return unknown
   */
  function createFormTag($action, $content, $attributes=array()) {
		$searchAttributes = array('method'=>false);
		self::hasAttributes($attributes, &$searchAttributes);
  	
      // If params isn't a array or in params don't exist the 'method', than set 'method="post"'
		if (!$searchAttributes['method']) {
			$attributes['method'] = 'post';
		}
		
      // If $content contains a <input>-Tag for File-Upload, than the <form>-Tag neads 'enctype="multipart/form-data'
    if (preg_match('/(<input).*(type=\"file\")/i', $content, $result) && strpos('>', $result)===FALSE) {
			$attributes['enctype'] = 'multipart/form-data';
    } 
		
    	// Das 'action'-Attribute setzen
    $attributes['action'] = $action;
    
		$tag = self::createContentTag('form', $content, $attributes);
		$tag->setHtmlentities(false);
		return $tag;
  } 
	
	/**
	 * Erstellt ein <input>-Tag.
	 * 
	 * @param $type
	 * @param $name
	 * @param $attributes
	 * @return Tag
	 */
	static public function createInputTag($type, $name, $value, $attributes=array()) {
		$searchAttributes = array('id'=>false, 'class'=>false);
		self::hasAttributes($attributes, &$searchAttributes);

		$attributes['type'] = $type;
		$attributes['value'] = $value;
		
			// ID-Attribute
		$name = preg_replace('/\s/', '_', $name);														// Alle Whitespaces durch einen '_' ersetzten, da Whitespaces im 'name'- und 'id'-Attribute nicht zulässig bzw. 'unschön' sind
		if (!$searchAttributes['id']) {
			self::addIdAttribute($attributes, $name);
		}
		
			// NAME-Attribute
		self::addNameAttribute($attributes, $name);
		
			// CLASS-Attribute
		if (false === $searchAttributes['class']) {
			$attributes['class'] = (($type=='submit') ? 'submit' : 'input '.$type);
		}
		
		return self::createTag('input', $attributes);
	} 
	
	/**
	 * Erstellt ein <label>-Tag
	 * 
	 * @param string $for
	 * @param string $content
	 * @param array $attributes
	 * @return Tag
	 */		
	static public function createLabelTag($for, $content, $attributes=array()) {
		$searchAttributes = array('class'=>false);
		self::hasAttributes($attributes, &$searchAttributes);
		
		$for = preg_replace('/\s/', '_', $for);															// Alle Whitespaces durch einen '_' ersetzten, da Whitespaces im 'name'- und 'id'-Attribute nicht zulässig bzw. 'unschön' sind
		$attributes['for'] =  (self::hasPrefixId()) ? self::$prefixId . '_' . $for : $for;
		
      // Default CLASS-Attribute
		if (!$searchAttributes['class']) {
			$attributes['class'] = 'label';
		}
		
		$tag = self::createContentTag('label', $content, $attributes);
		return $tag->setHtmlentities(false);
	} 
	
	/**
	 * Erstellt ein <input>-Tag und das zugehörige <label>-Tag.
	 * 
	 * @param string $labelContent Beschriftung des Label-Tags
	 * @param string $type Type des Input-Tags, z.B. 'checkbox'
	 * @param string $name Name des Input-Tags
	 * @param string $value Wert Input-Tags, welcher übermittelt wird
	 * @param array $inputAttributes Optionale Attribute die dem Input-Tag hinzugefügt werden können
	 * @param array $labelAttributes Optionale Attribute die dem Label-Tag hinzugefügt werden können
	 * @return string
	 */
	static public function createLabeledInputTag($labelContent, $type, $name, $value, $inputAttributes=array(), $labelAttributes=array()) {
		/**
		 * :TODO: das 'checked'-Attribute muss noch in das <input>-Tag kommen!
		 */
		$input = self::createInputTag($type, $name, $value, $inputAttributes);
		if ($input->getAttribute('id') instanceof Attribute) {
			$for = $input->getAttribute('id')->getValue();
				// In '$for' kann 'self::$prefixId' schon behinhalten, das muss hier herausgefiltert werden
			if (self::$prefixId.'_' === substr($for, 0, (strlen(self::$prefixId)+1))) {
				$for = substr($for, (strlen(self::$prefixId)+1));
			}
			$label = self::createLabelTag($for, $labelContent, $labelAttributes);
		} else {
			throw new TagException('Beim erstellen eines <label>-Tag wird ein id-Attribute benötigt!');
		}
		return $label.$input;
	} 

 /**
   * Erstellt ein <textarea>-Tag
   *
   * @param string $name Name des Tags, über dem der Inhalt ausgelesen werden kann (name-Attribute)
   * @param string $content Content zwischen dem <textarea>- und </textarea>-Tag
   * @param array $params Additional parameter like 'class' 
   * @return unknown
   */
  static public function createTextareaTag($name, $content='', $attributes=array()) {
		$searchAttributes = array('id'=>false, 'class'=>false, 'cols'=>false, 'rows'=>false);
		self::hasAttributes($attributes, &$searchAttributes);
		
			// Id-Attribute
		$name = preg_replace('/\s/', '_', $name);														// Alle Whitespaces durch einen '_' ersetzten, da Whitespaces im 'name'- und 'id'-Attribute nicht zulässig bzw. 'unschön' sind
		if (!$searchAttributes['id']) {
			self::addIdAttribute($attributes, $name);
		}
		
			// Name-Attribute
		self::addNameAttribute($attributes, $name);

			// Default Class-Attribute
		if (!$searchAttributes['class']) {
			$attributes['class'] = 'textarea';
		}
		
      // Default Cols-Attribute
		if (!$searchAttributes['cols']) {
			$attributes['cols'] = self::$defaultTextareaCols;
		}
		
      // Default Rows-Attribute
		if (!$searchAttributes['rows']) {
			$attributes['rows'] = self::$defaultTextareaRows;
		}

		$tag = self::createContentTag('textarea', $content, $attributes);
		return $tag->setHtmlentities(false);
  } 
  
  /**
   * Erstellt ein <fieldset>-Tag. Bei Angabe eines $legendContent, wird zudem noch ein <legend>-Tag (in Form des Fieldset-Content) hinzugefügt.
   * 
   * @param $legendContent
   * @param $fieldsetContent
   * @param $fieldsetAttributes
   * @param $legendAttributes
   * @return unknown_type
   */
  static public function createFieldsetTag($fieldsetContent, $legendContent=NULL, $fieldsetAttributes=array(), $legendAttributes=array()) {
		$searchAttributes = array('class'=>false);
		self::hasAttributes($fieldsetAttributes, &$searchAttributes);
		
      // Default CLASS-Attribute
		if (!$searchAttributes['class']) {
			$fieldsetAttributes['class'] = 'fieldset';
		}
		
		if (null !== $legendContent) {
			$legend = self::createContentTag('legend', $legendContent, $legendAttributes);
			$legend->setHtmlentities(false);
		} else {
			$legend = '';
		}
		
		$fieldset = self::createContentTag('fieldset', $legend.$fieldsetContent, $fieldsetAttributes);
		return $fieldset->setHtmlentities(false);
  } 

  /**
   * 
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return unknown_type
   */
  static public function createListTag($listItems, $listType='ul', $attritbutes=array()) {
		$searchAttributes = array('id'=>false, 'class'=>false);
		self::hasAttributes($attritbutes, $searchAttributes);
		
			// Default CLASS-Attribute
		if (false === $searchAttributes['class']) {
			$attritbutes['class'] = 'select ' . $listType;
		}
		
		$items = array();
		$itemsContent = '';
		foreach($listItems as $key => $value) {
			switch($listType) {
				case 'ul':
				case 'ol':
					if ($value instanceof Tag) {
						// Es wurde ein Array mit Tags (<li>-Tags) übergeben (hoffentlich)
						if ('li' == $value->getName()) {
							$items[] = $value;
						} else {
							throw new TagTypeException('Es sind in dem Listentyp \''.$listType.'\' nur \'<li>\'-Tags erlaubt!');
						}
					} else {
						if (is_array($value)) {
								// In $value stecken die Attribute und in $key der Content für das kommende <li>-Tag
							$itemObj = self::createContentTag('li', $key, $value);
						} else {
								// In $value steckt der Inhalt für das kommende <li>-Tag.
								// Attribute sind nicht angegeben.
							$itemObj = self::createContentTag('li', $value);
						}
						$items[] = $itemObj;
					}
					break;
				
				case 'dl':
					break;
				
				default:
					throw new TagTypeException('Der angegebene Listentyp ($listType=\''.$listType.'\') existiert nicht!');
			}
		}

		$itemsContent = '';
		foreach($items as $item) {
			$itemsContent.= "\n\t".$item->display();
		}	
			
		$tag = self::createContentTag($listType, $itemsContent, $attritbutes);
		return $tag->setHtmlentities(false);
  } 

  /**
   *:TODO:
   *
   * @param $items
   * @param $name
   * @param $type
   * @param $attributes
   * @return unknown_type
   */
	static public function createChoiceTag($items, $name, $type='radio', $attributes=array()) {
		$itemObj = array();
		
		if ($type === 'radio' || ($type === 'checkbox')) {
				// Radio- oder  Checkbox-Buttons
			foreach($items as $inputValue => $itemValue) {
				$inputAttributes = array();
				
				if (is_string($itemValue)) {
					$label = $itemValue;																		// Inhalt des <label>-Tags
					
				} else if (is_array($itemValue)) {
					$label = array_shift($itemValue);												// Inhalt des <label>-Tags

					if (!empty($itemValue)) {
						$inputAttributes = array_shift($itemValue);						// Der Rest des Array, sind Attribute für das <input>-Tag 
					}
				}
				
				self::addIdAttribute($inputAttributes, $inputValue);
				
					// Wenn eine PrefixId gesetzt ist, wird $name schon ein eckige Klammern ('[]') gepackt,
					// also müssen die Klammern für die $name nicht so '[]' sondern so '][' angefügt werden!
					// Mit PrefixId: dummy[$name] => dummy[$name.']['.] => dummy[$name][]
				if (self::hasPrefixId()) {
					$inputName = $name.'][';						 	// 'name'-Attribute im <input>-Tag
				} else {
					$inputName = $name.'[]';
				}

				$itemObj[] = self::createLabeledInputTag($label, $type, $inputName, $inputValue, $inputAttributes);
			}
		}

		return $itemObj;
	} 
  
  /**
   * Fügt das ID-Attribute den in $attributes übergebenen Attributen hinzu.
   * 
   * @param array $attributes Referenz mit den Attributen
   * @param string $value Wert des ID-Attributes
   * @return void
   */
  static public function addIdAttribute(&$attributes, $value) {
		$name = preg_replace('/\s/', '_', $value);														// Alle Whitespaces durch einen '_' ersetzten, da Whitespaces im 'name'- und 'id'-Attribute nicht zulässig bzw. 'unschön' sind
		$attributes['id'] = ((self::hasPrefixId()) ? self::$prefixId.'_'  : '') . $value;
  } 
  
  /**
   * Fügt das NAME-Attribute den in $attributes übergeben Attributen hinzu.
   * 
   * @param array $attributes Referenz mit den Attributen
   * @param string $value Wert des ID-Attributes
   * @return void
   */
  static public function addNameAttribute(&$attributes, $value) {
		$attributes['name'] = (self::hasPrefixId()) ? self::$prefixId . '[' . $value . ']' : $value;
  } 
  
	/**
	 * Erstellt ein Tag und fügt dem den übergebenen Inhalt, sowie die übergebenen Attribute hinzu.
	 * 
	 * @param string $tagName Name des Tags, z.B. 'a', 'p', 'div'
	 * @param string $content Inhalt der zwischen den öffnenden und schließenden Tag stehen soll.
	 * @param array $attributes Attribute, die dem Tag hinzugefügt werden sollen
	 * @return Tag
	 */
	static private function createContentTag($tagName, $content, $attributes=array()) {
		$tag = Tag::createTag($tagName);
		$tag->addAttributes(AttributeFactory::createAttributes($tag->getName(), $attributes))
				->setContent($content);

		return $tag;
	} 
		
	/**
	 * Setzen der PrefixId.
	 * Wenn diese angegeben ist, wird z.B. bei createInputTag() das ID-Attribute automatisch gesetzt (sofern nicht schon in dem Attribute-Array aufgeführt) und die PrefixId vorangestellt.
	 * 
	 * @param $prefixId
	 * @return void
	 */
	static public function setPrefixId($prefixId) {
		if (is_string($prefixId)) {
			self::$prefixId = $prefixId;
		} else {
			throw new TagException('Die PrefixId muss ein String sein!');		
		}
	} 
	
	/**
	 * Setzen die Default-Größe für das 'cols'-Attribute beim <textarea>-Tag.
	 * 
	 * @param $cols
	 * @return void
	 */
	static public function setDefaultTextareaCols($cols) {
		$cols = intval($cols);
		if (0 < $cols) {
			self::$defaultTextareaCols = $cols;
		} else {
			throw new TagException('Die angebenene Größe für \'TextareaCols\' muss vom Typ Integer und > 0 sein!');		
		}
	} 

	/**
	 * Setzen die Default-Größe für das 'rows'-Attribute beim <textarea>-Tag.
	 * 
	 * @param $rows
	 * @return void
	 */
	static public function setDefaultTextareaRows($rows) {
		$rows = intval($rows);
		if (0 < $rows) {
			self::$defaultTextareaRows = $rows;
		} else {
			throw new TagException('Die angebenene Größe für \'TextareaRows\' muss vom Typ Integer und > 0 sein!');		
		}
	} 
	
	/**
	 * Prüft, ob Prefix für diverse Attribute (z.B. 'id' oder 'name') gesetzt wurde.
	 * @return boolean
	 */
	static protected function hasPrefixId() {
		return (is_string(self::$prefixId) && '' != self::$prefixId);
	} 
	
	/**
	 * Durchsucht die übergebenen $attributes nach Attributen, die in $search in Form von Schlüsseln angegeben werden.
	 * 
	 * @param array $attributes Array mit Attributen
	 * @param array $search Array mit den Attributen nach denen in $attributes gesucht werden soll (array('id'=>false, 'class'=>false, ...)
	 * @return boolean
	 */
	static protected function hasAttributes($attributes, &$search) {
		$has = false;
		if (is_array($attributes) && is_array($search)) {
			foreach ($attributes as $attrName => $attrValue) {																// Alle Attribute durchsuchen
				if (array_key_exists($attrName, $search)) {																			// und zwar nach denen, die in $search als Schlüssel aufgeführt wurden
					$search[$attrName] = true;
					$has = true;
					break;
				}
			}
		}
		return $has;
	} 
	
} 

