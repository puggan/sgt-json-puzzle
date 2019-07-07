<?php

	require_once __DIR__ . '/base.php';

	exec('bin/fillingsolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->state = az_grid(fill_2d($m['h2'], $m['w2'], 0), $id);
