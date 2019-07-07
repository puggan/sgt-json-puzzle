<?php

	ob_start();
	define('DEBUG', !empty($_GET['debug']) || ($argv[1] ?? '') === '-v');
	define('CLI', PHP_SAPI === 'cli');

	$config = NULL;
	$id = NULL;
	$o = NULL;
	$seed = NULL;
	$data = (object) [];
	$data->id = NULL;
	$data->name = NULL;
	$data->seed = NULL;
	$data->settings = (object) [];
	$data->state = (object) [];

	chdir(__DIR__ . '/..');

	if(!CLI)
	{
		header('Content-Type: application/json');

		$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
		if($origin && preg_match('#^https://[a-z]+(\.[a-z]+)+$#', $origin))
		{
			header('Access-Control-Allow-Origin: ' . $origin);
		}
	}

	register_shutdown_function(
		static function () {
			global $data, $o;
			if($error = trim(ob_get_clean())){
				if(CLI)
				{
					fprintf(STDERR, "%s\n", $error);
					return;
				}

				header('HTTP/1.1 500 ' . $error);
				echo 'false';
				return;
			}

			if(DEBUG)
			{
				$data->debug = $o;
				echo json_encode($data, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
				return;
			}

			echo json_encode($data);
		}
	);

	/**
	 * @param string[] $o
	 * @param bool $ncsi
	 */
	function parse_ncis($o, $ncsi = false)
	{
		global $config, $data, $id, $o, $seed;
		$s = ': ';
		$data->name = strstr($o[0], $s, true);
		$config = substr(strstr($o[0], $s), 2);
		$id = substr(strstr($o[$ncsi ? 2 : 1], $s), 2);
		$seed = substr(strstr($o[$ncsi ? 1 : 2], $s), 2);
		$data->id = $config . ':' . $id;
		$data->seed = $config . '#' . $seed;
	}

	/**
	 * @param int[][]|string[][] $grid
	 * @param string $id
	 * @param bool $za26: {25: y, 26: za} vs {25:y , 26: z, 27: za}
	 * @param null|callable $n_callback
	 * @param string $n_rule
	 *
	 * @return mixed
	 */
	function az_grid($grid, $id, $za26 = false, $n_callback = null, $n_rule = '\d')
	{
		if($za26)
		{
			if(!preg_match_all('/(?<d>z*[a-y_])?(?<n>' . $n_rule . '?)/', $id, $parts, PREG_SET_ORDER))
			{
				return $grid;
			}
		}
		else if(!preg_match_all('/(?<d>z*[a-z_])?(?<n>' . $n_rule . ')/', $id, $parts, PREG_SET_ORDER))
		{
			return $grid;
		}

		$in_range_error = null;
		$columns = count($grid[0]);
		$pos = 0;
		foreach($parts as $index => $part)
		{
			if($part[0] === '')
			{
				continue;
			}
			if($in_range_error) {
				throw new RuntimeException($in_range_error);
			}
			if($part['d'] && $part['d'] !== '_')
			{
				$d = $part['d'];
				while($d && $d[0] === 'z')
				{
					$pos += $za26 ? 25 : 26;
					$d = substr($d, 1);
				}
				if($d)
				{
					$pos += ord($d[0]) - 96;
				}
			}
			$col = $pos % $columns;
			$row = ($pos - $col) / $columns;
			if(!isset($grid[$row][$col]))
			{
				$in_range_error = "Missing cell: {$row}x{$col}";
				continue;
			}
			if($n_callback)
			{
				$grid[$row][$col] = $n_callback($part['n']);
			}
			else
			{
				$grid[$row][$col] = +$part['n'];
			}
			$pos++;
		}
		return $grid;
	}

	function fill_2d($r, $c, $d)
	{
		return array_fill(0, $r, array_fill(0, $c, $d));
	}
