<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StatusDokumen extends Migration
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
			'id_doc_dituju'          => [
				'type'           => 'INT',
				'constraint'     => 5,
				'unsigned'       => true,
			],
			'status'          => [
                'type'           => 'VARCHAR',
				'constraint'     => '255',
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
		$this->forge->createTable('status_dokumens');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('status_dokumens');
	}
}
