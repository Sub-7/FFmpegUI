<?php
	/////////////////////refresh///////////////////////////////
	if (isset($_POST['reload'])) {
		header("refresh:0;url=index.php");
	}
	/////////////////////Youtube DL///////////////////////////////
	if (isset($_POST['yt_dl'])) {
		if ($_POST['yt_url'] != "") {
			$link = $_POST['yt_url'];
			$video_quality = $_POST['video_quality'];
			$cmd = "youtube-dl -o /var/www/html/FFmpeg_UI/media/input/'%(title)s.%(ext)s' -f '$video_quality+bestaudio[ext=m4a]/bestvideo+bestaudio'  --merge-output-format mkv $link";
			shell_exec($cmd);
			echo "<div class='alert alert-success'>Download successful.(check spaces, use 'Delete blank')</div>";
			} else {
			echo "<div class='alert alert-danger'>it doesn't work without a download link!</div>";
		}
	}
	/// Best Video and Audio ->  $cmd = "youtube-dl -f 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/bestvideo+bestaudio' --merge-output-format mp4 $link";
	/// Best 1080p and Audio ->  $cmd = "youtube-dl -f '137+bestaudio[ext=m4a]/bestvideo+bestaudio' --merge-output-format mp4 $link";
	
	//////////////////////////COPY///////////////////////
	if (isset($_POST['copy'])) {
		if (isset($_POST['file'])) {
			if (is_dir($_POST['file'])) {
				$source = $path . $_POST['file'];
				$destination = $media . $_POST['destiny_folder'] . $_POST['file'];
				$cmd = "cp -R $source  $destination";
				shell_exec($cmd);
				echo "<div class='alert alert-danger'>Error !!!</div>";
				} else {
				$source = $path . $_POST['file'];
				$destination = $media . $_POST['destiny_folder'] . $_POST['file'];
				$cmd = "cp -u $source  $destination";
				shell_exec($cmd);
				echo "<div class='alert alert-success'>File copied.</div>";
			}
		}
	}
	////////////////////////////DELETE BLANK//////////////////////////////////
	if (isset($_POST['rename'])) if (isset($_POST['file'])) { {
		$file_name = $_POST['file'];
		$new = clean($file_name);
		echo "<div class='alert alert-success'>File edited.</div>";
		rename("$path/$file_name", "$path/$new");
	}
	}

	///////////////////////////////DELETE//////////////////////////////
	if (isset($_POST['delete'])) if (isset($_POST['file'])) { {
		$folder = ($path . $_POST['file']);
		if (is_dir($folder)) {
			exec("rm -rf $folder*");
			$file = ($_POST['file']);
			echo "<div class='alert alert-success'>File is gone.</div>";
			} else {
			$file = ($path . $_POST['file']);
			$file = ($_POST['file']);
			unlink($path . $_POST['file']);
			echo "<div class='alert alert-success'>File is gone.</div>";
		}
	}
	}
	////////////////////////UNPACK////////////////////
	if (isset($_POST['unpack'])) if (isset($_POST['file'])) { {
		$file_path = ($path . $_POST['file']);
		$to_path = $path;
		function extract_file($file_path, $path) {
			$file_type = substr($file_path, strrpos($file_path, '.') -strlen($file_path) +1);
			if ("zip" === $file_type) {
				$zip = new ZipArchive;
				if ($zip->open($file_path) === TRUE) {
					$zip->extractTo($path);
					$zip->close();
					echo "<div class='alert alert-success'>Folder unzipped.</div>";
					} else {
					echo 'Error!!!';
					return false;
				}
				} elseif ("rar" == $file_type) {
				$rar_file = rar_open($file_path) or die("Can't open Rar archive");
				$entries = rar_list($rar_file);
				if ($entries) {
					foreach($entries as $entry) {
						echo "<div class='alert alert-success'>Folder unzipped.</div>";
						$entry->extract($path);
					}
					rar_close($rar_file);
					} else {
					echo "extract fail";
					return false;
				}
			}
			}
			extract_file($file_path, $path);
			}
			}
			//////////////////////////GET INFO////////////////////////////////////
			if (isset($_POST['info'])) {
			if (isset($_POST['file'])) {
			$_SESSION["file"] = $_POST['file'];
			$file = $_POST['file'];
			$size = filesize($path . $_POST['file']);
			$MB = round(($size/1048576) , 2); // bytes to MB
			$mime_type = mime_content_type($path . $_POST['file']);
			}
			}
			////////////////////////////RENAME/////////////////////
			if (isset($_POST['rename_file'])) {
			if ($_POST['new_file_name'] != "") {
			if (isset($_POST['new_file_name'])) {
			$new_file_name = $_POST['new_file_name'];
			$old_file_name = $_POST['old_file_name'];
			rename("$path_rename/$old_file_name", "$path_rename/$new_file_name");
			echo "<div class='alert alert-success'>File renamed.</div>";
			}
			} else {
			echo "<div class='alert alert-danger'>choose file from the window.</div>";
			}
			}
			///////////////////////////DIRECT DOWNLOAD/////////////////////////
			if (isset($_POST['download'])) {
			stream_context_set_default(['ssl' => ['verify_peer' => false, "verify_peer_name" => false, ]]);
			$filename = explode("/", $_POST['url']);
			$file = $filename[count($filename) -1];
			if (!@copy($_POST['url'], $path . $file)) {
			$errors = error_get_last();
			echo "<div class='alert alert-danger'>it doesn't work without a download link!</div>";
			} else {
			echo "<div class='alert alert-success'>Direct download successful.</div>";
			}
			}
			//////////////////////////////////////////////////////////////
			
			?>
			<form name="file" action='' method='post'>
			<ul class="form-style-1">
			<li>
			<select name='file' class="field-long field-textarea" multiple table="true">
			<?php
			$handle = opendir($path);
			while ($file = readdir($handle)) {
			if ($file != "." && $file != "..") {
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
			<input type='submit' name='rename' value='Del. spaces'/>
			<input type='submit' name='reload' value='Reload'/>
			</li>
			<li>
			<select  class="field-long field-select_copy" name="destiny_folder">
			<option value="plex/PLEX/">PLEX</option>
			<option value="plex/PLEX/YT/">YT</option>
			<option value="backup/">FFmpeg_GUI/media/Backup</option>
			<option value="tmp/">FFmpeg_GUI/media/tmp</option>
			<input type='submit' name='copy'  value='Copy'/>
			
			</li>
			<li>
			<input class="field-long field-select_copy" type="text" name="new_file_name" value="<?php if (isset($_SESSION['file'])) {
			echo $_SESSION['file'];
			} ?>">
			<input type="hidden" name="old_file_name" value="<?php echo $_SESSION['file']; ?>">
			<input type='submit' name='rename_file' value='Rename'/>
			</li>
			<li>
			<input class="field-long field-select_copy" type="text" name="url" value="...direct link to file">
			<li>
			<input  type='submit' name='download' value=' Download'/>
			</li>    
			</li>
			
			<li>
			<input class="field-long-yt field-select_copy-yt" type="text" name="yt_url" value="">
			<li>
			<input type="radio" name="video_quality" value="bestvideo[ext=mp4]">BEST
			<input type="radio" name="video_quality" checked="checked" value="137">1080p
			<input type="radio" name="video_quality" value="22">720p
			<input  type='submit' name='yt_dl' value='Youtube'/>
			
			</li>    
			</li>
			
			</select>
			
			
			</form>			