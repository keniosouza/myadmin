<?php

try{


    /** Inicia a sessão do usuário */
    $Main->SessionStart();

    // destroi a sessao
    @session_destroy();

    // gera um novo id para a sessao
    @session_regenerate_id();
    

    /** Informa o resultado positivo **/
    $result = [

        'cod' => 99,
        'title' => '',
        'url' => '',/** Caso seja preciso redirecionar para uma url especifica */
        'message' => 'Sessão finalizada com sucesso',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;    

}catch(Exception $exception){

    /** Prepara a div com a informação de erro */
    $div  = '<div class="col-lg-12">';
    $div .= '   <div class="card shadow mb-12">';
    $div .= '       <div class="card-header py-3">';
    $div .= '           <h6 class="m-0 font-weight-bold text-primary">Erro(s) encontrados.</h6>';
    $div .= '       </div>';
    $div .= '       <div class="card-body">';
    $div .= '           <p>' . $exception->getFile().'<br/>'.$exception->getMessage().'</p>';
    $div .= '       </div>';
    $div .= '   </div>';
    $div .= '</div>';

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'data' => $div,
        'title' => 'Erro Interno',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}