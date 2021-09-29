<?php

namespace Welltonmiranda\BackupDatabase\App\Jobs\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class Database implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $backup;
	protected $disk;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($backup, $disk) {
		$this->backup = $backup;
		$this->disk = $disk;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {

		$contents = file_get_contents($this->backup->path);

		$first = collect(config('backup_database.plataforms'))->where('disk', $this->disk)->first();

		$search = '{{app_name}}';
		$replace = \Str::slug(config('app.name'), '_');
		$subject = $first['root'];

		$root = str_replace($search, $replace, $subject);

		if ($this->disk == 'spaces'):
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
		endif;

		if ($this->disk == 'ftp'):
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
		endif;

		$disk->put($this->backup->name, $contents);

	}
}
