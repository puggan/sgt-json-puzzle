<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		't' => 'Trival',
		'b' => 'Basic',
		'i' => 'Intermediate',
		'a' => 'Advanced',
		'e' => 'Extreme',
		'u' => 'Unreasonable',
	];

	exec('bin/solosolver', $o);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<x>x)?(?<k>k)?(?<s>a|[mr]d?[248])?(d(?<d>.))?$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $config . ':' . substr($o[1], 9);
	$data->name = $name;
	$data->seed = $config . '#' . substr($o[2], 6);
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
