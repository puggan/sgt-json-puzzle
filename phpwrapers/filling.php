<?php

	require_once __DIR__ . '/base.php';

	exec('bin/fillingsolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);

	if(1 || !preg_match('/^(?<w2>\d+)x(?<h2>\d+)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings->columns = $m['w2'];
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . $seed;

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
