<?php

namespace Welltonmiranda\BackupDatabase\App\Jobs\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Mail implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $backup;
	protected $send_email;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($backup, $send_email) {
		$this->backup = $backup;
		$this->send_email = $send_email;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {

		\Mail::to($this->send_email)->send(new Welltonmiranda\BackupDatabase\App\Mail\Backup\Database($this->backup));

	}
}
