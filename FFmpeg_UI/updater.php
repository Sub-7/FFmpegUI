<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>FFmpeg UI Update</title>
		<link href="css/ffmpeg_default.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="scripts/jQuery.js"></script>
		<script src="scripts/jquery.min.js"></script>
		<script src="scripts/bootstrap.min.js"></script>
		
		
		
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
			ini_set( 'default_charset', "UTF-8" );
			session_start();
		?>
		<!-- Main Container -->
		<div class="container">
			<!-- Header -->
			<header class="header">
				<div class="logo_img"><div class="donate"><a href="https://www.paypal.me/SubS7v7n" target="_blank"><img src="images/donate.png"></a></br></div></div>
				
			</header>
			<!-- Hero Section -->
			
			<!-- Stats Gallery Section -->
			<div class="gallery">
				<div class="thumbnail_full">
					<h4>UPDATE</h4>
					<div class="text_updater">
						<?php
							
							/////////// Check Version ///////////
							
							if (file_exists("include/version"))
							{ 
								$current = "include/version";        
								$version_current = implode('', file($current));
								echo "</br>Current: V" . trim($version_current);
								
								}else{
								echo "<div class='alert alert-danger'>Cannot determine installed version.</div>";
							}
							
							$online = "https://raw.githubusercontent.com/Sub-7/FFmpegUI/master/FFmpeg_UI/version";
							$version_online = implode('', file($online));
							if ($version_online !="")
							{ 
								
								echo "</br>Online :  V" . trim($version_online); 
								if($version_current != $version_online)
								{
									echo "<h4>New update is available.</h4>Please visit following link and follow the update instruction. <a href='https://github.com/Sub-7/FFmpegUI' target='_blank'>https://github.com/Sub-7/FFmpegUI</a></br>
									If you want to skip the version, edit /FFmpeg_UI/include/version and replace for example 1.01 through 1.02
									<hr>";
									
								?>
								
							</th>
							<th>
								<?php
									
									}else{
									echo "<hr>You're up to date.";
								}    
								}else{
								echo "<div class='alert alert-danger'>Cannot determine online version.</div>";
							}
						?>
						
						<form method='post' action='index.php'><ul class="form-style-1"><input type="submit" name="info" value="BACK"/></ul></form>
						
					</div>
					</div>  
					</div>
					<!-- Footer Section -->
					<footer id="contact">
					<details><summary>FFmpeg</summary><pre><?php require('include/ffmpeg_info.php'); ?></pre></details>
					</footer>
					<!-- Copyrights Section -->
					<div class="copyright">&copy;<?php echo date("Y"); ?> FFmpeg UI v1.01</div>
					</div>
					<!-- Main Container Ends -->
					</body>
					</html>								