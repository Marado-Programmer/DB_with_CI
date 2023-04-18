<?php

namespace App\Database\Migrations;

use App\Models\CatFactsModel;
use CodeIgniter\Database\Migration;

class InitDB extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "fact" => [
                "type" => "VARCHAR",
                "constraint" => CatFactsModel::CAT_FACTS_FACT_MAX_LEN,
                "unique" => true,
            ],
            "length" => [
                "type" => "INT",
                "constraint" => 10,
                "unsigned" => true,
            ]
        ])->addPrimaryKey("id");

        if (
            $this->forge->createTable(CatFactsModel::CAT_FACTS_TABLE, true, [
            "ENGINE" => "InnoDB",
            ]) === false
        ) {
            throw new \RuntimeException("Could not create `" . CatFactsModel::CAT_FACTS_TABLE . "` table.");
        }
    }

    public function down()
    {
    }
}
