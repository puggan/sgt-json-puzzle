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
	 * @param bool $nics
	 */
	function parse_ncis($o, $ncsi = false)
	{
		global $config, $data, $id, $o, $seed;
		$s = ': ';
		$data->name = strstr($o[0], $s, true);
		$id = strstr($o[0], $s);
		$seed = strstr($o[$ncsi ? 2 : 1], $s);
		$config = strstr($o[$ncsi ? 1 : 2], $s);
		$data->id = $config . ':' . $id;
		$data->seed = $config . '¤' . $seed;
	}
