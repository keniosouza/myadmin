<?php

/** Importação de classes  */
use vendor\model\Products;
use vendor\controller\products\ProductsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){          

        /** Instânciamento de classes  */
        $Products = new Products();
        $ProductsValidate = new ProductsValidate();

        /** Parametros de entrada  */
        $description    = isset($_POST['description'])      ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $reference      = isset($_POST['reference'])        ? (string)filter_input(INPUT_POST,'reference', FILTER_SANITIZE_SPECIAL_CHARS)     : '';
        $version        = isset($_POST['version'])          ? (int)filter_input(INPUT_POST,'version', FILTER_SANITIZE_SPECIAL_CHARS)          : 0;
        $versionRelease = isset($_POST['version_release'])  ? (int)filter_input(INPUT_POST,'version_release', FILTER_SANITIZE_SPECIAL_CHARS)  : 0;
        $productsId     = isset($_POST['products_id'])      ? (int)filter_input(INPUT_POST,'products_id', FILTER_SANITIZE_SPECIAL_CHARS)      : 0;
        $productsTypeId = isset($_POST['products_type_id']) ? (int)filter_input(INPUT_POST,'products_type_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;    


        /** Validando os campos de entrada */
        $ProductsValidate->setDescription($description);
        $ProductsValidate->setProductsTypeId($productsTypeId);
        $ProductsValidate->setReference($reference);
        $ProductsValidate->setVersion($version);
        $ProductsValidate->setVersionRelease($versionRelease);
        $ProductsValidate->setProductsId($productsId);    

        /** Verifico a existência de erros */
        if (!empty($ProductsValidate->getErrors())) {

            /** Preparo o formulario para retorno **/
            $result = [

                'cod' => 0,
                'title' => 'Atenção',
                'message' => '<div class="alert alert-danger" role="alert">'.$ProductsValidate->getErrors().'</div>',

            ];

        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Products->Save($ProductsValidate->getProductsId(), $ProductsValidate->getProdutctsTypeId(), $ProductsValidate->getDescription(), $ProductsValidate->getReference(), $ProductsValidate->getVersion(), $ProductsValidate->getVersionRelease())){

                /** Prepara a mensagem de retorno - sucesso */
                $message = '<div class="alert alert-success" role="alert">'.($ProductsValidate->getProductsId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso').'</div>';

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => $message,
                    'redirect' => '',

                ];

            } else {/** Caso ocorra algum erro, informo */

                throw new InvalidArgumentException(($productsId > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível cadastrar o novo produto'), 0);	

            }

        }

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