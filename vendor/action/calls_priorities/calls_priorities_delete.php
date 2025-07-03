<?php

/** Importação de classes */
use vendor\model\CallsPriorities;
use vendor\controller\calls_priorities\CallsPrioritiesValidate;

/** Instânciamento de classes */
$CallsPriorities = new CallsPriorities();
$CallsPrioritiesValidate = new CallsPrioritiesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsPrioritiesValidate->setCallPriorityId(@(int)filter_input(INPUT_POST, 'CALL_PRIORITY_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($CallsPrioritiesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsPrioritiesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsPriorities->delete($CallsPrioritiesValidate->getCallPriorityId()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro Removido com Sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS_PRIORITIES&ACTION=CALLS_PRIORITIES_DATAGRID'

            ];

        }
        else
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel remover o registro', 0);

        }

    }

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

}
catch (Exception $exception)
{

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