<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaBackupDatabase extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('backup_database', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->boolean('ftp')->default(false);
			$table->boolean('s3')->default(false);
			$table->boolean('spaces')->default(false);
			$table->boolean('mail')->default(false);
			$table->string('name')->nullable();
			$table->longText('path')->nullable();
			$table->timestamps(6);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('backup_database');
	}
}
