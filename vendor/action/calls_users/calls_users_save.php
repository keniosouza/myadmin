<?php

/** Importação de classes */
use vendor\model\CallsUsers;
use vendor\controller\calls_users\CallsUsersValidate;

/** Instânciamento de classes */
$CallsUsers = new CallsUsers();
$CallsUsersValidate = new CallsUsersValidate();

try
{

    /** Percorro todos os registros */
    foreach ($_POST['call_user_id'] as $keyResult => $result)
    {

        /** Parâmetros de entrada */
        $CallsUsersValidate->setCallUserId(@(int)filter_input(INPUT_POST, 'call_user_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsUsersValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsUsersValidate->setUserId($result);
        $CallsUsersValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

        /** Defino o histórico do registro */
        $history[0]['title'] = 'Cadastro';
        $history[0]['description'] = 'Novo usuario vinculado';
        $history[0]['date'] = date('d-m-Y');
        $history[0]['time'] = date('H:i:s');
        $history[0]['class'] = 'badge-primary';
        $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

        /** Definição do histórico */
        $CallsUsersValidate->setHistory($history);

        /** Verifico a existência de erros */
        if (!empty($CallsUsersValidate->getErrors()))
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException($CallsUsersValidate->getErrors(), 0);

        }
        else
        {

            /** Verifico se o usuário foi localizado */
            if ($CallsUsers->Save($CallsUsersValidate->getCallUserId(), $CallsUsersValidate->getCallId(), $CallsUsersValidate->getUserId(), $CallsUsersValidate->getCompanyId(), json_encode($CallsUsersValidate->getHistory(), JSON_PRETTY_PRINT)))
            {

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Sucesso',
                    'message' => 'Operador vinculado com sucesso',
                    'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsUsersValidate->getCallId()

                ];

            }
            else
            {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);

            }

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