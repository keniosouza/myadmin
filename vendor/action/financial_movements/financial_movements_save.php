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
        $movementDatePaid     = isset($_POST['movement_date_paid'])     ? (string)filter_input(INPUT_POST,'movement_date_paid', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
        $note                 = isset($_POST['note'])                   ? (string)filter_input(INPUT_POST,'note', FILTER_SANITIZE_SPECIAL_CHARS)                : '';
        $movementValuePaid    = isset($_POST['movement_value_paid'])    ? (string)filter_input(INPUT_POST,'movement_value_paid', FILTER_SANITIZE_SPECIAL_CHARS) : '0';
        $movementValueFees    = isset($_POST['movement_value_fees'])    ? (string)filter_input(INPUT_POST,'movement_value_fees', FILTER_SANITIZE_SPECIAL_CHARS) : '0';
        $financialMovementsId = isset($_POST['financial_movements_id']) ? (int)filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $financialOutputsId   = isset($_POST['financial_outputs_id'])   ? (int)filter_input(INPUT_POST,'financial_outputs_id', FILTER_SANITIZE_SPECIAL_CHARS)   : 0;
        $financialEntriesId   = isset($_POST['financial_entries_id'])   ? (int)filter_input(INPUT_POST,'financial_entries_id', FILTER_SANITIZE_SPECIAL_CHARS)   : 0;
        $updateValue          = isset($_POST['updateValue'])            ? (string)filter_input(INPUT_POST,'updateValue', FILTER_SANITIZE_SPECIAL_CHARS)         : 'N';

        /** Verifica se é somente atualização de valor */
        if($updateValue == 'S'){

            /** Validando os campos de entrada */
            $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);
            $FinancialMovementsValidate->setMovementValuePaid($movementValuePaid);

        }else{

            /** Validando os campos de entrada */
            $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);
            $FinancialMovementsValidate->setFinancialTypeId($financialOutputsId, $financialEntriesId);
            $FinancialMovementsValidate->setMovementDatePaid($movementDatePaid);
            $FinancialMovementsValidate->setNote($note);
            $FinancialMovementsValidate->setMovementValuePaid($movementValuePaid);
            $FinancialMovementsValidate->setMovementValueFees($movementValueFees); 
        }          

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {

            /** Verifica se é somente atualização de valor */
            if($updateValue == 'S'){  


                if($FinancialMovements->SaveMovementValue($FinancialMovementsValidate->getFinancialMovementsId(), $FinancialMovementsValidate->getMovementValuePaid())){


                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 200,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert">Valor da movimentação atualizado com sucesso!</div>',
                        'disabled' => '#btnModalPage'

                    ];

                    sleep(1);

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;                     


                }else{/** Caso ocorra um erro na hora de atualizar a saída */

                    /** Trata a mensagem de resposta */
                    $msg .= "Não foi possível atualizar o valor da movimentação selecionada";

                    /** Informo */
                    throw new InvalidArgumentException($msg, 0);                     

                }

                
            }else{/** Atualiza a baixa da movimentação */


                /** Atualiza a saída */
                if($FinancialMovements->SaveMovement($FinancialMovementsValidate->getFinancialMovementsId(), $FinancialMovementsValidate->getFinancialOutputsId(), $FinancialMovementsValidate->getFinancialEntriesId(), $FinancialMovementsValidate->getMovementDatePaid(), $FinancialMovementsValidate->getMovementValuePaid(), $FinancialMovementsValidate->getNote(), $FinancialMovementsValidate->getMovementValueFees() )){

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 200,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert">Movimentação atualizada com sucesso!</div>',
                        'disabled' => '#btnModalPage'

                    ];

                    sleep(1);

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;                     


                }else{/** Caso ocorra um erro na hora de atualizar a saída */

                    /** Trata a mensagem de resposta */
                    $msg .= "Não foi possível atualizar a movimentação selecionada";

                    /** Informo */
                    throw new InvalidArgumentException($msg, 0);                     

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
        'message' => '<div class="alert alert-warning" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    sleep(1);

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}