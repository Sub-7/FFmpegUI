<!-- Get Stream  -->
<div class=video>
	<form name="file" class="form-style-1" action='' method='post'>
		<h4>Get Stream:</h4><input class="field-long-stream" type="text" name="get_stream" value="">
		<input type='submit' name='get' value='Get'/><br><br>
	</form>
	
	<?php
		
		//////////////////// isert contents to file  ////////////////////
		$link_list = 'include/link_list';
		if (isset($_POST['get'])) {
			if ($_POST['get_stream'] != "") {
				$stream_link = $_POST['get_stream'];
				$current = file_get_contents($link_list);
				$current .= $stream_link."\n";
				file_put_contents($link_list, $current);
			}
		}
		
		///////// delete line from list  /////////
		if (isset($_POST['stream_delete_x'])) {
			exec('sed -i ' . $_POST['line_number'] . 'd ' . $link_list);
		}
		///////// load Link for Player  /////////
		if (isset($_POST['stream_play_x'])) {
			$Stream_Link = $_POST['line'];
		}
		
		//////////////////// show file contents  ////////////////////	
		
		if (file_exists($link_list)) {
			$handle = fopen("$link_list", "r");
			if ($handle) {
				echo "<div class='stream_list'>";
				$lineNumber = "0";
				while (($line = fgets($handle)) !== false) {
					$lineNumber++;
					
				?>
				<form action='' name='' method='post'>
					<input type="hidden" name="line" width="14" value="<?php echo $line; ?>"/>
					<input type="hidden" name="line_number" width="14" value="<?php echo $lineNumber; ?>"/>
					<input type="image" name="stream_play" width="25" src="images/play.png" value="<?php echo $lineNumber; ?>"/>
					<input type="image" name="stream_delete" width="14" src="images/delete.png" value="<?php echo $lineNumber; ?>"/><?php	echo $lineNumber . ": " . $line;?>
				</form>
				
				<?php
					
				}
				echo $line . "</div>";
				fclose($handle);
				} else {
				// error opening the file.
				echo "not ok";
			}
		}
		
		
		///////// Create Stream  /////////
		if (isset($_POST['create'])) {
			if ($_POST['create_stream'] != "") {
				echo $_POST['create_stream'];
			}
		}
	?>
	
	
	<br><form name="file" class="form-style-1" action='' method='post'>
		<h4>Create Stream:</h4><input class="field-long-stream" type="text" name="create_stream" value="">
		<input  type='submit' name='create' value='Create'/>
	</form>
	
	
</div>

<!-- video-js -->

<div class=video>
	<div class=tv><img class=box stack-top src="images/tv.png" width="620" ></div>
	<video-js id="vid1" controls preload="auto" autoplay width="600">
		
		<source src="<?php echo $Stream_Link; ?>" type="application/x-mpegURL">
	</video-js>	
	
</div>
<script>
	var vid = document.getElementById('vid1');
	var player = videojs(vid);
</script>														