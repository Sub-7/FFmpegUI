<?php


/////////////////////////////////////////////////
if(isset($_POST['copy']))
	
{
  
if (is_dir($_POST['file']))
        { 
	      $source = $path.$_POST['file'];
          $destination = $media.$_POST['destiny_folder'].$_POST['file'];
	      $cmd = "cp -R $source  $destination";
	      shell_exec($cmd);
        } 
	   else
        {
	     $source = $path.$_POST['file'];
         $destination = $media.$_POST['destiny_folder'].$_POST['file'];
	     $cmd = "cp -u $source  $destination";
	     shell_exec($cmd);
        }
}

//////////////////////////////////////////////////////////////

if(isset($_POST['rename']))
	if(isset($_POST['file']))
	{
		
	
   {
	$challenge = $_POST['file'];
	$new = str_replace(' ', '_', $challenge);
    echo str_replace(' ', '_', $challenge);
	rename("$path/$_POST[file]","$path/$new");
   }
	}
/////////////////////////////////////////////////////////////

if (isset($_POST['delete']))
	if(isset($_POST['file']))
	{
		
	

   {
	  $folder = ($path.$_POST['file']);
	  if(is_dir($folder))
        
	    {
		  exec("rm -rf $folder*");
          $file = ($_POST['file']);
		  echo "$file  is gone ";
        }
	  else
	    {
		  $file = ($path.$_POST['file']);
		  $file = ($_POST['file']);
		  unlink($path . $_POST['file']);
		  echo "$file  is gone ";
        }
    }
}
////////////////////////////////////////////

if(isset($_POST['unpack'] ))
	if(isset($_POST['file']))
	{
		
	
{
	       $file_path = ($path.$_POST['file']);
	       $to_path = $path;
           function extract_file($file_path, $path)
     {
           $file_type = substr($file_path, strrpos($file_path, '.') - strlen($file_path) + 1);
           if ("zip" === $file_type) 
		{
		   $zip = new ZipArchive;
           if ($zip->open($file_path) === TRUE) 
            {
             $zip->extractTo($path);
             $zip->close();
             echo 'ok';
            } 
	else 
			     {
                  echo 'Fehler';
	              return false;
                 }
        } 
	elseif ("rar" == $file_type) 
		   {
             $rar_file = rar_open($file_path) or die("Can't open Rar archive");
             $entries = rar_list($rar_file);
             if ($entries) 
			 {
                foreach ($entries as $entry) 
				{
                 echo 'Filename: ' . $entry->getName() . "\n";
                 $entry->extract($path);
                }
                rar_close($rar_file);
            } 
	else
			 {
            echo "extract fail";
            return false;
        }

    }
}
extract_file($file_path, $path);
}
}

//////////////////////////////////////////////////////////////


if(isset($_POST['info']))
	
{ 
	if(isset($_POST['file']))
       {
		$file = $_POST['file'];
        echo "$file <hr>";
        $size = filesize($path . $_POST['file']);
        $MB = round(($size / 1048576), 2); // bytes to MB
        echo "$MB  MB";
        $mime_type = mime_content_type( $path . $_POST['file']);
        echo "</br>$mime_type\n";
       }
}

////////////////////////////////////////////////////
if(isset($_POST['download']))
	
{
stream_context_set_default(
[
	'ssl' => [
		'verify_peer' => false,
		"verify_peer_name"=>false,
	]
]
);
	
	$filename = explode("/", $_POST['url']);
	$file = $filename[count($filename) - 1];


	
	if(!@copy($_POST['url'],$path.$file))
{
    $errors= error_get_last();
    echo "COPY ERROR: ".$errors['type'];
    echo "<br />\n".$errors['message'];
} else {
    echo "File copied from remote!";
}
	

}





//////////////////////////////////////////////////////////////

?>
<form name="file" action='' method='post'>
<select name='file' class="input_box" size="10" table="true">
<?php
$handle=opendir ($path);	
while ($file = readdir ($handle)) 
{
     if ($file != "." && $file != "..") 
	 {
		 
		
	       echo "<option value='$file'>$file</option><br/>";
         }
     }  
closedir($handle);
	echo $_POST['hhh'];
	?>
</select><br/><br/>
	<select name="destiny_folder">
    <option value="output/">FFmpeg_GUI/media/Output</option>
    <option value="backup/">FFmpeg_GUI/media/Backup</option>
	<option value="tmp/">FFmpeg_GUI/media/tmp</option>
	
	<input type='submit' name='copy'  value='Copy'/><hr>
	<input type="text" name="url" value="...direct link to file">
	<input type='submit' name='download' value='Download'/><hr>
</select>
<input type='submit' name='info' value='Info'/>
<input type='submit' name='delete' value='Delete'/>
<input type='submit' name='unpack'  value='Unpack'/>	
<input type='submit' name='rename' value='Rename'/>	
<input type='submit' name='reload' value='Reload'/>
<input type='submit' name='ffmpeg_go' value='Encode'/>

</form>