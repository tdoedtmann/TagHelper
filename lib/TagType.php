<?php
require_once 'Attribute.php';

define('HTML_VARIANTS',									'strict, frameset, transitional');
define('HTML_VARIANT',									'transitional');

define('XHTML_TAGS',										'a, abbr, acronym, address, applet, area, b, base, basefont, bdo, big, blockquote, body, br, button, caption, center, cite, code, col, colgroup, dd, del, dfn, dir, div, dl, dt, em, fieldset, font, form, frame, frameset, h1, h2, h3, h4, h5, h6, head, hr, html, i, iframe, img, input, ins, isindex, kbd, label, legend, li, link, map, menu, meta, noframes, noscript, object, ol, optgroup, option, p, param, pre, q, s, samp, script, select, small, span, strike, strong, style, sub, sup, table, tbody, td, textarea, tfoot, th, thead, title, tr, tt, u, ul, var');

define('STRICT_BLOCK_ELEMENTS',					'address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul');
define('STRICT_INLINE_ELEMENTS',				'a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var');

define('FRAMESET_BLOCK_ELEMENTS',				'address, blockquote, del, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, noscript, ol, p, pre, table, ul');
define('FRAMESET_INLINE_ELEMENTS',			'a, abbr, acronym, b, bdo, big, br, button, cite, code, del, dfn, em, i, img, ins, input, kbd, label, map, object, q, samp, script, select, small, span, strong, sub, sup, textarea, tt, var');

define('TRANSITIONAL_BLOCK_ELEMENTS',		'address, blockquote, center, del, dir, div, dl, fieldset, form, h1, h2, h3, h4, h5, h6, hr, ins, isindex, menu, noframes, noscript, ol, p, pre, table, ul');
define('TRANSITIONAL_INLINE_ELEMENTS',	'a, abbr, acronym, applet, b, basefont, bdo, big, br, button, cite, code, del, dfn, em, font, i, img, ins, input, iframe, kbd, label, map, object, q, s, samp, script, select, small, span, strike, strong, sub, sup, textarea, tt, u, var');

define('STANDALONE_TAGS', 							'area, base, basefont, br, col, frame, hr, img, input, isindex, link, meta, param');

define('ENCODING',											'UTF-8');


function allowedTags($include, $exclude=null) {
  $include = trimExplode($include);
  
  if (null !== $exclude) {
    $exclude = trimExplode($exclude);
    foreach($exclude as $element) {
      unset($include[$element]);
    }
  }
  
  return $include;
} 

$allowedParents = array(
  'a'           => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS.',td',                  'a,button')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS.',td',              'a,button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',td,body', 'a,button')), 
  ),
  'abbr'        => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'acronym'     => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'address'     => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ),
  'applet'      => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ),
  'area'        => array(
    'strict'       => array(allowedTags('map')), 
    'frameset'     => array(allowedTags('map')), 
    'transitional' => array(allowedTags('map')),
  ),

  'b'           => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'base'        => array(
    'strict'       => array(allowedTags('head')), 
    'frameset'     => array(allowedTags('head')), 
    'transitional' => array(allowedTags('head')),
  ),
  'basefont'    => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ),
  'bdo'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'big'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'pre')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ),
  'blockquote'  => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ),
  'body'        => array(
    'strict'       => array(allowedTags('html')), 
    'frameset'     => array(allowedTags('html')), 
    'transitional' => array(allowedTags('html, noframes')),
  ),
  'br'          => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'button'      => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'button')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'button')), 
  ),
  
  'caption'     => array(
    'strict'       => array(allowedTags('table')), 
    'frameset'     => array(allowedTags('table')), 
    'transitional' => array(allowedTags('table')),
  ),
  'center'      => array(
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ),
  'cite'        => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'code'        => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
  'col'         => array(
    'strict'       => array(allowedTags('colgroup, table')), 
    'frameset'     => array(allowedTags('colgroup, table')), 
    'transitional' => array(allowedTags('colgroup, table')),
  ),
  'colgroup'    => array(
    'strict'       => array(allowedTags('table')), 
    'frameset'     => array(allowedTags('table')), 
    'transitional' => array(allowedTags('table')),
  ),
 
  'dd'          => array(
    'strict'       => array(allowedTags('dl')), 
    'frameset'     => array(allowedTags('dl')), 
    'transitional' => array(allowedTags('dl')),
  ), 
  'del'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS.',body')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS.',body')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'dfn'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'dir'         => array(
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'div'         => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'dl'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'dt'          => array(
    'strict'       => array(allowedTags('dl')), 
    'frameset'     => array(allowedTags('dl')), 
    'transitional' => array(allowedTags('dl')),
  ), 

  'em'          => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 

  'fieldset'    => array(
    'strict'       => array(allowedTags('applet | blockquote | body | button | center | dd | del | div | fieldset | form | iframe | ins | li | map | noframes | noscript | object | td | th')), 
    'frameset'     => array(allowedTags('applet | blockquote | body | button | center | dd | del | div | fieldset | form | iframe | ins | li | map | noframes | noscript | object | td | th')), 
    'transitional' => array(allowedTags('applet | blockquote | body | button | center | dd | del | div | fieldset | form | iframe | ins | li | map | noframes | noscript | object | td | th')),
  ), 
  'font'        => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ), 
  'form'        => array(
    'strict'       => array(allowedTags('applet | blockquote | body | button | center | dd | del | div | fieldset | iframe | ins | li | map | noframes | noscript | object | td | th')), 
    'frameset'     => array(allowedTags('applet | blockquote | body | button | center | dd | del | div | fieldset | iframe | ins | li | map | noframes | noscript | object | td | th')), 
    'transitional' => array(allowedTags('applet | blockquote | body | button | center | dd | del | div | fieldset | iframe | ins | li | map | noframes | noscript | object | td | th')),
  ), 
  'frame'       => array(
    'frameset'     => array(allowedTags('frameset')), 
  ), 
  'frameset'    => array(
    'frameset'     => array(allowedTags('html')), 
  ), 
  
  'h1'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'h2'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'h3'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'h4'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'h5'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'h6'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'head'        => array(
    'strict'       => array(allowedTags('html')), 
    'frameset'     => array(allowedTags('html')), 
    'transitional' => array(allowedTags('html')),
  ), 
  'hr'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'html'        => array(
    'strict'       => array(allowedTags('')), 
    'frameset'     => array(allowedTags('')), 
    'transitional' => array(allowedTags('')),
  ), 
   
  'i'           => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'iframe'      => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'button')), 
  ), 
  'img'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'pre')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ), 
  'input'       => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'button')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'button')), 
  ), 
  'ins'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS.',body')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS.',body')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'isindex'     => array(
    'frameset'     => array(allowedTags('applet, blockquote, body, center, dd, del, div, fieldset, form, head, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, center, dd, del, div, fieldset, form, head, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 

  'kbd'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  
  'label'       => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'button')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'button')), 
  ), 
  'legend'      => array(
    'strict'       => array(allowedTags('fieldset')), 
    'frameset'     => array(allowedTags('fieldset')), 
    'transitional' => array(allowedTags('fieldset')),
  ), 
  'li'          => array(
    'strict'       => array(allowedTags('dir, menu, ol, ul')), 
    'frameset'     => array(allowedTags('dir, menu, ol, ul')), 
    'transitional' => array(allowedTags('dir, menu, ol, ul')),
  ), 
  'link'        => array(
    'strict'       => array(allowedTags('head')), 
    'frameset'     => array(allowedTags('head')), 
    'transitional' => array(allowedTags('head')),
  ), 
  
  'map'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS)), 
  ), 
  'menu'        => array(
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'meta'        => array(
    'strict'       => array(allowedTags('head')), 
    'frameset'     => array(allowedTags('head')), 
    'transitional' => array(allowedTags('head')),
  ), 
  
  'noframes'    => array(
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, frameset, iframe, ins, li, map, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, frameset, iframe, ins, li, map, noscript, object, td, th')),
  ), 
  'noscript'    => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
   
  'object'      => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS.',head',             'pre')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS.',head',         'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',head', 'pre')), 
  ), 
  'ol'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'optgroup'    => array(
    'strict'       => array(allowedTags('select')), 
    'frameset'     => array(allowedTags('select')), 
    'transitional' => array(allowedTags('select')),
  ), 
  'option'      => array(
    'strict'       => array(allowedTags('select, optgroup')), 
    'frameset'     => array(allowedTags('select, optgroup')), 
    'transitional' => array(allowedTags('select, optgroup')),
  ), 
  
  'p'           => array(
    'strict'       => array(allowedTags('address, applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('address, applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('address, applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'param'       => array(
    'strict'       => array(allowedTags('applet, object')), 
    'frameset'     => array(allowedTags('applet, object')), 
    'transitional' => array(allowedTags('applet, object')),
  ), 
  'pre'         => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  
  'q'           => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
   
  's'           => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'samp'        => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'script'      => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS.',head,body')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS.',head,body')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',head,body')), 
  ), 
  'select'      => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'button')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'button')), 
  ), 
  'small'       => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'pre')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ), 
  'span'        => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'strike'      => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'strong'      => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'style'       => array(
    'strict'       => array(allowedTags('head')), 
    'frameset'     => array(allowedTags('head')), 
    'transitional' => array(allowedTags('head')),
  ), 
  'sub'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'pre')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ), 
  'sup'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'pre')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'pre')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'pre')), 
  ), 
  
  'table'       => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  'tbody'       => array(
    'strict'       => array(allowedTags('table')), 
    'frameset'     => array(allowedTags('table')), 
    'transitional' => array(allowedTags('table')),
  ), 

  'td'          => array(
    'strict'       => array(allowedTags('tr')), 
    'frameset'     => array(allowedTags('tr')), 
    'transitional' => array(allowedTags('tr')),
  ), 
  'textarea'    => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS,                     'button')), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS,                 'button')), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body', 'button')), 
  ), 
  'tfoot'       => array(
    'strict'       => array(allowedTags('table')), 
    'frameset'     => array(allowedTags('table')), 
    'transitional' => array(allowedTags('table')),
  ), 
  'th'          => array(
    'strict'       => array(allowedTags('tr')), 
    'frameset'     => array(allowedTags('tr')), 
    'transitional' => array(allowedTags('tr')),
  ), 
  'thead'       => array(
    'strict'       => array(allowedTags('table')), 
    'frameset'     => array(allowedTags('table')), 
    'transitional' => array(allowedTags('table')),
  ), 
  'title'       => array(
    'strict'       => array(allowedTags('head')), 
    'frameset'     => array(allowedTags('head')), 
    'transitional' => array(allowedTags('head')),
  ), 
  'tr'          => array(
    'strict'       => array(allowedTags('table, tbody, tfoot, thead')), 
    'frameset'     => array(allowedTags('table, tbody, tfoot, thead')), 
    'transitional' => array(allowedTags('table, tbody, tfoot, thead')),
  ), 
  'tt'          => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  
  'u'           => array(
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ), 
  'ul'          => array(
    'strict'       => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'frameset'     => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')), 
    'transitional' => array(allowedTags('applet, blockquote, body, button, center, dd, del, div, fieldset, form, iframe, ins, li, map, noframes, noscript, object, td, th')),
  ), 
  
  'var'         => array(
    'strict'       => array(allowedTags(STRICT_BLOCK_ELEMENTS.','.STRICT_INLINE_ELEMENTS)), 
    'frameset'     => array(allowedTags(FRAMESET_BLOCK_ELEMENTS.','.FRAMESET_INLINE_ELEMENTS)), 
    'transitional' => array(allowedTags(TRANSITIONAL_BLOCK_ELEMENTS.','.TRANSITIONAL_INLINE_ELEMENTS.',body')), 
  ),
);

  /*
   * a::childs(#PCDATA, [Inline-Elemente] (außer a))
   * abbr::childs(#PCDATA [Inline-Elemente]) 
   * acronym::childs(#PCDATA [Inline-Elemente]) 
   * address::childs(#PCDATA [Inline-Elemente] | p (p nur bei  HTML Transitional)) 
   * applet::childs([Block-Elemente] | [Inline-Elemente] | param) 
   * area::childs(__LEER__) 
   * b::childs(#PCDATA [Inline-Elemente]) 
   * base::childs(__LEER__) 
   * basefont::childs(__LEER__ ) 
   * bdo::childs(#PCDATA [Inline-Elemente]) 
   * big::childs(#PCDATA [Inline-Elemente]) 
   * blockquote::childs(1. nach  HTML Strict:       [Block-Elemente] | script
                        2. nach  HTML Transitional: #PCDATA und [Block-Elemente] | [Inline-Elemente]) 
   * body::childs(1. nach  HTML Strict: [Block-Elemente] | script
                  2. nach  HTML Transitional: #PCDATA und [Block-Elemente] | [Inline-Elemente]) 
   * br::childs(__LEER__ ) 
   * button::childs(#PCDATA abbr | acronym | address | applet | b | basefont | bdo | big | blockquote | br | center | cite | code | dfn | dl | dir | div | em | font | h1-6 | hr | i | img | kbd | map | menu | noframes | noscript | object | ol | p | pre | q | samp | script | small | span | strong | sub | sup | table | tt | ul | var) 
   * 
   * caption::childs(#PCDATA [Inline-Elemente]) 
   * center::childs(#PCDATA [Block-Elemente] | [Inline-Elemente]) 
   * cite::childs(#PCDATA [Inline-Elemente]) 
   * code::childs(#PCDATA [Inline-Elemente]) 
   * col::childs(__LEER__) 
   * colgroup::childs(col) 
   * 
   * dd::childs(#PCDATA [Block-Elemente] | [Inline-Elemente]) 
   * del::childs(#PCDATA [Block-Elemente] bei Verwendung als Block-Element | [Inline-Elemente]) 
   * dfn::childs(#PCDATA [Inline-Elemente]) 
   * dir::childs(Muss (ein- oder mehrmal): li (Hinweis: li darf in diesem Zusammenhang keine Block-Elemente enthalten)) 
   * div::childs(#PCDATA [Block-Elemente] | [Inline-Elemente]) 
   * dl::childs(dd | dt) 
   * dt::childs(#PCDATA [Inline-Elemente]) 
   * 
   * em::childs(#PCDATA [Inline-Elemente]) 
   * 
   * fieldset::childs(#PCDATA legend, gefolgt von: [Block-Elemente] | [Inline-Elemente]) 
   * font::childs(#PCDATA [Inline-Elemente]) 
   * form::childs(1. nach  HTML Strict: [Block-Elemente] (außer form) | script
                  2. nach  HTML Transitional: [Block-Elemente] (außer form) | [Inline-Elemente]) 
   * frame::childs(__LEER__) 
   * frameset::childs(frame | frameset | noframes) 
   * 
   * h1::childs(#PCDATA [Inline-Elemente]) 
   * h2::childs(#PCDATA [Inline-Elemente]) 
   * h3::childs(#PCDATA [Inline-Elemente]) 
   * h4::childs(#PCDATA [Inline-Elemente]) 
   * h5::childs(#PCDATA [Inline-Elemente]) 
   * h6::childs(#PCDATA [Inline-Elemente]) 
   * head::childs(1. MUSS: title
                  2. KANN:
                  2.1. nach  HTML Strict: base, isindex, link, meta, object, script, style
                  2.2. nach  HTML Transitional: isindex) 
   * hr::childs(__LEER__) 
   * html::childs(1. Strict | Transitional: head, gefolgt von body
                  2. Frameset: head, gefolgt von frameset)
   * 
   * i::childs(#PCDATA [Inline-Elemente]) 
   * iframe::childs(#PCDATA [Block-Elemente] | [Inline-Elemente]) 
   * img::childs(__LEER__) 
   * input::childs(__LEER__) 
   * ins::childs(#PCDATA [Block-Elemente] bei Verwendung als Block-Element | [Inline-Elemente]) 
   * isindex::childs(__LEER__) 
   * 
   * kbd::childs(#PCDATA [Inline-Elemente]) 
   * 
   * label::childs(#PCDATA [Inline-Elemente] (außer label)) 
   * legend::childs(#PCDATA [Inline-Elemente]) 
   * li::childs(#PCDATA dir | menu | ol | ul
                1. bei dir und menu: [Inline-Elemente]
                2. bei ol und ul:    [Block-Elemente] | [Inline-Elemente]) 
   * link::childs(__LEER__) 
   * 
   * map::childs([Block-Elemente] | area) 
   * menu::childs(li (Hinweis: li darf in diesem Zusammenhang keine Block-Elemente enthalten)) 
   * meta::childs(__LEER__) 
   * 
   * noframes::childs(#PCDATA 
                      1. nach  HTML Frameset:     body
                      2. nach  HTML Transitional: [Block-Elemente] | [Inline-Elemente]) 
   * noscript::childs(1. nach  HTML Strict:       [Block-Elemente]
                      2. nach  HTML Transitional: #PCDATA und [Block-Elemente] | [Inline-Elemente]) 
   * 
   * object::childs(#PCDATA [Block-Elemente], [Inline-Elemente], param) 
   * ol::childs(li) 
   * optgroup::childs(option) 
   * option::childs(#PCDATA) 
   * 
   * p::childs(#PCDATA [Inline-Elemente]) 
   * param::childs(__LEER__) 
   * pre::childs(#PCDATA a, abbr, acronym, applet, b, bdo, br, button, cite, code, dfn, em, i, input, iframe, kbd, label, map, q, samp, script, select, span, strong, textarea, tt, var) 
   * 
   * q::childs(#PCDATA [Inline-Elemente]) 
   * 
   * s::childs(#PCDATA [Inline-Elemente]) 
   * samp::childs(#PCDATA [Inline-Elemente]) 
   * script::childs(Darf nichts als #PCDATA enthalten, die als Script-Code zu interpretieren sind) 
   * select::childs(#PCDATA optgroup, option) 
   * small::childs(#PCDATA [Inline-Elemente]) 
   * span::childs(#PCDATA [Inline-Elemente]) 
   * strike::childs(#PCDATA [Inline-Elemente]) 
   * strong::childs(#PCDATA [Inline-Elemente]) 
   * style::childs(Darf nichts als #PCDATA enthalten, die als Style-Definitionen interpretiert werden) 
   * 
   * sub::childs(#PCDATA [Inline-Elemente]) 
   * sup::childs(#PCDATA [Inline-Elemente]) 
   * 
   * table::childs(Darf folgende anderen HTML-Elemente (in dieser Reihenfolge) enthalten: caption (optional), col oder colgroup (optional), thead (optional), tfoot (optional), tbody (ein oder mehrere - wenn nur einmal benötigt, darf tbody auch entfallen, weshalb die herkömmliche Konstruktion, wonach table direkt aus tr-Elementen besteht, ebenfalls zulässig ist)) 
   * tbody::childs(tr) 
   * 
   * td::childs([Block-Elemente], [Inline-Elemente]) 
   * textarea::childs(Darf nichts als #PCDATA enthalten) 
   * tfoot::childs(tr) 
   * th::childs([Block-Elemente], [Inline-Elemente]) 
   * thead::childs(tr) 
   * title::childs(Darf nichts als #PCDATA enthalten.) 
   * tr::childs(td, th) 
   * tt::childs(#PCDATA [Inline-Elemente]) 
   * 
   * u::childs(#PCDATA [Inline-Elemente]) 
   * ul::childs(li) 
   * 
   * var::childs(#PCDATA [Inline-Elemente]) 
   */  


  $allowedParent = array(
    'a'     => '',
  );






function trimExplode($string, $delim=',', $onlyNonEmptyValues=true)    {
  $temp = explode($delim,$string);
  $newtemp = array();
  while (list($key, $val) = each($temp))      {
    if (!$onlyNonEmptyValues || strcmp('', trim($val)))      {
      $newtemp[] = trim($val);
    }
  }
  reset($newtemp);
  return $newtemp;
} 

function viewArray($array)  {
  if (!is_array($array)) {
    return false;
  }

  if (!count($array)) {
    $tableContent.= Tag::createTag('tr')->setContent(
    Tag::createTag('td')->setContent(
    Tag::createTag('strong')->setContent(htmlspecialchars("EMPTY!"))));

  } else {
    while (list($key, $val) = each($array)) {
      $td1 = Tag::createTag('td', array('valign'=>'top'))->setContent(htmlspecialchars((string)$key));

      $tdValue = (is_array($array[$key])) ? viewArray($array[$key]) : Tag::createTag('span', array('style'=>'color:red;'))->setContent(nl2br(htmlspecialchars((string)$val)) . Tag::createTag('br'));
      $td2 = Tag::createTag('td', array('valign'=>'top'))->setContent($tdValue);
       
      $tableContent.= Tag::createTag('tr')->setContent($td1 . $td2);
    }
  }
   
  $tableAttr = array(
    'cellpadding' => '1',
    'cellspacing' => '0',
    'border'      => '1'
  );
  
   return Tag::createTag('table', $tableAttr)->setContent($tableContent);
} 



/**********************************************************************
 * Exception-Klassen
 **********************************************************************/

/**
 * Exception Klasse für AbstractTag-Fehler
 *
 * @author Timo Strotmann
 */
class AbstractTagException extends Exception {
} 

/**
 * Exception Klasse für TagHtmlVariant-Fehler
 *
 * @author Timo Strotmann
 */
class TagHtmlVariantException extends Exception {
} 

/**
 * Exception Klasse für TagInlineElement-Fehler
 *
 * @author Timo Strotmann
 */
class TagInlineElementException extends Exception {
} 

/**
 * Exception Klasse für UnknownTag-Fehler
 *
 * @author Timo Strotmann
 */
class UnknownTagException extends Exception {
} 

/**
 * Exception Klasse für TagFactory-Fehler
 *
 * @author Timo Strotmann
 */
class TagTypeException extends Exception {
} 

/**
 * Exception Klasse für StandaloneTag-Fehler
 *
 * @author Timo Strotmann
 */
class StandaloneTagException extends Exception {
} 



/**
 *
 * @author Timo Strotmann
 */
interface TagInterface {
  public function getName();
  public function getAttributes();
  public function getAttribute($name);
  public function isInlineTag();
  public function setName($name);
  public function addAttribute(Attribute $value);
  public function addAttributes($value);
  public function removeAttribute(Attribute $value);
  public function display();
} 



/**
 *
 * @author Timo Strotmann
 */
class AbstractTag implements TagInterface {

  protected $name = null;
  //protected $parent = null;
  protected $attributes = null;
  protected $isInlineTag = true;
  protected $isBlockTag  = true;

  protected $displayContentWithHtmlEntities = false;

  /**
   * Konstruktor von AbstractTag.
   *
   * @param string $name Name des zu erstellenden Tags
   * @return void
   */
  public function __construct($name) {
    $this->name = $name;
    $attributes = array();

    if (false === array_search(HTML_VARIANT, trimExplode(HTML_VARIANTS))) {
      throw new TagHtmlVariantException('Die angegeben HTML-Variante existiert nicht');
    }

    switch(HTML_VARIANT) {
      case 'strict':
        $this->isInlineTag = in_array($this->name, trimExplode(STRICT_INLINE_ELEMENTS));
        $this->isBlockTag  = in_array($this->name, trimExplode(STRICT_BLOCK_ELEMENTS));
        break;

      case 'frameset':
        $this->isInlineTag = in_array($this->name, trimExplode(FRAMESET_INLINE_ELEMENTS));
        $this->isBlockTag  = in_array($this->name, trimExplode(FRAMESET_BLOCK_ELEMENTS));
        break;

      case 'transitional':
        $this->isInlineTag = in_array($this->name, trimExplode(TRANSITIONAL_INLINE_ELEMENTS));
        $this->isBlockTag  = in_array($this->name, trimExplode(TRANSITIONAL_BLOCK_ELEMENTS));
        break;
    }
  } 

  /**
   * __toString()-Methode zum ausgeben des Tags (siehe display()-Methode).
   *
   * @return string
   */
  public function __toString() {
    return $this->display();
  } 

  /**
   * Gibt den Namen des Tags zurück.
   *
   * @return string
   */
  public function getName() {
    return $this->name;
  } 

  // :TODO: Wenn man das Parent-Element (-Tag) mit abspeichert, kann man besser validieren, denn es ist ja nicht jedes Tag überall erlaubt!
  //	public function getParent() {
  //		return $this->parent;
  //	}

  /**
   * Gibt ein Array mit den Attributen zu diesem Tags zurück.
   *
   * @return array
   */
  public function getAttributes() {
    return $this->attributes;
  } 

  /**
   * Gibt ein Array mit den Attributen zu diesem Tags zurück.
   *
   * @return array
   */
  public function getAttribute($name) {
    if (array_key_exists($name, $this->attributes)) {
      return $this->attributes[$name];
    }
    return false;
  } 

  /**
   * Gibt TRUE zurück, wenn es sich bei dem Tag um ein "Inline-Tag", wie z.B. <br />, <hr />, <span> etc., zurück.
   *
   * @return boolean
   */
  public function isBlockTag() {
    return (boolean)$this->isBlockTag;
  } 

  /**
   * Alias-Methode für isBlockTag()
   *
   * @return boolean
   */
  public function isBlockElement() {
    return $this->isBlockTag();
  } 

  /**
   * Alias-Methode für isBlockTag()
   *
   * @return boolean
   */
  public function isBlock() {
    return $this->isBlockTag();
  } 

  /**
   * Gibt TRUE zurück, wenn es sich bei dem Tag um ein "Inline-Tag", wie z.B. <br />, <hr />, <span> etc., zurück.
   *
   * @return boolean
   */
  public function isInlineTag() {
    return (boolean)$this->isInlineTag;
  } 

  /**
   * Alias-Methode für isInlineTag()
   *
   * @return boolean
   */
  public function isInlineElement() {
    return $this->isInlineTag();
  } 

  /**
   * Alias-Methode für isInlineTag()
   *
   * @return boolean
   */
  public function isInline() {
    return $this->isInlineTag();
  } 

  /**
   * Setzt den Namen des Tags.
   *
   * @return Tag $this
   */
  public function setName($value) {
    $this->name = $value;
    return $this;
  } 

  /**
   * Fügt dem Tag ein Attribute hinzu.
   *
   * @return Tag $this
   */
  public function addAttribute(Attribute $value) {
    $this->attributes[$value->getName()] = $value;
    return $this;
  } 

  /**
   * Fügt dem Tag die übergebenen Attribute hinzu.
   *
   * @return Tag $this
   */
  public function addAttributes($value) {
    if (is_array($value) && !empty($value)) {
      foreach($value as $attribute) {
        if ($attribute instanceof Attribute) {
          $this->addAttribute($attribute);
        } else {
          throw new AbstractTagException('Die Methode \'addAttributes()\' erwartet ein Array mit Attribute-Objekten!');
        }
      }
    }

    return $this;
  } 

  /**
   * Entfernt dem Tag das übergebene Attribute.
   *
   * @return Tag $this
   */
  public function removeAttribute(Attribute $value) {
    if (array_key_exists($value->getName(), $this->attributes)) {
      unset($this->attributes[$value->getName()]);
    }
    
    return $this;
  } 

  /**
   * Ist dieser Wert gesetzt, so wird der Inhalt, der innerhalb eines ModularTags (z.B. <p>-Tag) steht, mit der htmlentities()-Funktion aufgerufen.
   *
   * @return Tag $this
   */
  public function setHtmlentities($value) {
    $this->displayContentWithHtmlEntities = (boolean)$value;
    return $this;
  } 

  /**
   * Gibt TRUE zurück, wenn der Inhalt des Tags mit der htmlentities()-Funktion von PHP aufgerufen werden soll, andernfalls FALSE.
   *
   * @return boolean
   */
  public function getHtmlentities() {
    return $this->displayContentWithHtmlEntities;
  } 

  /**
   * Rendern den Anfang des Tags.
   * Dies behinhaltend das öffnenden des Tags und die Auflistung der Attribute, z.B. '<a href="http://www.timo-strotmann.de" target="_blank"'.
   * Zu beachten ist, das das öffnede Tag nicht nicht geschlossen wird, dies liegt daran, das ein StandaloneTag mit ' />' und ein ModularTag mit '>' geschlossen wird!
   *
   * @return string
   */
  public function display() {
    $str = '<'.$this->name;
    if (!empty($this->attributes)) {
      foreach($this->attributes as $attr) {
        $str.= $attr;
      }
    }

    return $str;
  }
  
} 


/**
 * Hierbei handelt es sich um Tags, die kein abschließdes Tag haben, wie z.B. <br />, <hr />, <input /> etc.
 *
 * @author Timo Strotmann
 */
class StandaloneTag extends AbstractTag {

  protected $content = '';
  protected $contentAfter = true;

  /**
   * Gibt den "Content", der für diesen Tag hinterlegt ist, zurück.
   *
   * @return mixed $content
   */
  public function getContent() {
    return $this->content;
  } 

  /**
   * Setzt den "Content" (z.B. die "Beschriftung" eines <input>-Tags), der vor oder hinter dem Tag erscheinen soll.
   *
   * @param string $content
   * @return Tag $this
   */
  public function setContent($content) {
    if (is_string($content)) {
      $this->content = $content;
    } else {
      throw new StandaloneTagException('Der in $content hinterlegte Wert muss ein String sein!');
    }
    return $this;
  } 

  /**
   * Bestimmt, ob der "Content" (z.B. die "Beschriftung" eines <input>-Tags) vor oder hinter dem Tag stehen soll.
   *
   * @param boolean $value
   * @return Tag $this
   */
  public function setContentAfter($value = true) {
    $this->contentAfter = (boolean)$value;
    return $this;
  } 

  /**
   * Bestimmt, ob der "Content" (z.B. die "Beschriftung" eines <input>-Tags) vor oder hinter dem Tag stehen soll.
   *
   * @param boolean $value
   * @return Tag $htis
   */
  public function setContentBefore($value = false) {
    $this->setContentAfter(!(boolean)$value);
    return $this;
  } 

  /**
   * Vervollständigung der AbstragTag::display()-Methode
   *
   * @return string
   */
  public function display() {
    $tag = parent::display() . ' />';

    $content = '';
    if (is_array($this->content)) {
      foreach($this->content as $value) {
        $content.=$value;
      }
    } else {
      $content = $this->content;
    }

    if ($this->contentAfter) {
      $str = $tag . $content;
    } else {
      $str = $content . $tag;
    }
    
    return $str;
  } 
  
} 


/**
 * Hierbei handelt es sich um Tags, die sowhol aus einem öffnedem und schließendem Tag bestehen, wie z.B. <p>, <span>, <textarea> etc.
 *
 * @author Timo Strotmann
 */
class ModularTag extends AbstractTag {

  protected $content = '';

  /**
   * Gibt den "Content", der z.B. zwischen dem öffneden und schließendem Tag steht (z.B. 'Hello World!' für <p>Hello World!</p>) zurück.
   *
   * @return mixed $content
   */
  public function getContent() {
    return $this->content;
  } 

  /**
   * Setzt den "Content", der zwischen dem öffneden und schließendem Tag steht (z.B. 'Hello World!' für <p>Hello World!</p>).
   *
   * @param string $content
   * @return Tag $this
   */
  public function setContent($content) {
    $this->content = $content;
    return $this;
  } 

  /**
   * Vervollständigung der AbstragTag::display()-Methode
   *
   * @return string
   */
  public function display() {
    $tag = parent::display();

    $content = '';
    if (is_array($this->content)) {
      foreach($this->content as $value) {
        $content.= $value;
      }
    } else {
      $content = $this->content;
    }
    
    if ($this->displayContentWithHtmlEntities) {
      $content = htmlentities($content, ENT_NOQUOTES, ENCODING);
    }
    
    return "{$tag}>{$content}</{$this->name}>";
  } 
  
  
} 
