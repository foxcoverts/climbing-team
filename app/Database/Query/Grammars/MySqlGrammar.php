<?php

namespace App\Database\Query\Grammars;

/**
 * Custom MySQL Grammar.
 */
class MySqlGrammar extends \Illuminate\Database\Query\Grammars\MySqlGrammar
{
    /**
     * Include microseconds with stored dates.
     *
     * @see https://carbon.nesbot.com/laravel/
     */
    public function getDateFormat(): string
    {
        return 'Y-m-d H:i:s.u';
    }
}
