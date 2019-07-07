<?php

	header('Content-Type: application/json');

	chdir(__DIR__ . '/..');
	exec('bin/signpostsolver -v', $o);

	[$config, $id] = explode(':', $o[1]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<c>c)?$/', $config, $m)) {
		header('HTTP/1.1 500 Failed generations');
		echo $config, PHP_EOL;
		die('false');
	}

	$data = (object) [];
	$data->id = $o[1];
	$data->name = 'signpost';
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->seed = $o[0];
	$data->state = [];

	$dir = [
		'a' => 'N',
		'b' => 'NE',
		'c' => 'E',
		'd' => 'SE',
		'e' => 'S',
		'f' => 'SW',
		'g' => 'W',
		'h' => 'NW',
	];

	$goal = $data->settings->columns * $data->settings->rows;

	/** @noinspection NotOptimalRegularExpressionsInspection */
	preg_match_all('#(?<n>\d*)(?<d>[a-h])#', $id, $parts, PREG_SET_ORDER);
	foreach($parts as $index => $part)
	{
		$c = $index % $data->settings->columns;
		$r = ($index - $c) / $data->settings->columns;
		$data->state[$r][$c] = [
			$part['n'] === $goal ? 'G' : $dir[$part['d']],
			$part['n'] ? +$part['n'] : 0,
		];
	}

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	} else {
		$data->debug = $o;
		echo json_encode($data, 128*3);
	}

