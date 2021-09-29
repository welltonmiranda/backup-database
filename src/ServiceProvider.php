<?php

namespace Welltonmiranda\BackupDatabase;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	public function boot() {

		$this->publishes([
			__DIR__ . '/../config/backup_database.php' => config_path('backup_database.php'),
		]);

		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		if ($this->app->runningInConsole()) {
			$this->commands([
				Welltonmiranda\BackupDatabase\App\BackupDatabase::class,
				Welltonmiranda\BackupDatabase\App\RemoveDatabase::class,
			]);
		}
	}

}