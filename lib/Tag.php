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

  /**
   * :TODO: 
   * XHTML-TAGS
   * [X] a 
   * [X] abbr 
   * [X] acronym 
   * [ ] address 
   * [ ] applet 
   * [ ] area 
   * [X] b 
   * [ ] base 
   * [ ] basefont 
   * [ ] bdo 
   * [X] big 
   * [X] blockquote 
   * [ ] body 
   * [X] br 
   * [X] button 
   * [ ] caption 
   * [X] center 
   * [X] cite 
   * [X] code 
   * [ ] col 
   * [ ] colgroup 
   * [X] dd 
   * [ ] del 
   * [X] dfn 
   * [ ] dir 
   * [X] div 
   * [X] dl 
   * [X] dt 
   * [X] em 
   * [X] fieldset 
   * [ ] font 
   * [X] form 
   * [ ] frame 
   * [ ] frameset 
   * [X] h1 
   * [X] h2 
   * [X] h3 
   * [X] h4 
   * [X] h5 
   * [X] h6 
   * [ ] head 
   * [X] hr 
   * [ ] html 
   * [X] i 
   * [ ] iframe 
   * [X] img 
   * [X] input 
   * [ ] ins 
   * [ ] isindex 
   * [X] kbd 
   * [X] label 
   * [X] legend 
   * [X] li 
   * [ ] link 
   * [ ] map 
   * [ ] menu 
   * [ ] meta 
   * [ ] noframes 
   * [ ] noscript 
   * [ ] object 
   * [X] ol 
   * [ ] optgroup 
   * [ ] option 
   * [X] p 
   * [X] param 
   * [X] pre 
   * [X] q 
   * [X] s 
   * [X] samp 
   * [ ] script 
   * [X] select 
   * [X] small 
   * [X] span 
   * [X] strike 
   * [X] strong 
   * [ ] style 
   * [X] sub 
   * [X] sup 
   * [ ] table 
   * [X] tbody 
   * [X] td 
   * [X] textarea 
   * [X] tfoot 
   * [X] th 
   * [X] thead 
   * [ ] title 
   * [X] tr 
   * [X] tt 
   * [X] u 
   * [X] ul 
   * [X] var'
   */
  
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
  static public function create($name, $attributes=array()) {
    if (false === array_search($name, trimExplode(STANDALONE_TAGS))) {
      $tag = new ModularTag($name);
    } else {
      $tag = new StandaloneTag($name);
    }
    if (is_array($attributes) && !empty($attributes)) {
      $tag->addAttributes(AttributeFactory::createAttributes($tag->getName(), $attributes));
    }

    return $tag;
  } 

  /**
   * Erstellt ein Tag und fügt dem den übergebenen Inhalt, sowie die übergebenen Attribute hinzu.
   *
   * @param string $tagName Name des Tags, z.B. 'a', 'p', 'div'
   * @param string $content Inhalt der zwischen den öffnenden und schließenden Tag stehen soll.
   * @param array $attributes Attribute, die dem Tag hinzugefügt werden sollen
   * @return Tag
   */
  static  public function content($tagName, $content, $attributes=array()) {
    $tag = self::create($tagName, $attributes);
    $tag->setContent($content);

    return $tag;
  } 
  
  
  
  
  /**********************************************************************
   * Standalone-Tags ****************************************************
   **********************************************************************/
  
  /**
   * Erstelt ein <br>-Tag
   * 
   * @param array $attributes
   * @return Tag
   */
  static public function br($attributes=array()) {
    return self::create('br', $attributes);
  } 
  
  /**
   * Erstelt ein <hr>-Tag
   * 
   * @param array $attributes
   * @return Tag
   */
  static public function hr($attributes=array()) {
    return self::create('hr', $attributes);
  } 
  
  /**
   * Erstellt ein <img>-Tag
   * 
   * @param string $src
   * @param array $attributes
   * @return Tag
   */
  static public function img($src, $attributes=array()) {
    return self::createTag('img', array_merge($attributes, array('src'=>$src)));
  } 
  
  
  
  
  /**********************************************************************
   * Modular-Tags *******************************************************
   **********************************************************************/
  
  /**
   * Erstellt ein <a>-Tag und fügt dem den übergebenen Inhalt, sowie die übergebenen Attribute hinzu.
   *
   * @param string $href Wert des href-Attribute
   * @param string $content Text, der Links
   * @param array $attributes Zusätliche Attribute für das Tag
   * @return Tag
   */
  static public function a($href, $content, $attributes=array()) {
    $attributes['href'] = $href;
    return self::content('a', $content, $attributes);
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
  static public function fieldset($fieldsetContent, $legendContent=NULL, $fieldsetAttributes=array(), $legendAttributes=array()) {
    $searchAttributes = array('class'=>false);
    self::hasAttributes($fieldsetAttributes, $searchAttributes);

      // Default CLASS-Attribute
    if (!$searchAttributes['class']) {
      $fieldsetAttributes['class'] = 'fieldset';
    }

    $fieldset = self::create('fieldset', $fieldsetAttributes);

    if (null !== $legendContent) {
      // <legend>-Tag erstellen und dem <fieldset> hinzufügen
      $legend = self::legend($legendContent, $legendAttributes);
      $legend->setHtmlentities(false);
      $fieldset->setContent($legend);
    }

    return $fieldset->setContent($fieldsetContent)->setHtmlentities(false);
  } 
  
  /**
   * Erstellt ein <lagend>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function legend($content, $attributes=array()) {
    return self::content('legend', $content, $attributes);
  }
  
  
  
  
  /**********************************************************************
   * Formular-Tags ******************************************************
   **********************************************************************/
    
  /**
   * Erstellt ein <form>-Tag
   *
   * @param string $action URL an der das <form> versendet wird
   * @param string $formContent Content zwischen dem <form>- und </form>-Tag
   * @param array $params Additional parameter like 'class'
   * @return unknown
   */
  static public function form($action, $content, $attributes=array()) {
    $searchAttributes = array('method'=>false);
    self::hasAttributes($attributes, $searchAttributes);
     
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

    $tag = self::content('form', $content, $attributes);
    $tag->setHtmlentities(false);
    return $tag;
  } 

  /**
   * Erstellt ein <input>-Tag.
   *
   * @param string $type
   * @param string $name
   * @param array $attributes
   * @return Tag
   */
  static public function input($type, $name, $value='', $attributes=array()) {
    $searchAttributes = array('id'=>false, 'class'=>false);
    self::hasAttributes($attributes, $searchAttributes);

    $attributes['type'] = $type;

    if (is_int($value) || !empty($value)) {
      $attributes['value'] = $value;
    }

      // ID-Attribute
    if (!$searchAttributes['id']) {
      self::addIdAttribute($attributes, $name);
    }

      // NAME-Attribute
    self::addNameAttribute($attributes, $name);

      // CLASS-Attribute
    if (false === $searchAttributes['class']) {
      $attributes['class'] = (($type=='submit') ? 'submit' : 'input '.$type);
    }

    return self::create('input', $attributes);
  } 

  /**
   * Erstellt ein <label>-Tag
   *
   * @param string $for
   * @param string $content
   * @param array $attributes
   * @return Tag
   */
  static public function label($for, $content, $attributes=array()) {
    $searchAttributes = array('class'=>false);
    self::hasAttributes($attributes, $searchAttributes);

      // FOR-Attribute
    self::addForAttribute($attributes, $for);

      // Default CLASS-Attribute
    if (!$searchAttributes['class']) {
      $attributes['class'] = 'label';
    }

    $tag = self::content('label', $content, $attributes);
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
   * @param boolean $inputBeforeLabel Bestimmt, ob das <input>-Tag vor dem <label>-Tag steht, oder umgekehrt
   * @return string
   */
  static public function labeledInput($labelContent, $type, $name, $value, $inputAttributes=array(), $labelAttributes=array(), $inputBeforeLabel=true) {
    $input = self::input($type, $name, $value, $inputAttributes);
    
    if ($input->getAttribute('id') instanceof Attribute) {
      $for = $input->getAttribute('id')->getValue();
        // In '$for' kann 'self::$prefixId' schon behinhalten, das muss hier herausgefiltert werden
      if (self::$prefixId.'_' === substr($for, 0, (strlen(self::$prefixId)+1))) {
        $for = substr($for, (strlen(self::$prefixId)+1));
      }
      $label = self::label($for, $labelContent, $labelAttributes);
    } else {
      throw new TagException('Beim erstellen eines <label>-Tag wird ein id-Attribute benötigt!');
    }

    return ((boolean)$inputBeforeLabel) ? $input.$label : $label.$input;
  } 

  /**
   * Erstellt ein <textarea>-Tag
   *
   * @param string $name Name des Tags, über dem der Inhalt ausgelesen werden kann (name-Attribute)
   * @param string $content Content zwischen dem <textarea>- und </textarea>-Tag
   * @param array $params Additional parameter like 'class'
   * @return unknown
   */
  static public function textarea($name, $content='', $attributes=array()) {
    $searchAttributes = array('id'=>false, 'class'=>false, 'cols'=>false, 'rows'=>false);
    self::hasAttributes($attributes, $searchAttributes);

      // Id-Attribute
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

    $tag = self::content('textarea', $content, $attributes);
    return $tag->setHtmlentities(false);
  } 

  /**
   * Erstellt ein <input>-Tag vom Type "submit"
   * 
   * @param string $name
   * @param sting $value
   * @param array $attributes
   * @return Tag
   */
  static public function submit($name, $value='submit', $attributes=array()) {
    return self::input('submit', $name, $value, $attributes);
  } 
  
  /**
   * Erstellt ein <button>-Tag
   * 
   * @param unknown_type $type
   * @param unknown_type $name
   * @param unknown_type $value
   * @param unknown_type $attributes
   * @return unknown_type
   */
  static public function button($name, $value, $type='button', $attributes=array()) {
    $searchAttributes = array('id'=>false, 'class'=>false);
    self::hasAttributes($attributes, $searchAttributes);

    $attributes['type'] = $type;
    
    if (is_numeric($value) || !empty($value)) {
      $attributes['value'] = $value;
    }
    
      // ID-Attribute
    if (!$searchAttributes['id']) {
      self::addIdAttribute($attributes, $name);
    }
    
      // NAME-Attribute
    self::addNameAttribute($attributes, $name);
    
      // CLASS-Attribute
    if (false === $searchAttributes['class']) {
      $attributes['class'] = 'button '.$type;
    }
    
    return self::create('button', $attributes);
  } 
  
  
  /**********************************************************************
   * Auswahl-Tags *******************************************************
   **********************************************************************/
  
  /**
   * Erstellt, in Abhängigkeit von $type, Checkboxen, Radio-Buttons oder eine Select-Box
   *
   * @param array $items
   * @param string $name
   * @param string $type
   * @param array $attributes
   * @return Tag
   */
  static public function choices($items, $name, $type='radio', $attributes=array(), $asString=true) {
    $itemObj = array();

    if ($type === 'radio' || ($type === 'checkbox')) {
      
        // Radio- oder  Checkbox-Buttons
      foreach($items as $inputValue => $itemValue) {
        $inputAttributes = array();

        if (is_string($itemValue)) {
          $label = $itemValue;                                    // Inhalt des <label>-Tags
            
        } else if (is_numeric($itemValue)) {
          $label = $itemValue;                                    // Inhalt des <label>-Tags
        
        } else if (is_array($itemValue)) {
          if (isset($itemValue['value'])) {                       // Inhalt des <label>-Tags
            $label = $itemValue['value'];
            unset($itemValue['value']);
          } else {
            $label = array_shift($itemValue);
          }
          
          if (!empty($itemValue)) {                               // Der Rest des Array, sind Attribute für das <input>-Tag
            if (isset($itemValue['attribute'])) {
              $inputAttributes = $itemValue['attribute'];
              unset($itemValue['attribute']);
            } else if (isset($itemValue['attributes'])) {
              $inputAttributes = $itemValue['attributes'];
              unset($itemValue['attributes']);
            } else {
              $inputAttributes = array_shift($itemValue);
            }
          }
        }

        $searchAttributes = array('id'=>false);
        self::hasAttributes($inputAttributes, $searchAttributes);

          // Default ID-Attribute
        if (false === $searchAttributes['id']) {
          self::addIdAttribute($inputAttributes, $inputValue);
        }

          // :ACHTUNG: Folgendes ist NUR bei einer "Mehrfachauswahl" zubeachten!
          // Wenn eine PrefixId gesetzt ist, wird $name schon ein eckige Klammern ('[]') gepackt,
          // also müssen die Klammern für die $name nicht so '[]' sondern so '][' angefügt werden!
          // Mit PrefixId: dummy[$name] => dummy[$name.']['.] => dummy[$name][]
        if ($type == 'radio') {
          $inputName = $name;
        } else {
          if (self::hasPrefixId()) {
            $inputName = $name.'][';              // 'name'-Attribute im <input>-Tag
          } else {
            $inputName = $name.'[]';
          }
        }
        $itemObj[] = self::labeledInput($label, $type, $inputName, $inputValue, $inputAttributes);
      }
      
    } else if ($type === 'select') {
        // :TODO: 
        // <optgroup>-Tags sind noch nicht berücksichtigt
      foreach($items as $optionValue => $optionData) {
        $optionAttributes = array();
        
        if (is_string($optionData)) {
          $optionContent = $optionData;                           // Inhalt des <label>-Tags
        
        } else if (is_numeric($optionData)) {
          $optionContent = $optionData;                           // Inhalt des <label>-Tags
          
        } else if (is_array($optionData)) {                       // Inhalt des <label>-Tags
          if (isset($optionData['content'])) {
            $optionContent = $optionData['content'];
            unset($optionData['content']);
          } else if (isset($optionData['optionContent'])) {
            $optionContent = $optionData['optionContent'];
            unset($optionData['optionContent']);
          } else {
            $optionContent = array_shift($optionData);
          }
          
          if (!empty($optionData)) {                              // Der Rest des Array, sind Attribute für das <input>-Tag
            if (isset($optionData['attribute'])) {
              $optionAttributes = $optionData['attribute'];
              unset($itemValue['attribute']);
            } else if (isset($optionData['attributes'])) {
              $optionAttributes = $optionData['attributes'];
              unset($optionData['attributes']);
            } else {
              $optionAttributes = array_shift($optionData);
            }
          }
        }
        
        $searchAttributes = array('value'=>false);
        self::hasAttributes($optionAttributes, $searchAttributes);
        
          // Default VALUE-Attribute
        if (false === $searchAttributes['value']) {
          $optionAttributes['value'] = $optionValue;
        }
        $optionTags[] = self::content('option', $optionContent, $optionAttributes);
      }
      
      $searchAttributes = array('id'=>false);
      self::hasAttributes($attributes, $searchAttributes);
        
        // Default ID-Attribute
      if (false === $searchAttributes['id']) {
        self::addIdAttribute($attributes, $name);
      }
      
        // NAME-Attribute
      self::addNameAttribute($attributes, $name);
      
      $itemObj = self::content('select', $optionTags, $attributes);
    }
    
    if ($asString) {
      foreach($itemObj as $item) {
        $result.=$item;        
      }
    } else {
      $result = $itemObj;
    }
    
    return $result;
  } 

  /**
   * Alias für createChoiceTag(..., ..., $type='radio', ...)
   *
   * @param array $items
   * @param string $name
   * @param array $attributes
   * @return Tag
   */
  static public function radio($items, $name, $attributes=array(), $asString=true) {
    return self::choices($items, $name, 'radio', $attributes, $asString);
  } 

  /**
   * Alias für createChoiceTag(..., ..., $type='checkbox', ...)
   *
   * @param array $items
   * @param string $name
   * @param array $attributes
   * @return Tag
   */
  static public function checkbox($items, $name, $attributes=array(), $asString=true) {
    return self::choices($items, $name, 'checkbox', $attributes, $asString);
  } 

  /**
   * Alias für createChoiceTag(..., ..., $type='select', ...)
   *
   * @param array $items
   * @param string $id
   * @param array $attributes
   * @return Tag
   */
  static public function select($items, $id, $attributes=array(), $asString=false) {
    return self::choices($items, $id, 'select', $attributes);
  } 
  
  
  /**********************************************************************
   * Listen-Tags ********************************************************
   **********************************************************************/
  
  /**
   * Erstellt, in Abhängikeit von $listType, eine <ul>-, <ol>- oder <dl>-List.
   * Man kann auch die direkten Methoden dafür aufrüfen.
   *
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return Tag
   */
  static public function lists($listItems, $listType='ul', $attritbutes=array()) {
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

          if ($value instanceof AbstractTag) {
              // Es wurde ein Array mit Tags (<li>-Tags) übergeben (hoffentlich)
            if ('li' == $value->getName()) {
              $items[] = $value;
            } else {
              throw new TagTypeException('Es sind in dem Listentyp \''.$listType.'\' nur \'<li>\'-Tags erlaubt!');
            }
          } else {
            if (is_array($value)) {
                // In $value stecken die Attribute und in $key der Content für das kommende <li>-Tag
              $itemObj = self::content('li', $key, $value);
            } else {
                // In $value steckt der Inhalt für das kommende <li>-Tag.
                // Attribute sind nicht angegeben.
              $itemObj = self::content('li', $value);
            }
            $items[] = $itemObj;
          }
          break;

        case 'dl':
          if ($value instanceof AbstractTag) {
            if ('dt' == $value->getName()) {              // Es wurde ein Array mit Tags (<dt>-Tags) übergeben (hoffentlich)
              $items[] = $value;
            } else if ('dd' == $value->getName()) {       // Es wurde ein Array mit Tags (<dd>-Tags) übergeben (hoffentlich)
              $items[] = $value;
            } else {
              throw new TagTypeException('Es sind in dem Listentyp \''.$listType.'\' nur \'<dt>\' und \'<dd>\'-Tags erlaubt!');
            }
          } else {
            if (is_array($value)) {
              foreach($value as $dlItemName => $dlItemData) {
                if ('dt' == $dlItemName || 'dd' == $dlItemName) {
                  if (is_array($dlItemData)) {
                      // In $dlItemData stecken die Attribute und in $key der Content für das kommende <li>-Tag
                    $dlItemContent    = isset($dlItemData['content'])    ? $dlItemData['content']    : array_shift($dlItemData);
                    $dlItemAttributes = isset($dlItemData['attributes']) ? $dlItemData['attributes'] : array_shift($dlItemData);
                    $itemObj = self::content($dlItemName, $dlItemContent, $dlItemAttributes);
                  } else {
                      // In $dlItemData steckt der Inhalt für das kommende <dt>- oder <dd>-Tag.
                      // Attribute sind nicht angegeben.
                    $itemObj = self::content($dlItemName, $dlItemData);
                  }
                  $items[] = $itemObj;
                } else {
                  throw new TagTypeException('Es sind in dem Listentyp \''.$listType.'\' nur \'<dt>\' und \'<dd>\'-Tags erlaubt!');
                }
              }
            }
          }
          break;

        default:
          throw new TagTypeException('Der angegebene Listentyp ($listType=\''.$listType.'\') existiert nicht!');
      }
    }

    $tag = self::content($listType, $items, $attritbutes);
    return $tag->setHtmlentities(false);
  } 

  /**
   * Alias für createListTag(..., 'ul', ...)
   *
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return Tag
   */
  static public function ul($listItems, $attritbutes=array()) {
    return self::lists($listItems, 'ul', $attritbutes);
  } 

  /**
   * Alias für createListTag(..., 'ol', ...)
   *
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return Tag
   */
  static public function ol($listItems, $attritbutes=array()) {
    return self::lists($listItems, 'ol', $attritbutes);
  } 

  /**
   * Alias für createListTag(..., 'dl', ...)
   *
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return Tag
   */
  static public function dl($listItems, $attritbutes=array()) {
    return self::lists($listItems, 'dl', $attritbutes);
  } 

  /**
   * Erstellt ein <li>-Tag
   *
   * @param mixed $content
   * @param mixed $attritbutes
   * @return Tag
   */
  static public function li($content, $attritbutes=array()) {
    return self::content('li', $content, $attritbutes);
  } 

  /**
   * Erstellt ein <dt>-Tag
   *
   * @param mixed $content
   * @param mixed $attritbutes
   * @return Tag
   */
  static public function dt($content, $attritbutes=array()) {
    return self::content('li', $content, $attritbutes);
  } 

  /**
   * Erstellt ein <dd>-Tag
   *
   * @param mixed $content
   * @param mixed $attritbutes
   * @return Tag
   */
  static public function dd($content, $attritbutes=array()) {
    return self::content('li', $content, $attritbutes);
  } 
  
  
  /**********************************************************************
   * Physische Auszeichnung-Tags ****************************************
   **********************************************************************/
  
  /**
   * Erstellt ein <b>-Tag, dies zeichnet einen Text als fett aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function b($content, $attributes=array()) {
    return self::content('b', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <big>-Tag, dies zeichnet einen Text größer als normal aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function big($content, $attributes=array()) {
    return self::content('big', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <i>-Tag, dies zeichnet einen Text als kursiv aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function i($content, $attributes=array()) {
    return self::content('i', $content, $attributes);
  } 

  /**
   * Erstellt ein <s>-Tag, dies zeichnet einen Text als durchgestrichen aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function s($content, $attributes=array()) {
    return self::content('s', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <small>-Tag, dies zeichnet einen Text kleiner als normal aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function small($content, $attributes=array()) {
    return self::content('small', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <strike>-Tag, dies zeichnet einen Text als durchgestrichen aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function strike($content, $attributes=array()) {
    return self::content('strike', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <sub>-Tag, dies zeichnet einen Text als tiefgestellt aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function sub($content, $attributes=array()) {
    return self::content('sub', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <sup>-Tag, dies zeichnet einen Text als hochgestellt aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function sup($content, $attributes=array()) {
    return self::content('sup', $content, $attributes);
  } 

  /**
   * Erstellt ein <tt>-Tag, dies zeichnet einen Text als dicktengleich formatiert aus (tt = Teletyper = Fernschreiber)
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function tt($content, $attributes=array()) {
    return self::content('tt', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <u>-Tag, dies zeichnet einen Text als unterstrichen aus
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function u($content, $attributes=array()) {
    return self::content('u', $content, $attributes);
  } 
  
  
  /**********************************************************************
   * Logischen Auszeichnung-Tags ****************************************
   **********************************************************************/
  
  /**
   * Erstellt ein <abbr>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist eine Abkürzung"
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function abbr($content, $attributes=array()) {
    return self::content('abbr', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <acronym>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist ein Akronym".
   * Akronyme sind besondere Abkürzungen, die aus den Anfangsbuchstaben mehrerer (Teil-)Wörter gebildet werden. 
   * Sie werden im Deutschen in der Regel ohne Punkte gebildet ("Lkw"). Akronyme lassen sich darüber hinaus meist als Wort aussprechen (z.B. "NATO").
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function acronym($content, $attributes=array()) {
    return self::content('acronym', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <cite>-Tag, dies 
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function cite($content, $attributes=array()) {
    return self::content('cite', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <code>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist Quelltext"
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function code($content, $attributes=array()) {
    return self::content('code', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <dfn>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist eine Definition".
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function dfn($content, $attributes=array()) {
    return self::content('dfn', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <em>-Tag, dies zeichnet einen Text aus als betonten, wichtigen Text ("emphatisch")
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function em($content, $attributes=array()) {
    return self::content('em', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <kbd>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies stellt eine Benutzereingabe dar"
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function kbd($content, $attributes=array()) {
    return self::content('kbd', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <q cite="..">-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist ein Zitat mit Quellenangabe"
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function qCite($cite, $content, $attributes=array()) {
    $attributes['cite'] = $cite;
    return self::content('q', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <samp>-Tag, dies zeichnet einen Text aus mit der Bedeutung "Dies ist ein Beispiel". Im engeren Sinne können auch Beispiel-Ausgaben von Programmen und Scripten auf diese Weise ausgezeichnet werden.
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function samp($content, $attributes=array()) {
    return self::content('samp', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <strong>-Tag, dies zeichnet einen Text aus mit der Bedeutung "stark betont" (Steigerung von "em")
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function strong($content, $attributes=array()) {
    return self::content('strong', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <var>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist eine Variable oder ein variabler Name"
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function variable($content, $attributes=array()) {
    return self::content('var', $content, $attributes);
  } 
  
  
  /**********************************************************************
   * Headline-Tags ******************************************************
   **********************************************************************/

  /**
   * Erstellt in Abhängigkeit vom $type ein <h1>- bis <h6>-Tag.
   * 
   * @param mixed $content
   * @param string $type
   * @param array $attributes
   * @return Tag
   */
  static public function headline($content, $type='h1', $attributes=array()) {
    $type = strtolower($type);
    switch($type) {
      case 'h1':
      case 'h2':
      case 'h3':
      case 'h4':
      case 'h5':
      case 'h6':
        return self::content($type, $content, $attributes);
        break;
      default:
       throw new TagTypeException('Der angegebene Headline vom Type \''.$type.'\' existiert nicht! Nur \'<h1>\'- bis \'<h6>\'-Tags sind erlaubt!');
    }
  } 
  
  /**
   * Erstellt ein <h1>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function h1($content, $attributes=array()) {
    return self::headline($content, 'h1', $attributes);
  } 
  
  /**
   * Erstellt ein <h2>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function h2($content, $attributes=array()) {
    return self::headline($content, 'h2', $attributes);
  } 
  
  /**
   * Erstellt ein <h3>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function h3($content, $attributes=array()) {
    return self::headline($content, 'h3', $attributes);
  } 
  
  /**
   * Erstellt ein <h4>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function h4($content, $attributes=array()) {
    return self::headline($content, 'h4', $attributes);
  } 
  
  /**
   * Erstellt ein <h5>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function h5($content, $attributes=array()) {
    return self::headline($content, 'h5', $attributes);
  } 
  
  /**
   * Erstellt ein <h6>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function h6($content, $attributes=array()) {
    return self::headline($content, 'h6', $attributes);
  } 
  
  
  /**********************************************************************
   * Absatz-Tags (BLOCK) ************************************************
   **********************************************************************/
  
  /**
   * Erstellt ein <blockquote>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function blockquote($content, $attributes=array()) {
    return self::content('blockquote', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <center>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function center($content, $attributes=array()) {
    return self::content('center', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <div>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function div($content, $attributes=array()) {
    return self::content('div', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <p>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function p($content, $attributes=array()) {
    return self::content('p', $content, $attributes);
  } 

  /**
   * Erstellt ein <param>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function param($content, $attributes=array()) {
    return self::content('param', $content, $attributes);
  } 
  
  /**
   * Erstellt ein <pre>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function pre($content, $attributes=array()) {
    return self::content('pre', $content, $attributes);
  } 
  
  
  /**********************************************************************
   * Absatz-Tags (INLINE) ***********************************************
   **********************************************************************/
  
  /**
   * Erstellt ein <span>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function span($content, $attributes=array()) {
    return self::content('span', $content, $attributes);
  } 
  
  
  /**********************************************************************
   * Tabellen-Tags ******************************************************
   **********************************************************************/

  /**
   * Erstellt ein <table>-Tag
   * 
   * @param mixed $content
   * @param array $attributes
   * @return Tag
   */
  static public function table($content, $attributes=array()) {
    $tag = self::createTag('table', $attributes);
    $tag->setContent($content);
    return $tag;
  } 

  static public function thead($content, $attributes=array()) {
    $tag = self::createTag('thead', $attributes);
    $tag->setContent($content);
    return $tag;
  } 
  
  static public function tbody($content, $attributes=array()) {
    $tag = self::createTag('tbody', $attributes);
    $tag->setContent($content);
    return $tag;
  } 
  
  static public function tfoot($content, $attributes=array()) {
    $tag = self::createTag('tfoot', $attributes);
    $tag->setContent($content);
    return $tag;
  } 

  static public function tr($content, $attributes=array()) {
    $tag = self::createTag('tr', $attributes);
    $tag->setContent($content);
    return $tag;
  } 
  
  static public function td($content, $attributes=array()) {
    $tag = self::createTag('td', $attributes);
    $tag->setContent($content);
    return $tag;
  } 

  



  /**********************************************************************
   * Hilfsfunktionen
   **********************************************************************/

  /**
   * Fügt das ID-Attribute den in $attributes übergebenen Attributen hinzu.
   *
   * @param array $attributes Referenz mit den Attributen
   * @param string $value Wert des ID-Attributes
   * @return void
   */
  static public function addIdAttribute(&$attributes, $value) {
    $id = preg_replace('/\s/', '_', $value);                            // Alle Whitespaces durch einen '_' ersetzten, da Whitespaces im 'name'- und 'id'-Attribute nicht zulässig bzw. 'unschön' sind
    $id = preg_replace('/\]\[/', '_', $id);                             // Alle "][", welches in PHP zu einen Array geparst wird, durch einen '_' ersetzten
    $id = preg_replace('/\[/', '_', $id);                               //
    $id = preg_replace('/\]/', '_', $id);                               //
    $attributes['id'] = ((self::hasPrefixId()) ? self::$prefixId.'_'  : '') . $id;
  } 

  /**
   * Fügt das FOR-Attribute in den $attributes übergebenen Attributen hinzu.
   *
   * @param array $attributes Referenz mit den Attributen
   * @param string $value Wert des ID-Attributes
   * @return void
   */
  static public function addForAttribute(&$attributes, $value) {
    $for = preg_replace('/\s/', '_', $value);                           // Alle Whitespaces durch einen '_' ersetzten, da Whitespaces im 'name'- und 'id'-Attribute nicht zulässig bzw. 'unschön' sind
    $for = preg_replace('/\]\[/', '_', $for);                           // Alle "][", welches in PHP zu einen Array geparst wird, durch einen '_' ersetzten
    $for = preg_replace('/\[/', '_', $for);                             //
    $for = preg_replace('/\]/', '_', $for);                             //
    $attributes['for'] = ((self::hasPrefixId()) ? self::$prefixId.'_'  : '') . $for;
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
   * Gibt die prefixId zurück.
   * @return string
   */
  static public function getPrefixId() {
    return self::$prefixId;
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
        }
      }
    }
    return $has;
  } 
  
  
  
  
  /**********************************************************************
   * Aus Kompatibilitäts-Gründen
   **********************************************************************/
  
  /**
   * Alias für create()
   *
   * @param string $name Name des Tags, z.B. 'a', 'input', 'br' etc.
   * @param array $attributes Array mit den zu erstellenden Attributen.
   * @return Tag
   */
  static public function createTag($name, $attributes=array()) {
    return self::create($name, $attributes);
  } 
  
  /**
   * Alias für content()
   *
   * @param string $tagName Name des Tags, z.B. 'a', 'p', 'div'
   * @param string $content Inhalt der zwischen den öffnenden und schließenden Tag stehen soll.
   * @param array $attributes Attribute, die dem Tag hinzugefügt werden sollen
   * @return Tag
   */
  static public function createContentTag($tagName, $content, $attributes=array()) {
    return self::content($tagName, $content, $attributes);
  } 

  /**
   * Alias für a()
   *
   * @param string $href Wert des href-Attribute
   * @param string $content Text, der Links
   * @param array $attributes Zusätliche Attribute für das Tag
   * @return Tag
   */
  static public function createATag($href, $content, $attributes=array()) {
    return self::a($href, $content, $attributes);
  } 
  
  /**
   * Alias für form()
   *
   * @param string $action URL an der das <form> versendet wird
   * @param string $formContent Content zwischen dem <form>- und </form>-Tag
   * @param array $params Additional parameter like 'class'
   * @return Tag
   */
  static public function createFormTag($action, $content, $attributes=array()) {
    return self::form($action, $content, $attributes);
  } 
  
  /**
   * Alias für input()
   *
   * @param string $type
   * @param string $name
   * @param array $attributes
   * @return Tag
   */
  static public function createInputTag($type, $name, $value='', $attributes=array()) {
    return self::input($type, $name, $value, $attributes);
  } 
  
  /**
   * Alias für label()
   *
   * @param string $for
   * @param string $content
   * @param array $attributes
   * @return Tag
   */
  static public function createLabelTag($for, $content, $attributes=array()) {
    return self::label($for, $content, $attributes);
  } 
  
  /**
   * Alias für labeledInput()
   *
   * @param string $labelContent Beschriftung des Label-Tags
   * @param string $type Type des Input-Tags, z.B. 'checkbox'
   * @param string $name Name des Input-Tags
   * @param string $value Wert Input-Tags, welcher übermittelt wird
   * @param array $inputAttributes Optionale Attribute die dem Input-Tag hinzugefügt werden können
   * @param array $labelAttributes Optionale Attribute die dem Label-Tag hinzugefügt werden können
   * @param boolean $inputBeforeLabel Bestimmt, ob das <input>-Tag vor dem <label>-Tag steht, oder umgekehrt
   * @return string
   */
  static public function createLabeledInputTag($labelContent, $type, $name, $value, $inputAttributes=array(), $labelAttributes=array(), $inputBeforeLabel=true) {
    return self::labeledInput($labelContent, $type, $name, $value, $inputAttributes, $labelAttributes, $inputBeforeLabel);
  } 


  /**
   * Alias für textarea()
   *
   * @param string $name Name des Tags, über dem der Inhalt ausgelesen werden kann (name-Attribute)
   * @param string $content Content zwischen dem <textarea>- und </textarea>-Tag
   * @param array $params Additional parameter like 'class'
   * @return unknown
   */
  static public function createTextareaTag($name, $content='', $attributes=array()) {
    return self::textarea($name, $content, $attributes);
  } 
  
  /**
   * Alias für fieldset()
   *
   * @param $legendContent
   * @param $fieldsetContent
   * @param $fieldsetAttributes
   * @param $legendAttributes
   * @return unknown_type
   */
  static public function createFieldsetTag($fieldsetContent, $legendContent=NULL, $fieldsetAttributes=array(), $legendAttributes=array()) {
    return self::fieldset($fieldsetContent, $legendContent, $fieldsetAttributes, $legendAttributes);
  } 

  /**
   * Alias für choices()
   *
   * @param array $items
   * @param string $name
   * @param string $type
   * @param array $attributes
   * @return Tag
   */
  static public function createChoiceTag($items, $name, $type='radio', $attributes=array()) {
    return self::choices($items, $name, $type, $attributes);
  } 

  /**
   * Alias für lists()
   *
   * @param array $items
   * @param string $type
   * @param array $attributes
   * @return Tag
   */
  static public function createListTag($items, $type='ul', $attritbutes=array()) {
    return self::lists($items, $type, $attritbutes);
  } 
  
  
  
  
  
  static public function createTagsByArray($array) {
    /**
     * 1.) Gibt es für den Key eine Factory?
     * 1.1.) Wenn ja, dann
     */

    $content = '';
    foreach ($array as $key => $factoryArray) {
      foreach ($factoryArray as $factory => $factoryData) {
        // Umwandeln von 'dies_ist_eine_funktion' zu 'DiesIstEineFunktion'
        $factoryCamelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $factory)));
        $factoryName = 'create'. $factoryCamelCase . 'Tag';

        if (in_array($factoryName, get_class_methods('Tag')) ) {
          // Es existiert eine passende Factory-Methode
          //					$content.= '<h3>Factory: '.$factoryName.'</h3>';
          	
          switch($factoryName) {
            case 'createATag':
              $content.= self::createATag($factoryData['href'], $factoryData['content'], $factoryData['attributes']);
              break;
              	
            case 'createFormTag':
              //						$content.= Tag::createFormTag($action, $factoryData['content'], $factoryData['attributes']);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createInputTag':
              $content.= self::createInputTag($factoryData['type'], $factoryData['name'], $value, $factoryData['attributes']);
              break;

            case 'createLabelTag':
              //						$content.= Tag::createLabelTag($for, $factoryData['content'], $factoryData['attributes']);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createLabeledInputTag':
              //						$content.= Tag::createLabeledInputTag($labelContent, $factoryData['type'], $factoryData['name'], $value, $inputAttributes, $labelAttributes);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createTextareaTag':
              //						$content.= Tag::createTextareaTag($factoryData['name'], $factoryData['content'], $factoryData['attributes']);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createFieldsetTag':
              //						$content.= Tag::createFieldsetTag($fieldsetContent, $legendContent, $fieldsetAttributes, $legendAttributes);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createListTag':
              $content.= self::createListTag($factoryData['content'], $factoryData['type'], $factoryData['attributes']);
              break;

            case 'createChoiceTag':
              self::createChoiceTag($factoryData['content'], $factoryData['name'], $factoryData['type'], $factoryData['attributes']);
              $br = self::createTag('br');
              foreach(self::createChoiceTag($factoryData['content'], $factoryData['name'], $factoryData['type'], $factoryData['attributes']) as $item) {
                $content.= $item . $br;
              }
              	
              break;
              	
            default:
              throw new TagException('Diese Factory (Tag::'.$factoryName.') ist nicht vorhanden!');
          }
          	
        } else {
            // Keine Factory-Methode vorhanden.
          //$content.= '<h3>Self: '.$factory.'</h3>';
          if (is_array($factoryData)) {
              // Es sind weitere Daten/Konfigurationen vorhanden.
          } else {
              // Es handelt sich anscheinend um ein Standalone-Tag
            $content.= self::createTag($factory);
          }
        }
      }
    }

    return $content;
  }
}

