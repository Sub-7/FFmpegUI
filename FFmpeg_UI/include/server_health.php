<?php

// Returns used memory (either in percent (without percent sign) or free and overall in bytes)
    function getServerMemoryUsage($getPercentage=true)
    {
        $memoryTotal = null;
        $memoryFree = null;

        if (stristr(PHP_OS, "win")) {
            // Get total physical memory (this is in bytes)
            $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
            @exec($cmd, $outputTotalPhysicalMemory);

            // Get free physical memory (this is in kibibytes!)
            $cmd = "wmic OS get FreePhysicalMemory";
            @exec($cmd, $outputFreePhysicalMemory);

            if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) {
                // Find total value
                foreach ($outputTotalPhysicalMemory as $line) {
                    if ($line && preg_match("/^[0-9]+\$/", $line)) {
                        $memoryTotal = $line;
                        break;
                    }
                }

                // Find free value
                foreach ($outputFreePhysicalMemory as $line) {
                    if ($line && preg_match("/^[0-9]+\$/", $line)) {
                        $memoryFree = $line;
                        $memoryFree *= 1024;  // convert from kibibytes to bytes
                        break;
                    }
                }
            }
        }
        else
        {
            if (is_readable("/proc/meminfo"))
            {
                $stats = @file_get_contents("/proc/meminfo");

                if ($stats !== false) {
                    // Separate lines
                    $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                    $stats = explode("\n", $stats);

                    // Separate values and find correct lines for total and free mem
                    foreach ($stats as $statLine) {
                        $statLineData = explode(":", trim($statLine));

                        //
                        // Extract size (TODO: It seems that (at least) the two values for total and free memory have the unit "kB" always. Is this correct?
                        //

                        // Total memory
                        if (count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") {
                            $memoryTotal = trim($statLineData[1]);
                            $memoryTotal = explode(" ", $memoryTotal);
                            $memoryTotal = $memoryTotal[0];
                            $memoryTotal *= 1024;  // convert from kibibytes to bytes
                        }

                        // Free memory
                        if (count($statLineData) == 2 && trim($statLineData[0]) == "MemFree") {
                            $memoryFree = trim($statLineData[1]);
                            $memoryFree = explode(" ", $memoryFree);
                            $memoryFree = $memoryFree[0];
                            $memoryFree *= 1024;  // convert from kibibytes to bytes
                        }
                    }
                }
            }
        }

        if (is_null($memoryTotal) || is_null($memoryFree)) {
            return null;
        } else {
            if ($getPercentage) {
                return (100 - ($memoryFree * 100 / $memoryTotal));
            } else {
                return array(
                    "total" => $memoryTotal,
                    "free" => $memoryFree,
                );
            }
        }
    }

    function getNiceFileSize($bytes, $binaryPrefix=true) {
        if ($binaryPrefix) {
            $unit=array('B','KiB','MiB','GiB','TiB','PiB');
            if ($bytes==0) return '0 ' . $unit[0];
            return @round($bytes/pow(1024,($i=floor(log($bytes,1024)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
        } else {
            $unit=array('B','KB','MB','GB','TB','PB');
            if ($bytes==0) return '0 ' . $unit[0];
            return @round($bytes/pow(1000,($i=floor(log($bytes,1000)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
        }
    }

    // Memory usage: 4.55 GiB / 23.91 GiB (19.013557664178%)
    $memUsage = getServerMemoryUsage(false);
    ;
/*##################################################*/
function shapeSpace_system_cores() {
	
    $cmd = "uname";
    $OS = strtolower(trim(shell_exec($cmd)));
 
    switch($OS) {
       case('linux'):
          $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
          break;
       case('freebsd'):
          $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
          break;
       default:
          unset($cmd);
    }
 
    if ($cmd != '') {
       $cpuCoreNo = intval(trim(shell_exec($cmd)));
    }
    
    return empty($cpuCoreNo) ? 1 : $cpuCoreNo;
    
}

$cpu_cores= shapeSpace_system_cores();

/*################################################################*/

function shapeSpace_system_load($cpu_cores, $interval = 1) {
	$rs = sys_getloadavg();
	$interval = $interval >= 1 && 1 <= $interval ? $interval : 1;
	$load = $rs[$interval];
	return round(($load * 100) / $cpu_cores,0);
}

$cpu_load= shapeSpace_system_load($cpu_cores, $interval = 1);
/*#############################################################*/



$start_time = microtime(TRUE);

$free = shell_exec('free');
$free = (string)trim($free);
$free_arr = explode("\n", $free);
$mem = explode(" ", $free_arr[1]);
$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
$mem = array_merge($mem); // puts arrays back to [0],[1],[2] after filter removes nulls

//print_r($mem); echo '<hr>';
$memtotal = round($mem[1] / 1000000,2);
$memused = round($mem[2] / 1000000,2);
$memfree = round($mem[3] / 1000000,1);

$membuffer = round($mem[5] / 1000000,2);
$memcached = round($mem[6] / 1000000,2);

$memusage = round(($memused - $memcached - $membuffer)/$memtotal*100,2);

$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 



/*######################################*/
$load = sys_getloadavg();
$cpuload = $cpu_load;

$diskfree = round(disk_free_space(".") / 1000000000);
$disktotal = round(disk_total_space(".") / 1000000000);
$diskused = round($disktotal - $diskfree);

$diskusage = round($diskused/$disktotal*100);

if ($memusage > 85 || $cpuload > 90 || $diskusage > 90) {
	$trafficlight = 'red';
} elseif ($memusage > 70 || $cpuload > 40 || $diskusage > 85) {
	$trafficlight = 'orange';
} else {
	$trafficlight = '#2F2';
}

$end_time = microtime(TRUE);
$time_taken = $end_time - $start_time;
$total_time = round($time_taken,4);

?>
