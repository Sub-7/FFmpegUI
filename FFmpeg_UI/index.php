<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FFmpeg UI</title>
<link href="css/ffmpeg_default.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="include/jQuery.js"></script>
	<script type="text/javascript">
     $(document).ready(function() {
       $("#inhalt").load("progress.php");
       var refresh = setInterval(function() {
          $("#inhalt").load("progress.php");
       }, 10);
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
</head>
<body>
<?php require_once('settings.php');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!-- Main Container -->
<div class="container">
	<!-- Header -->
  <header class="header">
	  <div class="logo_img"><div class="donate"><a href="https://www.paypal.me/SubS7v7n" target="_blank"><img src="images/donate.png"></a></div></div>
	  
  </header>
  <!-- Hero Section -->
  
  <!-- Stats Gallery Section -->
  <div class="gallery">
    <div class="thumbnail">
		<div class="server_health">
			
	  <div id="system_info"><?php require('include/system_info.php'); ?></div>
		</div>
      <h4>SELECT A FILE</h4>
      <p class="tag">... select a file and press a button
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
		 echo "... first select a file on the left";
	   }
	
		
		
		?>
		</p>
		
	
		
		
      <div class="ffmpeg"><?php require_once('ffmpeg.php'); ?></div>
		
    </div>
	
  </div>
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
  <div class="copyright">&copy;<?php echo date("Y"); ?> FFmpeg UI v1.0</div>
</div>
<!-- Main Container Ends -->
</body>
</html>
