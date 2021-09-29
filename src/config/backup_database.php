<?php

return [
	'local' => false, // Habilita/desabilita no modo desenvolvimento
	'production' => false, // Habilita/desabilita no modo produção
	'schedule' => 'hourly', // Opções configuráveis: everyMinute, everyFiveMinutes, everyTenMinutes, everyFifteenMinutes, everyThirtyMinutes, hourly, daily, weekly
	'tries' => '3', // Tentativas
	'timeout' => '60', // Tempo limite
	'expire_days' => '3', // Tem para os arquivos expirar e serem excluídos
	'plataforms' => [

		[
			'disk' => 'spaces', // Obs: Não alterar este valor
			'active' => false, // Ativado/desativado
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
			'disk' => 'mail', // Obs: Não alterar este valor
			'active' => false, // Ativado/desativado
			'emails' => [], // Ex: ['email@google.com','email2@google.com']
		],

		[
			'disk' => 'ftp', // Não modificar
			'active' => false, // Ativado/desativado
			'driver' => 'ftp', // Obs: Não alterar este valor
			'host' => '',
			'username' => '',
			'password' => '',
			'port' => (int) 21,
			'root' => 'backup-database', // Obs: Dentro da raiz do ftp crie uma pasta/local aonde serão salvos. Ex: backup-database
			'passive' => (bool) false,
			'ssl' => (bool) false,
			'timeout' => (int) 60,
		],

	],
];