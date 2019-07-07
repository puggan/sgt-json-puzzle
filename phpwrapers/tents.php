<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	exec('bin/tentssolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$clues = explode(',', $id);
	$tree = static function () {
		return 1;
	};

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->state->columns = array_slice($clues, 1, $m['w2']);
	$data->state->rows = array_slice($clues, 1 + $m['w2']);
	$data->state->grid = az_grid(fill_2d($m['h2'], $m['w2'], 0), $clues[0], true, $tree);
