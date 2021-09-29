<?php

namespace Welltonmiranda\BackupDatabase;

use \Illuminate\Support\ServiceProvider;

class BackupDatabaseProvider extends ServiceProvider {

	public function boot() {

		$this->publishes([
			__DIR__ . '/../config/backup_database.php' => config_path('backup_database.php'),
		]);

		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		if ($this->app->runningInConsole()) {
			$this->commands([
				App\Console\Commands\BackupDatabase::class,
				App\Console\Commands\RemoveDatabase::class,
			]);
		}
	}

}