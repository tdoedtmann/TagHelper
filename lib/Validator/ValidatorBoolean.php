<?php
/**
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @version $Id$
 * @copyright Timo Strotmann, 18 October, 2010
 * @package default
**/

/**
 * Validator for boolean
 *
 * @package default
 * @author Timo Strotmann
 * @copyright Timo Strotmann, 18 October, 2010
**/
class ValidatorBoolean extends ValidatorBase {

  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * TRUE_values:  The list of TRUE values
   *  * FALSE_values: The list of FALSE values
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
  **/
  protected function configure($options = array(), $messages = array()) {
    $this->addOption('TRUE_values', array('TRUE', 't', 'yes', 'y', 'on', '1'));
    $this->addOption('FALSE_values', array('FALSE', 'f', 'no', 'n', 'off', '0'));

    $this->setOption('required', FALSE);
    $this->setOption('empty_value', FALSE);
  }

  /**
   * @see sfValidatorBase
  **/
  protected function doClean($value) {
    if (in_array($value, $this->getOption('TRUE_values'))) {
      return TRUE;
    }

    if (in_array($value, $this->getOption('FALSE_values'))) {
      return FALSE;
    }

    throw new sfValidatorError($this, 'invalid', array('value' => $value));
  }

} // END ValidatorBoolean
?>