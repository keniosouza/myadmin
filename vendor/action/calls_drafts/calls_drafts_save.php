<?php

/** Importação de classes */
use vendor\model\Drafts;
use vendor\model\CallsDrafts;
use vendor\controller\calls_drafts\CallsDraftsValidate;

/** Instânciamento de classes */
$Drafts = new Drafts();
$CallsDrafts = new CallsDrafts();
$CallsDraftsValidate = new CallsDraftsValidate();

try
{

    /** Lista de nomes dos clientes */
    $draft = null;

    /** Percorro todos os registros */
    foreach ($_POST['call_draft_id'] as $keyResult => $result)
    {

        /** Busco a minuta desejada */
        $resultDraft = $Drafts->get($result);

        /** Parâmetros de entrada */
        $CallsDraftsValidate->setCallDraftId(@(int)filter_input(INPUT_POST, 'call_draft_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsDraftsValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
        $CallsDraftsValidate->setDraftId($result);
        $CallsDraftsValidate->setText($resultDraft->text);
        $CallsDraftsValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

        /** Defino o histórico do registro */
        $history[0]['title'] = 'Cadastro';
        $history[0]['description'] = 'Novo cliente vinculado';
        $history[0]['date'] = date('d-m-Y');
        $history[0]['time'] = date('H:i:s');
        $history[0]['class'] = 'badge-primary';
        $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

        /** Definição do histórico */
        $CallsDraftsValidate->setHistory($history);

        /** Verifico a existência de erros */
        if (!empty($CallsDraftsValidate->getErrors()))
        {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException($CallsDraftsValidate->getErrors(), 0);

        }
        else
        {

            /** Verifico se o usuário foi localizado */
            if ($CallsDrafts->Save($CallsDraftsValidate->getCallDraftId(), $CallsDraftsValidate->getCallId(), $CallsDraftsValidate->getDraftId(), $CallsDraftsValidate->getCompanyId(), $CallsDraftsValidate->getText(), json_encode($CallsDraftsValidate->getHistory(), JSON_PRETTY_PRINT)))
            {

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Sucesso',
                    'message' => 'Texto vinculado com sucesso',
                    'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsDraftsValidate->getCallId()

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