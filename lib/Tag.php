<?php
/**
 *
 *
 * @package TagHelper
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @copyright Timo Strotmann, 18 October, 2010
**/

require_once 'AttributeFactory.php';
require_once 'TagType.php';

// ============================================================================
// = Exception-Klassen ====================================================== =
// ============================================================================
/**
 * Exception Klasse für Tag-Fehler
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class TagException extends Exception {
}


// ============================================================================
// = AttributeTypeFactory-Klasse ============================================ =
// = -> zum erstellen/ermitteln eines Tags ================================== =
// ============================================================================
/**
 * Tag
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
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
   * [X] table
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
  **/

  static protected $prefixId = '';
  static protected $defaultTextareaCols = 20;
  static protected $defaultTextareaRows = 5;

  static protected $imagePath = 'images/';
  static protected $cssPath   = 'css/';


  /**
   * Erstellt Tag und fügt diesem, die übergebenen Attribute hinzu.
   * Das Array mit den Attributen ist wie folgt aufgebaut:
   *   $data = array(
   *     array('name'=>'width', 'value'=>'10'),
   *     array('name'=>'style', 'value'=>'color:#333', 'options'=>array('addSlashes'=>FALSE))
   *   );
   * Wobei die Keys, weggelassen werden können, dann ist die Reihenfolge aber 'name'=0, 'value'=1, 'options'=2!
   *
   * @param string $name Name des Tags, z.B. 'a', 'input', 'br' etc.
   * @param array $attributes Array mit den zu erstellenden Attributen.
   * @return Tag
  **/
  static public function create($name, $attributes=array()) {
    if (array_search($name, trimExplode(STANDALONE_TAGS)) === FALSE) {
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
  **/
  static  public function content($tagName, $content, $attributes=array()) {
    $tag = self::create($tagName, $attributes);
    $tag->setContent($content);

    return $tag;
  }


  // ==========================================================================
  // = Standalone-Tags ====================================================== =
  // ==========================================================================
  /**
   * Erstelt ein <br>-Tag
   *
   * @param array $attributes
   * @return Tag
  **/
  static public function br($attributes=array()) {
    return self::create('br', $attributes);
  }

  /**
   * Erstelt ein <hr>-Tag
   *
   * @param array $attributes
   * @return Tag
  **/
  static public function hr($attributes=array()) {
    return self::create('hr', $attributes);
  }

  /**
   * Erstellt ein <img>-Tag
   *
   * @param string $src
   * @param array $attributes
   * @return Tag
  **/
  static public function img($src, $attributes=array()) {
    return self::createTag('img', array_merge($attributes, array('src'=>$src)));
  }


  // ==========================================================================
  // = Modular-Tags =========================================================== =
  // ==========================================================================
  /**
   * Erstellt ein <a>-Tag und fügt dem den übergebenen Inhalt, sowie die übergebenen Attribute hinzu.
   *
   * @param string $href Wert des href-Attribute
   * @param string $content Text, der Links
   * @param array $attributes Zusätliche Attribute für das Tag
   * @return Tag
  **/
  static public function a($href, $content, $attributes=array()) {
    $attributes['href'] = $href;
    return self::content('a', $content, $attributes);
  }

  /**
   * Erstellt ein <fieldset>-Tag. Bei Angabe eines $legendContent, wird zudem noch ein <legend>-Tag (in Form des Fieldset-Content) hinzugefügt.
   *
   * @param mixed $legendContent
   * @param mixed $fieldsetContent
   * @param array $fieldsetAttributes
   * @param array $legendAttributes
   * @return Tag
  **/
  static public function fieldset($fieldsetContent, $legendContent=NULL, $fieldsetAttributes=array(), $legendAttributes=array()) {
    $searchAttributes = array('class'=>FALSE);
    self::hasAttributes($fieldsetAttributes, $searchAttributes);

      // Default CLASS-Attribute
    if (!$searchAttributes['class']) {
      $fieldsetAttributes['class'] = 'fieldset';
    }

    $fieldset = self::create('fieldset', $fieldsetAttributes);

    if (NULL !== $legendContent) {
      // <legend>-Tag erstellen und dem <fieldset> hinzufügen
      $legend = self::legend($legendContent, $legendAttributes);
      $legend->setHtmlentities(FALSE);
      $fieldset->setContent($legend);
    }

    return $fieldset->setContent($fieldsetContent)->setHtmlentities(FALSE);
  }

  /**
   * Erstellt ein <lagend>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function legend($content, $attributes=array()) {
    return self::content('legend', $content, $attributes);
  }


  // ==========================================================================
  // = Formular-Tags ======================================================== =
  // ==========================================================================
  /**
   * Erstellt ein <form>-Tag
   *
   * @param string $action URL an der das <form> versendet wird
   * @param string $formContent Content zwischen dem <form>- und </form>-Tag
   * @param array $params Additional parameter like 'class'
   * @return Tag
  **/
  static public function form($action, $content, $attributes=array()) {
    $searchAttributes = array('method'=>FALSE);
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
    $tag->setHtmlentities(FALSE);
    return $tag;
  }

  /**
   * Erstellt ein <input>-Tag.
   *
   * @param string $type
   * @param string $name
   * @param array $attributes
   * @return Tag
  **/
  static public function input($type, $name, $value='', $attributes=array()) {
    $searchAttributes = array('id'=>FALSE, 'class'=>FALSE);
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
    if (FALSE === $searchAttributes['class']) {
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
  **/
  static public function label($for, $content, $attributes=array()) {
    $searchAttributes = array('class'=>FALSE);
    self::hasAttributes($attributes, $searchAttributes);

      // FOR-Attribute
    self::addForAttribute($attributes, $for);

      // Default CLASS-Attribute
    if (!$searchAttributes['class']) {
      $attributes['class'] = 'label';
    }

    $tag = self::content('label', $content, $attributes);
    return $tag->setHtmlentities(FALSE);
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
  **/
  static public function labeledInput($labelContent, $type, $name, $value, $inputAttributes=array(), $labelAttributes=array(), $inputBeforeLabel=TRUE) {
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
   * @return Tag
  **/
  static public function textarea($name, $content='', $attributes=array()) {
    $searchAttributes = array('id'=>FALSE, 'class'=>FALSE, 'cols'=>FALSE, 'rows'=>FALSE);
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
    return $tag->setHtmlentities(FALSE);
  }

  /**
   * Erstellt ein <input>-Tag vom Type "submit"
   *
   * @param string $name
   * @param sting $value
   * @param array $attributes
   * @return Tag
  **/
  static public function submit($name, $value='submit', $attributes=array()) {
    return self::input('submit', $name, $value, $attributes);
  }

  /**
   * Erstellt ein <button>-Tag
   *
   * @param string $name
   * @param string $value
   * @param string $type
   * @param array $attributes
   * @return Tag
  **/
  static public function button($name, $value, $type='button', $attributes=array()) {
    $searchAttributes = array('id'=>FALSE, 'class'=>FALSE);
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
    if ($searchAttributes['class'] === FALSE) {
      $attributes['class'] = 'button '.$type;
    }

    return self::create('button', $attributes);
  }


  // ==========================================================================
  // = Auswahl-Tags ========================================================= =
  // ==========================================================================
  /**
   * Erstellt, in Abhängigkeit von $type, Checkboxen, Radio-Buttons oder eine Select-Box
   *
   * @param array $items
   * @param string $name
   * @param string $type
   * @param array $attributes
   * @return Tag
  **/
  static public function choices($items, $name, $type='radio', $attributes=array(), $asString=TRUE) {
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

        $searchAttributes = array('id'=>FALSE);
        self::hasAttributes($inputAttributes, $searchAttributes);

          // Default ID-Attribute
        if (FALSE === $searchAttributes['id']) {
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

        $searchAttributes = array('value'=>FALSE);
        self::hasAttributes($optionAttributes, $searchAttributes);

          // Default VALUE-Attribute
        if (FALSE === $searchAttributes['value']) {
          $optionAttributes['value'] = $optionValue;
        }
        $optionTags[] = self::content('option', $optionContent, $optionAttributes);
      }

      $searchAttributes = array('id'=>FALSE);
      self::hasAttributes($attributes, $searchAttributes);

        // Default ID-Attribute
      if (FALSE === $searchAttributes['id']) {
        self::addIdAttribute($attributes, $name);
      }

        // NAME-Attribute
      self::addNameAttribute($attributes, $name);

      $itemObj = self::content('select', $optionTags, $attributes);
    }

    $result = '';
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
  **/
  static public function radio($items, $name, $attributes=array(), $asString=TRUE) {
    return self::choices($items, $name, 'radio', $attributes, $asString);
  }

  /**
   * Alias für createChoiceTag(..., ..., $type='checkbox', ...)
   *
   * @param array $items
   * @param string $name
   * @param array $attributes
   * @return Tag
  **/
  static public function checkbox($items, $name, $attributes=array(), $asString=TRUE) {
    return self::choices($items, $name, 'checkbox', $attributes, $asString);
  }

  /**
   * Alias für createChoiceTag(..., ..., $type='select', ...)
   *
   * @param array $items
   * @param string $id
   * @param array $attributes
   * @return Tag
  **/
  static public function select($items, $id, $attributes=array(), $asString=FALSE) {
    return self::choices($items, $id, 'select', $attributes);
  }


  // ==========================================================================
  // = Listen-Tags ========================================================== =
  // ==========================================================================
  /**
   * Erstellt, in Abhängikeit von $listType, eine <ul>-, <ol>- oder <dl>-List.
   * Man kann auch die direkten Methoden dafür aufrüfen.
   *
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return Tag
  **/
  static public function lists($listItems, $listType='ul', $attritbutes=array()) {
    $searchAttributes = array('id'=>FALSE, 'class'=>FALSE);
    self::hasAttributes($attritbutes, $searchAttributes);

      // Default CLASS-Attribute
    if (FALSE === $searchAttributes['class']) {
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
    return $tag->setHtmlentities(FALSE);
  }

  /**
   * Alias für createListTag(..., 'ul', ...)
   *
   * @param $listItems
   * @param $listType
   * @param $listAttritbutes
   * @param $itemAttributes
   * @return Tag
  **/
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
  **/
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
  **/
  static public function dl($listItems, $attritbutes=array()) {
    return self::lists($listItems, 'dl', $attritbutes);
  }

  /**
   * Erstellt ein <li>-Tag
   *
   * @param mixed $content
   * @param mixed $attritbutes
   * @return Tag
  **/
  static public function li($content, $attritbutes=array()) {
    return self::content('li', $content, $attritbutes);
  }

  /**
   * Erstellt ein <dt>-Tag
   *
   * @param mixed $content
   * @param mixed $attritbutes
   * @return Tag
  **/
  static public function dt($content, $attritbutes=array()) {
    return self::content('li', $content, $attritbutes);
  }

  /**
   * Erstellt ein <dd>-Tag
   *
   * @param mixed $content
   * @param mixed $attritbutes
   * @return Tag
  **/
  static public function dd($content, $attritbutes=array()) {
    return self::content('li', $content, $attritbutes);
  }


  // ==========================================================================
  // = Physische Auszeichnung-Tags ========================================== =
  // ==========================================================================
  /**
   * Erstellt ein <b>-Tag, dies zeichnet einen Text als fett aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function b($content, $attributes=array()) {
    return self::content('b', $content, $attributes);
  }

  /**
   * Erstellt ein <big>-Tag, dies zeichnet einen Text größer als normal aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function big($content, $attributes=array()) {
    return self::content('big', $content, $attributes);
  }

  /**
   * Erstellt ein <i>-Tag, dies zeichnet einen Text als kursiv aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function i($content, $attributes=array()) {
    return self::content('i', $content, $attributes);
  }

  /**
   * Erstellt ein <s>-Tag, dies zeichnet einen Text als durchgestrichen aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function s($content, $attributes=array()) {
    return self::content('s', $content, $attributes);
  }

  /**
   * Erstellt ein <small>-Tag, dies zeichnet einen Text kleiner als normal aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function small($content, $attributes=array()) {
    return self::content('small', $content, $attributes);
  }

  /**
   * Erstellt ein <strike>-Tag, dies zeichnet einen Text als durchgestrichen aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function strike($content, $attributes=array()) {
    return self::content('strike', $content, $attributes);
  }

  /**
   * Erstellt ein <sub>-Tag, dies zeichnet einen Text als tiefgestellt aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function sub($content, $attributes=array()) {
    return self::content('sub', $content, $attributes);
  }

  /**
   * Erstellt ein <sup>-Tag, dies zeichnet einen Text als hochgestellt aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function sup($content, $attributes=array()) {
    return self::content('sup', $content, $attributes);
  }

  /**
   * Erstellt ein <tt>-Tag, dies zeichnet einen Text als dicktengleich formatiert aus (tt = Teletyper = Fernschreiber)
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function tt($content, $attributes=array()) {
    return self::content('tt', $content, $attributes);
  }

  /**
   * Erstellt ein <u>-Tag, dies zeichnet einen Text als unterstrichen aus
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function u($content, $attributes=array()) {
    return self::content('u', $content, $attributes);
  }


  // ==========================================================================
  // = Logischen Auszeichnung-Tags ========================================== =
  // ==========================================================================
  /**
   * Erstellt ein <abbr>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist eine Abkürzung"
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
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
  **/
  static public function acronym($content, $attributes=array()) {
    return self::content('acronym', $content, $attributes);
  }

  /**
   * Erstellt ein <cite>-Tag, dies
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function cite($content, $attributes=array()) {
    return self::content('cite', $content, $attributes);
  }

  /**
   * Erstellt ein <code>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist Quelltext"
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function code($content, $attributes=array()) {
    return self::content('code', $content, $attributes);
  }

  /**
   * Erstellt ein <dfn>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist eine Definition".
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function dfn($content, $attributes=array()) {
    return self::content('dfn', $content, $attributes);
  }

  /**
   * Erstellt ein <em>-Tag, dies zeichnet einen Text aus als betonten, wichtigen Text ("emphatisch")
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function em($content, $attributes=array()) {
    return self::content('em', $content, $attributes);
  }

  /**
   * Erstellt ein <kbd>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies stellt eine Benutzereingabe dar"
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function kbd($content, $attributes=array()) {
    return self::content('kbd', $content, $attributes);
  }

  /**
   * Erstellt ein <q cite="..">-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist ein Zitat mit Quellenangabe"
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
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
  **/
  static public function samp($content, $attributes=array()) {
    return self::content('samp', $content, $attributes);
  }

  /**
   * Erstellt ein <strong>-Tag, dies zeichnet einen Text aus mit der Bedeutung "stark betont" (Steigerung von "em")
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function strong($content, $attributes=array()) {
    return self::content('strong', $content, $attributes);
  }

  /**
   * Erstellt ein <var>-Tag, dies zeichnet einen Text aus mit der Bedeutung "dies ist eine Variable oder ein variabler Name"
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function variable($content, $attributes=array()) {
    return self::content('var', $content, $attributes);
  }


  // ==========================================================================
  // = Headline-Tags ======================================================== =
  // ==========================================================================
  /**
   * Erstellt in Abhängigkeit vom $type ein <h1>- bis <h6>-Tag.
   *
   * @param mixed $content
   * @param string $type
   * @param array $attributes
   * @return Tag
  **/
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
  **/
  static public function h1($content, $attributes=array()) {
    return self::headline($content, 'h1', $attributes);
  }

  /**
   * Erstellt ein <h2>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function h2($content, $attributes=array()) {
    return self::headline($content, 'h2', $attributes);
  }

  /**
   * Erstellt ein <h3>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function h3($content, $attributes=array()) {
    return self::headline($content, 'h3', $attributes);
  }

  /**
   * Erstellt ein <h4>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function h4($content, $attributes=array()) {
    return self::headline($content, 'h4', $attributes);
  }

  /**
   * Erstellt ein <h5>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function h5($content, $attributes=array()) {
    return self::headline($content, 'h5', $attributes);
  }

  /**
   * Erstellt ein <h6>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function h6($content, $attributes=array()) {
    return self::headline($content, 'h6', $attributes);
  }


  // ==========================================================================
  // = Absatz-Tags (BLOCK) ================================================== =
  // ==========================================================================
  /**
   * Erstellt ein <blockquote>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function blockquote($content, $attributes=array()) {
    return self::content('blockquote', $content, $attributes);
  }

  /**
   * Erstellt ein <center>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function center($content, $attributes=array()) {
    return self::content('center', $content, $attributes);
  }

  /**
   * Erstellt ein <div>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function div($content, $attributes=array()) {
    return self::content('div', $content, $attributes);
  }

  /**
   * Erstellt ein <p>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function p($content, $attributes=array()) {
    return self::content('p', $content, $attributes);
  }

  /**
   * Erstellt ein <param>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function param($content, $attributes=array()) {
    return self::content('param', $content, $attributes);
  }

  /**
   * Erstellt ein <pre>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function pre($content, $attributes=array()) {
    return self::content('pre', $content, $attributes);
  }


  // ==========================================================================
  // = Absatz-Tags (INLINE) ================================================= =
  // ==========================================================================
  /**
   * Erstellt ein <span>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function span($content, $attributes=array()) {
    return self::content('span', $content, $attributes);
  }


  // ==========================================================================
  // = Tabellen-Tags ======================================================== =
  // ==========================================================================
  /**
   * Erstellt ein <table>-Tag
   *
   * @param mixed $content
   * @param array $attributes
   * @return Tag
  **/
  static public function table($content, $attributes=array()) {
    $thead = NULL;
    $tfoot = NULL;
    $tbody = NULL;
    $tableContent = array();
    if (is_array($content)) {
      if (isset($content['tbody'])) {
          // Nur wenn ein "tbody" gesetzt ist, macht ein "thead" bzw. "tfoot" Sinn
        if (isset($content['thead'])) {
          $theadContent    = isset($content['thead']['content'])    ? $content['thead']['content'] : $content['thead'];
          $theadAttributes = isset($content['thead']['attributes']) ? $content['thead']['attributes'] : array();
          $tableContent['thead'] = self::thead($theadContent, $theadAttributes);
        }
        if (isset($content['tfoot'])) {
          $tfootContent    = isset($content['tfoot']['content'])    ? $content['tfoot']['content'] : $content['tfoot'];
          $tfootAttributes = isset($content['tfoot']['attributes']) ? $content['tfoot']['attributes'] : array();
          $tableContent['tfoot'] = self::tfoot($tfootContent, $tfootAttributes);
        }
        $tbodyContent    = isset($content['tbody']['content'])    ? $content['tbody']['content'] : $content['tbody'];
        $tbodyAttributes = isset($content['tbody']['attributes']) ? $content['tbody']['attributes'] : array();
        $tableContent['tbody'] = self::tbody($tbodyContent, $tbodyAttributes);
      } else {
        $tbodyContent    = isset($content['content'])    ? $content['content']    : $content;
        $tbodyAttributes = isset($content['attributes']) ? $content['attributes'] : array();
        $tableContent['tbody'] = self::tbody($tbodyContent, $tbodyAttributes);
      }
    }

    return self::content('table', $tableContent, $attributes);
  }

  /**
   * Erstellt ein <thead>-Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function thead($content, $attributes=array()) {
    return self::tablePart('thead', $content, $attributes);
  }

  /**
   * Erstellt ein <tbody>-Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function tbody($content, $attributes=array()) {
    return self::tablePart('tbody', $content, $attributes);
  }

  /**
   * Erstellt ein <tfoot>-Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function tfoot($content, $attributes=array()) {
    return self::tablePart('tfoot', $content, $attributes);
  }

  /**
   * Erstellt ein anhand des übergebenen $type, dass gleichnamige (<thead>-, <tfoot>- oder <tbody>-) Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static private function tablePart($type, $content, $attributes=array()) {
    $type = strtolower($type);
    $avaiableTableParts = array('thead', 'tfoot', 'tbody');

    if (!in_array($type, $avaiableTableParts)) {
      throw new TagTypeException('Der angegebene Tabellenteil ($type=\''.$type.'\') ist innerhalb eines \'table\'-Tags nicht erlaubt!');
    }

    $typeContent = array();
    if (is_array($content)) {
      foreach($content as $row) {
        $trContent    = (is_array($row) && isset($row['content']))    ? $row['content']    : $row;
        $trAttributes = (is_array($row) && isset($row['attributes'])) ? $row['attributes'] : array();
        $typeContent[] = Tag::tr($trContent, $trAttributes);
      }

    } else if ($content instanceof AbstractTag) {
      $typeContent[] = $content;

    } else {
      throw new TagTypeException('Der &uuml;bergebene \'$content\' (\''.gettype($content).'\') kann in dem \''.$type.'\'-Tags nicht verarbeitet werden!');
    }

    if (empty($typeContent)) {
      throw new TagTypeException('In dem \''.$type.'\'-Tags muss mindestens ein \'tr\'-Tags enthalten sein!');
    }

    return self::content($type, $typeContent, $attributes);
  }

  /**
   * Erstellt ein <tr>-Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function tr($content, $attributes=array()) {
    $trContent = array();
    if (is_array($content)) {
      foreach($content as $k => $row) {
        if ($row instanceof AbstractTag) {
          if ('td' == $row->getName() || 'th' == $row->getName()) {
            $trContent[] = $row;
          } else if ($row->isBlockTag() || $row->isInlineTag()) {
            $trContent[] = Tag::td($row);
          } else {
            throw new TagTypeException('In einem \'tr\'-Tags sind nur \'td\'- und \'th\'-Tags erlaubt, aber kein \''.$row->getName().'\'-Tag!');
          }
        } else {
          $tdContent    = (is_array($row) && isset($row['content']))    ? $row['content']    : $row;
          $tdAttributes = (is_array($row) && isset($row['attributes'])) ? $row['attributes'] : array();
          $trContent[]  = Tag::td($tdContent, $tdAttributes);
        }
      }
    }

    return self::content('tr', $trContent, $attributes);
  }

  /**
   * Erstellt ein <td>-Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function td($content, $attributes=array()) {
    return self::tableCell('td', $content, $attributes);
  }

  /**
   * Erstellt ein <th>-Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function th($content, $attributes=array()) {
    return self::tableCell('th', $content, $attributes);
  }

  /**
   * Erstellt ein anhand des übergebenen $type, dass gleichnamige (<td>- oder <th>-) Tag.
   *
   * @param mixed $content Inhalt des Tags
   * @param array $attributes
   * @return Tag
  **/
  static public function tableCell($type, $content, $attributes=array()) {
    $availableTypes = array('td', 'th');
    $type = strtolower($type);

    if (!in_array($type, $availableTypes)) {
      throw new TagTypeException('Der angegebene Tabellenzellen-Type ($type=\''.$type.'\') ist innerhalb eines \'tr\'-Tags nicht erlaubt!');
    }

    $tag = NULL;
    if ($content instanceof AbstractTag) {
      if ($type == $content->getName()) {
        $tag = $content;
      } else if ($content->isBlockTag() || $content->isInlineTag()) {
        $tag = self::content($type, $content);
      } else {
        throw new TagTypeException('In einem \''.$type.'\'-Tags sind nur Inline- und Block-Elemente erlaubt, aber nicht \''.$content->getName().'\'-Tags!');
      }
    } else {
      $tag = self::content($type, $content);
    }

    if (is_array($attributes) && !empty($attributes)) {
      $tag->addAttributes(AttributeFactory::createAttributes($tag->getName(), $attributes));
    }

    return $tag;
  }


  // ==========================================================================
  // = Hilfsfunktionen ====================================================== =
  // ==========================================================================
  /**
   * Prüft, ob Prefix für diverse Attribute (z.B. 'id' oder 'name') gesetzt wurde.
   *
   * @return boolean
  **/
  static protected function hasPrefixId() {
    return (is_string(self::$prefixId) && '' != self::$prefixId);
  }

  /**
   * Durchsucht die übergebenen $attributes nach Attributen, die in $search in Form von Schlüsseln angegeben werden.
   *
   * @param array $attributes Array mit Attributen
   * @param array $search Array mit den Attributen nach denen in $attributes gesucht werden soll (array('id'=>FALSE, 'class'=>FALSE, ...)
   * @return boolean
  **/
  static protected function hasAttributes($attributes, &$search) {
    $has = FALSE;
    if (is_array($attributes) && is_array($search)) {
      foreach ($attributes as $attrName => $attrValue) {                                // Alle Attribute durchsuchen
        if (array_key_exists($attrName, $search)) {                                     // und zwar nach denen, die in $search als Schlüssel aufgeführt wurden
          $search[$attrName] = TRUE;
          $has = TRUE;
        }
      }
    }
    return $has;
  }

  /**
   * Fügt das ID-Attribute den in $attributes übergebenen Attributen hinzu.
   *
   * @param array $attributes Referenz mit den Attributen
   * @param string $value Wert des ID-Attributes
   * @return void
  **/
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
  **/
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
  **/
  static public function addNameAttribute(&$attributes, $value) {
    $attributes['name'] = (self::hasPrefixId()) ? self::$prefixId . '[' . $value . ']' : $value;
  }

  /**
   * Setzen der PrefixId.
   * Wenn diese angegeben ist, wird z.B. bei createInputTag() das ID-Attribute automatisch gesetzt (sofern nicht schon in dem Attribute-Array aufgeführt) und die PrefixId vorangestellt.
   *
   * @param $prefixId
   * @return void
  **/
  static public function setPrefixId($prefixId) {
    if (!is_string($prefixId)) {
      throw new TagException('Die PrefixId muss ein String sein!');
    }

    self::$prefixId = $prefixId;
  }

  /**
   * Gibt die prefixId zurück.
   * @return string
  **/
  static public function getPrefixId() {
    return self::$prefixId;
  }

  /**
   * Setzen die Default-Größe für das 'cols'-Attribute beim <textarea>-Tag.
   *
   * @param $cols
   * @return void
  **/
  static public function setDefaultTextareaCols($cols) {
    $cols = intval($cols);
    if ($cols <= 0) {
      throw new TagException('Die angebenene Größe für \'TextareaCols\' muss vom Typ Integer und > 0 sein!');
    }

    self::$defaultTextareaCols = $cols;
  }

  /**
   * Setzen die Default-Größe für das 'rows'-Attribute beim <textarea>-Tag.
   *
   * @param $rows
   * @return void
  **/
  static public function setDefaultTextareaRows($rows) {
    $rows = intval($rows);
    if ($rows <= 0) {
      throw new TagException('Die angebenene Größe für \'TextareaRows\' muss vom Typ Integer und > 0 sein!');
    }

    self::$defaultTextareaRows = $rows;
  }

  /**
   * Gibt den Pfad zu den Images zurück.
   * Default: 'images/'
   *
   * @return string
  **/
  static public function getImagePath() {
    return basename(self::$imagePath);
  }

  /**
   * Setzt den Pfad zu dem Images.
   * Default: 'images/'
   *
   * @param sting $path
   * @return void
  **/
  static public function setImagePath($path) {
    if (!is_string($path) || empty($path)) {
      throw new TagException('Der angegebene Pfad zu dem Image-Verzeichnis muss vom Type String und nicht darf nicht leer sein!');
    }

    self::$imagePath = $path;
  }

  /**
   * Gibt den Pfad zu den CSS-Dateien zurück.
   * Default: 'css/'
   *
   * @return string
  **/
  static public function getCssPath() {
    return basename(self::$cssPath);
  }

  /**
   * Setzt den Pfad zu dem CSS-Dateien.
   * Default: 'css/'
   *
   * @param sting $path
   * @return void
  **/
  static public function setCssPath($path) {
    if (!is_string($path) || empty($path)) {
      throw new TagException('Der angegebene Pfad zu dem CSS-Verzeichnis muss vom Type String und nicht darf nicht leer sein!');
    }

    self::$cssPath = $path;
  }


  // ==========================================================================
  // = Aus Kompatibilitäts-Gründen ========================================= =
  // ==========================================================================
  /**
   * Alias für create()
   *
   * @param string $name Name des Tags, z.B. 'a', 'input', 'br' etc.
   * @param array $attributes Array mit den zu erstellenden Attributen.
   * @return Tag
  **/
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
  **/
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
  **/
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
  **/
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
  **/
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
  **/
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
  **/
  static public function createLabeledInputTag($labelContent, $type, $name, $value, $inputAttributes=array(), $labelAttributes=array(), $inputBeforeLabel=TRUE) {
    return self::labeledInput($labelContent, $type, $name, $value, $inputAttributes, $labelAttributes, $inputBeforeLabel);
  }

  /**
   * Alias für textarea()
   *
   * @param string $name Name des Tags, über dem der Inhalt ausgelesen werden kann (name-Attribute)
   * @param string $content Content zwischen dem <textarea>- und </textarea>-Tag
   * @param array $params Additional parameter like 'class'
   * @return Tag
  **/
  static public function createTextareaTag($name, $content='', $attributes=array()) {
    return self::textarea($name, $content, $attributes);
  }

  /**
   * Alias für fieldset()
   *
   * @param mixed $legendContent
   * @param mixed $fieldsetContent
   * @param array $fieldsetAttributes
   * @param array $legendAttributes
   * @return Tag
  **/
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
  **/
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
  **/
  static public function createListTag($items, $type='ul', $attritbutes=array()) {
    return self::lists($items, $type, $attritbutes);
  }


  // ==========================================================================
  // = MessageBox =========================================================== =
  // ==========================================================================
  /**
   * Erstellt für den übergebene $type eine MessageBox. Diese Box ist dem $type entsprechend farblich hervorgehoben.
   *
   * @param string  $type       Erlaubte Type 'notice', 'info', 'ok', 'warning', 'error'
   * @param mixed   $content    Entweder direkt ein Tag-Objekt, oder ein String
   * @param mixed   $headline   Entweder direkt ein Tag-Objekt, oder ein String
   * @param array   $attributes
   * @return Tag
  **/
  static public function abstractMessageBox($type, $content, $headline=NULL, $attributes=array()) {
    $type = strtolower($type);

    if (!in_array($type, array('notice', 'info', 'ok', 'warning', 'error'))) {
      throw new TagTypeException('Dieser Typ ('.$type.') vom MessageBox ist nicht erlaubt!');
    }

    $imagePath = self::getImagePath();
    $styleMessage = array(
      "base"      => "padding: 6px; padding-left: 26px; margin-bottom: 4px; background-repeat: no-repeat; background-position: 5px 7px; border: 1px solid;",
      "notice"    => "background-color: #f6f7fa; border-color: #c2cbcf; background-image: url({$imagePath}/notice_16.png);",
      "info"      => "background-color: #ddeef9; border-color: #8aafc4; background-image: url({$imagePath}/info_16.png);",
      "ok"        => "background-color: #cdeaca; border-color: #58b548; background-image: url({$imagePath}/ok_16.png);",
      "warning"   => "background-color: #fbffb3; border-color: #c4b70d; background-image: url({$imagePath}/warning_16.png);",
      "error"     => "background-color: #fbb19b; border-color: #dc4c42; background-image: url({$imagePath}/error_16.png);",
      "headline"  => "color: #000000; font-size: 1.2em;",
      "content"   => "color: #000000; font-size: 1.0em;",
    );

      // *** Headline ***
    if (NULL !== $headline) {
      if ($headline instanceof AbstractTag) {
        $headline->appendAttribute('style', $styleMessage['headline'])
                 ->appendAttribute('class', "message_box_{$type}_headline");

      } else if (is_string($headline)) {
        $headlineAttributes = array(
          'style' => $styleMessage['headline'],
          'class' => "message_box_{$type}_headline",
        );
        if (isset($attributes['headline'])) {
          $headlineAttributes = array_merge($headlineAttributes, $attributes);
        }

        $headline = Tag::strong(Tag::span($headline, $headlineAttributes));
      } else {
        throw new TagTypeException('Die $headline muss entweder ein AbstractTag sein, oder eins String!');
      }
    }

      // *** Content ***
    if ($content instanceof AbstractTag) {
      $content->appendAttribute('style', $styleMessage['content'])
              ->appendAttribute('class', "message_box_{$type}_content");

    } else if (is_string($content)) {
      $contentAttributes = array(
        'style' => $styleMessage['content'],
        'class' => "message_box_{$type}_content",
      );
      if (isset($attributes['content'])) {
        $contentAttributes = array_merge($contentAttributes, $attributes);
      }
      $content = Tag::div($content, $contentAttributes);
    }

    $boxAttributes = array(
      'style' => $styleMessage['base'] . $styleMessage[$type],
      'class' => "message_box_{$type}",
    );
    return Tag::div($headline . $content, $boxAttributes);
  }

  /**
   * Erstellt eine 'Notice'-MessageBox.
   *
   * @param mixed   $content    Entweder direkt ein Tag-Objekt, oder ein String
   * @param mixed   $headline   Entweder direkt ein Tag-Objekt, oder ein String
   * @param array   $attributes
   * @return Tag
  **/
  static public function notice($content, $headline=NULL, $attributes=array()) {
    return Tag::abstractMessageBox('notice', $content, $headline, $attributes);
  }

  /**
   * Erstellt eine 'Info'-MessageBox.
   *
   * @param mixed   $content    Entweder direkt ein Tag-Objekt, oder ein String
   * @param mixed   $headline   Entweder direkt ein Tag-Objekt, oder ein String
   * @param array   $attributes
   * @return Tag
  **/
  static public function info($content, $headline=NULL, $attributes=array()) {
    return Tag::abstractMessageBox('info', $content, $headline, $attributes);
  }

  /**
   * Erstellt eine 'Ok'-MessageBox.
   *
   * @param mixed   $content    Entweder direkt ein Tag-Objekt, oder ein String
   * @param mixed   $headline   Entweder direkt ein Tag-Objekt, oder ein String
   * @param array   $attributes
   * @return Tag
  **/
  static public function ok($content, $headline=NULL, $attributes=array()) {
    return Tag::abstractMessageBox('ok', $content, $headline, $attributes);
  }

  /**
   * Erstellt eine 'Warning'-MessageBox.
   *
   * @param mixed   $content    Entweder direkt ein Tag-Objekt, oder ein String
   * @param mixed   $headline   Entweder direkt ein Tag-Objekt, oder ein String
   * @param array   $attributes
   * @return Tag
  **/
  static public function warning($content, $headline=NULL, $attributes=array()) {
    return Tag::abstractMessageBox('warning', $content, $headline, $attributes);
  }

  /**
   * Erstellt eine 'Error'-MessageBox.
   *
   * @param mixed   $content    Entweder direkt ein Tag-Objekt, oder ein String
   * @param mixed   $headline   Entweder direkt ein Tag-Objekt, oder ein String
   * @param array   $attributes
   * @return Tag
  **/
  static public function error($content, $headline=NULL, $attributes=array()) {
    return Tag::abstractMessageBox('error', $content, $headline, $attributes);
  }




  static public function createTagsByArray($array) {
    /**
     * 1.) Gibt es für den Key eine Factory?
     * 1.1.) Wenn ja, dann
    **/

    $content = '';
    foreach ($array as $key => $factoryArray) {
      foreach ($factoryArray as $factory => $factoryData) {
        // Umwandeln von 'dies_ist_eine_funktion' zu 'DiesIstEineFunktion'
        $factoryCamelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $factory)));
        $factoryName = 'create'. $factoryCamelCase . 'Tag';

        if (in_array($factoryName, get_class_methods('Tag')) ) {
          // Es existiert eine passende Factory-Methode
          //          $content.= '<h3>Factory: '.$factoryName.'</h3>';

          switch($factoryName) {
            case 'createATag':
              $content.= self::createATag($factoryData['href'], $factoryData['content'], $factoryData['attributes']);
              break;

            case 'createFormTag':
              //            $content.= Tag::createFormTag($action, $factoryData['content'], $factoryData['attributes']);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createInputTag':
              $content.= self::createInputTag($factoryData['type'], $factoryData['name'], $value, $factoryData['attributes']);
              break;

            case 'createLabelTag':
              //            $content.= Tag::createLabelTag($for, $factoryData['content'], $factoryData['attributes']);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createLabeledInputTag':
              //            $content.= Tag::createLabeledInputTag($labelContent, $factoryData['type'], $factoryData['name'], $value, $inputAttributes, $labelAttributes);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createTextareaTag':
              //            $content.= Tag::createTextareaTag($factoryData['name'], $factoryData['content'], $factoryData['attributes']);
              throw new TagException('Diese Factory (Tag::'.$factoryName.') wird mit dieser noch nicht unterstürzt!');
              break;

            case 'createFieldsetTag':
              //            $content.= Tag::createFieldsetTag($fieldsetContent, $legendContent, $fieldsetAttributes, $legendAttributes);
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