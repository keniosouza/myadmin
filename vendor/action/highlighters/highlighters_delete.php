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
    $HighlightersValidate->setHighlighterId(@(int)filter_input(INPUT_POST, 'HIGHLIGHTER_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($HighlightersValidate->getErrors()))
    {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($HighlightersValidate->getErrors(), 0);

    }
    else
    {

        /** Verifico se o usuário foi localizado */
        if ($Highlighters->Delete($HighlightersValidate->getHighlighterId()))
        {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro removido com sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=highlighters&ACTION=HIGHLIGHTERS_DATAGRID'

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