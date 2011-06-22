<?php
/**
 *
 *
 * @package TagHelper
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @copyright Timo Strotmann, 18 October, 2010
**/

require_once 'AttributeType.php';

// ============================================================================
// = Attribute-Exception-Klassen ============================================ =
// ============================================================================
/**
 * Exception Klasse für Attribute-Fehler
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeException extends Exception {
}


// ============================================================================
// = Attribute-Klasse ======================================================= =
// ============================================================================
/**
 * Attribute
 *
 * @package TagHelper
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
   * @var AbstractAttributeType
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
   * @param string                $name z.B. 'href'
   * @param string                $value z.B. 'http://www.saltation.de'
   * @param AbstractAttributeType $type z.B. Objekt von der Klasse AttributeTypeCdata
   * @param array                 $options
   * @return void
  **/
  public function __construct($name, $value, $tagName, $options = array()) {
    if (!is_string($name)) {
      throw new AttributeException('Der Name des Attributes muss ein String sein ('.$name.', '.$value.', '.$tagName.')!');
    }

    $this->name     = $name;
    $this->tagName  = $tagName;
    $this->type     = AttributeTypeFactory::getAttributeType($name, $tagName);

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
   * Gibt den Namen des Attributes zurück.
   *
   * @return string
  **/
  public function getName() {
    return $this->name;
  }

  /**
   * Gibt den Typ des Attributes zurück.
   *
   * @return AbstractAttributeType
  **/
  public function getType() {
    return $this->type;
  }

  /**
   * Gibt den Value-Wert zurück.
   *
   * @return string
  **/
  public function getValue() {
    return $this->value;
  }

  /**
   * Gitb TRUE zurück, wenn es eine Pflichtangabe ist, sosnt FALSE.
   *
   * @return boolean
  **/
  public function isRequired() {
    return (boolean)$this->requried;
  }

  /**
   * GIbt TRUE zurück, wenn der Value-Wert valide ist, sonst FALSE.
   *
   * @return boolean
  **/
  public function isValid() {
    return $this->type->isValid($this->value);
  }

  /**
   *
   * @param string $value
   * @return Attribute
  **/
  private function setValue_Private($value) {
    if ($this->type->isValid($value)) {
      $this->value = $value;
    } else {
      throw new AttributeException('Der übergebene Wert \'' . $value.'\' für das Attribute \''.$this->name.'\' validiert (RegExp: '.$this->type->getRegExp().') nicht mit dem AttributeType \''.$this->type->getName().'\'!');
    }
  }

  /**
   * Setzt den Valiue-Wert.
   *
   * @param string $value
   * @return Attribute
  **/
  public function setValue($value) {
    $this->setValue_Private($value);
    return $this;
  }

  /**
   *
   * @param string $defaultValue (Default: NULL)
   * @return Attribute
  **/
  public function setDefault($defaultValue=NULL) {
    $this->default = $defaultValue;
    return $this;
  }

  /**
   *
   * @param boolean $requiredValue (Default: FALSE)
   * @return Attribute
  **/
  public function setRequired($requiredValue=FALSE) {
    $this->requried = (boolean)$requiredValue;
    return $this;
  }

  /**
   * Gitb TRUE zurück, wenn 'addSlashes' gesetzt ist.
   *
   * @return boolean
  **/
  public function hasAddSlashes() {
    return $this->addSlashes;
  }

}