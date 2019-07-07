<?php

	header('Content-Type: application/json');

	$dificulties = [
		't' => 'Trival',
		'b' => 'Basic',
		'i' => 'Intermediate',
		'a' => 'Advanced',
		'e' => 'Extreme',
		'u' => 'Unreasonable',
	];

	chdir(__DIR__ . '/..');
	exec('bin/solosolver', $o);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<x>x)?(?<k>k)?(?<s>a|[mr]d?[248])?(d(?<d>.))?$/', $config, $m))
	{
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$data = (object) [];
	$data->id = $config . ':' . substr($o[1], 9);
	$data->name = $name;
	$data->seed = $config . '#' . substr($o[2], 6);
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->diagonal = !empty($m['x']);
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->jigsaw = FALSE;
	$data->settings->killer = !empty($m['k']);
	$data->settings->rows = $m['h2'];
	$data->settings->symmetry = $m['s'] ?? 'r2';
	$data->state = [];

	foreach([3,4,5, 7,8,9, 11,12,13] as $row_index)
	{
		$data->state[] = explode(' ', strtr($o[$row_index], [' | ' => ' ', '.' => 0]));
	}

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}
