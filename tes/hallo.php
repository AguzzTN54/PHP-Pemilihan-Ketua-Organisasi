<?php

header('Content-Type: text/event-stream');
// header('Content-Type: Application/json');
header('Cache-Control: no-cache');

	$time = date('r');
	// echo 'id: 1';
	// echo 'data: server date {$time}';
	// echo 'retry: 10000';y
	echo 'data : {
	  "success": false,
	  "status": "keluar",
	  "status_msg": "tes"
	}';
	flush();
	sleep(20);
?>