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
        $ClientProductsId = isset($_POST['client_product_id']) ? (int)filter_input(INPUT_POST,'client_product_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        $clientsId        = isset($_POST['clients_id'])        ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)       : 0;

        /** Validando os campos de entrada */
        $ClientProductsValidate->setClientProductId($ClientProductsId);
        $ClientProductsValidate->setClientsId($clientsId);
        
        /** Verifico a existência de erros */
        if (!empty($ClientProductsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientProductsValidate->getErrors(), 0);        

        } else {

            /** Salva as alterações ou cadastra um novo registro */
            if($ClientProducts->Delete($ClientProductsValidate->getClientProductId())){   
                                    

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
                    'message' => '<div class="alert alert-success" role="alert">Produto excluído com sucesso!</div>',
                    'procedure' => $procedure

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;  

           }else{

                throw new InvalidArgumentException('Não foi possível excluir o produto', 0);	
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