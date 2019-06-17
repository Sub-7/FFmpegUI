<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);


/// get title
     $lines = file('../DISC_Info');
     $searchstr = 'CINFO:2,0,';
     foreach ($lines as $line)
    {if(strpos($line, $searchstr) !== false){$title[] = $line;}}
/// get size
     $lines1 = file('../DISC_Info');
     $searchstr1 = 'TINFO:0,10,0,';
     foreach ($lines1 as $line1)
    {if(strpos($line1, $searchstr1) !== false){$size[] = $line1;}}

    
	
	
	
	echo substr($title[0], 11, -2) . "<br>";
	echo substr($size[0], 14, -2) . "<br>";












?>



