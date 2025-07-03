<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){          

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();

        /** Parametros de entrada  */
        $ourNumber = isset($_POST['ourNumber']) ? filter_input(INPUT_POST,'ourNumber', FILTER_SANITIZE_SPECIAL_CHARS)   : '';

        /** Valida os parametros de entrada */
        $FinancialMovementsValidate->setOurNumber($ourNumber);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {
            
            /** Separa os itens a serem inseridos, converto em array a string */
            $data = explode(',', $FinancialMovementsValidate->getOurNumber());

            /** Listo os itens  */
            for($i=0; $i < count($data); $i++){

                /** Separa os item em duas partes, Nosso Número e ID da movimentação */
                $dataItem = explode('*', $data[$i]);

                /** Consulta a movimentação para ver se a mesma já possui o retorno sicoob */
                /** Consulta os dados da movimentação */
                $FinancialMovementsResults = $FinancialMovements->Get($dataItem[0]);               
                
                /** Verifica se não existe o retorno do Sicoob */
                if(empty($FinancialMovementsResults->sicoob_response)){                

                    /** Prepara o Json a ser gravado */               
                    $result = new stdClass();
                    $result->resultado[] = new stdClass();
                    $result->resultado[0]->boleto = new stdClass();
                    $result->resultado[0]->boleto->nossoNumero = $dataItem[1];

                    /** Grava o Json com os dados do boleto */
                    $FinancialMovements->SaveOurNumber(json_encode($result, JSON_PRETTY_PRINT), (int)$FinancialMovementsResults->financial_movements_id);

                    unset($dataItem);
                }
            }

            /** Informa o resultado positivo **/
            $result = [

                'cod' => 200,
                'title' => 'Atenção',
                'message' => '<div class="alert alert-success" role="alert">Nosso número salvo nos itens selecionados</div>',

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