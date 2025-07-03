<?php

/** Importação de classes  */
use vendor\model\FinancialAccounts;
use vendor\model\FinancialBalanceAdjustment;
use vendor\controller\financial_balance_adjustment\FinancialBalanceAdjustmentValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $FinancialAccounts = new FinancialAccounts();
        $FinancialBalanceAdjustment = new FinancialBalanceAdjustment();
        $FinancialBalanceAdjustmentValidate = new FinancialBalanceAdjustmentValidate();

        /** Parametros de entrada  */
        $financialAccountsId = isset($_POST['financial_accounts_id']) ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $FinancialBalanceAdjustmentValidate->setFinancialAccountsId($financialAccountsId);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialBalanceAdjustmentValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialBalanceAdjustmentValidate->getErrors(), 0);        

        } else {  

            /** Verifica se o ID da solicitação foi informado */
            if($FinancialBalanceAdjustmentValidate->getFinancialAccountsId() > 0){

                /** Consulta os dados */
                $FinancialAccountsResult = $FinancialAccounts->Get($FinancialBalanceAdjustmentValidate->getFinancialAccountsId());

            }else{/** Caso o ID da solicitação não tenha sido informado, carrego os campos como null */

                /** Carrega os campos da tabela */
                $FinancialAccountsResult = $FinancialAccounts->Describe();
            }
        }

        /** Controles  */
        $err = 0;
        $msg = "";

        /** Verifica se alguma conta foi informada para a solicitação */
        if((int)$FinancialAccountsResult->financial_accounts_id > 0){ ?> 
        
            <form class="user" id="frmFinancialBalanceAdjustment" autocomplete="off">
                
                <div class="form-group row">
                    
                    <div class="col-sm-6 mb-2">

                        <label for="current_balance ">Saldo atual R$:</label>
                        <input type="text" class="form-control form-control" id="current_balance " disabled value="<?php echo number_format($FinancialAccountsResult->current_balance, 2, ',', '.');?> " >
                    </div>

                    <div class="col-sm-6 mb-2">

                        <label for="adjusted_value">Valor do ajuste R$: <span class="text-danger"> * </span></label>
                        <input type="text" class="form-control form-control price" id="adjusted_value" name="adjusted_value" value="" placeholder="0,00">
                    </div>                
                
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-12 mb-2"> 

                        <label for="description">Descrição: <span class="text-danger"> * </span></label>
                        <textarea class="form-control form-control" id="description" name="description" placeholder="Exemplo: Ajuste mensal"></textarea>
                    </div>
                </div> 
                
                <div class="form-group row">
                    <div class="col-sm-12 mb-2 text-center" id="messageSend"> </div>
                </div>            
                
                <input type="hidden" name="TABLE" value="financial_balance_adjustment" />
                <input type="hidden" name="ACTION" value="financial_balance_adjustment_save" />
                <input type="hidden" name="FOLDER" value="action" />
                <input type="hidden" name="financial_accounts_id" value="<?php echo (int)$FinancialAccountsResult->financial_accounts_id;?>" />
                <input type="hidden" name="current_balance" value="<?php echo $FinancialAccountsResult->current_balance;?>" />

            </form>

            <script type="text/javascript">

            /** Carrega as mascaras dos campos inputs */
            $(document).ready(function(e) {

                /** inputs mask */
                loadMask();    

            });

            </script>        

    <?php 


            /** Pego a estrutura do arquivo */
            $div = ob_get_contents();

            /** Removo o arquivo incluido */
            ob_clean();

            /** Result **/
            $result = array(

                'cod' => 201,
                'data' => $div,
                'title' => 'Ajustar saldo - '.$FinancialAccountsResult->description,
                'func' => 'sendForm(\'#frmFinancialBalanceAdjustment\', \'\', true, \'Ajustando saldo...\', 0, \'#messageSend\', \'Enviando solicitação de ajuste de saldo...\', \'random\', \'circle\', \'sm\', true)'

            );


            sleep(1);

            /** Envio **/
            echo json_encode($result);

            /** Paro o procedimento **/
            exit;  

        }else{/** Caso nenhuma conta tenha sido informada */


            throw new InvalidArgumentException('Nenhuma conta informada para a solicitação informada.', 0);
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
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}