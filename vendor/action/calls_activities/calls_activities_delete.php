<?php

/** Importação de classes */
use vendor\model\CallsActivities;
use vendor\controller\calls_activities\CallsActivitiesValidate;

/** Instânciamento de classes */
$CallsActivities = new CallsActivities();
$CallsActivitiesValidate = new CallsActivitiesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsActivitiesValidate->setCallActivityId(@(int)filter_input(INPUT_POST, 'CALL_ACTIVITY_ID', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsActivitiesValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($CallsActivitiesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsActivitiesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsActivities->delete($CallsActivitiesValidate->getCallActivityId()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro removido com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsActivitiesValidate->getCallId()

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