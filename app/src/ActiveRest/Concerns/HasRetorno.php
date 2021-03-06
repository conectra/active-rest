<?php
namespace ActiveRest\Concerns;

//texception
use Solis\Breaker\Abstractions\TExceptionAbstract;

trait HasRetorno
{
    //Retorno
    protected $retorno;

    //Mensagens
    protected $aMessages;

    //Tipo de Processamento
    protected $synchronous;
    protected $capSynchronous;

    //Contadores
    protected $count;
    protected $countFail;
    protected $countSuccess;
    protected $countTotal;

    /**
     * @param $param
     * @return string
     */
    public function prepareRetorno(
        $param
    ) {
        try {
            // Template do Retorno
            $this->retorno = [
                'status' => true,
                'message' => null,
                'count' => 0,
                'detail' => null,
                'data' => []
            ];

            // Contadores
            $this->count = 0;
            $this->countFail = 0;
            $this->countSuccess = 0;
            $this->countTotal = count($param);

            // Tipo de Processamento, baseado na quantidade de parametros
            $this->synchronous = $this->countTotal === $this->capSynchronous ? true : false;

            //Inicia o array de mensagens para compor o detalhamento
            $this->aMessages = [];

        } catch (\Exception $e) {
            return 'exception: '.$e->getMessage();
        }
    }

    /**
     * Incrementa o contador do TOTAL e da FALHA
     * @param string $message
     * @return array
     */
    public function newFail(
        string $message
    ) {
        try {
            //Contadores
            $this->count++;
            $this->countFail++;

            //Detalhamento
            $this->aMessages[] = $message;

            // Retorno Individual via HasPrepareRetorno
            $this->retorno['data'][] = [
                'success' => false,
                'message' => $message
            ];
        } catch (\Exception $e) {
            return ['exception' => $e->getMessage()];
        }
    }

    /**
     * Incrementa o contador do TOTAL e da FALHA
     * @param \Throwable $e
     * @throws \Exception
     */
    public function newThrowableFail(\Throwable $e)
    {
        $message = $e instanceof TExceptionAbstract ? $e->getError()->getEntry('message') : $e->getMessage();

        throw new \Exception($message, 500);
    }

    /**
     * Incrementa o contador do TOTAL e do SUCESSO
     * @param string $message
     * @param string $extra
     * @return array
     */
    public function newSuccess(
        string $message,
        $extra = null
    ) {
        try {
            //Contadores
            $this->count++;
            $this->countSuccess++;

            //Detalhamento
            $this->aMessages[] = $message;

            //Extra
            $extra = !is_null($extra) ? ['extra' => $extra] : [];

            // Retorno Individual via HasPrepareRetorno
            $this->retorno['data'][] = array_merge(
                [
                    'success' => true,
                    'message' => $message,
                ],
                $extra
            );
        } catch (\Exception $e) {
            return ['exception' => $e->getMessage()];
        }
    }

    /**
     * @return array
     */
    public function getRetornoProcessamento(): array
    {
        // Valida se tem dados processados
        if (
            empty($this->retorno['data']) &&
            empty($this->aMessages)
        ) {
            return [
                'status' => false,
                'message' => 'Nenhum registro processado, verifique os argumentos fornecidos'
            ];
        }

        $message = $this->countSuccess . ' registros processados e ' . $this->countFail . ' não foram processados. ';
        return [
            'status' => true,
            'message' => $message,
            'count' => $this->count,
            'detail' => '('.implode(', ', $this->aMessages).')',
            'data' => $this->retorno['data']
        ];
    }

}