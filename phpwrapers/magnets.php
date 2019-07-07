<?php

	header('Content-Type: application/json');

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	chdir(__DIR__ . '/..');
	exec('bin/magnetssolver', $o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)(?<S>S?):(?<c>[\d\.,]+)(?<l>[BLRT]+) \(seed (?<s>\d+)\)$/', $o[0], $m))
	{
		header('HTTP/1.1 500 Failed generations');
		echo implode(PHP_EOL, $o), PHP_EOL;
		die('false');
	}

	$data = (object) [];
	$data->name = 'Magnets';
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->settings->strip = !empty($m['S']);
	$data->id = strstr($o[0], ' ', TRUE);
	$data->seed = strstr($o[0], ':', TRUE) . '#' . $m['s'];
	$data->state = (object) [];
	$clues = explode(',', strtr($m['c'], ['.' => ' ']));
	$data->N = str_split($clues[0]);
	$data->W = str_split($clues[1]);
	$data->S = str_split($clues[2]);
	$data->E = str_split($clues[3]);
	$data->layout = array_chunk(str_split($m['l']), $data->settings->columns);

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, 128 * 3);
	}

