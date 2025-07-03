<?php

/** Importação de classes */
use vendor\controller\calls\CallsValidate;
use vendor\model\CallsClients;
use vendor\controller\calls_clients\CallsClientsValidate;

/** Instânciamento de classes */
$CallsValidate = new CallsValidate();
$CallsClients = new CallsClients();
$CallsClientsValidate = new CallsClientsValidate();

try
{

    /** Lista de nomes dos clientes */
    $clients = null;

    /** Percorro todos os registros */
    foreach ($_POST['call_client_id'] as $keyResult => $result)
    {

        /** Parâmetros de entrada */
        $CallsClientsValidate->setCallClientId(@(int)filter_input(INPUT_POST, 'call_client_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsClientsValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsClientsValidate->setClientId($result);
        $CallsClientsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

        /** Defino o histórico do registro */
        $history[0]['title'] = 'Cadastro';
        $history[0]['description'] = 'Novo cliente vinculado';
        $history[0]['date'] = date('d-m-Y');
        $history[0]['time'] = date('H:i:s');
        $history[0]['class'] = 'badge-primary';
        $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

        /** Definição do histórico */
        $CallsClientsValidate->setHistory($history);

        /** Verifico a existência de erros */
        if (!empty($CallsClientsValidate->getErrors()))
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException($CallsClientsValidate->getErrors(), 0);

        }
        else
        {

            /** Verifico se o usuário foi localizado */
            if ($CallsClients->Save($CallsClientsValidate->getCallClientId(), $CallsClientsValidate->getCallId(), $CallsClientsValidate->getClientId(), $CallsClientsValidate->getCompanyId(), json_encode($CallsClientsValidate->getHistory(), JSON_PRETTY_PRINT)))
            {

                /** Adição de elementos na array */
                array_push($message, array('sucesso', 'Usuário cadastrado com sucesso'));

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Sucesso',
                    'message' => $message,
                    'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsClientsValidate->getCallId()

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