<?php

/** Importação de classes  */
use vendor\model\ClientBudgets;
use vendor\model\FinancialEntries;
use vendor\model\FinancialMovements;
use vendor\controller\client_budgets\ClientBudgetsValidate;
use vendor\controller\financial_entries\FinancialEntriesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $ClientBudgets = new ClientBudgets();
        $FinancialEntries = new FinancialEntries();
        $FinancialMovements = new FinancialMovements();
        $ClientBudgetsValidate = new ClientBudgetsValidate();
        $FinancialEntriesValidate = new FinancialEntriesValidate();

        /** Parametros de entrada  */
        $clientBudgetsId = isset($_POST['client_budgets_id']) ? (int)filter_input(INPUT_POST,'client_budgets_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Controles */
        $movementDateScheduled = null;
        $next = null;
        $startDate = null;
        $description = null;

        /** Valida os parametros de entrada */
        $ClientBudgetsValidate->setClientsBudgetsId($clientBudgetsId);

        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados do orçamento ou 
         * efetua o cadastro de um novo*/
        /** Verifico a existência de erros */
        if (!empty($ClientBudgetsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsValidate->getErrors(), 0);        

        } else {        

            /** Consulta o orçamento */
            $ClientBudgetsResult = $ClientBudgets->Get($ClientBudgetsValidate->getClientsBudgetsId());

            /** Validando os campos de entrada */
            $FinancialEntriesValidate->setDescription($ClientBudgetsResult->description);
            $FinancialEntriesValidate->setFinancialCategoriesId($ClientBudgetsResult->financial_categories_id);
            $FinancialEntriesValidate->setClientsId($ClientBudgetsResult->clients_id);    
            $FinancialEntriesValidate->setFinancialAccountsId($ClientBudgetsResult->financial_accounts_id);
            $FinancialEntriesValidate->setActive('S'); 
            $FinancialEntriesValidate->setFixed(2);
            $FinancialEntriesValidate->setDuration($ClientBudgetsResult->often);  
            $FinancialEntriesValidate->setEntrieValue(number_format($ClientBudgetsResult->readjustment_budget, 2, ',', '.'));
            $FinancialEntriesValidate->setStartDate(date('d/m/Y', strtotime($ClientBudgetsResult->date_start)));  
            
            /** Verifica se não existem erros a serem informados */
            if (!empty($FinancialEntriesValidate->getErrors())) {

                /** Informo */
                throw new InvalidArgumentException($FinancialEntriesValidate->getErrors(), 0);        

            } else { 
                
                /** Consulta se o orçamento informado já possui entrada e suas respectivas movimentações */
                $FinancialEntriesResult = $FinancialEntries->GetBudgets($ClientBudgetsResult->client_budgets_id);

                /** Se o orçamento possuir uma entrada, atualiza a mesma a partir do orçamento */
                if((int)$FinancialEntriesResult->financial_entries_id > 0){
                    
                    /** Atualiza o registro junto ao banco de dados */
                    if($FinancialEntries->Save((int)$FinancialEntriesResult->financial_entries_id, $FinancialEntriesValidate->getClientsId(), $ClientBudgetsResult->client_budgets_id, $FinancialEntriesValidate->getDescription(), $FinancialEntriesValidate->getFixed(), $FinancialEntriesValidate->getDuration(), $FinancialEntriesValidate->getStartDate(), $FinancialEntriesValidate->getEntrieValue(), $FinancialEntriesValidate->getEnddate(), $FinancialEntriesValidate->getFinancialAccountsId(), $FinancialEntriesValidate->getActive(),$FinancialEntriesValidate->getFinancialCategoriesId(), NULL)){

                        /** Localiza as movimentações do orçamento */
                        $FinancialMovementsResult = $FinancialMovements->GetBudgets($ClientBudgetsResult->client_budgets_id);

                        /** Lista as movimentações de um orçamento */
                        $i=1;
                        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){     
                            
                            /** Data do vencimento da movimentação */
                            $movementDateScheduled = ( $i == 1 ? $FinancialEntriesValidate->getStartDate() : date("Y-m-d", mktime(0,0,0, (date('m', strtotime($FinancialEntriesValidate->getStartDate()))+$i), date('d', strtotime($FinancialEntriesValidate->getStartDate())), date('Y', strtotime($FinancialEntriesValidate->getStartDate())))) );	
                            
                            /** Descrição da movimentação */
                            $next = $FinancialEntriesValidate->getDescription() . ' - '.($i).'/'.$FinancialEntriesValidate->getDuration();  
                            
                            /** Gera a referência da movimentação para gravar no boleto */
                            $reference = $Result->financial_movements_id.$Result->reference.'/'.$Result->reference_client.'-';

                            /** Atualiza a movimentação */
                            $FinancialMovements->SaveMovementBudgets($Result->financial_movements_id, 
                                                                     $Result->financial_entries_id, 
                                                                     $movementDateScheduled, 
                                                                     $FinancialEntriesValidate->getEntrieValue(), 
                                                                     $next,
                                                                     $reference.$Main->setzeros($i, 2));

                                                                     $i++;//Contabiliza a posição da parcela                                                                     
                        }                        
                        
                        /** Informa o resultado positivo **/
                        $result = [

                            'cod' => 200,
                            'title' => 'Atenção',
                            'message' => '<div class="alert alert-success" role="alert">Entrada atualizada com sucesso!</div>',
                        ];

                        /** Envio **/
                        echo json_encode($result);

                        /** Paro o procedimento **/
                        exit;                         

                    }else{

                        /** Informo */
                        throw new InvalidArgumentException('Não foi possível atualizar a entrada do orçamento', 0);	                        
                    }


                }else{

                
                    /** Salva o registro junto ao banco de dados */
                    if($FinancialEntries->Save($FinancialEntriesValidate->getFinancialEntriesId(), $FinancialEntriesValidate->getClientsId(), $ClientBudgetsResult->client_budgets_id, $FinancialEntriesValidate->getDescription(), $FinancialEntriesValidate->getFixed(), $FinancialEntriesValidate->getDuration(), $FinancialEntriesValidate->getStartDate(), $FinancialEntriesValidate->getEntrieValue(), $FinancialEntriesValidate->getEnddate(), $FinancialEntriesValidate->getFinancialAccountsId(), $FinancialEntriesValidate->getActive(),$FinancialEntriesValidate->getFinancialCategoriesId(), NULL )){

                        /** Informa o resultado positivo **/
                        $result = [

                            'cod' => 200,
                            'title' => 'Atenção',
                            'message' => '<div class="alert alert-success" role="alert">Entrada cadastrada com sucesso!</div>',
                        ];

                        /** Envio **/
                        echo json_encode($result);

                        /** Paro o procedimento **/
                        exit; 

                    }else{

                        /** Informo */
                        throw new InvalidArgumentException('Não foi possível cadastrar a nova entrada', 0);	           
                    }
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