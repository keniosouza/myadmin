<?php

/** Importação de classes */
use vendor\model\ModulesAcls;
use vendor\controller\modules_acls\ModulesAclsValidate;

/** Instânciamento de classes */
$ModulesAcls = new ModulesAcls();
$ModulesAclsValidate = new ModulesAclsValidate();

try {

    /** Defino as novas permissõess */
    $permission = array();

    /** Listo todas as permissões informadas */
    foreach ($_POST['permission'] as $key => $result) {

        /** Verifico se esta preenchido */
        if (!empty(trim($result))) {

            /** Tratamento da informação */
            $ModulesAclsValidate->setPermission($result);

            /** Guardo as permissões cadastradas dentro de uma array */
            array_push($permission, $ModulesAclsValidate->getPermission());

        }

    }

    /** Parâmetros de entrada */
    $ModulesAclsValidate->setModulesAclsId(@(int)filter_input(INPUT_POST, 'modules_acls_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $ModulesAclsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $ModulesAclsValidate->setModulesId(@(int)filter_input(INPUT_POST, 'modules_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $ModulesAclsValidate->setDescription(@(string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS));
    $ModulesAclsValidate->setPreferences($permission);

    /** Verifico a existência de erros */
    if (!empty($ModulesAclsValidate->getErrors())) {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($ModulesAclsValidate->getErrors(), 0);

    } else {

        /** Verifico se o usuário foi localizado */
        if ($ModulesAcls->Save($ModulesAclsValidate->getModulesAclsId(), $ModulesAclsValidate->getModulesId(), $ModulesAclsValidate->getCompanyId(), $ModulesAclsValidate->getDescription(), json_encode($ModulesAclsValidate->getPreferences(), JSON_PRETTY_PRINT))) {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro salvo com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=modules_acls&ACTION=modules_acls_datagrid'

            ];

        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);

        }

    }

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

} catch (Exception $exception) {

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