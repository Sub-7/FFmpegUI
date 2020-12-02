<?php
	
	echo "<hr>";
	///////////////////////////// is FFmpeg installed?
	
	$cmd = 'wich ffmpeg';
	$tmp = '/tmp';
	exec($cmd, $tmp, $return);
	if ($return == '127'){
		echo "FFmpeg OK!</br>";
		}else{
		echo "FFmpeg is NOT available! -> ";
		
		$compile_ffmpeg = file_get_contents('./include/compile_ffmpeg.txt');
		echo "<details><summary>FFmpeg Compilation Guide</summary><pre>$compile_ffmpeg</pre></details><hr>";
		
	}
	
	///////////////////////////// is RAR installed?
	
	if (extension_loaded('rar')) {
		echo "RAR OK!</br>";
	}
	else
	{
		echo "Extension 'rar' not loaded! -> <a href='http://mewbies.com/how_to_enable_the_viewing_and_extracting_of_rar_files_on_your_webserver.htm'>How To</a></br>
		or</br>: apt-get install gcc php-pear php7.0-dev</br>
		pecl -v install rar</br>
		nano /etc/php/7.0/apache2/php.ini</br>
		
		Add towards the top of the file AFTER [PHP]</br>
		
		extension=rar.so</br>
		
		service apache2 restart</br>
		";
		
	}
	///////////////////////////// is ZIP installed?
	
	if (extension_loaded('zip')) {
    echo "ZIP OK!</br>";
    }
	else
	{
	echo "Extension 'zip' not loaded! -> sudo apt-get install php7.0-zip</br>";
	}
	
	///////////////////////////// is bcmath installed?
	
	if (extension_loaded('bcmath')) {
    echo "BCMATH OK!</br>";
    }
	else
	{
	echo "Extension 'bcmath' not loaded! -> sudo apt install php7.0-bcmath -> service apache2 restart</br>";
	}
	
	
	?>	