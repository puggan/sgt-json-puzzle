<?php

	header('Content-Type: application/json');

	chdir(__DIR__ . '/..');
	exec('bin/keensolver', $o);

	$data = (object) [];
	$data->name = 'keen';
	$data->settings = (object) [];
	$data->id = '';
	$data->seed = '';
	$data->state = [];

	if(1 || empty($_GET['debug']))
	{
		echo json_encode($data);
	} else {
		$data->debug = $o;
		echo json_encode($data, 128*3);
	}

