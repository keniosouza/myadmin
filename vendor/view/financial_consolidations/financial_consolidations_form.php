<?php

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){  ?>

        <div class="form-group row" id="uploadDocuments">

            <div class="col-sm-12 mb-2">

                <label for="selectFiles">Arquivos: <span class="text-danger">* Tamanho máximo do arquivo 5mb</span></label>
                <input type="file" id="selectFiles" class="upload filestyle" accept="application/ret, application/RET, application/CED, application/ced" />
                <div id="preview"></div>
                <div id="results" class="row"></div>

            </div>

        </div>   

        <script type="text/javascript">

            /** Carrega as mascaras dos campos inputs */
            $(document).ready(function(e) {

                /** Upload de arquivos */
                uploadFiles('action', 'financial_consolidations', 'financial_consolidations_upload', null, null, null, null);                 

            });
                        
        </script>    

        <?php
        /** Pego a estrutura do arquivo */
        $div = ob_get_contents();

        /** Removo o arquivo incluido */
        ob_clean();

        /** Result **/
        $result = array(

            'cod' => 201,
            'data' => $div,
            'title' => 'Enviar arquivo de consolidação', 
            'func' => ''
                    
        );  

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
        exit;         
        
    /** Caso o token de acesso seja inválido, informo */
    }else{
		
		/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
		$authenticate = true;				

        /** Informo */
        throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
    }    

}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}