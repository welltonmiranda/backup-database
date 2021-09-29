<?php

namespace Welltonmiranda\BackupDatabase\Providers;

use \Illuminate\Support\ServiceProvider;

class BackupDatabaseServiceProvider extends ServiceProvider {

	public function boot() {

		$this->publishes([
			__DIR__ . '/../config/backup_database.php' => config_path('backup_database.php'),
		], 'backup-database-config');

		$this->loadMigrationsFrom(__DIR__ . 'database/migrations');

		if ($this->app->runningInConsole()) {
			$this->commands([
				\Welltonmiranda\BackupDatabase\App\Console\Commands\BackupDatabase::class,
				\Welltonmiranda\BackupDatabase\App\Console\Commands\RemoveBackup::class,
			]);
		}
	}

}