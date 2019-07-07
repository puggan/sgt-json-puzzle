<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		't' => 'Trival',
		'e' => 'Easy',
		'k' => 'Tricky',
		'x' => 'Extreme',
		'r' => 'Recursive',
	];

	exec('bin/unequalsolver', $o);

	[$name, $id] = explode(': ', $o[2], 2);
	[$name, $seed] = explode(': ', $o[1]);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)(?<a>a?)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$size = $m['w2'];
	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings->columns = $size;
	$data->settings->difficulty = $dificulties[$m['d'] ?? -1] ?? NULL;
	$data->settings->rows = $size;
	$data->settings->adjacent = !empty($m['a']);
	$data->seed = $config . '#' . $seed;

	if(!preg_match_all('#(?<d>\d+)(?<c>[URDL]*),#', $id, $parts, PREG_SET_ORDER))
	{
		die('Failed generations');
	}

	$clues = [];
	foreach($parts as $cell)
	{
		$clues[] = [+$cell['d'], $cell['c']];
	}
	$data->state = array_chunk($clues, $size);
