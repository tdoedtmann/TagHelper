<?php
/**
 *
 *
 * @package TagHelper
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @copyright Timo Strotmann, 18 October, 2010
**/

// ============================================================================
// = AttributeType-Exceptions =============================================== =
// ============================================================================
/**
 * Exception Klasse für AttributeValue-Fehler
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeValueException extends Exception {
}

/**
 * Exception Klasse für Attribute-Fehler
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class UnknownAttributeException extends Exception {
}

/**
 * Exception Klasse für AttributeType-Fehler
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeException extends Exception {
}

/**
 * Exception Klasse für AttributeType-Fehler
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class UnknownAttributeTypeException extends Exception {
}


// ============================================================================
// = AttributeType-Interface ================================================ =
// ============================================================================
/**
 * AttributeTypeInterface
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
interface AttributeTypeInterface {
  public function getName();
  public function getRegExp();
  public function isValid($value);
}


// ============================================================================
// = AttributeType-Klassen ================================================== =
// ============================================================================
/**
 * AbstractAttributeType
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AbstractAttributeType implements AttributeTypeInterface {

  /**
   * Name des AttributeTypes
   * @var string
  **/
  protected $name = NULL;

  /**
   * RegExp, welches alle Zeichen zulässt.
   * @var string
  **/
  protected $regExp = NULL;


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
  **/
  public function __construct() {
    $this->name   = '__ABSTRACT__';                                             // Kein echtes (darum __ABSTRACT__) Attribute
    $this->regExp = "((.){1,})";                                                // Alle Zeichen, aber mind. 1 Zeichen
  }

  /**
   * Gibt den Namen des AttributeTypes zurück.
   *
   * @return string
  **/
  public function getName() {
    return $this->name;
  }

  /**
   * Gibt den Regulären Ausdruck zu dem Attribute Type zurück.
   *
   * @return string
  **/
  public function getRegExp() {
    return $this->regExp;
  }

  /**
   * Prüft, ob der übergebene Wert (String) mit dem hinterlegten regulären Ausdruck validiert.
   *
   * @param string $value
   * @return boolean
  **/
  public function isValid($value) {
    $matches = array();
    if ((boolean)preg_match($this->regExp, $value, $matches)) {
      return (boolean)($matches[0] === $value);
    }
    return FALSE;
  }

}


/**
 * AttributeTypeCdata
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeCdata extends AbstractAttributeType {

  /**
   * Singleton-Instanz
   * @var AbstractAttributeType
  **/
  private static $instance = NULL;


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
  **/
  public function __construct() {
    $this->name   = 'CDATA';                                                    // CDATA, also Normaler Text (z.B. value bei <input>)                   
    $this->regExp = "((.){1,})";                                                // Alle Zeichen, aber mind. ein Zeichen
  }

  /**
   * Da es sich hier um ein Singleton handelt, muss die __clone()-Methode eliminiert werden, um das Klonen zu verhindern
   *
   * @return void
  **/
  private function __clone() {}

  /**
   * Stellt sicher das es ein Singleton ist!
   *
   * @return AttributeTypeCdata
  **/
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new AttributeTypeCdata();
    }
    return self::$instance;
  }

}


/**
 * AttributeTypeNumber
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeNumber extends AbstractAttributeType {

  /**
   * Singleton-Instanz
   * @var AbstractAttributeType
  **/
  private static $instance = NULL;


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
  **/
  public function __construct() {
    $this->name   = 'NUMBER';
    $this->regExp = "[0-9]";
  }

  /**
   * Da es sich hier um ein Singleton handelt, muss die __clone()-Methode eliminiert werden, um das Klonen zu verhindern
   *
   * @return void
  **/
  private function __clone() {}

  /**
   * Stellt sicher das es ein Singleton ist!
   *
   * @return AttributeTypeNumber
  **/
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new AttributeTypeNumber();
    }
    return self::$instance;
  }

  /**
   * Validiert die übergebene Zahl
   *
   * @param mixed $value 
   * @return boolean
  **/
  public function isValid($value) {
    return (is_numeric($value) && intval($value) == $value);
  }

}


/**
 * AttributeTypeId
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeId extends AbstractAttributeType {

  /**
   * Singleton-Instanz
   * @var AbstractAttributeType
  **/
  private static $instance = NULL;


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
  **/
  public function __construct() {
    $this->name   = 'ID';
    $this->regExp = "(^([A-Za-z]{1,1})+([a-zA-Z0-9\_\-\.\:]{1,}))";             // Eine ID muss mind. mit einem Buchstaben anfangen und kann dann auch Zahlen und "_", "-", ".", ":" enthalten
  }

  /**
   * Da es sich hier um ein Singleton handelt, muss die __clone()-Methode eliminiert werden, um das Klonen zu verhindern
   *
   * @return void
  **/
  private function __clone() {}

  /**
   * Stellt sicher das es ein Singleton ist!
   *
   * @return AttributeTypeId
  **/
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new AttributeTypeId();
    }
    return self::$instance;
  }

}


/**
 * AttributeTypeIdref
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeIdref extends AbstractAttributeType {

  /**
   * Singleton-Instanz
   * @var AbstractAttributeType
  **/
  private static $instance = NULL;


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
  **/
  public function __construct() {
    $this->name   = 'IDREF';
    $this->regExp = "(^([A-Za-z]{1,1})+([a-zA-Z0-9\_\-\.\:]{1,}))";             // Eine IDREF muss mind. mit einem Buchstaben anfangen und kann dann auch Zahlen und "_", "-", ".", ":" enthalten
  }

  /**
   * Da es sich hier um ein Singleton handelt, muss die __clone()-Methode eliminiert werden, um das Klonen zu verhindern
   *
   * @return void
  **/
  private function __clone() {}

  /**
   * Stellt sicher das es ein Singleton ist!
   *
   * @return AttributeTypeId
  **/
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new AttributeTypeIdref();
    }
    return self::$instance;
  }

}


/**
 * AttributeTypeName
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeName extends AbstractAttributeType {

  /**
   * Singleton-Instanz
   * @var AbstractAttributeType
  **/
  private static $instance = NULL;


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
  **/
  public function __construct() {
    $this->name   = 'NAME';
    $this->regExp = "(^([A-Za-z]{1,1})+([a-zA-Z0-9\_\-\.\:]{1,}))";             // Ein NAME muss mind. mit einem Buchstaben anfangen und kann dann auch Zahlen und "_", "-", ".", ":" enthalten
  }

  /**
   * Da es sich hier um ein Singleton handelt, muss die __clone()-Methode eliminiert werden, um das Klonen zu verhindern
   *
   * @return void
  **/
  private function __clone() {}

  /**
   * Stellt sicher das es ein Singleton ist!
   *
   * @return AttributeTypeId
  **/
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new AttributeTypeName();
    }
    return self::$instance;
  }

}


/**
 * AttributeTypeEnum
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeEnum extends AbstractAttributeType {

  /**
   * Trennzeichen zwischen dem $name und dem $regExp um diese gegebenefalls wieder trennen zu können
   * @var string
  **/
  private static $delim = '__#__';

  /**
   * Variantion des Singleton.
   * @var AbstractAttributeType
  **/
  private static $instances = array();


  /**
   * Konstuktor, zum definieren des Names und des regulären Ausdrucks
   *
   * @return void
   * @author Timo Strotmann
  **/
  public function __construct($name, $regExp) {
    $this->name   = 'ENUM';
    $this->regExp = $regExp;
  }

  /**
   * Da es sich hier um ein Singleton handelt, muss die __clone()-Methode eliminiert werden, um das Klonen zu verhindern
   *
   * @return void
   * @author Timo Strotmann
  **/
  private function __clone() {}

  /**
   * Stellt sicher das es ein Singleton ist!
   *
   * @return AttributeTypeId
   * @author Timo Strotmann
  **/
  public static function getInstance($name, $regExp) {
    $identifier = strtoupper($name).self::$delim.$regExp;

    if (!isset(self::$instances[$identifier])) {
      self::$instances[$identifier] = new AttributeTypeEnum($name, $regExp);
    }
    return self::$instances[$identifier];
  }

}