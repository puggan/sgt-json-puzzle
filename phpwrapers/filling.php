<?php

	header('Content-Type: application/json');

	chdir(__DIR__ . '/..');
	exec('bin/fillingsolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		header('HTTP/1.1 500 Failed generations');
		die('false');
	}

	$data = (object) [];
	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . $seed;
	$data->state = (object) [];

	$c = $data->settings->columns;
	$data->state = array_fill(0, $data->settings->rows, array_fill(0, $c, 0));

	if(preg_match_all('#(?<d>z*[a-z_])?(?<t>\d)#', $id, $parts, PREG_SET_ORDER))
	{
		$pos = 0;
		foreach($parts as $part)
		{
			if($part['d'] && $part['d'] !== '_')
			{
				$d = $part['d'];
				while($d && $d[0] === 'z')
				{
					$pos += 26;
					$d = substr($d, 1);
				}
				if($d)
				{
					$pos += ord($d[0]) - 96;
				}
			}
			$col = $pos % $c;
			$row = ($pos - $col) / $c;
			$data->state[$row][$col] = ($part['t'] === 'B' ? 5 : +$part['t']);
			$pos++;
		}
	}

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}

