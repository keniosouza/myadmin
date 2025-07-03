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
    $CallsActivitiesValidate->setCallActivityId(@(int)filter_input(INPUT_POST, 'call_activity_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsActivitiesValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsActivitiesValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $CallsActivitiesValidate->setName(@(string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsActivitiesValidate->setDescription(base64_encode(@(string)$_POST['description']));
    $CallsActivitiesValidate->setDateExpected(@(string)filter_input(INPUT_POST, 'date_expected', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($CallsActivitiesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsActivitiesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsActivities->Save($CallsActivitiesValidate->getCallActivityId(), $CallsActivitiesValidate->getCallId(), $CallsActivitiesValidate->getCompanyId(), $CallsActivitiesValidate->getName(), $CallsActivitiesValidate->getDescription(), $CallsActivitiesValidate->getDateExpected(), $CallsActivitiesValidate->getHistory()))
        {

            /** Adição de elementos na array */
            array_push($message, array('sucesso', 'Usuário cadastrado com sucesso'));

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => $message,
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsActivitiesValidate->getCallId(),

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