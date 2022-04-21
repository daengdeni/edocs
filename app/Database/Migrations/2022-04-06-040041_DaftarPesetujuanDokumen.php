<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DaftarPesetujuanDokumen extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id'     => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
				'auto_increment' => true
			],
			'id_doc'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
			],
			'id_user'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
			],
			'status'          => [
				'type'           => 'VARCHAR',
				'constraint'     => 100,
                'DEFAULT'       => 'nope'
			],
			'created_at' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
			],
			'updated_at' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
            ],

		]);
		$this->forge->addPrimaryKey('id', true);
		$this->forge->createTable('daftar_persetujuan_dokumen');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('daftar_persetujuan_dokumen');
	}
}
