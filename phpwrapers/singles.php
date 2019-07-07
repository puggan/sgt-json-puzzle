<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	exec('bin/singlessolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $config . ':' . $id;
	$data->name = $name;
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
