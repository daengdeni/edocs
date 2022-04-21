<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JenisDokumen extends Migration
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
			'jenis'          => [
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
		$this->forge->createTable('jenis_dokumens');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('jenis_dokumens');
	}
}
