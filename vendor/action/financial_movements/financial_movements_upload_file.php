<?php

/** Importação de classes  */
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){         

        /** Instânciamento de classes  */
        $FinancialMovementsValidate = new FinancialMovementsValidate();

        /** Parametros de entrada  */
        $financialMovementsId = isset($_POST['financial_movements_id']) ? $Main->antiInjection( filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_SPECIAL_CHARS) ) : '';
        $file                 = isset($_POST['file'])                   ? $Main->antiInjection( filter_input(INPUT_POST,'file', FILTER_SANITIZE_SPECIAL_CHARS) )                   : '';
        $name                 = isset($_POST['name'])                   ? $Main->antiInjection( filter_input(INPUT_POST,'name', FILTER_SANITIZE_SPECIAL_CHARS) )                   : '';

        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);
        $FinancialMovementsValidate->setName($name);
        $FinancialMovementsValidate->setFile($file);  


        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {

        
            /** Informa o resultado positivo **/
            $result = [

                'cod' => 200,
                'financial_movements_id' => $FinancialMovementsValidate->getFinancialMovementsId(),
                'nameFile' => $FinancialMovementsValidate->getName(),
                'path' => $FinancialMovementsValidate->getDirTemp().'/'.$FinancialMovementsValidate->getDirUser().'/'.$FinancialMovementsValidate->getName()

            ];

            /** Envio **/
            echo json_encode($result);       

            /** Paro o procedimento **/
            exit;  
        
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