<?php

	ob_start();
	define('DEBUG', !empty($_GET['debug']) || ($argv[1] ?? '') === '-v');
	define('CLI', PHP_SAPI === 'cli');

	$o = NULL;
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
