<?php/** Importação de classes  */use vendor\model\Accounts;/** Instânciamento de classes  */$Accounts = new Accounts();/** Parametros de entrada  */

/** Controles  */$err = 0;$msg = "";
try{









}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}