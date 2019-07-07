<?php

	header('Content-Type: application/json');

	$dificulties = [
		'e' => 'Easy',
		't' => 'Tricky',
	];

	chdir(__DIR__ . '/..');
	exec('bin/tentssolver', $o);

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
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->seed = $config . '#' . $seed;
	$data->state = (object) [];

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

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	}
	else
	{
		$data->debug = $o;
		echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
	}
