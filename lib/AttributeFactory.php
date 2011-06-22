<?php
/**
 *
 *
 * @package TagHelper
 * @author Timo Strotmann <timo@timo-strotmann.de>
 * @copyright Timo Strotmann, 18 October, 2010
**/

require_once 'Attribute.php';

// ============================================================================
// = AttributeTypeFactory-Klasse ============================================ =
// = -> zum erstellen/ermitteln eines AttributeTypes ======================== =
// ============================================================================
/**
 * AttributeTypeFactory
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeTypeFactory {

  /**
   * Erstellt gegebenfalls eine AttributeType-Instanz und gibt diese zurück.
   *
   * @param string $type
   * @param string $name    (Optional, Default: '')
   * @param string $regExp  (Optional, Default: '((.){1,})')
   * @return AttributeType or NULL
  **/
  static public function createAttributeType($type, $name='', $regExp='((.){1,})') {
    switch (strtolower($type)) {
      case 'cdata':
        return AttributeTypeCdata::getInstance();

      case 'number':
        return AttributeTypeNumber::getInstance();

      case 'id':
        return AttributeTypeId::getInstance();

      case 'idref':
        return AttributeTypeIdref::getInstance();

      case 'name':
        return AttributeTypeName::getInstance();

      case 'enum':
        return AttributeTypeEnum::getInstance($name, $regExp);

      default:
        throw new UnknownAttributeTypeException('Dieser AttributeType [AttributeType'.ucfirst(strtolower($type)).'($name='.$name.', $regExp='.$regExp.')] ist unbekannt!');
    }
  }

  /**
   * Ermittelt zu den $name (Attribute-Name) und den $tagName (Name des Tags) den passenden AttributeTyp
   * und gibt diesen mittels 'AttributeTypeFactory::createAttributeType' zurück.
   *
   * @param string $name
   * @param string $tagName
   * @return AttributeType
  **/
  static public function getAttributeType($name, $tagName) {
    $attributeType = NULL;

    $allTags = array('a', 'abbr', 'acronym', 'address', 'applet', 'area', 'b', 'base', 'basefont', 'bdo', 'big', 'blockquote', 'body', 'br', 'button', 'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'dd', 'del', 'dfn', 'dir', 'div', 'dl', 'dt', 'em', 'fieldset', 'font', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'isindex', 'kbd', 'label', 'legend', 'li', 'link', 'map', 'menu', 'meta', 'noframes', 'noscript', 'object', 'ol', 'optgroup', 'option', 'p', 'param', 'pre', 'q', 's', 'samp', 'script', 'select', 'small', 'span', 'strike', 'strong', 'style', 'sub', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'title', 'tr', 'tt', 'u', 'ul', 'var');

    $cdataConfig = array(
      'abbr'            => array('td', 'th'),
      'accept'          => array('form', 'input'),
      'accept-charset'  => array('form'),
      'accesskey'       => array('a', 'area', 'button', 'input', 'label', 'legend', 'textarea'),
      'action'          => array('form'),
      'alt'             => array('applet', 'area', 'img', 'input'),
      'archive'         => array('applet', 'object'),
      'axis'            => array('td', 'th'),
      'background'      => array('body'),
      'bgcolor'         => array('table', 'tr', 'td', 'th', 'body'),
      'border'          => array('table', 'img', 'object'),
      'cellpadding'     => array('table'),
      'cellspacing'     => array('table'),
      'char'            => array('col', 'colgroup', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr'),
      'charoff'         => array('col', 'colgroup', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr'),
      'charset'         => array('a', 'link', 'script'),
      'cite'            => array('blockquote', 'del', 'ins', 'q'),
      'classid'         => array('object'),
      'class'           => array_diff($allTags, array('base', 'basefont', 'head', 'html', 'meta', 'param', 'script', 'style', 'title')),
      'code'            => array('applet'),
      'codebase'        => array('applet', 'object'),
      'codetype'        => array('object'),
      'color'           => array('basefont', 'font'),
      'cols'            => array('frameset'),
      'colspan'         => array('td', 'th'),
      'content'         => array('meta'),
      'coords'          => array('area', 'a'),
      'data'            => array('object'),
      'datetime'        => array('del', 'ins'),
      'enctype'         => array('form'),
      'face'            => array('basefont', 'font'),
      'height'          => array('iframe', 'td', 'th', 'img', 'object', 'applet'),
      'href'            => array('a', 'area', 'link', 'base'),
      'hreflang'        => array('a', 'link'),
      'hspace'          => array('applet', 'img', 'object'),
      'label'           => array('option', 'optgroup'),
      'lang'            => array_diff($allTags, array('base', 'basefont', 'br', 'frame', 'frameset', 'iframe', 'param', 'script')),
      'language'        => array('script'),
      'link'            => array('body'),
      'longdesc'        => array('img', 'frame', 'iframe'),
      'marginheight'    => array('frame', 'iframe'),
      'marginwidth'     => array('frame', 'iframe'),
      'media'           => array('style', 'link'),
      'name'            => array('button', 'textarea', 'applet', 'select', 'form', 'frame', 'iframe', 'img', 'a', 'input', 'object', 'map', 'param'),
      'nohref'          => array('area'),
      'object'          => array('applet'),
      'onblur'          => array('a', 'area', 'button', 'input', 'label', 'select', 'textarea'),
      'onchange'        => array('input', 'select', 'textarea'),
      'onclick'         => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'ondblclick'      => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onfocus'         => array('a', 'area', 'button', 'input', 'label', 'select', 'textarea'),
      'onkeydown'       => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onkeypress'      => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onkeyup'         => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onload'          => array('frameset', 'body'),
      'onmousedown'     => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onmousemove'     => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onmouseout'      => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onmouseover'     => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onmouseup'       => array_diff($allTags, array('applet', 'base', 'basefont', 'bdo', 'br', 'font', 'frame', 'frameset', 'head', 'html', 'iframe', 'isindex', 'meta', 'param', 'script', 'style', 'title')),
      'onreset'         => array('form'),
      'onselect'        => array('input', 'textarea'),
      'onsubmit'        => array('form'),
      'onunload'        => array('frameset', 'body'),
      'profile'         => array('head'),
      'prompt'          => array('isindex'),
      'rel'             => array('a', 'link'),
      'rev'             => array('a', 'link'),
      'rows'            => array('frameset'),
      'rowspan'         => array('td', 'th'),
      'size'            => array('hr', 'font', 'input', 'basefont', 'select'),
      'src'             => array('script', 'input', 'frame', 'iframe', 'img'),
      'standby'         => array('object'),
      'style'           => array_diff($allTags, array('base', 'basefont', 'head', 'html', 'meta', 'param', 'script', 'style', 'title')),
      'summary'         => array('table'),
      'text'            => array('body'),
      'title'           => array_diff($allTags, array('base', 'basefont', 'head', 'html', 'meta', 'param', 'script', 'title')),
      'type'            => array('a', 'link', 'object', 'param',  'script', 'style'),
      'usemap'          => array('img, input, object'),
      'value'           => array('input', 'option', 'param', 'button'),
      'version'         => array('html'),
      'vlink'           => array('body'),
      'vspace'          => array('applet', 'img', 'object'),
      'width'           => array('applet', 'col', 'colgroup', 'hr', 'iframe', 'img', 'object', 'table', 'td', 'th'),
    );

    $idConfig = array(
      'id'              => array_diff($allTags, array('base', 'head', 'html', 'meta', 'script', 'style', 'title')),
    );

    $idrefConfig = array(
      'for'             => array('label', 'script'),
        // :TODO: Hier ist zu beachten, dass mehrere IDREFs möglich sind!
      'headers'         => array('td', 'th'),
    );

    $nameConfig = array(
      'http-equiv'      => array('meta'),
      'name'            => array('meta'),
      'schema'          => array('meta'),
    );

    $numberConfig = array(
      'cols'            => array('textarea'),
      'maxlength'       => array('input'),
      'rows'            => array('textarea'),
      'span'            => array('col', 'colgroup'),
      'start'           => array('ol'),
      'tabindex'        => array('a', 'area', 'button', 'input', 'object', 'select', 'textarea'),
      'value'           => array('li'),
      'width'           => array('pre'),
    );

    $enumConfig = array(
      'align' => array(
        '(top|bottom|left|right|middle|center)' => array('applet', 'input'),
        '(top|bottom|left|right|middle)'        => array('iframe', 'img', 'object'),
        '(top|bottom|left|right)'               => array('caption', 'legend'),
        '(left|center|right|justify|char)'      => array('col', 'colgroup', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr'),
        '(left|center|right|justify)'           => array('div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'),
        '(left|center|right)'                   => array('hr', 'table'),
      ),

      'checked' => array(
        '(checked)'                             => array('input'),
      ),
      'clear' => array(
        '(left|all|right|none)'                 => array('br'),
      ),
      'compact' => array(
        '(compact)'                             => array('dir', 'dl', 'menu', 'ol', 'ul'),
      ),

      'declare' => array(
        '(declare)'                             => array('object'),
      ),
      'dir' => array(
        '(ltr|rtl)'                             => array_diff($allTags, array('applet', 'base', 'basefont', 'br', 'frame', 'frameset', 'iframe', 'param', 'script')),
      ),
      'disabled' => array(
        '(disabled)'                            => array('button', 'input', 'optgroup', 'option', 'select', 'textarea'),
      ),
      'defer' => array(
        '(defer)'                               => array('script'),
      ),

      'frame' => array(
        '(void|above|below|hsides|lhs|rhs|vsides|box|border)'  => array('table'),
      ),
      'frameborder' => array(
        '(1|0)'                                 => array('frame', 'iframe'),
      ),

      'ismap' => array(
        '(ismap)'                               => array('img', 'input'),
      ),

      'method' => array(
        '(get|post)'                            => array('form'),
      ),
      'multiple' => array(
        '(multiple)'                            => array('select'),
      ),

      'noresize' => array(
        '(noresize)'                            => array('frame'),
      ),
      'noshade' => array(
        '(noshade)'                             => array('hr'),
      ),
      'nowrap' => array(
        '(nowrap)'                              => array('td', 'th'),
      ),

      'readonly' => array(
        '(readonly)'                            => array('input', 'textarea'),
      ),
      'rules' => array(
        '(none|groups|rows|cols|all)'           => array('table'),
      ),

      'scope' => array(
        '(row|col|rowgroup|colgroup)'           => array('td', 'th'),
      ),
      'scrolling' => array(
        '(yes|no|auto)'                         => array('frame', 'iframe'),
      ),
      'selected' => array(
        '(selected)'                            => array('option'),
      ),
      'shape' => array(
        '(rect|circle|poly|default)'            => array('a', 'area'),
      ),

      'target' => array(
        '(_blank|_parent|_self|_top|.)'         => array('a', 'area', 'form', 'link'),
        '(.)'                                   => array('base'),
      ),
      'type' => array(
        '(button|submit|reset|text|password|checkbox|radio|file|hidden|image)'  => array('input'),
        '(button|submit|reset)'                 => array('button'),
        '(disc|square|circle|1|a|A|i|I)'        => array('li'),
        '(disc|square|circle)'                  => array('ul'),
        '(1|a|A|i|I)'                           => array('ol'),
      ),

      'valuetype' => array(
        '(data|ref|object)'                     => array('param'),
      ),
      'valign' => array(
        '(top|middle|bottom|baseline)'          => array('col', 'colgroup', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr'),
      )
    );

    $configs = array(
      'cdata'   => $cdataConfig,
      'id'      => $idConfig,
      'idref'   => $idrefConfig,
      'name'    => $nameConfig,
      'number'  => $numberConfig,
    );
    foreach($configs as $configType => $config) {
      if (array_key_exists($name, $config) && array_search($tagName, $config[$name]) !== FALSE) {
        return AttributeTypeFactory::createAttributeType($configType);
      }
    }

    if (array_key_exists($name, $enumConfig)) {
      foreach ($enumConfig[$name] as $regExp => $tags) {
        if (array_search($tagName, $tags) !== FALSE) {
          return AttributeTypeFactory::createAttributeType('enum', $name, $regExp);
        }
      }
    }

    throw new UnknownAttributeTypeException('Für das Tag \''.$tagName.'\' liegt kein Attribute mit dem Namen \''.$name.'\' vor!');
  }

}


/**
 * AttributeFactory
 *
 * @package TagHelper
 * @author Timo Strotmann
**/
class AttributeFactory {

  /**
   *
   * @param $name
   * @param $value
   * @param string $tagName
   * @param array  $options (Optional, Default: array())
   * @return Attribute
  **/
  static public function createAttribute($name, $value, $tagName, $options=array()) {
    return new Attribute($name, $value, $tagName, $options);
  }

  /**
   * Erstellt Attribute anhand des übergebene $data-Arrays. Jenes jener Syntax entsprechen:
   * $data = array(
   *   'id'      => 'importantID',
   *   'class'   => 'inputClass',
   *   'style'   => 'color:red; font-size:1.2em;',
   *   'onclick' => array(
   *     'value'   => "this.form.dummy_textfeld.value='Ich bin Text 2 - ganz normal'",
   *     'options' => array('addSlashes'=>FALSE)
   *   )
   * )
   *
   * @param string $tagName 
   * @param array  $data 
   * @return array<Attribute>
   * @author Timo Strotmann
  **/
  static public function createAttributes($tagName, $data) {
    $attributes = array();
    foreach($data as $attrName => $attrValue) {
      if (is_string($attrValue) || is_numeric($attrValue)) {
          // In '$attrValue' steckt schon der 'value'-Wert, Optionen sind nicht vorhanden!
        $attributes[] = AttributeFactory::createAttribute($attrName, (string)$attrValue, $tagName);

      } else if (is_array($attrValue)) {
          // mit "array_shift($attrValue)" nehmen wir das erste Element aus dem Array (in dem hoffentlich der Value steht)
          // Nach den array_shift ist $attrValue um das erste Element verkürzt worden und übrig bleiben die $options
        $attributes[] = AttributeFactory::createAttribute($attrName, array_shift($attrValue), $tagName, array_shift($attrValue));

      } else if (is_bool($attrValue) && $attrValue) {
          // Der 'value' ein boolean ist und 'TRUE', dann handelt es sich z.B. um "checked=TRUE" also soll daraus checked="chekced" werden ist
        $attributes[] = AttributeFactory::createAttribute($attrName, $attrName, $tagName);
      }
    }

    return $attributes;
  }

}