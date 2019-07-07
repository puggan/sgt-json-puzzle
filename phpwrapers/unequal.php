<?php

	header('Content-Type: application/json');

	$dificulties = [
		't' => 'Trival',
		'e' => 'Easy',
		'k' => 'Tricky',
		'x' => 'Extreme',
		'r' => 'Recursive',
	];

	chdir(__DIR__ . '/..');
	exec('bin/unequalsolver', $o);

	[$name, $id] = explode(': ', $o[2], 2);
	[$name, $seed] = explode(': ', $o[1]);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)(?<a>a?)d(?<d>.)$/', $config, $m))
	{
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$size = $m['w2'];
	$data = (object) [];
	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings = (object) [];
	$data->settings->columns = $size;
	$data->settings->difficulty = $dificulties[$m['d'] ?? -1] ?? NULL;
	$data->settings->rows = $size;
	$data->settings->adjacent = !empty($m['a']);
	$data->seed = $config . '#' . $seed;

	if(!preg_match_all('#(?<d>\d+)(?<c>[URDL]*),#', $id, $parts, PREG_SET_ORDER))
	{
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$clues = [];
	foreach($parts as $cell)
	{
		$clues[] = [+$cell['d'], $cell['c']];
	}
	$data->state = array_chunk($clues, $size);

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}
