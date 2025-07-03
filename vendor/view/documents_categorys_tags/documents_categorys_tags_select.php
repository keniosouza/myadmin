<?php

/** Importação de classes  */
use vendor\model\DocumentsCategorysTags;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){          

        /** Instânciamento de classes  */
        $DocumentsCategorysTags = new DocumentsCategorysTags();

        /** Parametros de entrada  */
        $documentsCategoryId   = isset($_POST['documents_category_id']) ? $Main->antiInjection( filter_input(INPUT_POST,'documents_category_id', FILTER_SANITIZE_SPECIAL_CHARS) ) : '';

        /** Controles  */
        $err = 0;
        $msg = "";
        $list = "";

        /** Verifico se a categoria ID foi informada */
        if($documentsCategoryId > 0){
            
            ?>
            
            <select class="form-control mb-2 mr-sm-2" name="documents_categorys_tags_id" id="documents_categorys_tags_id">

            <option value="">Selecione</option>

            <?php


                /** Efetua a consulta das tags da categoria */
                $DocumentsCategorysTagsResult = $DocumentsCategorysTags->loadTags($documentsCategoryId);
                foreach($DocumentsCategorysTagsResult as $DocumentsCategorysKey => $Result){  

                ?>                       

                <option value="<?php echo $Result->documents_categorys_tags_id;?>*<?php echo $Result->format;?>*<?php echo $Result->label;?>*<?php echo $Result->tag;?>"><?php echo $Result->label;?></option>

            <?php } ?>        

            </select>

            <script type="text/javascript">

            /** Habilita o campo de consulta a ser enviada */
            $('#documents_categorys_tags_id').change(function(){


                /** Verifica se algum valor foi selecionado */
                if($('#documents_categorys_tags_id').val()){

                    /** Carrega os valores da tag */
                    let tagVal = $('#documents_categorys_tags_id').val().split('*');

                    /** Define a mascara a ser utilizada */
                    switch(parseInt(tagVal[1])){

                        case 1 : 
                            mask = ''; 
                            placeholder = '';                                                                            
                        break
                        case 2 : 
                            mask = 'number'; 
                            placeholder = 'Somente números';                                                                            
                        break
                        case 3 : 
                            mask = 'date'; 
                            placeholder = '__/__/____';          
                        break
                        case 4 : 
                            mask = 'price'; 
                            placeholder = '0,00';          
                        break
                        case 5 : 
                            mask = 'cpf';
                            placeholder = '999.999.999-99';              
                        break
                        case 6 : 
                            mask = 'cnpj';  
                            placeholder = '99.999.999/9999-99';         
                        break
                        case 7 : 
                            mask = 'cep';  
                            placeholder = '99999-999';          
                        break
                        case 8 : 
                            mask = 'phone_with_ddd'; 
                            placeholder = '(99) 9999-9999)';
                        break
                        case 9 : 
                            mask = 'cel_with_ddd';
                            placeholder = '(99) 9 9999-9999)';   
                        break

                    }          

                    /** Carrega o campo para efetuar a consulta */
                    let inputText  = '  <input type="text" class="form-control form-control '+mask+'" id="tag" name="tag" value="" placeholder="'+placeholder+'" maxlength="160" >';
                        

                    /** Carrega o campo a ser informado a consulta */
                    $('#loadSearch').html(inputText);

                    /** Aplica o foco no campo que acabou de carregar */
                    $('#tag').focus();

                    /** Aplico a mascara no campo */
                    loadMask(); 
                    
                    /**Habilito o botão de confirmar **/ 
                    $('#btnModalPage').show(); 

                    
                    /** Informa a label da tag a ser consulta */
                    $('#loadSearch').append('<input type="hidden" name="label" value="'+tagVal[3]+'" />');
                    
                    /** Coloco a mensagem na hora de efetuar a consulta a partir do click do botão */
                    $('#btnModalPage').click(function(){

                        $('#loadSearchInfo').html('<center><b>Aguarde, enviando consulta...</b></center>');
                    })

                    
                }else{

                    /** Limpa o campo da consulta */
                    $('#loadSearch').html('');
                }


            });                

            </script>

            <?php

            /** Pego a estrutura do arquivo */
            $div = ob_get_contents();

            /** Removo o arquivo incluido */
            ob_clean();

            /** Result **/
            $result = array(

                'cod' => 200,
                'data' => $div

            );


            sleep(1);

            /** Envio **/
            echo json_encode($result);

            /** Paro o procedimento **/
            exit;   

        }else{


            /** Informo */
            throw new InvalidArgumentException("<ol><li>Nenhuma categoria de documento informada para esta solicitação</li></ol>", 0);        
        }

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