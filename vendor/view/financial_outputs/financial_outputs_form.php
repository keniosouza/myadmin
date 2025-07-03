<?php

/** Importação de classes  */
use vendor\model\Clients;
use vendor\model\FinancialOutputs;
use vendor\model\FinancialAccounts;
use vendor\model\FinancialCategories;
use vendor\controller\financial_outputs\FinancialOutputsValidate;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes  */
    $Clients = new Clients();
    $FinancialOutputs = new FinancialOutputs();
    $FinancialAccounts = new FinancialAccounts();
    $FinancialCategories = new FinancialCategories();
    $FinancialOutputsValidate = new FinancialOutputsValidate();

    /** Parametros de saida  */
    $financialOutputsId = isset($_POST['financial_outputs_id']) ? filter_input(INPUT_POST,'financial_outputs_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

    /** Validando os campos de entrada */
    $FinancialOutputsValidate->setFinancialOutputsId($financialOutputsId);

    /** Verifica se não existem erros a serem informados */
    if (!empty($FinancialOutputsValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($FinancialOutputsValidate->getErrors(), 0);        

    } else {

        /** Verifica se o ID da conta foi informado */
        if($FinancialOutputsValidate->getFinancialOutputsId() > 0){

            /** Consulta os dados da conta */
            $FinancialOutputsResult = $FinancialOutputs->Get($FinancialOutputsValidate->getFinancialOutputsId());

        }else{/** Caso o ID da conta não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $FinancialOutputsResult = $FinancialOutputs->Describe();

        }

    }

    ?>

    <div class="col-lg-12">


        <div class="card shadow mb-12">
                
            <div class="card-header">

                <div class="row">
                    
                    <div class="col-md-6">
                        
                        <h5 class="card-title"><?php echo (int)$FinancialOutputsResult->financial_outputs_id > 0 ? 'Editando dados da saida' : 'Cadastrar nova saida';?></h5>
                    
                    </div>

                    <div class="col-md-6 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_outputs&ACTION=financial_outputs_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova saida">

                            <i class="fas fa-plus-circle mr-1"></i>Nova

                        </button>


                        <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=financial_outputs&ACTION=financial_outputs_datagrid', '#loadContent', true, '', '', '', 'Carregando saidas cadastradas', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar contas cadastradas">

                            <i class="fas fa-plus-circle mr-1"></i>Saidas Cadastradas

                        </button>                        

                    </div>
                
                </div>            

            </div>


            <div class="card-body">

                <form class="user" id="frmFinancialOutputs" autocomplete="off">

                    <div class="form-group row">

                        <div class="col-sm-6 mb-3">

                            <label for="current_balance">Descrição:</label>
                            <input type="text" class="form-control form-control" id="description" name="description" maxlength="160" value="<?php echo $FinancialOutputsResult->description;?> ">
                        </div> 

                        <div class="col-sm-6 mb-3">

                            <label for="financial_categories_id">Categoria:</label>
                            <select class="form-control form-control" id="financial_categories_id" name="financial_categories_id">
                                <option value="0" selected>Selecione</option>

                                <?php
                                    /** Consulta todos os clientes do usuario logado */
                                    $FinancialCategoriesResult = $FinancialCategories->ComboBox('S');
                                    
                                    /** Lista os clientes do usuario logado */
                                    foreach($FinancialCategoriesResult as $FinancialCategoriesKey => $ResultFinancialCategories){ 

                                ?>

                                <option value="<?php echo $ResultFinancialCategories->financial_categories_id;?>" <?php echo (int)$ResultFinancialCategories->financial_categories_id === (int)$FinancialOutputsResult->financial_categories_id ? 'selected' : '';?>><?php echo $ResultFinancialCategories->description;?></option>
                                
                                <?php } ?>

                            </select>

                        </div>
                        
                    </div>

                    <div class="form-group row">

                        <div class="col-sm-4 mb-3">

                            <label for="clients_id">Cliente:</label>
                            <select class="form-control form-control" id="clients_id" name="clients_id">
                                <option value="0" selected>Selecione</option>

                                <?php
                                    /** Consulta todos os clientes do usuario logado */
                                    $ClientsResult = $Clients->All(null, null, null, null);
                                    
                                    /** Lista os clientes do usuario logado */
                                    foreach($ClientsResult as $ClientsKey => $ResultClients){ 

                                ?>

                                <option value="<?php echo $ResultClients->clients_id;?>" <?php echo (int)$FinancialOutputsResult->clients_id === (int)$ResultClients->clients_id ? 'selected' : '';?>><?php echo $ResultClients->fantasy_name;?></option>
                                
                                <?php } ?>

                            </select>             
                        </div> 
                        
                        <div class="col-sm-4 mb-3">

                            <label for="accounts_id">Conta:</label>
                            <select class="form-control form-control" id="financial_accounts_id" name="financial_accounts_id">
                                <option value="0" selected>Selecione</option>

                                <?php
                                    /** Consulta todos os clientes do usuario logado */
                                    $FinancialAccountsResult = $FinancialAccounts->All(0, 0);
                                    
                                    /** Lista os clientes do usuario logado */
                                    foreach($FinancialAccountsResult as $FinancialAccountsKey => $ResultAccounts){ 

                                ?>

                                    <option value="<?php echo $ResultAccounts->financial_accounts_id;?>" <?php echo (int)$FinancialOutputsResult->financial_accounts_id === (int)$ResultAccounts->financial_accounts_id ? 'selected' : '';?>><?php echo $ResultAccounts->description;?></option>
                                
                                <?php } ?>

                            </select>             
                        </div>
                        
                        <div class="col-sm-4 mb-3">

                            <label for="accounts_id">Ativo:</label>
                            <select class="form-control form-control" id="active" name="active">
                                <option value="0" selected>Selecione</option>
                                <option value="S" <?php echo (string)$FinancialOutputsResult->active === 'S' ? 'selected' : '';?>>Sim</option>
                                <option value="N" <?php echo ((string)$FinancialOutputsResult->active === 'N' || (string)$FinancialOutputsResult->active === '') ? 'selected' : '';?>>Não</option>
                            </select>             
                        </div>                     
                        
                    </div>                 
                    
                    <div class="form-group row">

                        <div class="col-sm-3 mb-3">

                            <label for="fixed">Fixa: <span class="text-danger"> * </span></label>        
                            <select class="form-control form-control" id="fixed" name="fixed">
                                <option value="" selected>Selecione</option>
                                <option value="1" <?php echo (int)$FinancialOutputsResult->fixed === 1 ? 'selected' : '';?>>Sim</option>
                                <option value="2" <?php echo (int)$FinancialOutputsResult->fixed === 2 ? 'selected' : '';?>>Não</option>
                            </select> 

                        </div>     

                        <div class="col-sm-3 mb-3">

                            <label for="duration">Duração(meses): <span class="text-danger"> * </span></label>        
                            <select class="form-control form-control" id="duration" name="duration">
                                <option value="" selected>Selecione</option>
                                <option value="1" <?php echo (int)$FinancialOutputsResult->duration === 1 ? 'selected' : '';?>>1</option>
                                <option value="2" <?php echo (int)$FinancialOutputsResult->duration === 2 ? 'selected' : '';?>>2</option>
                                <option value="3" <?php echo (int)$FinancialOutputsResult->duration === 3 ? 'selected' : '';?>>3</option>
                                <option value="4" <?php echo (int)$FinancialOutputsResult->duration === 4 ? 'selected' : '';?>>4</option>
                                <option value="5" <?php echo (int)$FinancialOutputsResult->duration === 5 ? 'selected' : '';?>>5</option>
                                <option value="6" <?php echo (int)$FinancialOutputsResult->duration === 6 ? 'selected' : '';?>>6</option>
                                <option value="7" <?php echo (int)$FinancialOutputsResult->duration === 7 ? 'selected' : '';?>>7</option>
                                <option value="8" <?php echo (int)$FinancialOutputsResult->duration === 8 ? 'selected' : '';?>>8</option>
                                <option value="9" <?php echo (int)$FinancialOutputsResult->duration === 9 ? 'selected' : '';?>>9</option>
                                <option value="10" <?php echo (int)$FinancialOutputsResult->duration === 10 ? 'selected' : '';?>>10</option>
                                <option value="11" <?php echo (int)$FinancialOutputsResult->duration === 11 ? 'selected' : '';?>>11</option>
                                <option value="12" <?php echo (int)$FinancialOutputsResult->duration === 12 ? 'selected' : '';?>>12</option>

                            </select> 

                        </div> 

                        <div class="col-sm-3 mb-3">

                            <label for="output_value">Valor R$: <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control form-control price" id="output_value" name="output_value" value="<?php echo number_format($FinancialOutputsResult->output_value, 2 ,',', '.');?> " placeholder="0,00">
                        </div> 

                        <div class="col-sm-3 mb-3">

                            <label for="start_date">Data inicial: <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control form-control date" id="start_date" name="start_date" value="<?php echo isset($FinancialOutputsResult->start_date) ? date('d/m/Y',strtotime($FinancialOutputsResult->start_date)) : '';?>" placeholder="99/99/9999">
                        </div>         
                        
                    </div>    

                    <div class="form-group row">
                                
                        <label for="btn-save"></label>
                        <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmFinancialOutputs', '', true, '', 0, '', '<?php echo $financialOutputsId > 0 ? 'Atualizando saida' : 'Cadastrando nova saida';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$financialOutputsId > 0 ? 'Salvar alterações da saida' : 'Cadastrar nova saida') ?></a>                               
                    
                    </div>                 

                    <input type="hidden" name="TABLE" value="financial_outputs" />
                    <input type="hidden" name="ACTION" value="financial_outputs_save" />
                    <input type="hidden" name="FOLDER" value="action" />
                    <input type="hidden" name="financial_outputs_id" value="<?php echo (int)$FinancialOutputsResult->financial_outputs_id;?>" />

                </form>

                <script type="text/javascript">

                /** Carrega as mascaras dos campos inputs */
                $(document).ready(function(e) {

                    /** inputs mask */
                    loadMask();    

                });

                </script> 

            </div>

        </div>

    </div>

<?php

/** Caso o token de acesso seja inválido, informo */
}else{
	
	/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
	$authenticate = true;		

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}            