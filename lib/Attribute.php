<?php
/**
 *
 *
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @version $Id$
 * @copyright Timo Strotmann, 18 October, 2010
 * @package default
**/

require_once 'AttributeType.php';

// ============================================================================
// = Attribute-Exception-Klassen ============================================ =
// ============================================================================
/**
 * Exception Klasse für Attribute-Fehler
 *
 * @author Timo Strotmann
**/
class AttributeException extends Exception {
}



// ============================================================================
// = Attribute-Klasse ======================================================= =
// ============================================================================
/**
 *
 * @author Timo Strotmann
**/
class Attribute {

  /**
   * Name des Attributes, z.B. 'href'
   * @var string
  **/
  protected $name = NULL;

  /**
   * AttributeType, der bestimmt, ob der Wert ($value) des Attributes auch korrekt ist.
   * @var unknown_type
  **/
  protected $type = NULL;

  /**
   * Wert des Attributes, für 'href' wäre dies z.B. 'http://www.saltation.de' (welches vom 'AttributeTypeCdata' wäre)
   * @var string
  **/
  protected $value = NULL;

  /**
   * Angabe, ob dieses Attribute in dem Tag zwingend angegeben werden muss
   * @var boolean
  **/
  protected $requried = FALSE;

  /**
   * Angabe, ob es einen Default-Wert gibt und wenn welchen
   * @var string
  **/
  protected $default = NULL;

  /**
   * Tag-Name, in dem dieses Attribute enthalten ist.
   * @var Tag
  **/
  protected $tagName = NULL;

  /**
   * Wenn dieser Parameter gesetzt ist, wird in der __toString()-Methode der $value-Wert mit addslashes() aufgerufen!
   * @var TRUE
  **/
  protected $addSlashes = TRUE;

  /**
   *
   * @param $name z.B. 'href'
   * @param $value z.B. 'http://www.saltation.de'
   * @param $type z.B. Objekt von der Klasse AttributeTypeCdata
   * @param $requried z.B. TRUE
   * @param $default z.B. NULL
   * @return $this
  **/
  public function __construct($name, $value, $tagName, $options = array()) {
    if (is_string($name)) {
      $this->name = $name;
      $this->tagName = $tagName;
      $this->type = AttributeTypeFactory::getAttributeType($name, $tagName);

      $this->setValue_Private($value);

      if (isset($options['requried'])) {
        $this->requried = (boolean)$options['requried'];
      }
      if (isset($options['default'])) {
        $this->default = $options['default'];
      }
      if (isset($options['addSlashes'])) {
        $this->addSlashes = (boolean)$options['addSlashes'];
      }

    } else {
      throw new AttributeException('Der Name des Attributes muss ein String sein ('.$name.', '.$value.', '.$tagName.')!');
    }
  }

  /**
   *
   * @return string
  **/
  public function __toString() {
    if ($this->addSlashes) {
      return ' ' . $this->name. '="' . addslashes($this->value) . '"';
    }
    return ' ' . $this->name. '="' . $this->value . '"';
  }

  /**
   *
   * @return unknown_type
  **/
  public function getName() {
    return $this->name;
  }

  /**
   *
   * @return unknown_type
  **/
  public function getType() {
    return $this->type;
  }

  /**
   *
   * @return unknown_type
  **/
  public function getValue() {
    return $this->value;
  }

  /**
   *
   * @return unknown_type
  **/
  public function isRequired() {
    return (boolean)$this->requried;
  }

  /**
   *
   * @return unknown_type
  **/
  public function isValid() {
    return $this->type->isValid($this->value);
  }

  /**
   *
   * @param $value
   * @return unknown_type
  **/
  private function setValue_Private($value) {
    if ($this->type->isValid($value)) {
      $this->value = $value;
    } else {
      throw new AttributeException('Der übergebene Wert \'' . $value.'\' für das Attribute \''.$this->name.'\' validiert (RegExp: '.$this->type->getRegExp().') nicht mit dem AttributeType \''.$this->type->getName().'\'!');
    }
  }

  /**
   *
   * @param $value
   * @return unknown_type
  **/
  public function setValue($value) {
    $this->setValue_Private($value);
    return $this;
  }

  /**
   *
   * @param $defaultValue
   * @return unknown_type
  **/
  public function setDefault($defaultValue = NULL) {
    $this->default = $defaultValue;
    return $this;
  }

  /**
   *
   * @param $requiredValue
   * @return unknown_type
  **/
  public function setRequired($requiredValue = FALSE) {
    $this->requried = (boolean)$requiredValue;
    return $this;
  }

  public function hasAddSlashes() {
    return $this->addSlashes;
  }

}