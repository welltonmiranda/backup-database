<?php

return [
	'local' => false,
	'production' => true,
	'schedule' => 'hourly', // everyMinute, everyFiveMinutes, everyTenMinutes, everyFifteenMinutes, everyThirtyMinutes, hourly, daily, weekly
	'tries' => '3',
	'timeout' => '60',
	'expire_days' => '3',
	'plataforms' => [

		[
			'disk' => 'spaces',
			'active' => true,
			'endpoint' => env('DO_SPACES_ENDPOINT'),
			'key' => env('DO_SPACES_KEY'),
			'secret' => env('DO_SPACES_SECRET'),
			'root' => 'backups/{{app_name}}/banco-de-dados', // Obs: Não alterar ou remover o {{app_name}}
			'region' => env('DO_SPACES_REGION'),
			'bucket' => env('DO_SPACES_BUCKET'),
			'visibility' => 'public',
			'driver' => 's3', // Obs: Não alterar este valor
		],

		[
			'disk' => 'mail',
			'active' => false,
			'emails' => [],
		],

		[
			'disk' => 'ftp',
			'active' => false,
			'driver' => 'ftp',
			'host' => env('FTP_HOST'),
			'username' => env('FTP_USERNAME'),
			'password' => env('FTP_PASSWORD'),
			'port' => (int) env('FTP_PORT'),
			'root' => 'milenium', // Obs: Dentro da raiz do ftp crie uma pasta/local aonde serão salvos os backups
			'passive' => (bool) env('FTP_PASSIVE'),
			'ssl' => (bool) env('FTP_SSL'),
			'timeout' => (int) env('FTP_TIMEOUT'),
		],

	],
];