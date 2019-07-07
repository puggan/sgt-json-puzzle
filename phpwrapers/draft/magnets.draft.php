<?php

	header('Content-Type: application/json');

	if(basename(__DIR__) === 'draft') chdir(__DIR__ . '/../..'); else
	chdir(__DIR__ . '/..');
	exec('bin/magnetssolver', $o);

	$data = (object) [];
	$data->name = 'magnets';
	$data->settings = (object) [];
	$data->id = '';
	$data->seed = '';
	$data->state = [];

	if(0 && empty($_GET['debug']))
	{
		echo json_encode($data);
	} else {
		$data->debug = $o;
		echo json_encode($data, 128*3);
	}

