<?php

/** Importação de classes */
use vendor\model\CallsDrafts;
use vendor\controller\calls_drafts\CallsDraftsValidate;

/** Instânciamento de classes */
$CallsDrafts = new CallsDrafts();
$CallsDraftsValidate = new CallsDraftsValidate();

try
{

    /** Parâmetros de entrada */
    $CallsDraftsValidate->setCallId(@(int)filter_input(INPUT_POST, 'call_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsDraftsValidate->setCallDraftId(@(int)filter_input(INPUT_POST, 'call_draft_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $CallsDraftsValidate->setText(base64_encode(@(string)$_POST['text']));

    /** Verifico a existência de erros */
    if (!empty($CallsDraftsValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsDraftsValidate->getErrors(), 0);

    }
    else
    {

        /** Busco o registro desejado */
        $resultCallsDrafts = $CallsDrafts->get($CallsDraftsValidate->getCallDraftId());

        /** Verifico se o registro foi localizado */
        if (@(int)$resultCallsDrafts->call_draft_id > 0) {

            /** Defino o histórico */
            $history[0]['title'] = 'Edicao';
            $history[0]['description'] = 'Texto alterado no dia';
            $history[0]['date'] = date('d-m-Y');
            $history[0]['time'] = date('H:i:s');
            $history[0]['class'] = 'badge-warning';
            $history[0]['user'] = $_SESSION['USERSNAMEFIRST'];

            /** Verifico se já existe históric */
            if (!empty($resultCallsDrafts->history)) {

                /** Pego o histórico existente */
                $resultHistory = json_decode($resultCallsDrafts->history, true);

                /** Unifico os históricos */
                $history = array_merge($resultHistory, $history);

            }

            /** Defino o histórico */
            $CallsDraftsValidate->setHistory($history);

            /** Verifico se o usuário foi localizado */
            if ($CallsDrafts->SaveText($CallsDraftsValidate->getCallDraftId(), $CallsDraftsValidate->getText(), json_encode($CallsDraftsValidate->getHistory(), JSON_PRETTY_PRINT)))
            {

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Sucesso',
                    'message' => 'Registro salvo com sucesso',
                    'redirect' => 'FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=' . $CallsDraftsValidate->getCallId()

                ];

            }
            else
            {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);

            }

        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi localizar o chamado', 0);

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