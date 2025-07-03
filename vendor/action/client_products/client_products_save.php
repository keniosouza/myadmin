<?php

/** Importação de classes  */
use vendor\model\ClientProducts;
use vendor\controller\client_products\ClientProductsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $ClientProducts = new ClientProducts();
        $ClientProductsValidate = new ClientProductsValidate();

        /** Parametros de entrada  */
        $clientProductId = isset($_POST['client_product_id']) ? (int)filter_input(INPUT_POST, 'client_product_id', FILTER_SANITIZE_NUMBER_INT)   : 0;
        $clientsId       = isset($_POST['clients_id'])        ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)          : 0;
        $produtctId      = isset($_POST['produtct_id'])       ? (int)filter_input(INPUT_POST,'produtct_id', FILTER_SANITIZE_NUMBER_INT)          : 0;
        $readjustment    = isset($_POST['readjustment'])      ? (string)filter_input(INPUT_POST,'readjustment', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $maturity        = isset($_POST['maturity'])          ? (int)filter_input(INPUT_POST,'maturity', FILTER_SANITIZE_NUMBER_INT)             : 0;
        $productValue    = isset($_POST['product_value'])     ? (string)filter_input(INPUT_POST, 'product_value', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $description     = isset($_POST['description'])       ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $dateContract    = isset($_POST['date_contract'])     ? (string)filter_input(INPUT_POST, 'date_contract', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $ClientProductsValidate->setClientProductId($clientProductId); 
        $ClientProductsValidate->setClientsId($clientsId); 
        $ClientProductsValidate->setProductsId($produtctId); 
        $ClientProductsValidate->setReadjustment($readjustment); 
        $ClientProductsValidate->setMaturity($maturity); 
        $ClientProductsValidate->setProductValue($productValue); 
        $ClientProductsValidate->setDescription($description);
        $ClientProductsValidate->setDateContract($dateContract);

        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados do orçamento ou 
         * efetua o cadastro de um novo*/
        /** Verifico a existência de erros */
        if (!empty($ClientProductsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientProductsValidate->getErrors(), 0);        

        } else {

            /** Salva as alterações ou cadastra um novo registro */
            if($ClientProducts->Save($ClientProductsValidate->getClientProductId(), 
                                     $ClientProductsValidate->getClientsId(), 
                                     $ClientProductsValidate->getProductsId(), 
                                     $ClientProductsValidate->getDateContract(), 
                                     $ClientProductsValidate->getDescription(), 
                                     $ClientProductsValidate->getReadjustment(), 
                                     $ClientProductsValidate->getProductValue(), 
                                     $ClientProductsValidate->getMaturity())){   
                                

                $procedure = '<script type="text/javascript">';
                $procedure .= '$(document).ready(function(e) {';
                $procedure .= ' setTimeout(() => {';
                $procedure .= '    request(\'FOLDER=view&TABLE=client_products&ACTION=client_products_datagrid&clients_id='.$ClientProductsValidate->getClientsId().'\', \'\', true, \'\', \'\', \'#loadProducts\', \'Carregando produtos...\', \'blue\', \'circle\', \'sm\', true);';
                $procedure .= ' }, "2000");';
                $procedure .= '});';
                $procedure .= '</script>';

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($ClientProductsValidate->getClientProductId() > 0 ? 'Produto atualizado com sucesso!' : 'Produto cadastrado com sucesso!') .'</div>',
                    'procedure' => $procedure

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;            


            }else{//Caso ocorra algum erro, informo

                throw new InvalidArgumentException(($ClientProductsValidate->getClientProductId() > 0 ? 'Não foi possível atualizar o novo produto' : 'Não foi possível cadastrar o novo produto'), 0);	
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