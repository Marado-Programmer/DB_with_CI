<?php

namespace App\Database\Seeds;

use App\Models\CatFactsModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Database\Seeder;

use function TorresDeveloper\Pull\pull;

class CatFactsSeeder extends Seeder
{
    static string $len = "max_length=" . CatFactsModel::CAT_FACTS_FACT_MAX_LEN;

    public function run()
    {
        $cache = Services::cache();
        $page = $cache->get("next_page_url");
        $url = ($page ? "$page&" : "https://catfact.ninja/facts?") . static::$len;

        ["next_page_url" => $next, "data" => $data] = json_decode(pull($url, headers: [
            "Accept" => "application/json",
        ]), true);

        $model = model(CatFactsModel::class);

        if (is_iterable($data)) {
            foreach ($data as $fact) {
                try {
                    $model->save($fact);
                } catch (\Throwable) {
                    continue;
                }
            }

            $cache->save("next_page_url", $next);
        } else {
            throw new \RuntimeException("Unexpected cat fact recieved");
        }
    }
}
