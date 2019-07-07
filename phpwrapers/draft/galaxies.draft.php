<?php

	require_once __DIR__ . '/base.php';

	exec('bin/galaxiessolver', $o);

	$data->name = 'galaxies';
	$data->id = '';
	$data->seed = '';
