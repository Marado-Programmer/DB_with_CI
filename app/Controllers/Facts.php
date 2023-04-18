<?php

namespace App\Controllers;

use App\Models\CatFactsModel;
use CodeIgniter\HTTP\Response;

final class Facts extends BaseController
{
    public function index(): Response
    {
        $facts = [];

        try {
            $facts = model(CatFactsModel::class)->getCatFacts();
        } catch (\Throwable $e) {
            throw $e;
            return $this->response->setStatusCode(500)->setJSON([
                "err" => $e->__toString(),
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON($facts);
    }
}
