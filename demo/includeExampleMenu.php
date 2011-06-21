<?php
  $menuItems = array();
  $ignoreFiles = array('.', '..');
  if ($path = dirname(__FILE__).'/examples/') {
    foreach (scandir($path) as $index => $file) {
      if (in_array($file, $ignoreFiles)) {
        continue;
      }
      $display = (!isset($display)) ? 'block' : 'none';
      $pathInfo = pathinfo($file);
      if ($pathInfo['extension'] == 'php') {
        $fileName = basename($file, '.php');
        $fileName = substr($fileName, 0, strrpos($fileName, '_'));
        $title = ucwords(str_replace('_', ' ', $fileName));
        $menuItems[$fileName] = $title;
      }
    }
  }
?>

<ul>
<?php 
foreach ($menuItems as $key => $item) {
  echo '<li><a href="?exampleType='.$key.'">'.$item.'</a></li>';
}
?>
</ul>
