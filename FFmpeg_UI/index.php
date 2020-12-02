<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FFmpeg UI</title>
		<link href="css/ffmpeg_default.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="scripts/jQuery.js"></script>
		<script src="scripts/jquery.min.js"></script>
		<script src="scripts/bootstrap.min.js"></script>
		<link href="css/video-js.css" rel="stylesheet" type="text/css">
                <script src="scripts/video.js"></script>


<link rel="apple-touch-icon" sizes="180x180" href="./images/Favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="./images/Favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="./images/Favicon/favicon-16x16.png">
<link rel="manifest" href="./images/Favicon/site.webmanifest">
<link rel="mask-icon" href="./images/Favicon/safari-pinned-tab.svg" color="#5bbad5">
<link rel="shortcut icon" href="./images/Favicon/favicon.ico">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-config" content="./images/Favicon/browserconfig.xml">
<meta name="theme-color" content="#ffffff">

		
		<script type="text/javascript">
			$(document).ready(function() {
				$("#inhalt").load("progress.php");
				var refresh = setInterval(function() {
					$("#inhalt").load("progress.php");
				}, 1000);
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#system_info").load("include/system_info.php");
				var refresh = setInterval(function() {
					$("#system_info").load("include/system_info.php");
				}, 500);
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#mkv_p").load("include/mkv_progress.php");
				var refresh = setInterval(function() {
					$("#mkv_p").load("include/mkv_progress.php");
				}, 1000);
			});
		</script>
		
		<script>
			window.setTimeout(function() {
				$(".alert").fadeTo(1000, 0).slideUp(2000, function(){
					$(this).remove(); 
				});
			}, 4000);
		</script>
		
		
	</head>
	<body>
		<?php require_once('settings.php');
			require_once('include/functions.php');
			ini_set( 'default_charset', "UTF-8" );
			session_start();
			//error_reporting(E_ALL);
			//ini_set('display_errors', 1);
			
		?>
		<!-- Main Container -->
		<div class="container">
			<!-- Header -->
			<header class="header">
				<a href="index.php"><div class="logo_img"></a><div class="donate"><a href="https://www.paypal.me/SubS7v7n" target="_blank"><img src="images/donate.png"></a></div></div>
				
			</header>
			<!-- Hero Section -->
			
			<!-- Stats Gallery Section -->
			<div class="gallery">
				<div class="thumbnail">
					
					<div class="server_health">
						<div id="system_info"><?php require('include/system_info.php'); ?></div>
					</div>
					
					
					<h4>FILE CORNER</h4>
					<?php require('updater_message.php'); ?>  
					<?php require('include/file_processing.php'); ?>
				</p>
			</div>
			
			
			<div class="thumbnail">
				<h4>FFMPEG</h4><p class="tag">
					<?php 
						
						if(isset($_SESSION["file"]))
						{
							echo "... select FFmpeg encode options and press 'ENCODE'";
						}
						else
						{
							echo "select a file on the left.";
						}
						
					?>
				</p>
				&emsp;
				<div class="ffmpeg"><?php require_once('ffmpeg.php'); ?></div>
				
			</div>
			
			<div class="thumbnail"><h4>MAKEMKV</h4><p class="tag"><?php require('include/makemkv.php'); ?></div>	
				
				<div class="thumbnail_full">
					
					<h4>STREAMING</h4>
					<?php require('include/stream.php'); ?> 	
				</div>
				
				
			</div>
			

			<details><summary>Filemanager</summary><pre><iframe width="100%" height="700" name="farben" src="http://<?php echo gethostname()?>:8000"></iframe></pre></details>
			
			<?php
				$compile_ffmpeg = file_get_contents('ffmpeg_encoding_history.txt');
				echo "</br><details><summary>FFmpeg encoding history</summary><pre>$compile_ffmpeg</pre></details></br>";
			?>

			
			<!-- <div class="gallery">
				<div class="thumbnail">
				<h4>TITLE</h4>
				<p class="tag">HTML, CSS, JS, WordPress</p>
				<p class="text_column">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
				<div class="thumbnail">
				<h4>TITLE</h4>
				<p class="tag">HTML, CSS, JS, WordPress</p>
				<p class="text_column">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
				</div>
				
			</div>-->
			<!-- Footer Section -->
			<footer id="contact">
				<details><summary>FFmpeg</summary><pre><?php require('include/ffmpeg_info.php'); ?></pre></details>
			</footer>
			<!-- Copyrights Section -->
			<div class="copyright">&copy;<?php echo date("Y");
			
			if (file_exists("include/version"))
			{ 
			$current = "include/version";        
			$version_current = implode('', file($current));
			echo " FFmpeg UI v" . trim($version_current);
			
			}
			?></div>
			</div>
			<!-- Main Container Ends -->
			</body>
			</html>
						