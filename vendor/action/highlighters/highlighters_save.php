<?php

/** Importação de classes */
use vendor\model\Highlighters;
use vendor\controller\highlighters\HighlightersValidate;

try
{

    /** Instânciamento de classes */
    $Highlighters = new Highlighters();
    $HighlightersValidate = new HighlightersValidate();

    /** Parâmetros de entrada */
    $HighlightersValidate->setHighlighterId(@(int)filter_input(INPUT_POST, 'highlighter_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $HighlightersValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);
    $HighlightersValidate->setName(@(string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Defino o histórico do registro */
    $highlighterJSON['table'] = filter_input(INPUT_POST, 'table', FILTER_SANITIZE_SPECIAL_CHARS);
    $highlighterJSON['column'] = filter_input(INPUT_POST, 'column', FILTER_SANITIZE_SPECIAL_CHARS);
    $highlighterJSON['primary_key'] = filter_input(INPUT_POST, 'primary_key', FILTER_SANITIZE_SPECIAL_CHARS);

    /** Converto para JSON */
    $HighlightersValidate->setText($highlighterJSON);

    /** Verifico a existência de erros */
    if (!empty($HighlightersValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($FinancialEntriesValidate->getErrors(), 0);

    }
    else
    {

        /** Busco o registro informado */
        $resultHighlighters = $Highlighters->Get($HighlightersValidate->getHighlighterId());

        /** Defino o histórico do registro */
        $historyData[0]['title'] = 'Cadastro';
        $historyData[0]['description'] = 'Texto Vinculado a Empresa';
        $historyData[0]['date'] = date('d-m-Y');
        $historyData[0]['time'] = date('H:i:s');
        $historyData[0]['class'] = 'badge-primary';

        /** Converto para JSON */
        $historyData = json_encode($historyData, JSON_PRETTY_PRINT);

        /** Verifico se o usuário foi localizado */
        if ($Highlighters->Save($HighlightersValidate->getHighlighterId(), $HighlightersValidate->getCompanyId(), $HighlightersValidate->getName(), json_encode($HighlightersValidate->getText(), JSON_PRETTY_PRINT), json_encode($HighlightersValidate->getHistory(), JSON_PRETTY_PRINT)))
        {

            /** Adição de elementos na array */
            array_push($message, array('sucesso', 'Produto cadastrado com sucesso'));

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => $message,
                'redirect' => 'FOLDER=VIEW&TABLE=highlighters&ACTION=HIGHLIGHTERS_DATAGRID'

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