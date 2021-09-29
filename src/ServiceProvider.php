<?php

namespace Welltonmiranda\BackupDatabase;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	use Welltonmiranda\BackupDatabase\App\Console\Commands\BackupDatabase;
	use Welltonmiranda\BackupDatabase\App\Console\Commands\RemoveDatabase;

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