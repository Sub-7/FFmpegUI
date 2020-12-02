<?php
	/*##################################################*/
	$free = shell_exec('free');
	$free = (string)trim($free);
	$free_arr = explode("\n", $free);
	$mem = explode(" ", $free_arr[1]);
	$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
	$mem = array_merge($mem); // puts arrays back to [0],[1],[2] after filter removes nulls
	//print_r($mem); echo '<hr>';
	$memtotal = round($mem[1] / 1048576,1);
	$memfree = round($mem[3] / 1048576,1);
	$memused = round($mem[2] / 1048576,4);
	$proz = round($memused/($memtotal/100),2);
	$memtotal = ($memtotal*10);
	$membuffer = round($mem[5] / 1048576,2);
	$memcached = round($mem[6] / 1048576,2);
	$memusage = round($memused/$memtotal*100);
	
	$start_time = microtime(TRUE);
	$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
	$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
	/*################ HDD ######################*/
	$diskfree = round(disk_free_space(".") / 1000000000);
	$disktotal = round(disk_total_space(".") / 1000000000);
	$diskused = round($disktotal - $diskfree);
	$diskusage = round($diskused/$disktotal*100);
	$end_time = microtime(TRUE);
	$time_taken = $end_time - $start_time;
	$total_time = round($time_taken,4);
	
	/*################ MEM ######################*/
	function shapeSpace_server_memory_usage() {
		
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = $mem[2] / $mem[1] * 100;
		
		return $memory_usage;
		
	}
	$total = shell_exec('svmon -G | grep memory');
	$memInPer = round(shapeSpace_server_memory_usage() / 1,2);
	
	/*################# CPU #####################*/
	$loads=sys_getloadavg();
	$core_nums=trim(shell_exec("grep -P '^physical id' /proc/cpuinfo|wc -l"));
	$cpuload=round($loads[0]/$core_nums*100);
	if ($cpuload < "1" ) {
		$cpuload= "1";
	}
	if ($cpuload > "100" ) {
		$cpuload= "100";
	}
	if ($memusage > 85 || $cpuload > 90 || $diskusage > 90) {
		$trafficlight = 'red';
		} elseif ($memusage > 70 || $cpuload > 40 || $diskusage > 85) {
		$trafficlight = 'orange';
		} else {
		$trafficlight = '#2F2';
	}
	
	$cores = exec('nproc');
	/*######################################*/
?>	