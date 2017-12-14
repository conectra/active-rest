<?php


namespace ActiveRest\Contracts;

/**
 * Interface HasTransactionContract
 *
 * @package ActiveRest\Contracts
 */
interface HasTransactionContract
{
    /**
     * Método responsável por iniciar uma transação com a persistência
     */
    public function beginTransaction();

    /**
     * Método resposável por comitar as operações realizadas na transação corrente
     */
    public function commitTransaction();

    /**
     * Método responsável por desfazer as operações realizadas na transação corrente
     */
    public function rollbackTransaction();
}