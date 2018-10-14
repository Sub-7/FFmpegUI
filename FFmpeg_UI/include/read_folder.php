<?php
$handle=opendir ($media);
$path = dirname(__FILE__);
echo $path;

if(isset($_POST['rename']))
{
	$challenge = $_POST['file'];
	$new = str_replace(' ', '_', $challenge);
echo str_replace(' ', '_', $challenge);
	rename("$path/$_POST[file]","$path/$new");

	}


if(isset($_POST['delete']))
{
	
	$file = ($_POST['file']);
	
	echo "$file  is gone ";
	unlink($media . $_POST['file']);
	}

if(isset($_POST['delete_folder']))
{
function rrmdir($src) {
	$src = ($media.$_POST['file']);
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}
	}


if(isset($_POST['unzip']))
{
$zip = new ZipArchive;
if ($zip->open($media . $_POST['file']) === TRUE) {
    $zip->extractTo($media);
    $zip->close();
    echo 'ok';
} else {
    echo 'Fehler';
}
}


if(isset($_POST['select']))
	
{ if ($_POST['file'] == $_POST['file'])
{$file = $_POST['file'];
 echo "$file <hr>";
$size = filesize($media . $_POST['file']);
$MB = round(($size / 1048576), 2); // bytes to MB
 echo "$MB  MB";
 $mime_type = mime_content_type( $media . $_POST['file']);
echo "</br>$mime_type\n";
}}

?>
<form action='' method='post'>
<select name='file'>
<?php
	
	
	
while ($file = readdir ($handle)) {
  if ($file != "." && $file != "..") {
	  
    echo "<option value='$file'>$file</option><br/>";
  }
}
closedir($handle);
	
?>
</select><br/>
<input type='submit' name='select' value='Select'/>
<input type='submit' name='delete' value='Delete'/>
<input type='submit' name='delete_folder' value='DeleteF'/>	
<input type='submit' name='unzip' value='Unzip'/>
<input type='submit' name='rename' value='Rename'/>	
<input type='submit' name='reload' value='Reload'/>
<input type='submit' name='ffmpeg_go' value='Encode'/>	
</form>