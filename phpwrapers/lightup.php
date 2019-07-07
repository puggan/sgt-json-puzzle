<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'Easy',
		'Tricky',
		'Hard',
	];
	$symm = [
		'None',
		'Ref2',
		'Rot2',
		'Ref4',
		'Rot4',
		'Max',
	];

	exec('bin/lightupsolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)b(?<b>\d+)s(?<s>\d+)d(?<d>\d+)$/', $config, $m))
	{
		die('Failed generations');
	}

	$block_test = static function ($n) {
		return $n === 'B' ? 5 : +$n;
	};

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? -1] ?? NULL;
	$data->settings->rows = $m['h2'];
	$data->settings->blackpc = $m['b'];
	$data->settings->symm = $symm[$m['s']] ?? NULL;
	$data->state = az_grid(fill_2d($m['h2'], $m['w2'], 6), $id, false, $block_test, '[\dB]');

	if(DEBUG)
	{
		$data->specification = [
			'No neighbors are lights',
			'1 neighbor is a light',
			'2 neighbors are lights',
			'3 neighbors are lights',
			'All neighbors are lights',
			'Unknown number of neighbors are lights',
			'Open space',
		];
	}
