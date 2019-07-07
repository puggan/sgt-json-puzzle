<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		'n' => 'Normal',
		't' => 'Tricky',
		'h' => 'Hard',
	];

	exec('bin/loopysolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)t(?<t>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->settings->type = $m['t'];

	$c = $data->settings->columns;
	$data->state = array_fill(0, $data->settings->rows, array_fill(0, $c, 5));

	if(preg_match_all('#(?<d>z*[a-z])?(?<t>\d)#', $id, $parts, PREG_SET_ORDER))
	{
		$pos = 0;
		foreach($parts as $part)
		{
			if($part['d'])
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
			$data->state[$row][$col] = +$part['t'];
			$pos++;
		}
	}
