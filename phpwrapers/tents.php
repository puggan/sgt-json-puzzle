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

	$cluesStrs = explode(',', $id);
	$clues = array_map('intval', $cluesStrs);
	$tree = static function () {
		return 1;
	};

	$data->settings->columns = (int) $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = (int) $m['h2'];
	$data->state->columns = array_slice($clues, 1, $m['w2']);
	$data->state->rows = array_slice($clues, 1 + $m['w2']);
	$data->state->grid = az_grid(fill_2d($m['h2'], $m['w2'], 0), $cluesStrs[0], true, $tree);
