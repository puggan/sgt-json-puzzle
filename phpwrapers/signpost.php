<?php

	require_once __DIR__ . '/base.php';

	exec('bin/signpostsolver -v', $o);

	[$config, $id] = explode(':', $o[1]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<c>c)?$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $o[1];
	$data->name = 'signpost';
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
