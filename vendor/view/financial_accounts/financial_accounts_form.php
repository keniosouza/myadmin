<?php

/** Importação de classes  */
use vendor\model\FinancialAccounts;
use vendor\controller\financial_accounts\FinancialAccountsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $FinancialAccounts = new FinancialAccounts();
        $FinancialAccountsValidate = new FinancialAccountsValidate();

        /** Parametros de entrada  */
        $FinancialAccountsId = isset($_POST['financial_accounts_id']) ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $FinancialAccountsValidate->setFinancialAccountsId($FinancialAccountsId);


        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialAccountsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialAccountsValidate->getErrors(), 0);        

        } else {    
        
            /** Verifica se o ID da conta foi informado */
            if($FinancialAccountsId > 0){

                /** Consulta os dados da conta */
                $FinancialAccountsResult = $FinancialAccounts->Get($FinancialAccountsValidate->getFinancialAccountsId());

            }else{/** Caso o ID da conta não tenha sido informado, carrego os campos como null */

                /** Carrega os campos da tabela */
                $FinancialAccountsResult = $FinancialAccounts->Describe();

            }

        }

        ?>

        <div class="col-lg-12">


            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-6">
                            
                            <h5 class="card-title"><?php echo $FinancialAccountsId > 0 ? 'Editando dados da conta' : 'Cadastrar nova conta';?></h5>
                        
                        </div>

                        <div class="col-md-6 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_accounts&ACTION=financial_accounts_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova conta">

                                <i class="fas fa-plus-circle mr-1"></i>Nova

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=financial_accounts&ACTION=financial_accounts_datagrid', '#loadContent', true, '', '', '', 'Carregando contas cadastradas', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar contas cadastradas">

                                <i class="fas fa-plus-circle mr-1"></i>Contas Cadastradas

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmFinacialAccounts" autocomplete="off">
                        
                        <div class="form-group row">
                            
                            <div class="col-sm-8 mb-2">

                                <label for="description">Descrição:</label>
                                <input type="text" class="form-control form-control" maxlength="60" id="description" name="description" value="<?php echo $FinancialAccountsResult->description;?>" placeholder="Informe a descrição">
                            
                            </div>

                            <div class="col-sm-4">
                                
                                <label for="genre">Tipo:</label>

                                <select class="form-control form-control" id="accounts_type" name="accounts_type">
                                    <option value="" selected>Selecione</option>
                                    <option value="1" <?php echo (int)$FinancialAccountsResult->accounts_type  === 1 ? 'selected' : '';?>>Conta Corrente</option>
                                    <option value="2" <?php echo (int)$FinancialAccountsResult->accounts_type  === 2 ? 'selected' : '';?>>Poupança</option>
                                    <option value="3" <?php echo (int)$FinancialAccountsResult->accounts_type  === 3 ? 'selected' : '';?>>Carteira</option>
                                    <option value="4" <?php echo (int)$FinancialAccountsResult->accounts_type  === 4 ? 'selected' : '';?>>Cartão de crédito</option>
                                    <option value="5" <?php echo (int)$FinancialAccountsResult->accounts_type  === 5 ? 'selected' : '';?>>Cartão de débito</option>
                                    <option value="6" <?php echo (int)$FinancialAccountsResult->accounts_type  === 6 ? 'selected' : '';?>>Pix</option>
                                </select>

                            </div>  
                        
                        </div> 

                        <div class="form-group row">

                            <div class="col-sm-4 mb-2">

                                <label for="description">Detalhes:</label>
                                <textarea class="form-control form-control" id="details" name="details" placeholder="Informe os detalhes da conta" rows="6"><?php echo $FinancialAccountsResult->details;?></textarea>
                            
                            </div>

                            <div class="col-sm-4 mb-2">

                                <label for="description">Saldo Atual R$:</label>
                                <input type="text" class="form-control form-control <?php echo (int)$FinancialAccountsResult->financial_accounts_id == 0 ? 'price' : ''; ?> <?php echo $FinancialAccountsResult->current_balance > 0 ? '' : 'text-danger';?>" id="current_balance " name="current_balance " <?php echo (int)$FinancialAccountsResult->financial_accounts_id > 0 ? 'disabled' : '';?> value="<?php echo number_format($FinancialAccountsResult->current_balance, 2 , ',', '.') ;?>" placeholder="Informe o valor do saldo">

                            </div>

                            <div class="col-sm-4 mb-2">

                                <label for="status">Situação:</label>

                                <select class="form-control form-control" id="status" name="status">
                                    <option value="1" <?php echo (int)$FinancialAccountsResult->status  === 1 ? 'selected' : '';?>>Ativo</option>
                                    <option value="2" <?php echo (int)$FinancialAccountsResult->status  === 2 ? 'selected' : '';?>>Desativado</option>
                                    <option value="3" <?php echo (int)$FinancialAccountsResult->status  === 3 ? 'selected' : '';?>>Excluído</option>
                                </select>                            

                            </div>

                        </div>

                        <div class="form-group row">
                                
                            <label for="btn-save"></label>
                            <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmFinacialAccounts', '', true, '', 0, '', '<?php echo $FinancialAccountsId > 0 ? 'Atualizando conta' : 'Cadastrando nova conta';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$FinancialAccountsId > 0 ? 'Salvar alterações da conta' : 'Cadastrar nova conta') ?></a>                               
                        
                        </div> 
                        
                        <input type="hidden" name="TABLE" value="financial_accounts" />
                        <input type="hidden" name="ACTION" value="financial_accounts_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="financial_accounts_id" value="<?php echo $FinancialAccountsId;?>" />


                    </form>

                </div>

            </div>


        </div>

        <script type="text/javascript">

        /** Carrega as mascaras dos campos inputs */
        $(document).ready(function(e) {

            /** inputs mask */
            loadMask();

            /** tooltips */
            $('[data-toggle="tooltip"]').tooltip();        

        });

        </script>

<?php

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