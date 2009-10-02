<?php
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
				$title = ucwords(str_replace('_', ' ', $fileName));
				?>
				<div class="post">
					<div class="title">
						<h2><a href="#"><span class="pointer toggle" id="toggleTabBox_<?php echo $index?>" onclick="toogleBox(this, 'boxTabContent_<?php echo $index?>');"><?php echo (($display=='block') ? '[&minus;]' : '[+]')?> <?php echo $title?></span></a></h2>
						<p>10.11.08</p>
					</div>
					<div class="entry">
					
						<div id="boxTabContent_<?php echo $index?>" style="display: <?php echo $display ?>; border: 1px solid black;">
							<div class="tabs">
								<ul class="tabMenu">
									<li class="tabMenuItem"><a href="#tabContent_1_<?php echo $index?>">Example</a></li>
									<li class="tabMenuItem"><a href="#tabContent_2_<?php echo $index?>">Code</a></li>
									<li class="tabMenuItem"><a href="#tabContent_3_<?php echo $index?>">POST</a></li>
									<li class="tabMenuItem"><a href="#tabContent_4_<?php echo $index?>">GET</a></li>
								</ul>
								<br style="clear: both;" />
								
								<div id="tabContent_1_<?php echo $index?>" class="ui-tabs-hide" style="overflow:auto;">
									<div id="exampleTabConent_<?php echo $index?>">
										<p><?php include_once $path.$file; ?></p>
									</div>
								</div>
								<div id="tabContent_2_<?php echo $index?>">
									<div id="sourceTabConent_<?php echo $index?>" style="overflow:auto;">
										<pre>
<?php echo str_replace(array("<?php", "?>"), array("&lt;?php", "&gt;"), file_get_contents($path.$file)); ?>
										</pre>
									</div>
								</div>
								
								<div id="tabContent_3_<?php echo $index?>">
									<div id="postTabConent_<?php echo $index?>" style="overflow:auto;">
										<pre>
<?php echo var_export($_POST,1); ?>
										</pre>
									</div>
								</div>
								
								<div id="tabContent_4_<?php echo $index?>">
									<div id="getTabConent_<?php echo $index?>" style="overflow:auto;">
										<pre>
<?php echo var_export($_GET,1); ?>
										</pre>
									</div>
								</div>
								
							</div>
							<br style="clear: both;" />
							
						</div>
					</div>
				</div>
			<?php
			}
		}
	}
?>
