<?php

	require_once __DIR__ . '/base.php';

	exec('bin/mapsolver', $o);

	$data->name = 'map';
	$data->id = '';
	$data->seed = '';
