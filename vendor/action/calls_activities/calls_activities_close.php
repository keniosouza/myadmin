<?php

/** Importação de classes */
use vendor\model\CallsMessages;
use vendor\model\CallsActivities;
use vendor\controller\calls_activities\CallsActivitiesValidate;

/** Instânciamento de classes */
$CallsMessages = new CallsMessages();
$CallsActivities = new CallsActivities();
$CallsActivities = new CallsActivities();
$CallsActivitiesValidate = new CallsActivitiesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsActivitiesValidate->setCallActivityId(@(int)filter_input(INPUT_POST, 'CALL_ACTIVITY_ID', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsActivitiesValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsActivitiesValidate->setDateClose(date('Y.m.d h:m:s'));

    /** Verifico a existência de erros */
    if (!empty($CallsActivitiesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsActivitiesValidate->getErrors(), 0);

    }
    else
    {

        /** Busco o registro desejado */
        $resultCallsActivities = $CallsActivities->Get($CallsActivitiesValidate->getCallActivityId());

        /** Verifico se o usuário foi localizado */
        if ($CallsActivities->SaveClose($CallsActivitiesValidate->getCallActivityId(), $CallsActivitiesValidate->getCallId(), $CallsActivitiesValidate->getDateClose(), 'asdasd'))
        {

            /** Defino o histórico do registro de mensagem */
            $history[0]['title'] = 'Cadastro';
            $history[0]['description'] = 'Nova mensagem vinculada';
            $history[0]['date'] = date('d-m-Y');
            $history[0]['time'] = date('H:i:s');
            $history[0]['class'] = 'badge-primary';
            $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

            /** Defino o texto que será postado */
            $text = 'Encerrando atividade: ' . '#'. $resultCallsActivities->call_activity_id . ' - ' . $resultCallsActivities->name;

            /** Publico uma mensagem */
            $CallsMessages->Save(0, $CallsActivitiesValidate->getCallId(), $_SESSION['USERSID'], $_SESSION['USERSCOMPANYID'], $text, $CallsActivitiesValidate->getDateClose(), json_encode($history, JSON_PRETTY_PRINT));

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Operador vinculado com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS_ACTIVITIES_USERS&ACTION=CALLS_ACTIVITIES_USERS_DETAILS&CALL_ID=' . $CallsActivitiesValidate->getCallId() . '&CALL_ACTIVITY_ID=' . $CallsActivitiesValidate->getCallActivityId()

            ];

        }
        else
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);

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