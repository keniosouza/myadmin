<?php
require_once('./vendor/autoload.php');

/** Carrego o arquivo de configuração */
$settings = (object)json_decode(file_get_contents('config/config.json'));

/** Importação de classes */
use vendor\model\Main;
use vendor\controller\routers\RouterValidate;

/** Instânciamento de classes */
$Main = new Main;
$RouterValidate = new RouterValidate;

/** Atualiza a sessão */
$Main->SessionStart();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

try {

    /** Parâmetros de Entrada */
    $RouterValidate->setTable(@(string)filter_input(INPUT_POST,  'TABLE',  FILTER_SANITIZE_SPECIAL_CHARS));
    $RouterValidate->setAction(@(string)filter_input(INPUT_POST, 'ACTION', FILTER_SANITIZE_SPECIAL_CHARS));
    $RouterValidate->setFolder(@(string)filter_input(INPUT_POST, 'FOLDER', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Constroles */
    $authenticate    = null;
    // $resultException = null;
    // $resultValidate  = null;
    // $resultRequest   = null;    

    /** Verifico a existência de erros */
    if (!empty($RouterValidate->getErrors())) {

        /** Mensagem de erro */
        throw new Exception($RouterValidate->getErrors());

    } else {


        /** Verifico se o arquivo de ação existe */
        if (is_file($RouterValidate->getFullPath())) {

            /** Inicio a coleta de dados */
            ob_start();

            /** Inclusão do arquivo desejado */
            @include_once $RouterValidate->getFullPath();

            /** Prego a estrutura do arquivo */
            $div = ob_get_contents();

            /** Removo o arquivo incluido */
            ob_clean();

            /** Result **/
            $result = array(

                'cod' => 200,
                'data' => $div,

            );

        } else {

            /** Mensagem de erro */
            throw new Exception('Erro :: Não há arquivo para ação informada.');

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
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
        'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

}