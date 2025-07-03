<?php

/** Importação de classes */
use vendor\model\CallsTypes;
use vendor\controller\calls_types\CallsTypesValidate;

/** Instânciamento de classes */
$CallsTypes = new CallsTypes();
$CallsTypesValidate = new CallsTypesValidate();

try
{

    /** Parâmetros de entrada */
    $CallsTypesValidate->setCallTypeId(@(int)filter_input(INPUT_POST, 'call_type_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsTypesValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $CallsTypesValidate->setDescription(@(string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsTypesValidate->setHistory(base64_encode('teste'));

    /** Verifico a existência de erros */
    if (!empty($CallsTypesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsTypesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsTypes->Save($CallsTypesValidate->getCallTypeId(), $CallsTypesValidate->getCompanyId(), $CallsTypesValidate->getDescription(), $CallsTypesValidate->getHistory()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Atenção',
                'message' => '<b>Registro salvo com Sucesso<b/>',
                'redirect' => ''

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