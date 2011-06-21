<?php
/**
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @version $Id$
 * @copyright Timo Strotmann, 18 October, 2010
 * @package default
**/

/**
 * Validator for email
 *
 * @package default
 * @author Timo Strotmann
 * @copyright Timo Strotmann, 18 October, 2010
**/
class ValidatorEmail extends ValidatorRegex {
  const REGEX_EMAIL = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';

  /**
   * @see sfValidatorRegex
  **/
  protected function configure($options = array(), $messages = array()) {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::REGEX_EMAIL);
  }

} // END ValidatorEmail
?>