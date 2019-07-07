<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	exec('bin/magnetssolver', $o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)(?<S>S?):(?<c>[\d.,]+)(?<l>[BLRT]+) \(seed (?<s>\d+)\)$/', $o[0], $m))
	{
		die('Failed generations');
	}

	$data->name = 'Magnets';
	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->settings->strip = !empty($m['S']);
	$data->id = strstr($o[0], ' ', TRUE);
	$data->seed = strstr($o[0], ':', TRUE) . '#' . $m['s'];

	$clues = explode(',', strtr($m['c'], ['.' => ' ']));
	$data->N = str_split($clues[0]);
	$data->W = str_split($clues[1]);
	$data->S = str_split($clues[2]);
	$data->E = str_split($clues[3]);
	$data->layout = array_chunk(str_split($m['l']), $data->settings->columns);
