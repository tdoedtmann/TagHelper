<?php

class ValidatorEmail extends ValidatorRegex {
  const REGEX_EMAIL = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';

  /**
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array()) {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::REGEX_EMAIL);
  } 
  
} 
