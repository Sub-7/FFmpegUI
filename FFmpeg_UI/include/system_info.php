<div id="system_info" >
	<?php require_once('server_health.php'); ?>
	<div class="server_health" ></div>
    <script>
		var canvas = document.getElementById('trafficlight');
		var context = canvas.getContext('2d');
		var centerX = canvas.width / 2;
		var centerY = canvas.height / 2;
		var radius = 5;
		
		context.beginPath();
		context.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
		context.fillStyle = '<?php echo $trafficlight; ?>';
		context.fill();
		context.lineWidth = 1;
		context.strokeStyle = '#003300';
		context.stroke();
	</script>
	<canvas id="trafficlight" width="11" height="11"></canvas>
	CPU:  <?php echo $cpuload; ?>% |
	RAM Free: <?php echo $memfree; ?> GB |
	HDD Free:<?php echo $diskfree; ?> GB |
	HDD Total:<?php echo $disktotal; ?> GB
</div>