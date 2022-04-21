<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Dokumen extends Migration
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
			'nomer_doc'          => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'judul_doc'          => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'slug_doc'          => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'summary_doc'          => [
				'type'           => 'TEXT',
			],
			'dibuat_oleh'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
			],
			'dibuat_tanggal'       => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
			],
			'periode_mulai' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
			],
			'periode_berakhir' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
			],
			'rahasia' => [
				'type'           => 'VARCHAR',
				'constraint'     => '100',
				'default'		 => 'ya'
			],
			'created_at' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
			],
			'updated_at' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
            ],
			'deleted_at' => [
				'type'           => 'DATETIME',
				'null'       	 => true,
            ],

		]);
		$this->forge->addPrimaryKey('id', true);
		$this->forge->createTable('dokumens');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('dokumens');
	}
}
