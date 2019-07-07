<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		'h' => 'Hard',
		'x' => 'Extreme',
		'u' => 'Unreasonable',
	];

	exec('bin/towerssolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)(d(?<d>.))?$/', $config, $m))
	{
		die('Failed generations');
	}

	$size = $m['w2'];
	$data->settings->columns = $size;
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $size;

	[$clues, $towers] = explode(',', $id);
	$clues = array_map('intval', explode('/', $clues));
	foreach(['N', 'S', 'W', 'E'] as $index => $dir)
	{
		$data->state->$dir = array_slice($clues, $index * $size, $size);
	}

	$data->state->grid = az_grid(fill_2d($size, $size, 0), $towers);
