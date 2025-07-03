<?php

/** Importação de classes  */
use vendor\model\DocumentsCategorysTags;
use vendor\controller\documents_categorys_tags\DocumentsCategorysTagsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $DocumentsCategorysTags = new DocumentsCategorysTags();
        $DocumentsCategorysTagsValidate = new DocumentsCategorysTagsValidate();

        /** Parametros de entrada  */
        $documentsCategorysTagsId = isset($_POST['documents_categorys_tags_id']) ? (int)filter_input(INPUT_POST,'documents_categorys_tags_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $documentsCategorysId     = isset($_POST['documents_categorys_id'])      ? (int)filter_input(INPUT_POST,'documents_categorys_id', FILTER_SANITIZE_SPECIAL_CHARS)      : 0;
        $description              = isset($_POST['description'])                 ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)              : '';
        $label                    = isset($_POST['label'])                       ? (string)filter_input(INPUT_POST,'label', FILTER_SANITIZE_SPECIAL_CHARS)                    : '';
        $size                     = isset($_POST['size'])                        ? (int)filter_input(INPUT_POST,'size', FILTER_SANITIZE_SPECIAL_CHARS)                        : 0;
        $format                   = isset($_POST['format'])                      ? (int)filter_input(INPUT_POST,'format', FILTER_SANITIZE_SPECIAL_CHARS)                      : 0;
        $obrigatory               = isset($_POST['obrigatory'])                  ? (string)filter_input(INPUT_POST,'obrigatory', FILTER_SANITIZE_SPECIAL_CHARS)               : '';
        
        /** Validando os campos de entrada */
        $DocumentsCategorysTagsValidate->setDocumentsCategorysTagsId($documentsCategorysTagsId);
        $DocumentsCategorysTagsValidate->setDocumentsCategorysId($documentsCategorysId);
        $DocumentsCategorysTagsValidate->setDescription($description);
        $DocumentsCategorysTagsValidate->setLabel($label);
        $DocumentsCategorysTagsValidate->setSize($size);
        $DocumentsCategorysTagsValidate->setFormat($format);
        $DocumentsCategorysTagsValidate->setObrigatory($obrigatory);
        $DocumentsCategorysTagsValidate->setTag($label);

    
        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados ou 
         * efetua o cadastro de um novo*/
        if (!empty($DocumentsCategorysTagsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsCategorysTagsValidate->getErrors(), 0);        

        } else {    


            /** Salva as alterações ou cadastra um novo usuário */
            if($DocumentsCategorysTags->Save($DocumentsCategorysTagsValidate->getDocumentsCategorysTagsId(), $DocumentsCategorysTagsValidate->getDocumentsCategorysId(), $DocumentsCategorysTagsValidate->getDescription(), $DocumentsCategorysTagsValidate->getLabel(), $DocumentsCategorysTagsValidate->getSize(), $DocumentsCategorysTagsValidate->getFormat(), $DocumentsCategorysTagsValidate->getObrigatory(), $DocumentsCategorysTagsValidate->getTag())){           

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($documentsCategorysTagsId > 0 ? 'Marcação de documento atualizada com sucesso!' : 'Marcação de documento cadastrado com sucesso!') . '</div>',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;            

            }else{//Caso ocorra algum erro, informo

                throw new InvalidArgumentException(($documentsCategorysTagsId > 0 ? 'Não foi possível atualizar o cadastro da marcação' : 'Não foi possível cadastrar a nova marcação'), 0);	
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
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate
		

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}