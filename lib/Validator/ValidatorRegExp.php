<?php
/**
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @version $Id$
 * @copyright Timo Strotmann, 18 October, 2010
 * @package default
**/

/**
 * Validator for regEx
 *
 * @package default
 * @author Timo Strotmann
 * @copyright Timo Strotmann, 18 October, 2010
**/
class ValidatorRegex extends ValidatorString {

  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * pattern:    A regex pattern compatible with PCRE or {@link sfCallable} that returns one (required)
   *  * must_match: Whether the regex must match or not (TRUE by default)
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorString
  **/
  protected function configure($options = array(), $messages = array()) {
    parent::configure($options, $messages);

    $this->addRequiredOption('pattern');
    $this->addOption('must_match', TRUE);
  }

  /**
   * @see sfValidatorString
  **/
  protected function doClean($value) {
    $clean = parent::doClean($value);

    $pattern = $this->getPattern();

    if (
      ($this->getOption('must_match') && !preg_match($pattern, $clean))
      ||
      (!$this->getOption('must_match') && preg_match($pattern, $clean))
    ) {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    return $clean;
  }

  /**
   * Returns the current validator's regular expression.
   *
   * @return string
  **/
  public function getPattern() {
    $pattern = $this->getOption('pattern');

    return $pattern instanceof sfCallable ? $pattern->call() : $pattern;
  }

} // END ValidatorRegex
?>