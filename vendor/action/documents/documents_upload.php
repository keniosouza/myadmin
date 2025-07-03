<?php
/** Importação de classes  */
use vendor\model\DocumentsCategorysTags;
use vendor\controller\documents\DocumentsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){       

        /** Instânciamento de classes  */
        $DocumentsCategorysTags = new DocumentsCategorysTags();
        $DocumentsValidate = new DocumentsValidate();

        /** Parametros de entrada  */
        $documentsCategorysId = isset($_POST['documents_categorys_id']) ? (int)filter_input(INPUT_POST, 'documents_categorys_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $clientsId            = isset($_POST['clients_id'])             ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_SPECIAL_CHARS)             : 0;
        $name                 = isset($_POST['name'])                   ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)                : '';
        $file                 = isset($_POST['file'])                   ? (string)filter_input(INPUT_POST, 'file',  FILTER_SANITIZE_SPECIAL_CHARS)               : '';

        /** Validando os campos de entrada */
        $DocumentsValidate->setDocumentsCategorysId($documentsCategorysId);
        $DocumentsValidate->setClientsId($clientsId);
        $DocumentsValidate->setName($name);
        $DocumentsValidate->setFile($file);


        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados ou 
         * efetua o cadastro de um novo*/
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        } else {    


            /** Verifica se o arquivo foi enviado */
            if( is_file($DocumentsValidate->getDirTemp().'/'.$DocumentsValidate->getDirUser().'/'.$DocumentsValidate->getName()) ){

                /** Localiza as marcações da categoria */
                $DocumentsCategorysTagsResult = $DocumentsCategorysTags->loadTags($DocumentsValidate->getDocumentsCategorysId());
                
                /** Array para armazenar as legendas das marcações */
                $label = [];

                /** Array para armazenar as marcações */
                $tag = [];  
                
                /** Array para armazenar os formatos */
                $format = []; 
                
                /** Array para armazenar os obrigatórios */
                $required = [];             

                /** Lista as legendas das marcações */
                foreach($DocumentsCategorysTagsResult as $DocumentsCategorysTagsKey => $Result){ 

                    /** Aramazena as legendas */
                    array_push($label, $Result->label);
                }

                /** Lista as marcações */
                foreach($DocumentsCategorysTagsResult as $DocumentsCategorysTagsKey => $Result){ 

                    /** Aramazena as marcações */
                    array_push($tag, $Result->tag);
                } 
                
                /** Lista os formatos */
                foreach($DocumentsCategorysTagsResult as $DocumentsCategorysTagsKey => $Result){ 

                    /** Aramazena as marcações */
                    array_push($format, $Result->format);
                }   
                
                /** Lista os requeridos */
                foreach($DocumentsCategorysTagsResult as $DocumentsCategorysTagsKey => $Result){ 

                    /** Aramazena as marcações */
                    array_push($required, $Result->obrigatory);
                } 

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'nameFile' => $DocumentsValidate->getName(),
                    'path' => $DocumentsValidate->getDirTemp().'/'.$DocumentsValidate->getDirUser().'/'.$DocumentsValidate->getName(),
                    'labels' => $label,
                    'tags' => $tag,
                    'formats' => $format,
                    'requireds' => $required,
                    'clients_id' => $DocumentsValidate->getClientsId()

                ];

                /** Envio **/
                echo json_encode($result);       

                /** Paro o procedimento **/
                exit;  
                
            }else{/** Caso o arquivo não tenha sido enviado */


                /** Informa o resultado negativo **/
                $result = [

                    'cod' => 0,
                    'nameFile' => $name

                ];

                /** Envio **/
                echo json_encode($result);       

                /** Paro o procedimento **/
                exit; 
            }

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
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}