<?php

	header('Content-Type: application/json');

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	chdir(__DIR__ . '/..');
	exec('bin/singlessolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)$/', $config, $m))
	{
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$data = (object) [];
	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d']] ?? NULL;
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . $seed;
	$data->state = array_chunk(
		array_map(
			static function ($c) {
				if($c <= '9')
				{
					return +$c;
				}
				if($c <= 'Z')
				{
					return ord($c) - 29;
				}
				return ord($c) - 87;
			},
			str_split($id)
		),
		$data->settings->columns
	);

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}

