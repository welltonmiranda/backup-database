# backup-database
## Instalação via composer
~~~
composer require --with-all-dependencies welltonmiranda/backup-database
~~~
## Comandos necessários
~~~
php artisan queue:table
~~~
~~~
php artisan queue:failed-table
~~~
~~~
php artisan migrate
~~~
~~~
php artisan vendor:publish --tag=backup-database-config
~~~
## Depois de instalado adicione as seguintes linhas no seu <code>app\Console\Kernel.php</code>
~~~
// As tarefas falhas voltam para fila de hora em hora
$schedule->command('queue:retry all')->hourly();
$env = config('app.env');
if (($env == 'local' AND config('backup_database.local')) OR ($env == 'production' AND config('backup_database.production'))):
  $schedule->command('backup:database')->{config('backup_database.schedule', 'hourly')}()->timezone('America/Sao_Paulo');
  $schedule->command('queue:work database --queue=high,backup-database --stop-when-empty --tries=' . config('backup_database.tries', '3') . ' --timeout=' . config('backup_database.timeout', '60'))->everyMinute()->timezone('America/Sao_Paulo');
  $schedule->command('remove:backup')->everyMinute()->timezone('America/Sao_Paulo');
endif;
~~~
## Local do arquivo de configuração <code>config\backup_database.php</code>
~~~
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
~~~