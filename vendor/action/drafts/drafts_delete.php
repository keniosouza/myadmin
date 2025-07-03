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
    $DraftsValidate->setDraftId(@(int)filter_input(INPUT_POST, 'DRAFT_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($DraftsValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($DraftsValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($Drafts->delete($DraftsValidate->getDraftId()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro removido com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=drafts&ACTION=DRAFTS_DATAGRID'

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