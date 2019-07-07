<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	exec('bin/slantsolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? -1] ?? NULL;
	$data->settings->rows = $m['h2'];
	$data->state = az_grid(fill_2d($m['h2'] + 1, $m['w2'] + 1, 5), $id);
