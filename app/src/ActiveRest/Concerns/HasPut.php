<?php
namespace ActiveRest\Concerns;

//texception
use Solis\Breaker\TException;
use Solis\Breaker\Abstractions\TExceptionAbstract;

/**
 * Trait HasPut
 * @package ActiveRest\Concerns
 */
trait HasPut
{
    // Mensagens
    protected static $MSG_UPDATE_ALL = ' registros foram atualizados e ';
    protected static $MSG_UPDATE_SUCCESS = 'Registro atualizado com sucesso';
    protected static $MSG_UPDATE_FAIL = 'Falha ao atualizar registro';
    protected static $MSG_BEFORE_UPDATE_FAIL = 'Falha ao executar as operacoes pre atualizacao';
    protected static $MSG_AFTER_UPDATE_FAIL = 'Falha ao executar as operacoes pos atualizacao';

    /**
     * Método executado antes de Atualizar via Update um Registro
     * @param $param
     * @return array
     */
    public function beforeUpdate(
        array $param
    ): array {
        return $this->before($param);
    }

    /**
     * Método executado depois de Atualizar via Update um Registro
     * @param $param
     * @return array
     */
    public function afterUpdate(
        $param
    ): array {
        return $this->after($param);
    }

    /**
     * Método responsável por atualizar via update 1 registro no database
     * @param array $param
     */
    public function updateOne(
        array $param
    ) {
        // Antes de ATUALIZAR
        $beforeUpdate = $this->beforeUpdate($param);

        // Valida se o beforePost retornou sucesso e os parâmetros
        if ($beforeUpdate['success'] === true && is_array($beforeUpdate['param'])) {
            //Carrega o Model com os parametros
            $this->setModel(
                call_user_func_array(
                    [get_class($this->getModel()), 'make'],
                    [$beforeUpdate['param']]
                )
            );

            // Valida a existencia de um metodo custom para atualização do registro
            if (method_exists(
                $this,
                'customUpdate'
            )) {
                $updated = $this->customUpdate($param);
            } else {
                $updated = $this->getModel()->update();
            }

            //Valida Atualização
            if (empty($updated)) {
                $this->newFail(self::$MSG_POST_FAIL);
                return;
            }

            // Depois de ATUALIZAR
            $afterUpdate = $this->afterUpdate($this->getModel());
            if ($afterUpdate['success'] === false || empty($afterUpdate['param'])) {
                $this->newFail(self::$MSG_AFTER_UPDATE_FAIL);
                return;
            }

            // Retorno
            $this->newSuccess(self::$MSG_UPDATE_SUCCESS);
        } elseif ($beforeUpdate['success'] === false || empty($beforeUpdate['param'])) {
            // Mensagem
            $message = $beforePost['message'] ?? self::$MSG_UPDATE_FAIL. ' - ' . self::$MSG_BEFORE_UPDATE_FAIL;
            // ADICIONA FALHA via HasPrepareRetorno
            $this->newFail($message);
        }
    }


    /**
     * Método Responsável por Atualizar via Update 1 ou N Registros
     * @param $params
     * @return array
     */
    public function update(
        array $params
    ): array {
        $this->beginTransaction();
        try {
            // Se os parâmetros não estão vazios carrega o Model
            if (empty($params)) {
                throw new TException(
                    __CLASS__,
                    __METHOD__,
                    'Metodo: [ ' . __CLASS__ . ' ], da Classe: [ ' . __METHOD__ . ' ], nao pode ser vazio.',
                    400
                );
            }

            // Retorna sempre um array de parametros, independente de fornecer 1 ou N
            $this->simpleArraytoMulti($params);

            // Inicia o retorno a partir do Trait HasPrepareRetorno
            $this->prepareRetorno(
                count($params)
            );

            // Itera sobre os parâmetros inserindo os registros um por um
            foreach ($params as $param) {
                $this->updateOne($param);
            }

            $this->commitTransaction();
        } catch (\Throwable $e) {
            $this->rollbackTransaction();

            $this->newThrowableFail($e);
        }

        //Retorno
        return $this->getRetornoProcessamento();
    }
}
