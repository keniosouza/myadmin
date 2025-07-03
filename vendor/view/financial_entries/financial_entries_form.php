<?php

/** Importação de classes  */
use vendor\model\FinancialEntries;
use vendor\model\FinancialAccounts;
use vendor\controller\financial_entries\FinancialEntriesValidate;
use vendor\model\FinancialCategories;
use vendor\model\Clients;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){  

    /** Instânciamento de classes  */
    $FinancialEntries = new FinancialEntries();
    $FinancialAccounts = new FinancialAccounts();
    $FinancialCategories = new FinancialCategories();
    $FinancialEntriesValidate = new FinancialEntriesValidate();
    $Clients = new Clients();

    /** Parametros de entrada  */
    $financialEntriesId = isset($_POST['financial_entries_id']) ? (int)filter_input(INPUT_POST,'financial_entries_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

    /** Validando os campos de entrada */
    $FinancialEntriesValidate->setfinancialEntriesId($financialEntriesId);

    /** Verifica se não existem erros a serem informados */
    if (!empty($FinancialEntriesValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($FinancialEntriesValidate->getErrors(), 0);        

    } else {

        /** Verifica se o ID da conta foi informado */
        if($financialEntriesId > 0){

            /** Consulta os dados da conta */
            $FinancialEntriesResult = $FinancialEntries->Get($financialEntriesId);

        }else{/** Caso o ID da conta não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $FinancialEntriesResult = $FinancialEntries->Describe();

        }

    }

    ?>

    <div class="col-lg-12">


        <div class="card shadow mb-12">
                
            <div class="card-header">

                <div class="row">
                    
                    <div class="col-md-6">
                        
                        <h5 class="card-title"><?php echo (int)$FinancialEntriesResult->financial_entries_id > 0 ? 'Editando dados da entrada' : 'Cadastrar nova entrada';?></h5>
                    
                    </div>

                    <div class="col-md-6 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova entrada">

                            <i class="fas fa-plus-circle mr-1"></i>Nova

                        </button>


                        <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_datagrid', '#loadContent', true, '', '', '', 'Carregando entradas cadastradas', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar contas cadastradas">

                            <i class="fas fa-plus-circle mr-1"></i>Entradas Cadastradas

                        </button>                        

                    </div>
                
                </div>            

            </div>


            <div class="card-body">

                <form class="user" id="frmFinancialEntries" autocomplete="off">

                    <div class="form-group row">

                        <div class="col-sm-6 mb-3">

                            <label for="current_balance">Descrição:</label>
                            <input type="text" class="form-control form-control" id="description" name="description" maxlength="160" value="<?php echo $FinancialEntriesResult->description;?> ">
                        </div> 

                        <div class="col-sm-6 mb-3">

                            <label for="financial_categories_id">Categoria:</label>
                            <select class="form-control form-control" id="financial_categories_id" name="financial_categories_id">
                                <option value="0" selected>Selecione</option>

                                <?php
                                    /** Consulta todos os clientes do usuario logado */
                                    $FinancialCategoriesResult = $FinancialCategories->ComboBox('E');
                                    
                                    /** Lista os clientes do usuario logado */
                                    foreach($FinancialCategoriesResult as $FinancialCategoriesKey => $ResultFinancialCategories){ 

                                ?>

                                <option value="<?php echo $ResultFinancialCategories->financial_categories_id;?>" <?php echo (int)$ResultFinancialCategories->financial_categories_id === (int)$FinancialEntriesResult->financial_categories_id ? 'selected' : '';?>><?php echo $ResultFinancialCategories->description;?></option>
                                
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
                                    $ClientsResult = $Clients->All(0, 0, null, null);
                                    
                                    /** Lista os clientes do usuario logado */
                                    foreach($ClientsResult as $ClientsKey => $ResultClients){ 

                                ?>

                                <option value="<?php echo $ResultClients->clients_id;?>" <?php echo (int)$FinancialEntriesResult->clients_id === (int)$ResultClients->clients_id ? 'selected' : '';?>><?php echo $ResultClients->reference;?> - <?php echo $ResultClients->fantasy_name;?></option>
                                
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

                                    <option value="<?php echo $ResultAccounts->financial_accounts_id;?>" <?php echo (int)$FinancialEntriesResult->financial_accounts_id === (int)$ResultAccounts->financial_accounts_id ? 'selected' : '';?>><?php echo $ResultAccounts->description;?></option>
                                
                                <?php } ?>

                            </select>             
                        </div>
                        
                        <div class="col-sm-4 mb-3">

                            <label for="accounts_id">Ativo:</label>
                            <select class="form-control form-control" id="active" name="active">
                                <option value="0" selected>Selecione</option>
                                <option value="S" <?php echo (string)$FinancialEntriesResult->active === 'S' ? 'selected' : '';?>>Sim</option>
                                <option value="N" <?php echo ((string)$FinancialEntriesResult->active === 'N' || (string)$FinancialEntriesResult->active === '') ? 'selected' : '';?>>Não</option>
                            </select>             
                        </div>                     
                        
                    </div>                 
                    
                    <div class="form-group row">

                        <div class="col-sm-2 mb-3">

                            <label for="fixed">Fixa: <span class="text-danger"> * </span></label>        
                            <select class="form-control form-control" id="fixed" name="fixed">
                                <option value="" selected>Selecione</option>
                                <option value="1" <?php echo (int)$FinancialEntriesResult->fixed === 1 ? 'selected' : '';?>>Sim</option>
                                <option value="2" <?php echo (int)$FinancialEntriesResult->fixed === 2 ? 'selected' : '';?>>Não</option>
                            </select> 

                        </div>     

                        <div class="col-sm-2 mb-3">

                            <label for="duration">Duração(meses): <span class="text-danger"> * </span></label>        
                            <select class="form-control form-control" id="duration" name="duration">
                                <option value="" selected>Selecione</option>
                                <option value="1" <?php echo (int)$FinancialEntriesResult->duration === 1 ? 'selected' : '';?>>1</option>
                                <option value="2" <?php echo (int)$FinancialEntriesResult->duration === 2 ? 'selected' : '';?>>2</option>
                                <option value="3" <?php echo (int)$FinancialEntriesResult->duration === 3 ? 'selected' : '';?>>3</option>
                                <option value="4" <?php echo (int)$FinancialEntriesResult->duration === 4 ? 'selected' : '';?>>4</option>
                                <option value="5" <?php echo (int)$FinancialEntriesResult->duration === 5 ? 'selected' : '';?>>5</option>
                                <option value="6" <?php echo (int)$FinancialEntriesResult->duration === 6 ? 'selected' : '';?>>6</option>
                                <option value="7" <?php echo (int)$FinancialEntriesResult->duration === 7 ? 'selected' : '';?>>7</option>
                                <option value="8" <?php echo (int)$FinancialEntriesResult->duration === 8 ? 'selected' : '';?>>8</option>
                                <option value="9" <?php echo (int)$FinancialEntriesResult->duration === 9 ? 'selected' : '';?>>9</option>
                                <option value="10" <?php echo (int)$FinancialEntriesResult->duration === 10 ? 'selected' : '';?>>10</option>
                                <option value="11" <?php echo (int)$FinancialEntriesResult->duration === 11 ? 'selected' : '';?>>11</option>
                                <option value="12" <?php echo (int)$FinancialEntriesResult->duration === 12 ? 'selected' : '';?>>12</option>

                            </select> 

                        </div> 

                        <div class="col-sm-2 mb-3">

                            <label for="entrie_value">Valor R$: <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control form-control price" id="entrie_value" name="entrie_value" value="<?php echo number_format($FinancialEntriesResult->entrie_value, 2 ,',', '.');?> " placeholder="0,00">
                        </div> 

                        <div class="col-sm-2 mb-3">

                            <label for="start_date">Data inicial: <span class="text-danger"> * </span></label>
                            <input type="text" class="form-control form-control date" id="start_date" name="start_date" value="<?php echo isset($FinancialEntriesResult->start_date) ? date('d/m/Y',strtotime($FinancialEntriesResult->start_date)) : '';?>" placeholder="99/99/9999">
                        </div> 
                        
                        <div class="col-sm-2 mb-3">

                            <label for="start_date">Referência: </label>
                            <input type="text" class="form-control form-control" maxlength="20" id="reference" name="reference" value="<?php echo $FinancialEntriesResult->reference;?>" placeholder="999/999-99">
                        </div>                         
                        
                    </div>    

                    <div class="form-group row">
                                
                        <label for="btn-save"></label>
                        <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmFinancialEntries', '', true, '', 0, '', '<?php echo $financialEntriesId > 0 ? 'Atualizando entrada' : 'Cadastrando nova entrada';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$financialEntriesId > 0 ? 'Salvar alterações da entrada' : 'Cadastrar nova entrada') ?></a>                               
                    
                    </div>                 

                    <input type="hidden" name="TABLE" value="financial_entries" />
                    <input type="hidden" name="ACTION" value="financial_entries_save" />
                    <input type="hidden" name="FOLDER" value="action" />
                    <input type="hidden" name="financial_entries_id" value="<?php echo (int)$FinancialEntriesResult->financial_entries_id;?>" />

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