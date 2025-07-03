<?php

/** Importação de classes  */
use vendor\model\Documents;
use vendor\controller\documents\DocumentsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){           

        /** Instânciamento de classes  */
        $Documents = new Documents();
        $DocumentsValidate = new DocumentsValidate();

        /** Parametros de entrada  */
        $documentsCategorysId = isset($_POST['documents_categorys_id']) ? (int)filter_input(INPUT_POST, 'documents_categorys_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $documentsId          = isset($_POST['documents_id'])           ? (int)filter_input(INPUT_POST, 'documents_id', FILTER_SANITIZE_SPECIAL_CHARS)           : 0;
        $clientsId            = isset($_POST['clients_id'])             ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_SPECIAL_CHARS)             : 0;
        $description          = isset($_POST['description'])            ? (string)filter_input(INPUT_POST, 'description',  FILTER_SANITIZE_SPECIAL_CHARS)        : '';
        $path                 = isset($_POST['path'])                   ? (string)filter_input(INPUT_POST, 'path',FILTER_SANITIZE_SPECIAL_CHARS )                : '';
        $tag                  = isset($_POST['tag'])                    ? $DocumentsValidate->setSanitizeArray($_POST['tag'])                                    : [];
        $mask                 = isset($_POST['mask'])                   ? $DocumentsValidate->setSanitizeArray($_POST['mask'])                                   : [];
        $required             = isset($_POST['required'])               ? $DocumentsValidate->setSanitizeArray($_POST['required'])                               : [];
        $format               = isset($_POST['format'])                 ? $DocumentsValidate->setSanitizeArray($_POST['format'])                                 : [];

        /** Validando os campos de entrada */    
        $DocumentsValidate->setDocumentsCategorysId($documentsCategorysId); 
        $DocumentsValidate->setClientsId($clientsId);   
        $DocumentsValidate->setDescription($description);
        $DocumentsValidate->setRequired($required);
        $DocumentsValidate->setMask($mask);
        $DocumentsValidate->setFormat($format);
        $DocumentsValidate->setTag($tag);  
        $DocumentsValidate->setDocumentsId($documentsId);              

        /** Controles  */
        $markings = new stdClass();


        /** Verifica se não existem erros a serem informados */
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        } else { 


            /** Caso não existam erros a serem informados, grava o arquivo em sua respectiva pasta */
            $DocumentsValidate->setPath($path);

            /** Verifica se não existem erros a serem informados, 
             * caso não haja erro(s) salvo os dados ou 
             * efetua o cadastro de um novo*/
            if (!empty($DocumentsValidate->getErrors())) {   
                
                /** Informo */
                throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);             
                
            }else{           

                                    
                /** Salva as informações do arquivo */
                if( !$Documents->Save($DocumentsValidate->getDocumentsId(), $DocumentsValidate->getDocumentsCategorysId(), $DocumentsValidate->getDescription(), $DocumentsValidate->getArchive(), $DocumentsValidate->getExtension(), $DocumentsValidate->getMarkings(), 0, $DocumentsValidate->getClientsId()) ){
                    
                    /** Informo */
                    throw new InvalidArgumentException('Não foi possível gravar o arquivo '.$description, 0);                 

                }else{/** Caso seja bem sucedido, informo */

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 210,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert"> Arquivo '.($DocumentsValidate->getDocumentsId() > 0 ? 'atualizado' : 'cadastrado').' com sucesso!</div>'

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;  
                }
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
        'message' => '<div class="alert alert-danger" role="alert">' . $exception->getMessage() . '</div>',
        'title' => 'Atenção  ',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}