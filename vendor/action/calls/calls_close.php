<?php

/** Importação de classes  */

use vendor\model\Calls;
use vendor\controller\calls\CallsValidate;

try {

    /** Instânciamento de classes  */
    $Calls = new Calls();
    $CallsValidate = new CallsValidate();

    /** Parametros de entrada  */
    $callId = @(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $dateClose = date('Y-m-d');

    /** Validando os campos de entrada */
    $CallsValidate->setCallId($callId);
    $CallsValidate->setDateClose($dateClose);

    /** Verifico a existência de erros */
    if (!empty($CallsValidate->getErrors())) {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsValidate->getErrors(), 0);

    } else {

        /** Busco o registro desejado */
        $resultCall = $Calls->get($CallsValidate->getCallId());

        /** Verifico se o registro foi localizado */
        if (@(int)$resultCall->call_id > 0) {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Calls->SaveClose($CallsValidate->getCallId(), $CallsValidate->getDateClose())) {

                /** Defino o histórico */
                $history[0]['title'] = 'Encerramento';
                $history[0]['description'] = 'Chamando encerrado no dia';
                $history[0]['date'] = date('d-m-Y');
                $history[0]['time'] = date('H:i:s');
                $history[0]['class'] = 'badge-danger';
                $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

                /** Verifico se já existe históric */
                if (!empty($resultCall->history)) {

                    /** Pego o histórico existente */
                    $resultHistory = json_decode($resultCall->history, true);

                    /** Unifico os históricos */
                    $history = array_merge($resultHistory, $history);

                }

                /** Defino o histórico */
                $CallsValidate->setHistory($history);

                /** Atualizo o histórioc */
                $Calls->SaveHistory($CallsValidate->getCallId(), json_encode($CallsValidate->getHistory(), JSON_PRETTY_PRINT));

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => 'Chamado encerrado com sucesso',
                    'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsValidate->getCallId()

                ];

            } else {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException('Não foi encerrar o chamado', 0);

            }

        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi localizar o chamado', 0);

        }

    }

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

} catch (Exception $exception) {

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}