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

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<x>x)?(?<k>k)?(?<s>a|[mr]d?[248])?(d(?<d>.))?$/', $config, $m))
	{
		die('Failed generations');
	}

	$size = $m['w2'] * $m['h2'];
	$data->settings->columns = $m['w2'];
	$data->settings->diagonal = !empty($m['x']);
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->jigsaw = FALSE;
	$data->settings->killer = !empty($m['k']);
	$data->settings->rows = $m['h2'];
	$data->settings->symmetry = $m['s'] ?? 'r2';
	$data->state = az_grid(fill_2d($size, $size, 0), $id);
