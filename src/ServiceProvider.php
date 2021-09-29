<?php

namespace Welltonmiranda\BackupDatabase;

use Welltonmiranda\BackupDatabase\App\Console\Commands\BackupDatabase;
use Welltonmiranda\BackupDatabase\App\Console\Commands\RemoveDatabase;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	public function boot() {

		$this->publishes([
			__DIR__ . '/../config/backup_database.php' => config_path('backup_database.php'),
		]);

		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		if ($this->app->runningInConsole()) {
			$this->commands([
				BackupDatabase::class,
				RemoveDatabase::class,
			]);
		}
	}

}