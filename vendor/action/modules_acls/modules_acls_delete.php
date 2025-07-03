<?php

/** Importação de classes */
use vendor\model\ModulesAcls;
use vendor\controller\modules_acls\ModulesAclsValidate;

/** Instânciamento de classes */
$ModulesAcls = new ModulesAcls();
$ModulesAclsValidate = new ModulesAclsValidate();

try
{

    /** Parâmetros de entrada */
    $ModulesAclsValidate->setModulesAclsId(@(int)filter_input(INPUT_POST, 'modules_acls_id', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($ModulesAclsValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($ModulesAclsValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($ModulesAcls->delete($ModulesAclsValidate->getModulesAclsId()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro removido com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=modules_acls&ACTION=modules_acls_datagrid'

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