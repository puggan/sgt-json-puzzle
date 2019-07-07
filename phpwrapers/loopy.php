<?php

	header('Content-Type: application/json');

	$dificulties = [
		'e' => 'Easy',
		'n' => 'Normal',
		't' => 'Tricky',
		'h' => 'Hard',
	];

	chdir(__DIR__ . '/..');
	exec('bin/loopysolver', $o);

	[$name, $seed] = explode(': ', $o[2]);
	[$name, $id] = explode(': ', $o[1], 2);
	[$name, $config] = explode(': ', $o[0]);


	if(!preg_match('/^(?<w2>\d+)x(?<h2>\d+)t(?<t>\d+)d(?<d>.)$/', $config, $m))
	{
		header('HTTP/1.1 500 Failed generations');
		echo $config, PHP_EOL;
		die('false');
	}

	$data = (object) [];
	$data->id = $config . ':' . $id;
	$data->name = $name;
	$data->settings = (object) [];
	$data->settings->columns = $m['w2'];
	$data->settings->difficulty = $dificulties[$m['d'] ?? 'e'] ?? $dificulties['e'];
	$data->settings->rows = $m['h2'];
	$data->settings->type = $m['t'];
	$data->seed = $config . '#' . $seed;
	$data->state = (object) [];

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

	if(empty($_GET['debug']) && ($argv[1] ?? '') !== '-v')
	{
		echo json_encode($data);
	} else {
		$data->debug = $o;
		echo json_encode($data, 128*3);
	}

