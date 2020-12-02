<?php
	/////////// Check Version ///////////
	if (file_exists("include/version")) {
		$current = "include/version";
		$version_current = implode('', file($current));
		} else {
		echo "<div class='alert alert-danger'>Cannot determine installed version.</div>";
	}
	$online = "https://raw.githubusercontent.com/Sub-7/FFmpegUI/master/FFmpeg_UI/version";
	$version_online = implode('', file($online));
	if ($version_online != "") {
		if ($version_current != $version_online) {
			echo "<div class='alert alert-success'>New update is available! <a href='updater.php'><ul class='form-style-1'><input type='submit' name='info' value='SEE MORE'/></ul></a></div>";
		}
	}
?>