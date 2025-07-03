<?php

/** Importação de classes  */
use vendor\model\DocumentsCategorys;
use vendor\controller\documents_categorys\DocumentsCategorysValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){       

        /** Instânciamento de classes  */
        $DocumentsCategorys = new DocumentsCategorys();
        $DocumentsCategorysValidate = new DocumentsCategorysValidate();

        /** Parametros de entrada  */
        $documentsCategorysid = isset($_POST['documents_categorys_id']) ? (int)filter_input(INPUT_POST, 'documents_categorys_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $description          = isset($_POST['description'])            ? (string)Filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $documentsTypesId     = isset($_POST['documents_types_id'])     ? (int)Filter_input(INPUT_POST, 'documents_types_id', FILTER_SANITIZE_SPECIAL_CHARS)     : 0;

        /** Validando os campos de entrada */
        $DocumentsCategorysValidate->setDocumentsCategorysid($documentsCategorysid);
        $DocumentsCategorysValidate->setDescription($description);
        $DocumentsCategorysValidate->setDocumentType($documentsTypesId);

        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados informados ou 
         * efetua o cadastro de um novo*/
        if (!empty($DocumentsCategorysValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsCategorysValidate->getErrors(), 0);        

        } else {    


            /** Salva as alterações ou cadastra um novo usuário */
            if($DocumentsCategorys->Save($DocumentsCategorysValidate->getDocumentsCategorysid(), $DocumentsCategorysValidate->getDescription(), $DocumentsCategorysValidate->getDocumentType())){           

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($documentsCategorysid > 0 ? 'Categoria de arquivos atualizada com sucesso!' : 'Categoria de arquivos cadastradas com sucesso!') . '</div>',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;            

            }else{//Caso ocorra algum erro, informo

                throw new InvalidArgumentException(($documentsCategorysid > 0 ? 'Não foi possível atualizar categoria de arquivos' : 'Não foi possível cadastrar a categoria de arquivos'), 0);	
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
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}