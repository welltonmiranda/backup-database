# laravel-backup-database
## Instalação via composer
composer require welltonmiranda/laravel-backup-database
## Depois de instalado adicione as seguintes linhas no seu <code>app\Console\Kernel.php</code>

~~~
if (($env == 'local' AND config('backup_database.local')) OR ($env == 'production' AND config('backup_database.production'))):
  $schedule->command('backup:database')->{config('backup_database.schedule', 'hourly')}()->timezone('America/Sao_Paulo');
  $schedule->command('queue:work database --queue=high,backup-database --stop-when-empty --tries=' . config('backup_database.tries', '3') . ' --timeout=' . config('backup_database.timeout', '60'))->everyMinute()->timezone('America/Sao_Paulo');
  $schedule->command('remove:backup')->everyMinute()->timezone('America/Sao_Paulo');
endif;
