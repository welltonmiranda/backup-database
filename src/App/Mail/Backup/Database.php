<?php

namespace Welltonmiranda\BackupDatabase\App\Mail\Backup;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Database extends Mailable implements ShouldQueue {

	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */

	protected $backup;

	public function __construct($backup) {
		$this->backup = $backup;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$introLines = [
			__('Você está recebendo um backup do banco de dados :app gerado na data :data.', [
				'data' => date('d/m/Y H:i:s', strtotime($this->backup->created_at)),
				'app' => config('app.name'),
			]),
		];

		$outroLines = [
			__('Mantenha em segurança os dados em anexo.'),
		];

		$config = [
			'header' => true,
			'footer' => true,
			'introLines' => $introLines,
			'outroLines' => $outroLines,
			'level' => 'blue',
			//'actionText' => __('Redefinir Senha'),
			//'actionUrl' => $actionUrl,
		];

		return $this
			->from(config('mail.from.address'), config('mail.from.name'))
			->subject(__('Backup - ' . config('app.name') . ' - ' . date('d/m/Y H:i:s', strtotime($this->backup->created_at))))
			->markdown('mails.backup.database')
			->with($config)
			->attach($this->backup->path);

	}
}
