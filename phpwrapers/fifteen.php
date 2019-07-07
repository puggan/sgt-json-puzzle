<?php

	require_once __DIR__ . '/base.php';

	exec('bin/fifteensolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];

	$clues = explode(',', $id);
	$data->state = array_chunk($clues, $data->settings->columns);
