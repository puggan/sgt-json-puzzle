<?php

	require_once __DIR__ . '/base.php';

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	exec('bin/tentssolver', $o);

	parse_ncis($o);

	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)d(?<d>.)$/', $config, $m))
	{
		die('Failed generations');
	}

	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];

	$c = $data->settings->columns;
	$clues = explode(',', $id);
	$data->state->columns = array_slice($clues, 1, $c);
	$data->state->rows = array_slice($clues, 1 + $c);
	$data->state->grid = array_fill(0, $data->settings->rows, array_fill(0, $c, 0));

	/** @noinspection NotOptimalRegularExpressionsInspection */
	if(preg_match_all('#z*[_a-y]#', $clues[0], $parts))
	{
		// The last one just fills up the rest of the board, so ignore it
		array_pop($parts[0]);
		$pos = 0;
		foreach($parts[0] as $part)
		{
			if($part !== '_')
			{
				$d = $part;
				while($d && $d[0] === 'z')
				{
					$pos += 25;
					$d = substr($d, 1);
				}
				if($d)
				{
					$pos += ord($d[0]) - 96;
				}
			}
			$col = $pos % $c;
			$row = ($pos - $col) / $c;
			$data->state->grid[$row][$col] = 1;
			$pos++;
		}
	}
