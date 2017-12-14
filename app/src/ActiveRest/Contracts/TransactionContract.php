<?php

namespace ActiveRest\Contracts;

/**
 * Interface HasTransactionContract
 *
 * @package ActiveRest\Contracts
 */
interface TransactionContract
{
    public function begin();

    public function commit();

    public function rollback();
}
