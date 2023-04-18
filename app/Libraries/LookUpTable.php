<?php

namespace App\Libraries;

final class LookUpTable
{
    private array $table;

    public function __construct(array &$table)
    {
        $this->table = $table;
    }

    public function set(int $i): void
    {
        //if (!isset($this->table[$this->keygen($i >> 6)])) {
        //    $this->table[$this->keygen($i >> 6)] = 0;
        //}

        //$this->table[$this->keygen($i >> 6)] |= 1 << ($i & (2 ** 6 - 1));
        if (!isset($_SESSION[$this->keygen($i >> 6)])) {
            $_SESSION[$this->keygen($i >> 6)] = 0;
        }

        $_SESSION[$this->keygen($i >> 6)] |= 1 << ($i & (2 ** 6 - 1));
    }

    public function has(int $i): bool
    {

        //return (($this->table[$this->keygen($i >> 6)] ?? 0) >> ($i & (2 ** 6 - 1))) & 1;
        return (($_SESSION[$this->keygen($i >> 6)] ?? 0) >> ($i & (2 ** 6 - 1))) & 1;
    }

    private function keygen(int $i): string
    {
        return "CAT_FACTS_LUT_$i";
    }
}
