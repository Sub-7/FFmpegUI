<?php
	
	echo $SR0=shell_exec("chown -R www-data /dev/sr0");
	$disc_progress="disc_progress";
	$BD="BD-ROM";
	$DVD="DVD-ROM";
	$DISC_Info="DISC_Info";
	
?>
<form  class="form-style-2" name="disc" align="center"  method="post" action="" >
	<table align="center">
		<td colspan="2">	
			<?php	
				//////////////////// eject
				if (isset($_POST["eject"]))
				{echo $MakeMKV=shell_exec("eject");}
				
				//////////////////// Rip Title	
				if (isset($_POST["ripp_title"]))
				{   
					$get_label=shell_exec("blkid -o value -s LABEL /dev/dvd 2>&1");
					$mounted_media=shell_exec("dvd+rw-mediainfo /dev/sr0 | grep 'Mounted Media:'");
					$disc_info=shell_exec("lsdvd 2>&1");
					$mounted_media."<br>";
					$DISC=trim($mounted_media);
					$DISC=substr($mounted_media, 29, -1);
					if ($DISC == $BD) {$image="blu-ray.png";}
					if ($DISC == $DVD){	$image="dvd.png";}	
					$TITLE=$_POST["option"];
					echo "Title " . $TITLE . " saved, ...press 'RELOAD'<br><br>";
					$MakeMKV=shell_exec("makemkvcon --decrypt --progress=disc_progress --robot mkv disc:0 $TITLE /var/www/html/FFmpeg_UI/media/input 2>&1");
					unlink('disc_progress');
				}
				
			//////////////////// Backup	
			if (isset($_POST["backup"]))
			{   
			$get_label=shell_exec("blkid -o value -s LABEL /dev/dvd 2>&1");
			$mounted_media=shell_exec("dvd+rw-mediainfo /dev/sr0 | grep 'Mounted Media:'");
			$disc_info=shell_exec("lsdvd 2>&1");
			$mounted_media."<br>";
			$DISC=trim($mounted_media);
			$DISC=substr($mounted_media, 29, -1);
			if ($DISC == $BD) {$image="blu-ray.png";}
			if ($DISC == $DVD){	$image="dvd.png";}
			$MakeMKV=shell_exec("makemkvcon --decrypt --progress=disc_progress --robot mkv disc:0 all /var/www/html/FFmpeg_UI/media/input 2>&1");
			unlink('disc_progress');
			}
			
			////////////////////Read Disc
			if (isset($_POST["read_disc"]) AND !empty($_POST["read_disc"]))
			{	
			$DISC=trim($mounted_media);
			$DISC=substr($mounted_media, 29, -1);
			
			
			
	        if (file_exists($disc_progress)) {unlink('disc_progress');}
			if (file_exists($DISC_Info)) {unlink('DISC_Info');}
			echo $BD_Info=shell_exec("makemkvcon -r info disc:0 > DISC_Info");
			
			/// get title
			$lines = file('DISC_Info');
			$searchstr = 'CINFO:2,0,';
			foreach ($lines as $line)
			{if(strpos($line, $searchstr) !== false){$title[] = $line;}}
			/// get title_total
			$lines7 = file('DISC_Info');
			$searchstr7 = 'TCOUNT:';
			foreach ($lines7 as $line7)
			{if(strpos($line7, $searchstr7) !== false){$title_total[] = $line7;}}
			
			$title_total=substr($title_total[0], 7, -1);
			$title=substr($title[0], 11, -2);
			
			//Get Title + Info
			$Title_count = 0;
			$number="0";
			$cut13=13;
			$cut14=14;
			$cut16=16;
			
			if (isset($title)){echo "<h3>".$title."</h3><br>";}
			
			echo "<table border='1' width='100%' align='ceter'>";
			echo "<tr><th>Title</th>";
			echo "<th>Size</th>";
			echo "<th>Duration</th>";
			echo "<th>Resolution</th>";
			echo "<th>Aspect ratio</th>";
			echo "<th>Bitrate</th>";
			echo "</tr>";
			
			do
			{
			if($number > 9){ $cut13=14;	$cut14=15; $cut16=17; }					
			/// get duration
			$linesL1 = file('DISC_Info');
		    $searchstrL1 = 'TINFO:'.$number.',9,0,';
            foreach ($linesL1 as $lineL1)
            {if(strpos($lineL1, $searchstrL1) !== false){$durationL1[] = $lineL1;}}
			$duration=substr($durationL1[$number], $cut13, -2);
			
			/// get size
            $linesL2 = file('DISC_Info');
            $searchstrL2 = 'TINFO:'.$number.',10,0,';
			foreach ($linesL2 as $lineL2)
            {if(strpos($lineL2, $searchstrL2) !== false){$sizeL2[] = $lineL2;}}
			$disc_size=substr($sizeL2[$number], $cut14, -2);
			
			/// get Resolution
            $linesL3 = file('DISC_Info');
            $searchstrL3 = 'SINFO:'.$number.',0,19,0,';
            foreach ($linesL3 as $lineL3)
            {if(strpos($lineL3, $searchstrL3) !== false){$resolutionL3[] = $lineL3;}}
			$resolution=substr($resolutionL3[$number], $cut16, -2);
			
			/// get aspect_ratio
			$linesL4 = file('DISC_Info');
            $searchstrL4 = 'SINFO:'.$number.',0,20,0,';
            foreach ($linesL4 as $lineL4)
            {if(strpos($lineL4, $searchstrL4) !== false){$aspect_ratioL4[] = $lineL4;}}
			$aspect_ratio=substr($aspect_ratioL4[$number], $cut16, -2);
			
			/// get bitrate
            $linesL5 = file('DISC_Info');
            $searchstrL5 = 'SINFO:'.$number.',0,13,0,';
            foreach ($linesL5 as $lineL5)
            {if(strpos($lineL5, $searchstrL5) !== false){$bitrateL5[] = $lineL5;}}
			$bitrate=substr($bitrateL5[0], $cut16, -2);
			//if (isset($bitrate) AND !empty($bitrate)){$bitrate=substr($bitrateL5[0], $cut16, -2);}else{$bitrate="";}
			
			
			
            /// draw table			
			echo "<tr>";
			echo "<td ><div align='left'>						
			<input type='radio' name='option' id='rad' value='$number' />
			</form>Title $number</div></td>";
			echo "<td>$disc_size</td>";
			echo "<td>$duration</td>";
			echo "<td>$resolution</td>";
			echo "<td>$aspect_ratio</td>";
			echo "<td>$bitrate</td>";
			echo "</tr>";
			$Title_count++;
			$number++;
			} 
			while ($Title_count < $title_total);
			echo "</table><br>";
			echo "<input type='submit' name='ripp_title' value='Rip Title'/><input type='submit' name='backup' value='Backup'/>";
			}
			
			
			
			?>	 
			<input type='submit' name='read_disc' value='Read Disc'/>
			<input type='submit' name='eject' value='Eject'/>
			<p>&nbsp;</p>			
			</td>
			</tr>
			<tr>
			<td colspan="3">
			<?php
			//progressbar
			echo "<div id='mkv_p'>";
			require('include/mkv_progress.php');
			echo "</div>";
			?>
			<p>&nbsp;</p></td></tr></table>
			</form>
			
			<details class="summary"> 
			<summary>
			Help
			</summary>
			<details class="summary">
			<summary>Key</summary>
			<hr>
			your MakeMKV Key:<br>
			<?php 
			$MakeMKV_KEY=shell_exec("cat /root/.MakeMKV/settings.conf | grep app_Key"); 
			$MakeMKV_KEY=substr($MakeMKV_KEY, 11, -2);
			echo $MakeMKV_KEY. "<br>(Key location: /root/.MakeMKV/settings.conf)"; 
			?>
			<br>If MakeMKV does not work, check the key <a href="https://www.makemkv.com/forum/viewtopic.php?t=1053">Link</a>
			<br>If you like this wonderful piece of software, buy it <a href="http://www.makemkv.com/buy/">Link</a>
			<hr>
			</details>
			
			
			<details class="summary">
			<summary>Settings</summary>
			<hr>
			your MakeMKV Setting:<br>
			<?php
			$MakeMKV_Settings=shell_exec("cat /root/.MakeMKV/settings.conf | grep app_DefaultSelectionString"); 
			$MakeMKV_Settings=substr($MakeMKV_Settings, 30, -2);
			echo $MakeMKV_Settings;
			?>
			<br>example: "-sel:all,+sel:(deu|eng|nolang),-sel:(core),-10:(forced*(eng)),-15:(deu),-20:(forced*(deu))"
			<br>(Setting location: /root/.MakeMKV/settings.conf)
			<hr>
			</details>
			</details>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
						