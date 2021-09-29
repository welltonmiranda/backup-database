<?php

namespace Welltonmiranda\BackupDatabase\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command {

	protected $signature = 'backup:database';

	protected $description = 'Faz Backup do banco de dados';

	protected $process;

	public function __construct() {

		parent::__construct();

		if (!file_exists(storage_path('app/backups'))):

			\Storage::makeDirectory('backups');

		endif;

		$this->created_at = date('Y-m-d H:i:s');
		$this->name = \Str::slug(config('app.name'), '_') . '_' . date('YmdHis', strtotime($this->created_at)) . '_backup.gz';
		$this->path = storage_path('app/backups/' . $this->name);

		$this->process = new Process([
			'mysqldump',
			'--user=' . config('database.connections.mysql.username'),
			'--password=' . config('database.connections.mysql.password'),
			'--skip-add-drop-table',
			'--skip-triggers',
			'--quote-names',
			'--compact',
			config('database.connections.mysql.database'),
			'--result-file=' . $this->path,
		]);

	}

	public function handle() {

		try {

			/* Grava o registro de backup no banco de dados*/
			$backup = new Welltonmiranda\BackupDatabase\App\Http\Models\BackupDatabase;
			$backup->name = $this->name;
			$backup->path = $this->path;
			$backup->created_at = $this->created_at;
			$backup->save();

			/* Fim  Grava o registro de backup no banco de dados*/

			$this->process->mustRun();

			/* Envia backup dos banco de dados por ftp */

			$plataforms = config('backup_database.plataforms');

			foreach ($plataforms as $plataform):

				/* Envia backup dos banco de dados */

				if ($plataform['disk'] == 'spaces' AND $plataform['active'] == true):

					// Cria um job
					Welltonmiranda\BackupDatabase\App\Jobs\Backup\Database::dispatch($backup, $plataform['disk'])
						->onConnection('database')
						->onQueue('backup-database');
					// Fim Cria um job

					$backup->spaces = true; // Enfileirado
					$backup->save();

					$this->info('Backup ' . $plataform['disk'] . ' realizado com sucesso');

				endif;

				if ($plataform['disk'] == 'ftp' AND $plataform['active'] == true):

					// Cria um job
					Welltonmiranda\BackupDatabase\App\Jobs\Backup\Database::dispatch($backup, $plataform['disk'])
						->onConnection('database')
						->onQueue('backup-database');
					// Fim Cria um job

					$backup->ftp = true; // Enfileirado
					$backup->save();

					$this->info('Backup ' . $plataform['disk'] . ' realizado com sucesso');

				endif;

				if ($plataform['disk'] == 'mail' AND $plataform['active'] == true):

					$emails = $plataform['emails'];

					foreach ($emails as $email):

						Welltonmiranda\BackupDatabase\App\Jobs\Backup\Mail::dispatch($backup, $email)
							->onConnection('database')
							->onQueue('backup-database');

						$backup->mail = true; // Enfileirado
						$backup->save();

					endforeach;

					$this->info('Backup ' . $plataform['disk'] . ' realizado com sucesso');

				endif;

			endforeach;

			$this->info('Tarefas realizadas com sucesso.');

		} catch (ProcessFailedException $exception) {

			\Log::debug($exception);

			$this->error('Ocorreu um erro ao efetuar o backup.');
		}

	}
}