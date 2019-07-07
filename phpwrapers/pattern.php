<?php

	require_once __DIR__ . '/base.php';

	exec('bin/patternsolver', $o);

	[$config, $id] = explode(':', substr($o[0], 9));

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $config . ':' . $id;
	$data->name = 'pattern';
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . substr($o[1], 6);

	$clues = array_map(
		static function ($r) {
			return explode('.', $r);
		},
		explode('/', $id)
	);
	$data->state->columns = array_slice($clues, 0, $data->settings->columns);
	$data->state->rows = array_slice($clues, $data->settings->columns);
