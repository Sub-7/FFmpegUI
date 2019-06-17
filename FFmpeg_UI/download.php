<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
     <head>
          <title></title>
		 <script type="text/javascript" src="include/jQuery.js"></script>
		   <script type="text/javascript">
  var progressElement = document.getElementById('progress');

function updateProgress(percentage) {
    document.getElementById('progress').style.width = percentage + '%';
    document.getElementById('progress').innerHTML = percentage + '%';
}</script>
          </head>
          <body>
<style>
#progress-bar {
    width: 400px;
    padding: 2px;
    border: 2px solid #aaa;
    background: #fff;
}

#progress {
    background: #000;
    border: 1px solid black;
    color: #fff;
    overflow: hidden;
    white-space: nowrap;
    padding: 5px 0;
    text-indent: 5px;
    width: 0%;
}
</style>

<div id="progress">0%</div>
<?php
function callback($download_size, $downloaded, $upload_size, $uploaded) {
    static $last; 
    if ($ind = @round($downloaded/$download_size*100, 1)) {
        if($last < $ind) {
        echo "<script>updateProgress(".$ind.")</script>";
        flush();
        $last = $ind;
    }
    } else {
        echo "<script>updateProgress(0)</script>";
    }
     //first ~8 times it will be divided by 0
    #echo '<script>$(function() {$( "#progressbar" ).progressbar({value: '.$dltotalprocent.'});});</script>';
    
}
function download($input, $id, $destination, $file_extension) {
    $ch = curl_init($input);
    curl_setopt($ch, CURLOPT_BUFFERSIZE, 128);
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'callback');
    $fp = fopen($destination.$id.".".$file_extension, "w");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    $data = curl_exec($ch);
}
flush();
download("https://5.9.94.126/rutorrent/archive.tgz","testfile", "/var/www/html/FFmpeg_GUI/media/input/", "tgz");

?>
          </body>
</html>