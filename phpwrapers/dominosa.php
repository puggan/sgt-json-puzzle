<?php

	$dificulties = [
		't' => 'Trival',
		'b' => 'Basic',
		'h' => 'Hard',
		'e' => 'Extreme',
		'a' => 'Ambiguous',
	];

	header('Content-Type: application/json');

	chdir(__DIR__ . '/..');
	exec('bin/dominosasolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<m>\d+)d(?<d>.)$/', $config, $m)) {
		header('HTTP/1.1 500 Failed generations');
		echo $config, PHP_EOL;
		die('false');
	}

	$data = (object) [];
	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->seed = $config . '#' . $seed;
	$data->settings = (object) [];
	$data->settings->columns = $m['m'] + 2;
	$data->settings->difficulty = $dificulties[$m['d'] ?? 't'] ?? $dificulties['t'];
	$data->settings->maxs = $m['m'];
	$data->settings->rows = $m['m'] + 1;
	$data->state = [];

	if(!preg_match_all('#\[\d+]|\d#', $id, $parts)) {
		header('HTTP/1.1 500 Failed generations');
		echo $config, PHP_EOL;
		die('false');
	}
	$clues = array_map(
		static function ($n) {
			return $n[0] === '[' ? substr($n, 1, -1) : $n;
		},
		$parts[0]
	);
	print_r($clues);
	$data->state = array_chunk($clues, $data->settings->columns);

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	} else {
		$data->debug = $o;
		echo json_encode($data, 128*3);
	}
