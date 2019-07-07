<?php

	require_once __DIR__ . '/base.php';

	exec('bin/fifteensolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . $seed;

	$clues = explode(',', $id);
	$data->state = array_chunk($clues, $data->settings->columns);
