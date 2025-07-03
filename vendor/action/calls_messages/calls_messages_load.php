<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\CallsMessages;
use vendor\controller\calls_messages\CallsMessagesValidate;

/** Instânciamento de classes */
$Main = new Main();
$CallsMessages = new CallsMessages();
$CallsMessagesValidate = new CallsMessagesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsMessagesValidate->setCallMesageId(@(int)filter_input(INPUT_POST, 'call_message_id', FILTER_SANITIZE_STRING));
    $CallsMessagesValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_STRING));
    $CallsMessagesValidate->setCompanyId(@(int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_STRING));

    /** Verifico a existência de erros */
    if (!empty($CallsMessagesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsMessagesValidate->getErrors(), 0);

    }
    else
    {

        /** Busco os registros */
        $resultCallsMessages = $CallsMessages->All($CallsMessagesValidate->getCallMesageId(), $CallsMessagesValidate->getCallId(), $CallsMessagesValidate->getCompanyId());

        /** Verifico se o usuário foi localizado */
        if (count($resultCallsMessages) > 0)
        {

            /** Trato os dados da mensagem */
            foreach ($resultCallsMessages as $key => $result)
            {

                /** Guardo a informação tratada */
                $resultCallsMessages[$key]->date = date('d/m/Y h:m:s', strtotime($result->date));
                $resultCallsMessages[$key]->name_first = $Main->decryptData($result->name_first);

            }

            /** Adição de elementos na array */
            array_push($message, array('sucesso', 'Usuário cadastrado com sucesso'));

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => $message,
                'data' => $resultCallsMessages,

            ];

        }
        else
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel localizar os registros', 0);

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