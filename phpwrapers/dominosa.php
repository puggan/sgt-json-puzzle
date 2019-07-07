<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		't' => 'Trival',
		'b' => 'Basic',
		'h' => 'Hard',
		'e' => 'Extreme',
		'a' => 'Ambiguous',
	];

	exec('bin/dominosasolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<m>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['m'] + 2;
	$data->settings->difficulty = $dificulties[$m['d'] ?? 't'] ?? $dificulties['t'];
	$data->settings->maxs = $m['m'];
	$data->settings->rows = $m['m'] + 1;

	if(!preg_match_all('#\[\d+]|\d#', $id, $parts))
	{
		die('Failed generations');
	}
	$clues = array_map(
		static function ($n) {
			return $n[0] === '[' ? substr($n, 1, -1) : $n;
		},
		$parts[0]
	);

	$data->state = array_chunk($clues, $data->settings->columns);
