<?php

/** Importação de classes */
use vendor\model\Calls;
use vendor\controller\calls\CallsValidate;

/** Instânciamento de classes */
$Calls = new Calls();
$CallsValidate = new CallsValidate();

try
{

    /** Parâmetros de entrada */
    $CallsValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsValidate->setCallTypeId(@(int)filter_input(INPUT_POST, 'call_type_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsValidate->setCallLevelId(@(int)filter_input(INPUT_POST, 'call_level_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsValidate->setCallPriorityId(@(int)filter_input(INPUT_POST, 'call_priority_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $CallsValidate->setName(@(string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsValidate->setDescription(base64_encode(@(string)$_POST['description']));

    /** Verifico a existência de erros */
    if (!empty($CallsValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($Calls->Save($CallsValidate->getCallId(), $CallsValidate->getCallTypeId(), $CallsValidate->getCallLevelId(), $CallsValidate->getCallPriorityId(), $CallsValidate->getCompanyId(), $CallsValidate->getName(), $CallsValidate->getDescription()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Chamado registrado com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DATAGRID'

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