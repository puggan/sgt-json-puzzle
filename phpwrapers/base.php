<?php

	ob_start();
	define('DEBUG', !empty($_GET['debug']) || ($argv[1] ?? '') === '-v');
	$o = NULL;
	$data = (object) [];
	$data->settings = (object) [];
	$data->state = (object) [];

	chdir(__DIR__ . '/..');

	if(isset($_SERVER))
	{
		header('Content-Type: application/json');
	}

	register_shutdown_function(
		static function () {
			global $data, $argv, $o;
			if($error = ob_end_clean()){
				if(isset($_SERVER)) {
					header('HTTP/1.1 500 ' . $error);
					echo 'false';
					return;
				}
				fprintf(STDERR, "%s\n", $error);
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
