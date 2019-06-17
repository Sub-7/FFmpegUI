<h3>
<?php 
////////////////////Progressbar
    $disc_progress="../disc_progress";
   if (file_exists($disc_progress)) 
    {
	    echo "&nbsp;";
	    echo "&nbsp;";	
		$get_progress_value=shell_exec("tail -1  /var/www/html/FFmpeg_UI/disc_progress");
	    $Progress_array=preg_split('/:|,/',$get_progress_value);
        $MakeMKV_Message=trim($Progress_array[0]);
	    $MakeMKV_Current=trim($Progress_array[1]);
	    $MakeMKV_Total=trim($Progress_array[2]);
	    $MakeMKV_Maximum=trim($Progress_array[3]);
	    $ProgressBar_Value=$MakeMKV_Current / $MakeMKV_Maximum * 100;
	    $ProgressBar_Value=round($ProgressBar_Value);
	    $MakeMKV_Info=trim($Progress_array[0]);
        echo $ProgressBar_Value."%";
        echo "<div class='meter animate'>";
        echo "<span style='width:";
	    echo $ProgressBar_Value."%";
        echo "'><span></span></span></div>";
 	}

?>
</h3>