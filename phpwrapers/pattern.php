<?php

	header('Content-Type: application/json');

	chdir(__DIR__ . '/..');
	exec('bin/patternsolver', $o);

	[$config, $id] = explode(':', substr($o[0], 9));

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$data = (object) [];
	$data->id = $config . ':' . $id;
	$data->name = 'pattern';
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . substr($o[1], 6);
	$data->state = (object) [];

	$clues = array_map(
		static function ($r) {
			return explode('.', $r);
		},
		explode('/', $id)
	);
	$data->state->columns = array_slice($clues, 0, $data->settings->columns);
	$data->state->rows = array_slice($clues, $data->settings->columns);

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}

