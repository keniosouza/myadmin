<?php

/** Importação de classes  */
use vendor\model\ClientBudgets;
use vendor\model\FinancialOutputs;
use vendor\controller\client_budgets\ClientBudgetsValidate;
use vendor\controller\financial_outputs\FinancialOutputsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $ClientBudgets = new ClientBudgets();
        $ClientBudgetsValidate = new ClientBudgetsValidate();

        /** Parametros de entrada  */
        $clientBudgetsCommissionsId = isset($_POST['client_budgets_commissions_id']) ? (string)filter_input(INPUT_POST,'client_budgets_commissions_id', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $ClientBudgetsValidate->setClientsBudgetsId($clientBudgetsId);
        $ClientBudgetsValidate->setClientsId($clientsId);
        
        /** Verifico a existência de erros */
        if (!empty($ClientBudgetsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsValidate->getErrors(), 0);        

        } else {

            /** Salva as alterações ou cadastra um novo registro */
            if($ClientBudgets->Delete($ClientBudgetsValidate->getClientsBudgetsId())){   
                                    

                $procedure = '<script type="text/javascript">';
                $procedure .= '$(document).ready(function(e) {';
                $procedure .= ' setTimeout(() => {';
                $procedure .= '    request(\'FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_datagrid&clients_id='.$ClientBudgetsValidate->getClientsId().'\', \'#loadBudgests\', true, \'Carregando orçamentos...\', \'\', \'\', \'Carregando orçamentos\', \'blue\', \'circle\', \'sm\', true);';
                $procedure .= ' }, "2000");';
                $procedure .= '});';
                $procedure .= '</script>';                

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">Orçamento excluído com sucesso!</div>',
                    'procedure' => $procedure

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;  

           }else{

                throw new InvalidArgumentException('Não foi possível excluir o orçamento', 0);	
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