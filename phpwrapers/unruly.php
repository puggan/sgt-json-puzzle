<?php

	require_once __DIR__ . '/base.php';

	exec('bin/unrulysolver', $o);

	$dificulties = ['e' => 'Easy', 'n' => 'Normal'];

	$config = preg_replace('/.* /', '', $o[0]);
	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<x>u)?d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $data->settings->columns . 'x' . $data->settings->rows . ':' . substr($o[1 + $data->settings->rows], 9);
	$data->name = 'unruly';
	$data->seed = $config . '#' . substr($o[2 + $data->settings->rows], 6);
	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d']] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->settings->unique = !empty($m['u']);
	$data->state = [];

	$chr_table = '.01';
	$chr_lookup = static function ($c) use ($chr_table) {
		return strpos($chr_table, $c);
	};

	foreach(range(1, $data->settings->rows) as $row_nr)
	{
		$data->state[] = array_map($chr_lookup, explode(' ', $o[$row_nr]));
	}
