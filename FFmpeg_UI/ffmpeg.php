<?php
require('settings.php');

////////////////////////////////////// get variables for files, folders, progressbar, ffmpeg options

if (isset($_POST["ffmpeg_go"],$_POST['file']))
         {
           $start = round(microtime(true) * 1);
	       $input_file        = $path.$_POST['file'];
	       $output_file       = $path. 'encoded_' . $_POST['file'];
           $input_folder      = $path;
           $map_0_1            = $_POST["map_0_1"];
	       $map_0_1_codec       = $_POST["map_0_1_codec"];
           $map_0_1_bitrate     = $_POST["map_0_1_bitrate"];
	       $map_0_2            = $_POST["map_0_2"];
	       $map_0_2_codec       = $_POST["map_0_2_codec"];
           $map_0_2_bitrate     = $_POST["map_0_2_bitrate"];
           $map_0_3            = $_POST["map_0_3"];
	       $map_0_3_codec       = $_POST["map_0_3_codec"];
           $map_0_3_bitrate     = $_POST["map_0_3_bitrate"];
	       $map_0_4            = $_POST["map_0_4"];
	       $map_0_4_codec       = $_POST["map_0_4_codec"];
           $map_0_4_bitrate     = $_POST["map_0_4_bitrate"];	
           $map_0_5            = $_POST["map_0_5"];
	       $map_0_5_codec       = $_POST["map_0_5_codec"];
           $map_0_5_bitrate     = $_POST["map_0_5_bitrate"];
	       $map_0_6            = $_POST["map_0_6"];
	       $map_0_6_codec       = $_POST["map_0_6_codec"];
           $map_0_6_bitrate     = $_POST["map_0_6_bitrate"];
	       $map_0_7       = $_POST["map_0_7"];
           $map_0_7_subtitle     = $_POST["map_0_7_subtitle"];
	       $video_codec       = $_POST["video_codec"];
	       $video_bitrate     = $_POST["video_bitrate"];
           $scale             = $_POST["scale"];
           $format            = $_POST["format"];
	       if ($format == "-f matroska")
           {$file_end = "mkv";}
	       if ($format == "-f flv")
           {$file_end = "flv";}
	       if ($format == "-f mp4")
           {$file_end = "mp4";}
	       if ($format == "-f avi")
           {$file_end = "avi";}
	       if ($format == "-f wmv")
           {$file_end = "wmv";}
	       $output_file_format = substr($path. 'encoded_' . $_POST['file'] , 0, - 4);
	       $loglevel          = "-loglevel info";

	       $vaapi = " -hwaccel vaapi -hwaccel_device /dev/dri/renderD128 -hwaccel_output_format vaapi";
	       $nvidia_gpu = "-hwaccel cuvid -c:v h264_cuvid";
	

///VAAPI
	
if ($video_codec == '-c:v h264_vaapi' or $video_codec == '-c:v mpeg2_vaapi' or $video_codec == '-c:v vp8_vaapi' or $video_codec == '-c:v vp9_vaapi' or $video_codec == '-c:v hevc_vaapi' )
		      {
$ffmpeg_line = "ffmpeg $loglevel $vaapi -y -i $input_file -map 0:0 $map_0_1 $map_0_2 $map_0_3 $map_0_4 $map_0_5 $map_0_6 $map_0_7 $scale $video_bitrate $video_codec $map_0_1_codec $map_0_1_bitrate $map_0_2_codec $map_0_2_bitrate $map_0_3_codec $map_0_3_bitrate $map_0_4_codec $map_0_4_bitrate $map_0_5_codec $map_0_5_bitrate $map_0_6_codec $map_0_6_bitrate $map_0_7_subtitle $output_file_format.$file_end $format 2> progress.txt";
	            echo $ffmpeg_line; ///shows what ffmpeg is doing, here with VAAPI, and further down with the rest
		        $ffmpeg_encode = shell_exec("$ffmpeg_line");
              }
///CUDA
	
if ($video_codec == '-c:v hevc_nvenc' or $video_codec == '-c:v h264_nvenc')
		     {
			   $scale_nv=$_POST["scale"];
			   $scale_npp = str_replace('_vaapi', '_npp', $scale_nv);
			   echo $scale_npp."<hr>";
			  			  
		       $ffmpeg_line = "ffmpeg $loglevel $nvidia_gpu -y -i $input_file $tracks $audio_codec $audio_bitrate $scale_npp $video_bitrate $video_codec $format -preset medium -rc cbr -profile high $output_file_format.$file_end 2> progress.txt";
	           echo $ffmpeg_line; ///shows what ffmpeg is doing, here with CUDA
		       $ffmpeg_encode = shell_exec("$ffmpeg_line");
	         }	
///REST	
	
if ($video_codec == '-c:v libx264' or $video_codec == '-c:v libx265' or $video_codec == '-c:v vp8_v4l2m2m' or $video_codec == '-c:v libvpx-vp9' or $video_codec == '-c:v mpeg2video' or $video_codec == '-c:v mpeg4' or $video_codec == '-c:v wmv1' or $video_codec == '-c:v wmv2')

	         {
			   echo $_POST['file']."</br>";
			   $cut__vaapi = $_POST["scale"];
			   $scale = str_replace('_vaapi', '', $cut__vaapi);
			   $ffmpeg_line = "ffmpeg $loglevel -y -i $input_file $tracks $audio_codec $audio_bitrate  $video_bitrate $video_codec $scale $output_file_format.$file_end $format 2> progress.txt";
			   echo $ffmpeg_line; ///shows what ffmpeg is doing
			   $ffmpeg_encode = shell_exec("$ffmpeg_line");
             }
}
	
////////////////////////////////////// progressbar

           require('progress.php');
           $fh = fopen('progress.txt', 'a');
           fclose($fh);
           $file = 'progress.txt';
           $newfile = 'ffmpeg_encoding_history.txt';
          
         if (!copy($file, $newfile)) 
		  {
            echo "copy $file ERROR...\n";
          }
          unlink('progress.txt');
        
        if (isset($start))
         {
           $encode_time = round(microtime(true) * 1) - $start;
           $hours = (int)($encode_time/60/60);
           $minutes = (int)($encode_time/60)-$hours*60;
           $seconds = (int)$encode_time-$hours*60*60-$minutes*60;
		   echo "</br>Encoding time " . $hours .":". $minutes .":". $seconds . "</br> ... done,</br> select a new file or press 'Reload'</br>";
	   	}

////////////////////////////////////// get media info using ffprobe

        if(isset($_SESSION["file"]))
         {
			if (file_exists($path.$_SESSION["file"])) 
		   {
	       $video_info = $path.$_SESSION["file"];
	       exec("ffprobe -i $video_info -v quiet -print_format xml -show_format -show_streams -hide_banner > temp_file", $output, $res);
	       $duration = exec("ffmpeg -i $video_info 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
		   $encoder = exec("ffmpeg -i $video_info 2>&1 | grep encoder");
           $info = new SimpleXMLElement(file_get_contents("temp_file"));
	       $size = filesize($video_info);
           $GB = round(($size / 1073741824), 3); // bytes to GB
	       $mime_type = mime_content_type($video_info);
		   $audio_track= exec("ffmpeg -i $video_info 2>&1 | grep Stream >track");
		   			
?>

<!--////////////////////////////////////// here is form with FFmpeg options-->

<form name="file" action='index.php' method='post'>
    <table  border="0">
		
	   <tr > 
		<th>Video codec</th> <th>Video bitrate</th> <th>Scale</th>  <th>Format</th>      
	   </tr>
		
		
      <td >
	    <select name="video_codec"> 
          <option selected value="-c:v h264_vaapi">H.264 VAAPI</option>  <!--// note 'selected' in this line, this value is preselected in the form-->
		  <option value="-c:v h264_nvenc">H.264 CUDA</option>  
		  <option value="-c:v hevc_nvenc">HEVC CUDA</option>
		  <option value="-c:v hevc_vaapi">HEVC VAAPI</option>	
		  <option value="-c:v vp8_vaapi">VP8 VAAPI</option>	
		  <option value="-c:v vp9_vaapi">VP9 VAAPI</option>
		  <option value="-c:v mpeg2_vaapi">MPEG-2 VAAPI</option>
		  <option value="-c:v libx264">x.264</option>
	      <option value="-c:v libx265">x.265 HEVC</option>
		  <option value="-c:v vp8_v4l2m2m">VP8</option>
		  <option value="-c:v libvpx-vp9">VP9</option>
	      <option value="-c:v mpeg2video">MPEG-2</option>
		  <option value="-c:v mpeg4">MPEG-4</option>
		  <option value="-c:v wmv1">WNV 7</option>
	      <option value="-c:v wmv2">WMV 8</option>
         </select>
      </td>
		
	  <td >
	    <select name="video_bitrate"> 
         <option value="-b:v 1M -maxrate 1M -bufsize 1M">1.000 kb/s</option>
	     <option value="-b:v 1.5M -maxrate 1.5M -bufsize 1.5M">1.500 kb/s</option>
         <option value="-b:v 2M -maxrate 2M -bufsize 2M">2.000 kb/s</option>
         <option selected value="-b:v 3M -maxrate 3M -bufsize 3M">3.000 kb/s</option>  
	     <option value="-b:v 4M -maxrate 4M -bufsize 4M">4.000 kb/s</option>
	     <option value="-b:v 5M -maxrate 5M -bufsize 5M">5.000 kb/s</option>
	     <option value="-b:v 6M -maxrate 6M -bufsize 6M">6.000 kb/s</option>
	     <option value="-b:v 7M -maxrate 7M -bufsize 7M">7.000 kb/s</option>
	     <option value="-b:v 8M -maxrate 8M -bufsize 8M">8.000 kb/s</option>
	     <option value="-b:v 9M -maxrate 9M -bufsize 9M">9.000 kb/s</option>
	     <option value="-b:v 10M -maxrate 10M -bufsize 10M">10.000 kb/s</option>
	     <option value="-b:v 15M -maxrate 15M -bufsize 15M">15.000 kb/s</option>
	     <option value="-b:v 20M -maxrate 20M -bufsize 20M">20.000 kb/s</option>
	     <option value="-b:v 30M -maxrate 30M -bufsize 30M">30.000 kb/s</option>
	     <option value="-b:v 40M -maxrate 40M -bufsize 40M">40.000 kb/s</option>
         <option value="-b:v 50M -maxrate 50M -bufsize 50M">50.000 kb/s</option>
	    </select>
      </td>
		
	

     <td >
	    <select name="scale"> 
          <option value="-vf scale_vaapi=w=-2:h=576">576p</option>
          <option selected value="-vf scale_vaapi=w=-2:h=720">720p</option>   
	      <option value="-vf scale_vaapi=w=-2:h=1080">1080p</option>
	      <option value="-vf scale_vaapi=w=-2:h=2048">2K UHD</option>
          <option value="-vf scale_vaapi=w=-2:h=2160">4K UHD</option>
	      <option value="-vf scale_vaapi=w=-2:h=4320">8K UHD</option>
	      <option value="-vf scale_vaapi=w=-2:h=144">144p</option> <!--////// note 'selected' in this line, this value is preselected in the form-->
          <option value="-vf scale_vaapi=w=-2:h=240">240p</option>
	      <option value="-vf scale_vaapi=w=-2:h=360">360p</option>
          <option value="-vf scale_vaapi=w=-2:h=480">480p</option>
        </select>
      </td>
	
      <td >	  
	    <select name="format"> 
          <option value="-f matroska">MKV</option>
          <option value="-f flv">FLV</option>
	      <option value="-f mp4">MP4</option>
		  <option value="-f avi">AVI</option>
		  <option value="-f wmv">WMV</option>
        </select>
      </td>
	
		
	<!--0:1#############################################################################################################################-->
		
		
		<tr >
		<th >Audio Track 0:1</th>  <th>Audio codec</th> <th>Audio bitrate</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_1">
			<option value="">nothing</option>
            <option selected value="-map 0:1">0:1</option> <!--////// note 'selected' in this line, this value is preselected in the form-->
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_1_codec">
		  <option value="">nothing</option> 
          <option selected value="-codec:a:0 copy">Copy</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="-codec:a:0 dca -strict -2">DTS</option>
		  <option value="-codec:a:0 truehd -strict -2">True HD</option>
          <option value="-codec:a:0 libfdk_aac">DD</option>
	      <option value="-codec:a:0 eac3">DD+</option>
	      <option value="-codec:a:0 mp2">MP2</option>
	      <option value="-codec:a:0 aac">AAC LC</option>
	      <option value="-codec:a:0 libfdk_aac">HE-AAC</option>
	      <option value="-codec:a:0 libvorbis">Vorbis</option>
	      <option value="-codec:a:0 libmp3lame">MP3</option>
	      <option value="-codec:a:0 libopus -strict -2">Opus</option>
	    </select>
      </td>	
		

	  <td >	  
		<select name="map_0_1_bitrate">
		  <option selected value="">nothing</option> <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="">Copy</option>  
          <option value="-b:a:0 1536k">1536 kb/s</option>
		  <option value="-b:a:0 768k">768 kb/s</option>
		  <option value="-b:a:0 384k">384 kb/s</option> 
		  <option value="-b:a:0 192k">192 kb/s</option>
		  <option value="-b:a:0 128k">128 kb/s</option>
		  
	    </select>
	 </td>
	

	<!--0:2#############################################################################################################################-->
		
				<tr >
		<th >Audio Track 0:2</th>  <th>Audio codec</th> <th>Audio bitrate</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_2">
			<option selected value="">nothing</option> <!--////// note 'selected' in this line, this value is preselected in the form--> 
            <option value="-map 0:2">0:2</option>  
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_2_codec">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
          <option value="-codec:a:1 copy">Copy</option>
		  <option value="-codec:a:1 dca -strict -2">DTS</option>
		  <option value="-codec:a:1 truehd -strict -2">True HD</option>
          <option value="-codec:a:1 libfdk_aac">DD</option>
	      <option value="-codec:a:1 eac3">DD+</option>
	      <option value="-codec:a:1 mp2">MP2</option>
	      <option value="-codec:a:1 aac">AAC LC</option>
	      <option value="-codec:a:1 libfdk_aac">HE-AAC</option>
	      <option value="-codec:a:1 libvorbis">Vorbis</option>
	      <option value="-codec:a:1 libmp3lame">MP3</option>
	      <option value="-codec:a:1 libopus -strict -2">Opus</option>
	    </select>
      </td>	
		

	  <td >	  
		<select name="map_0_2_bitrate">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="">Copy</option>
          <option value="-b:a:1 1536k">1536 kb/s</option>
		  <option value="-b:a:1 768k">768 kb/s</option>
		  <option value="-b:a:1 384k">384 kb/s</option> 
		  <option value="-b:a:1 192k">192 kb/s</option>
		  <option value="-b:a:1 128k">128 kb/s</option>
		  
	    </select>
	 </td>
		
		
		



		<!--0:3#############################################################################-->
		
		<tr >
		<th >Audio Track 0:3</th>  <th>Audio codec</th> <th>Audio bitrate</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_3">
			<option selected value="">nothing</option> <!--////// note 'selected' in this line, this value is preselected in the form--> 
	        <option value="-map 0:3">0:3</option>
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_3_codec">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
          <option value="-codec:a:2 copy">Copy</option>
		  <option value="-codec:a:2 dca -strict -2">DTS</option>
		  <option value="-codec:a:2 truehd -strict -2">True HD</option>
          <option value="-codec:a:2 libfdk_aac">DD</option>
	      <option value="-codec:a:2 eac3">DD+</option>
	      <option value="-codec:a:2 mp2">MP2</option>
	      <option value="-codec:a:2 aac">AAC LC</option>
	      <option value="-codec:a:2 libfdk_aac">HE-AAC</option>
	      <option value="-codec:a:2 libvorbis">Vorbis</option>
	      <option value="-codec:a:2 libmp3lame">MP3</option>
	      <option value="-codec:a:2 libopus -strict -2">Opus</option>
	    </select>
      </td>	
		

	  <td >	  
		<select name="map_0_3_bitrate">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="">Copy</option>
          <option value="-b:a:2 1536k">1536 kb/s</option>
		  <option value="-b:a:2 768k">768 kb/s</option>
		  <option value="-b:a:2 384k">384 kb/s</option> 
		  <option value="-b:a:2 192k">192 kb/s</option>
		  <option value="-b:a:2 128k">128 kb/s</option>
		  
	    </select>
	 </td>
		
			<!--0:4#############################################################################################################################-->
		
		
		<tr >
		<th >Audio Track 0:4</th>  <th>Audio codec</th> <th>Audio bitrate</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_4">
			<option selected value="">nothing</option> <!--////// note 'selected' in this line, this value is preselected in the form--> 
            <option value="-map 0:4">0:4</option>
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_4_codec">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
          <option value="-codec:a:3 copy">Copy</option>
		  <option value="-codec:a:3 dca -strict -2">DTS</option>
		  <option value="-codec:a:3 truehd -strict -2">True HD</option>
          <option value="-codec:a:3 libfdk_aac">DD</option>
	      <option value="-codec:a:3 eac3">DD+</option>
	      <option value="-codec:a:3 mp2">MP2</option>
	      <option value="-codec:a:3 aac">AAC LC</option>
	      <option value="-codec:a:3 libfdk_aac">HE-AAC</option>
	      <option value="-codec:a:3 libvorbis">Vorbis</option>
	      <option value="-codec:a:3 libmp3lame">MP3</option>
	      <option value="-codec:a:3 libopus -strict -2">Opus</option>
	    </select>
      </td>	
		

	  <td >	  
		<select name="map_0_4_bitrate">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="">Copy</option>
          <option value="-b:a:3 1536k">1536 kb/s</option>
		  <option value="-b:a:3 768k">768 kb/s</option>
		  <option value="-b:a:3 384k">384 kb/s</option> 
		  <option value="-b:a:3 192k">192 kb/s</option>
		  <option value="-b:a:3 128k">128 kb/s</option>
		  
	    </select>
	 </td>

	

	<!--0:5#############################################################################################################################-->
		
				<tr >
		<th >Audio Track 0:5</th>  <th>Audio codec</th> <th>Audio bitrate</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_5">
			<option selected value="">nothing</option> <!--////// note 'selected' in this line, this value is preselected in the form--> 
			<option value="-map 0:5">0:5</option>  
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_5_codec">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
          <option value="-codec:a:4 copy">Copy</option>
		  <option value="-codec:a:4 dca -strict -2">DTS</option>
		  <option value="-codec:a:4 truehd -strict -2">True HD</option>
          <option value="-codec:a:4 libfdk_aac">DD</option>
	      <option value="-codec:a:4 eac3">DD+</option>
	      <option value="-codec:a:4 mp2">MP2</option>
	      <option value="-codec:a:4 aac">AAC LC</option>
	      <option value="-codec:a:4 libfdk_aac">HE-AAC</option>
	      <option value="-codec:a:4 libvorbis">Vorbis</option>
	      <option value="-codec:a:4 libmp3lame">MP3</option>
	      <option value="-codec:a:4 libopus -strict -2">Opus</option>
	    </select>
      </td>	
		

	  <td >	  
		<select name="map_0_5_bitrate">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="">Copy</option>
          <option value="-b:a:4 1536k">1536 kb/s</option>
		  <option value="-b:a:4 768k">768 kb/s</option>
		  <option value="-b:a:4 384k">384 kb/s</option> 
		  <option value="-b:a:4 192k">192 kb/s</option>
		  <option value="-b:a:4 128k">128 kb/s</option>
		  
	    </select>
	 </td>
		
		
		<!--0:6#############################################################################-->
		
						<tr >
		<th >Audio Track 0:6</th>  <th>Audio codec</th> <th>Audio bitrate</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_6">
			<option selected value="">nothing</option> <!--////// note 'selected' in this line, this value is preselected in the form--> 
            <option value="-map 0:6">0:6</option> 
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_6_codec">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
          <option value="-codec:a:5 copy">Copy</option>
		  <option value="-codec:a:5 dca -strict -2">DTS</option>
		  <option value="-codec:a:5 truehd -strict -2">True HD</option>
          <option value="-codec:a:5 libfdk_aac">DD</option>
	      <option value="-codec:a:5 eac3">DD+</option>
	      <option value="-codec:a:5 mp2">MP2</option>
	      <option value="-codec:a:5 aac">AAC LC</option>
	      <option value="-codec:a:5 libfdk_aac">HE-AAC</option>
	      <option value="-codec:a:5 libvorbis">Vorbis</option>
	      <option value="-codec:a:5 libmp3lame">MP3</option>
	      <option value="-codec:a:5 libopus -strict -2">Opus</option>
	    </select>
      </td>	
		

	  <td >	  
		<select name="map_0_6_bitrate">
		  <option selected value="">nothing</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->
		  <option value="">Copy</option>
          <option value="-b:a:5 1536k">1536 kb/s</option>
		  <option value="-b:a:5 768k">768 kb/s</option>
		  <option value="-b:a:5 384k">384 kb/s</option> 
		  <option value="-b:a:5 192k">192 kb/s</option>
		  <option value="-b:a:5 128k">128 kb/s</option>
		  
	    </select>
	 </td>
		
		
	
		
		<!--Subtitle#################################################################-->
		
		
		
				<tr >
		<th >Subtitle</th>  <th>Subtitle Copy</th>     
	   </tr>
		
	   <td >
	      <select  name="map_0_7">
			<option selected value="">nothing</option>
            <option  value="-map 0:1">0:1 Subtitle</option>
			<option  value="-map 0:2">0:2 Subtitle</option>
			<option  value="-map 0:3">0:3 Subtitle</option>
			<option  value="-map 0:4">0:4 Subtitle</option>
			<option  value="-map 0:5">0:5 Subtitle</option>
			<option  value="-map 0:6">0:6 Subtitle</option>
			  
			  
		</select>
      </td>
		

		
      <td >	  
		<select name="map_0_7_subtitle">
		  <option selected value="">nothing</option> 
          <option  value="-c:s copy">Copy</option>  <!--////// note 'selected' in this line, this value is preselected in the form-->

	    </select>
      </td>			
		
		
		
  
   </table>

    <input type="hidden" name="file" value="<?php $encoded = $_SESSION["file"]; echo $_SESSION["file"];?>">
	<ul class="form-style-1">
	<li>	
    <?php if(isset($_SESSION["file"])){ echo  "<input type='submit' name='ffmpeg_go' value='ENCODE'/>"; }  ?>
	</li>
</form>
	
<!--////////////////////////////////////// show media info using ffprobe-->
	
<?php
	
	$level= $info->streams->stream[0]['level'];
    $bitrate_total = $info->streams->stream[0]['bit_rate'] + $info->streams->stream[1]['bit_rate'];
				
				
				
				
				function shortText($string,$lenght) 
				{
                 if(strlen($string) > $lenght) 
			   {
                 $string = substr($string,0,$lenght)."...";
                 $string_ende = strrchr($string, " ");
                 $string = str_replace($string_ende," ...", $string);
               }
                 return $string;
	           }
				
				
				
echo "<div class=file_info>";
				
	$file = 'track';
    $file_handle = fopen($file, 'r');
	echo "</br><h4>Audio & Video Streams:</h4><hr>";			
    while (!feof($file_handle)) 
	{
      $line = fgets($file_handle);
      echo shortText($line,90) . "</br>";
    }
    fclose($file_handle);					
				
echo "<h4>".$_SESSION["file"]."</h4><hr>";
echo "Size: " . $GB . " GB</br>";
echo "Type: " .$mime_type. "</br>";	
echo "Duration: " . substr($duration, 0, -3) . "</br>";
echo "Resolution: " . $info->streams->stream[0]['width']. ' x ' . $info->streams->stream[0]['height']."</br>";
echo "Profile: " . $info->streams->stream[0]['profile'] . "</br>";
echo "Level: " . substr($level,0,1).".".substr($level,1,1) . "</br>";
echo "Video Bitrate: " . substr($info->streams->stream[0]['bit_rate'], 0 , -3) . " kb/s</br>";

				

echo "</div>";
unset($_SESSION["file"]);
			}
			}
?>