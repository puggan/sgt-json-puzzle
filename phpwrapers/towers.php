<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		'h' => 'Hard',
		'x' => 'Extreme',
		'u' => 'Unreasonable',
	];

	exec('bin/towerssolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)(d(?<d>.))?$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['w2'];

	[$clues, $towers] = explode(',', $id);
	$clues = array_map('intval', explode('/', $clues));
	$c = $data->settings->columns;
	foreach(['N', 'S', 'W', 'E'] as $index => $dir)
	{
		$data->state->$dir = array_slice($clues, $index * $c, $c);
	}

	$data->state->grid = array_fill(0, $c, array_fill(0, $c, 0));

	if($towers && preg_match_all('#(?<d>z*[a-z_])?(?<t>\d+)#', $towers, $parts, PREG_SET_ORDER))
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
			$data->state->grid[$row][$col] = +$part['t'];
			$pos++;
		}
	}
