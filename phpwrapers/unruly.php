<?php

	header('Content-Type: application/json');

	chdir(__DIR__ . '/..');
	exec('bin/unrulysolver', $o);

	$config = preg_replace('/.* /', '', $o[0]);
	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)(?<u>u?)d(?<d>.)$/', $config, $m)) {
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$data = (object) [];
	$data->name = 'unruly';
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->settings->unique = !empty($m['u']);
	$data->settings->difficulty = $m['d'] === 'e' ? 'Easy' : 'Normal';
	$data->id = $data->settings->columns . 'x' .$data->settings->rows . ':' . substr($o[1 + $data->settings->rows], 9);
	$data->seed = $config . '#' . substr($o[2 + $data->settings->rows], 9);
	$data->state = [];

	$chr_table = '.01';
	$chr_lookup = static function ($c) use ($chr_table) {
		return strpos($chr_table, $c);
	};

	foreach(range(1, $data->settings->rows) as $row_nr)
	{
		$data->state[] = array_map($chr_lookup, explode(' ', $o[$row_nr]));
	}

	if(empty($_GET['debug']))
	{
		echo json_encode($data);
	} else {
		$data->debug = $o;
		echo json_encode($data, 128*3);
	}
