<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		'n' => 'Normal',
		't' => 'Tricky',
		'h' => 'Hard',
	];

	exec('bin/loopysolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)t(?<t>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->settings->type = $m['t'];
	$data->state = az_grid(fill_2d($m['h2'], $m['w2'], 5), $id);
