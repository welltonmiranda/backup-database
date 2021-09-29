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
			'active' => false,
			'endpoint' => '',
			'key' => '',
			'secret' => '',
			'root' => 'backup/database/{{app_name}}', // Obs: Não alterar ou remover o {{app_name}}
			'region' => '',
			'bucket' => '',
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
			'host' => '',
			'username' => '',
			'password' => '',
			'port' => (int) 21,
			'root' => 'backup-database', // Obs: Dentro da raiz do ftp crie uma pasta/local aonde serão salvos os backup-database
			'passive' => (bool) false,
			'ssl' => (bool) false,
			'timeout' => (int) 60,
		],

	],
];