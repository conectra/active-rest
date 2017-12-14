<?php


namespace ActiveRest\Concerns;

use ActiveRest\Contracts\TransactionContract;

/**
 * Trait HasTransaction
 *
 * @package ActiveRest\Concerns
 */
trait HasTransaction
{

    /**
     * @var TransactionContract
     */
    protected $transaction;

    /**
     * Método responsável por iniciar uma transação com a persistência
     */
    public function beginTransaction()
    {
        if ($this->transaction && $this->transaction instanceof TransactionContract) {
            $this->transaction->begin();
        }
    }

    /**
     * Método resposável por comitar as operações realizadas na transação corrente
     */
    public function commitTransaction()
    {
        if ($this->transaction && $this->transaction instanceof TransactionContract) {
            $this->transaction->commit();
        }
    }

    /**
     * Método responsável por desfazer as operações realizadas na transação corrente
     */
    public function rollbackTransaction()
    {
        if ($this->transaction && $this->transaction instanceof TransactionContract) {
            $this->transaction->rollback();
        }
    }
}
