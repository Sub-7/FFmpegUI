<?php
if(isset($_POST['reload']))	
{
header( "refresh:0;url=index.php");
}


/////////////////////////////////////////////////
if(isset($_POST['copy']))
	
{
  if(isset($_POST['file']))
	{
if (is_dir($_POST['file']))
        { 
	      $source = $path.$_POST['file'];
          $destination = $media.$_POST['destiny_folder'].$_POST['file'];
	      $cmd = "cp -R $source  $destination";
	      shell_exec($cmd);
	      echo $_POST['file']. ' was copied.';
        } 
	   else
        {
	     $source = $path.$_POST['file'];
         $destination = $media.$_POST['destiny_folder'].$_POST['file'];
	     $cmd = "cp -u $source  $destination";
	     shell_exec($cmd);
		 echo $_POST['file']. ' was copied.';
        }
}
}
//////////////////////////////////////////////////////////////

if(isset($_POST['rename']))
	if(isset($_POST['file']))
	{
		
	
   {
	$challenge = $_POST['file'];
	$new = str_replace(' ', '_', $challenge);
    echo $_POST['file']. ' was edited.';
	   
	
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
             echo $_POST['file'] .' unzipped.';
            } 
	else 
			     {
                  echo 'Error!!!';
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
                 echo $_POST['file'] .' unzipped.';
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
        $_SESSION["file"] = $_POST['file'];
		
		$file = $_POST['file'];

        $size = filesize($path . $_POST['file']);
        $MB = round(($size / 1048576), 2); // bytes to MB
   
        $mime_type = mime_content_type( $path . $_POST['file']);
   
       }
}


/////////////////////////////////////////////////

if(isset($_POST['rename_file']))
  {		
    if(isset($_POST['new_file_name']))
	{
	  
      $new_file_name = $_POST['new_file_name'];
      $old_file_name = $_POST['old_file_name'];
		
	  rename("$path_rename/$old_file_name","$path_rename/$new_file_name");	
		

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
<ul class="form-style-1">
<li>
<select name='file' class="field-long field-textarea" multiple table="true">
<?php
$handle=opendir ($path);	
while ($file = readdir ($handle)) 
{
     if ($file != "." && $file != "..") 
	 {
		 
		
	       echo "<option class='field-select' value='$file'>$file</option><br/>";
         }
     }  
closedir($handle);
	
	
	
	?>
	<hr>
</select>
	</li>
	<li>
<input type='submit' name='info' value='Select'/>
<input type='submit' name='delete' value='Delete'/>
<input type='submit' name='unpack'  value='Unpack'/>	
<input type='submit' name='rename' value='del. spaces'/>
<input type='submit' name='reload' value='Reload'/>
	</li>
	<li>
	<select  class="field-long field-select_copy" name="destiny_folder">
    <option value="output/">FFmpeg_GUI/media/Output</option>
    <option value="backup/">FFmpeg_GUI/media/Backup</option>
	<option value="tmp/">FFmpeg_GUI/media/tmp</option>
	<input type='submit' name='copy'  value='Copy'/>
	
	</li>
		<li>
		<input class="field-long field-select_copy" type="text" name="new_file_name" value="<?php if(isset($_SESSION['file'])){ echo $_SESSION['file'];}?>">
		<input type="hidden" name="old_file_name" value="<?php echo $_SESSION['file']; ?>">
		<input type='submit' name='rename_file' value='Rename'/>
	</li>
<li>
	<input class="field-long field-select_copy" type="text" name="url" value="...direct link to file">
	<li>
	<input  type='submit' name='download' value=' Download'/>
	</li>	
</li>
</select>


</form>