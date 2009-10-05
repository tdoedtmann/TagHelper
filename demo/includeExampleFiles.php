<?php
	$ignoreFiles = array('.', '..');
	if ($path = dirname(__FILE__).'/examples/') {
		foreach (scandir($path) as $index => $file) {
			if (in_array($file, $ignoreFiles)) {
				continue;
			}
			
			if (isset($_GET['exampleType']) && $_GET['exampleType'] != substr($file, 0, strlen($_GET['exampleType']))) {
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
						<p><?php echo date('d.m.Y', filemtime($path.$file));?></p>
					</div>
					<div class="entry">
					
						<div id="boxTabContent_<?php echo $index?>" style="display: <?php echo $display ?>; border: 1px solid black;">
							<div class="tabs">
								<ul class="tabMenu">
									<li class="tabMenuItem"><a href="#tabContent_1_<?php echo $index?>">Example</a></li>
									<li class="tabMenuItem"><a href="#tabContent_2_<?php echo $index?>">Code</a></li>
									<?php if (!empty($_POST[$fileName])): ?>								
										<li class="tabMenuItem"><a href="#tabContent_3_<?php echo $index?>">POST</a></li>
									<?php endif; ?>

									<?php if (!empty($_GET)): ?>								
										<li class="tabMenuItem"><a href="#tabContent_4_<?php echo $index?>">GET</a></li>
									<?php endif; ?>
									
								</ul>
								<br style="clear: both;" />
								
								<div id="tabContent_1_<?php echo $index?>" class="ui-tabs-hide" style="overflow:auto;">
									<div id="exampleTabConent_<?php echo $index?>">
										<?php include_once $path.$file; ?>
									</div>
								</div>
								<div id="tabContent_2_<?php echo $index?>">
									<div id="sourceTabConent_<?php echo $index?>" style="overflow:auto;">
										<pre class="brush: php; tab-size: 2; wrap-lines: false">
<?php echo htmlspecialchars(file_get_contents($path.$file)); ?>
										</pre>
									</div>
								</div>
								
								<?php if (!empty($_POST[$fileName])): ?>								
								<div id="tabContent_3_<?php echo $index?>">
									<div id="postTabConent_<?php echo $index?>" style="overflow:auto;">
										<?php echo viewArray($_POST[$fileName]); ?>
									</div>
								</div>
								<?php endif; ?>
								
								<?php if (!empty($_GET)): ?>								
								<div id="tabContent_4_<?php echo $index?>">
									<div id="getTabConent_<?php echo $index?>" style="overflow:auto;">
										<?php echo viewArray($_GET); ?>
									</div>
								</div>
								<?php endif; ?>
								
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
