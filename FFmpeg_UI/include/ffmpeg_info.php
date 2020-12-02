<?php
	$profile_and_entrypoints = "vainfo";
	$help = "ffmpeg -help";
	$formats = "ffmpeg -formats";
	$profile_and_entrypoints_info =  shell_exec($profile_and_entrypoints);	
	$ffmpeg_help = shell_exec($help);
	$encoders = "ffmpeg -encoders";
	$codecs = "ffmpeg -codecs";
	$encoder_h264_nvenc= "ffmpeg -h encoder=nvenc_h264";
	$encoder_hevc_nvenc= "ffmpeg -h encoder=hevc_nvenc";
	$encoder_h264_vaapi= "ffmpeg -h encoder=hevc_nvenc";
	$ffmpeg_codecs = shell_exec($codecs);
	$ffmpeg_formats = shell_exec($formats);
	$hevc_nv = file_get_contents('include/hevc_nvenc.txt');
	$h264_nv = file_get_contents('include/h264_nvenc.txt');
	$hevc_va = file_get_contents('include/hevc_vaapi.txt');
	$h264_va = file_get_contents('include/h264_vaapi.txt');
	
	$ffmpeg_encoders = shell_exec($encoders);
	echo "<details><summary>Supported profile and entrypoints</summary><pre>$profile_and_entrypoints_info<br></pre></details>";
	echo "<details><summary>Encoder HEVC NVENC</summary><pre>$hevc_nv<br></pre></details>";
	echo "<details><summary>Encoder H264 NVENC</summary><pre>$h264_nv<br></pre></details>";
	echo "<details><summary>Encoder HEVC VAAPI</summary><pre>$hevc_va<br></pre></details>";
	echo "<details><summary>Encoder H264 VAAPI</summary><pre>$h264_va<br></pre></details>";
	echo "<details><summary>FFmpeg Help</summary><pre>$ffmpeg_help<br></pre></details>";
	echo "<details><summary>Formats</summary><pre>$ffmpeg_formats<br></pre></details>";
	echo "<details><summary>Codecs</summary><pre>$ffmpeg_codecs<br></pre></details>";
	echo "<details><summary>Encoders</summary><pre>$ffmpeg_encoders<br></pre></details>";
	
?>