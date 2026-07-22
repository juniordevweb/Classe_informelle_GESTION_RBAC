<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMustChangePasswordToUsers extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('must_change_password', 'users')) {
            $this->forge->addColumn('users', [
                'must_change_password' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'after'      => 'status',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('must_change_password', 'users')) {
            $this->forge->dropColumn('users', 'must_change_password');
        }
    }
}
