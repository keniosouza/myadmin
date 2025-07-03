<?php

/** Importação de classes */
use vendor\model\Drafts;
use vendor\controller\drafts\DraftsValidate;

/** Instânciamento de classes */
$Drafts = new Drafts();
$DraftsValidate = new DraftsValidate();

try
{

    /** Parâmetros de entrada */
    $DraftsValidate->setDraftId(@(int)filter_input(INPUT_POST, 'draft_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $DraftsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $DraftsValidate->setName(@(string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $DraftsValidate->setText(base64_encode(@(string)$_POST['text']));

    /** Controle de mensagens */
    $message = Array();

    /** Verifico a existência de erros */
    if (!empty($DraftsValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($DraftsValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($Drafts->Save($DraftsValidate->getDraftId(), $DraftsValidate->getCompanyId(), $DraftsValidate->getName(), $DraftsValidate->getText()))
        {

            /** Adição de elementos na array */
            array_push($message, array('sucesso', 'Usuário cadastrado com sucesso'));

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => $message,
                'redirect' => 'FOLDER=VIEW&TABLE=drafts&ACTION=DRAFTS_DATAGRID'

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