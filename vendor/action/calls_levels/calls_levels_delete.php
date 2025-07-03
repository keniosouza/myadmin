<?php

/** Importação de classes */

use vendor\model\CallsLevels;
use vendor\controller\calls_levels\CallsLevelsValidate;

/** Instânciamento de classes */
$CallsLevels = new CallsLevels();
$CallsLevelsValidate = new CallsLevelsValidate();

try {

    /** Parâmetros de entrada */
    $CallsLevelsValidate->setCallLevelId(@(int)filter_input(INPUT_POST, 'CALL_LEVEL_ID', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($CallsLevelsValidate->getErrors())) {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException($CallsLevelsValidate->getErrors(), 0);

    } else {

        /** Verifico se o usuário foi localizado */
        if ($CallsLevels->delete($CallsLevelsValidate->getCallLevelId())) {

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Sucesso',
                'message' => 'Registro Removido com Sucesso',
                'redirect' => 'FOLDER=VIEW&TABLE=CALLS_LEVELS&ACTION=CALLS_LEVELS_DATAGRID'

            ];

        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel remover o registro', 0);

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