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
    $CallsTypesValidate->setCallTypeId(@(int)filter_input(INPUT_POST, 'CALL_TYPE_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($CallsTypesValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsTypesValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($CallsTypes->delete($CallsTypesValidate->getCallTypeId()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Atenção',
                'message' => 'Registro Removido com Sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS_TYPES&ACTION=CALLS_TYPES_DATAGRID'

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