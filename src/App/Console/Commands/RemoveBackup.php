<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RemoveBackup extends Command {

	protected $signature = 'remove:backup';

	protected $description = 'Exclui arquivos de backup';

	protected $process;

	public function __construct() {

		parent::__construct();

	}

	public function handle() {

		try {

			$expire = date('Y-m-d H:i:s', strtotime('-' . config('backup_database.expire_days') . ' days'));

			$backups = \App\Http\Models\BackupDatabase::where('created_at', '<', $expire)->get();

			foreach ($backups as $backup):

				// Armazena na variável o caminho do arquivo a ser deletado
				$path = $backup->path;

				//////////////////////////////////////////////////////////////////////////////////////////
				if ($backup->spaces):

					$first = collect(config('backup_database.plataforms'))->where('disk', 'spaces')->first();

					$search = '{{app_name}}';
					$replace = \Str::slug(config('app.name'), '_');
					$subject = $first['root'];

					$root = str_replace($search, $replace, $subject);

					// Inicia o processo de exclusão remoto
					$disk = Storage::build([
						'driver' => $first['driver'],
						'endpoint' => $first['endpoint'],
						'key' => $first['key'],
						'secret' => $first['secret'],
						'root' => $root,
						'region' => $first['region'],
						'bucket' => $first['bucket'],
						'visibility' => $first['visibility'],
					]);

					$disk->delete($backup->name);
					// Fim Inicia o processo de exclusão remoto
				endif;
				//////////////////////////////////////////////////////////////////////////////////////////
				if ($backup->ftp):

					$first = collect(config('backup_database.plataforms'))->where('disk', 'ftp')->first();

					// Inicia o processo de exclusão remoto
					$disk = Storage::build([
						'driver' => $first['driver'],
						'host' => $first['host'],
						'username' => $first['username'],
						'password' => $first['password'],
						'port' => $first['port'],
						'root' => $first['root'],
						'passive' => (bool) $first['passive'],
						'ssl' => (bool) $first['ssl'],
						'timeout' => (int) $first['timeout'],
					]);

					$disk->delete($backup->name);
					// Fim Inicia o processo de exclusão remoto
				endif;
				//////////////////////////////////////////////////////////////////////////////////////////

				// Remove o registro do banco de dados
				$backup->delete();

				// Remove o arquivo do servidor
				@unlink($path);

			endforeach;

			$this->info('Arquivos de backups excluídos com sucesso.');

		} catch (ProcessFailedException $exception) {

			\Log::debug($exception);

			$this->error('Ocorreu um erro ao excluir os arquivos de backup.');
		}
	}
}