<?php

/** Importação de classes */

use vendor\model\Calls;
use vendor\controller\calls\CallsValidate;

/** Instânciamento de classes */
$Calls = new Calls();
$CallsValidate = new CallsValidate();

try {

    /** Parâmetros de entrada */
    $CallsValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($CallsValidate->getErrors())) {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsValidate->getErrors(), 0);

    } else {

        /** Verifico se o usuário foi localizado */
        if ($Calls->delete($CallsValidate->getCallId())) {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro Removido com Sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DATAGRID'

            ];

        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel remover o registro', 0);

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
        'message' => '<div class="alert alert-danger" role="alert">' . $exception->getMessage() . '</div>',
        'title' => 'Atenção',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

}