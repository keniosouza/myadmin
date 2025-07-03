<?php

/** Importação de classes */
use vendor\model\CallsMessages;
use vendor\controller\calls_messages\CallsMessagesValidate;

/** Instânciamento de classes */
$CallsMessages = new CallsMessages();
$CallsMessagesValidate = new CallsMessagesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsMessagesValidate->setCallMesageId(@(int)filter_input(INPUT_POST, 'call_message_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsMessagesValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsMessagesValidate->setUserId(@(string)$_SESSION['USERSID']);
    $CallsMessagesValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $CallsMessagesValidate->setText(@(string)$_POST['text']);
    $CallsMessagesValidate->setDate(date('Y.m.d H:i:s'));

    /** Defino o histórico do registro */
    $history[0]['title'] = 'Cadastro';
    $history[0]['description'] = 'Nova mensagem vinculada';
    $history[0]['date'] = date('d-m-Y');
    $history[0]['time'] = date('H:i:s');
    $history[0]['class'] = 'badge-primary';
    $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

    /** Definição do histórico */
    $CallsMessagesValidate->setHistory($history);

    /** Verifico a existência de erros */
    if (!empty($CallsMessagesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsMessagesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsMessages->Save($CallsMessagesValidate->getCallMesageId(), $CallsMessagesValidate->getCallId(), $CallsMessagesValidate->getUserId(), $CallsMessagesValidate->getCompanyId(), $CallsMessagesValidate->getText(), $CallsMessagesValidate->getDate(), json_encode($CallsMessagesValidate->getHistory(), JSON_PRETTY_PRINT)))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Mensagem enviada com sucesso',

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