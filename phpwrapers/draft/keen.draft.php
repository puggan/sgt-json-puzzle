<?php

	require_once __DIR__ . '/base.php';

	exec('bin/keensolver', $o);

	$data->name = 'keen';
	$data->id = '';
	$data->seed = '';
