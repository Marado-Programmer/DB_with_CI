<?php

namespace App\Models;

use App\Database\Seeds\CatFactsSeeder;
use App\Libraries\LookUpTable;
use CodeIgniter\Model;
use Config\Database;
use Config\Services;

final class CatFactsModel extends Model
{
    public const CAT_FACTS_TABLE = "CatFacts";
    public const CAT_FACTS_FACT_MAX_LEN = 2 ** 8 - 1;
    public const CAT_FACTS_FACT_CHUNK_LEN = 5;

    protected $table = "CatFacts";

    protected $allowedFields = ["fact", "length"];

    public function getCatFacts(?int $id = null): array
    {
        $this->migrate();

        if (!isset($id)) {
            $facts = $this->findAll(static::CAT_FACTS_FACT_CHUNK_LEN);

            if (empty($facts)) {
                Database::seeder()->call(CatFactsSeeder::class);
                $facts = $this->findAll(static::CAT_FACTS_FACT_CHUNK_LEN);
            }

            $lut = new LookUpTable($_SESSION);
            $quantity = count($facts);

            do {
                $having = [];
                foreach ($facts as $k => $i) {
                    ["id" => $id] = $i;

                    if ($lut->has($id)) {
                        $having[] = $id;
                        unset($facts[$k]);
                        continue;
                    }

                    $lut->set($id);
                }

                if (!empty($having)) {
                    $more_facts = $this->whereNotIn("id", $having)->findAll(count($having));

                    if (count($more_facts) < $quantity) {
                        Database::seeder()->call(CatFactsSeeder::class);
                        $more_facts = $this->whereNotIn("id", $having)->findAll(count($more_facts));
                    }

                    foreach ($more_facts as $j) {
                        $facts[] = $j;
                    }
                }
            } while (!empty($having));

            return $facts;
        }

        return (array) $this->where(["id" => $id])->first() ?? [];
    }

    private function migrate(): void
    {
        Services::migrations()->latest();
    }
}
