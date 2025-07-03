<?php

/** Importação de classes  */
use vendor\model\Clients;
use vendor\model\ClientBudgets;
use vendor\model\FinancialAccounts;
use vendor\model\FinancialCategories;
use vendor\model\FinancialReadjustments;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $Clients = new Clients();
        $ClientBudgets = new ClientBudgets();
        $FinancialAccounts = new FinancialAccounts();
        $FinancialCategories = new FinancialCategories();
        $FinancialReadjustments = new FinancialReadjustments();

        /** Parametros de entrada */
        $clientsId        = isset($_POST['clients_id'])         ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_SPECIAL_CHARS)         : 0;
        $clientsBudgetsId = isset($_POST['clients_budgets_id']) ? (int)filter_input(INPUT_POST, 'clients_budgets_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Verifica se o ID do projeto foi informado */
        if($clientsId > 0){

            /** Consulta os dados do controle de acesso */
            $ClientsResult = $Clients->Get($clientsId);
            $ClientBudgetsResult = $ClientBudgets->Get($clientsBudgetsId);            

        }else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $ClientsResult = $Clients->Describe();
            $ClientBudgetsResult = $ClientBudgets->Describe();

        }

        /** Controles */
        $months = [ 1 => 'Jan', 
                    2 => 'Fev', 
                    3 => 'Mar', 
                    4 => 'Abr', 
                    5 => 'Mai', 
                    6 => 'Jun', 
                    7 => 'Jul', 
                    8 => 'Ago', 
                    9 => 'Set', 
                    10 => 'Out', 
                    11 => 'Nov', 
                    12 => 'Dez' 
                ];        


    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-4">
                            
                            <h5 class="card-title">Gerenciar orçamento</h5>
                        
                        </div>

                        <div class="col-md-8 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_form&clients_id=<?php echo $clientsId;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo orçamento">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_datagrid', '#loadContent', true, '', '', '', 'Carregando clientes cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar clientes cadastrados">

                                <i class="fas fa-plus-circle mr-1"></i>Clientes Cadastrados

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user mb-4" id="frmClientsBudgets" autocomplete="off">

                        <div class="form-group row">
                            
                            <div class="col-sm-6 mb-2">

                                <fieldset class="border p-2">
                                    <legend><h5 class="h6">Razão Social / Nome: <span class="text-danger">* Selecione um cliente</span> </h5></legend>
                                    <select class="form-control form-control" id="clients_id" name="clients_id">
                                    <option value="" selected>Selecione</option>

                                    <?php
                                        $ResultClients = $Clients->ListReference();
                                        foreach($ResultClients as $ClientsKey => $Result){ 
                                    ?>

                                    <option value="<?php echo $Result->clients_id;?>" <?php echo (int)$Result->clients_id == $clientsId ? 'selected' : '' ;?> ><?php echo $Result->reference;?> - <?php echo $Result->fantasy_name;?></option>

                                    <?php } ?>

                                </select>

                                </fieldset>
                            </div>

                            <div class="col-sm-3 mb-2">

                                <fieldset class="border p-2">
                                    <legend><h5 class="h6 pb-2">Referência: </h5></legend>
                                    <?php echo $ClientsResult->reference;?>&nbsp;
                                </fieldset>
                            </div>                             
                            
                            <div class="col-sm-3 mb-2">

                                <fieldset class="border p-2">
                                    <legend><h5 class="h6 pb-2">CPF / CNPJ: </h5></legend>
                                    <?php echo $Main->formatarCPF_CNPJ($ClientsResult->document);?>&nbsp;
                                </fieldset>
                            </div> 
                            
                        </div>

                        <div class="collapse" id="collapseBudgets">

                            <div class="form-group row">

                                <div class="col-sm-2 mb-2">

                                    <label for="budget">Valor Orçamento R$:</label>
                                    <input type="text" class="form-control form-control price" maxlength="120" id="budget" name="budget" placeholder="R$" value="<?php echo $ClientBudgetsResult->budget;?>">
                                </div> 
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="day_due">Vencimento/dia:</label>
                                    <select class="form-control form-control" id="day_due" name="day_due">
                                        <option value="" selected>Selecione</option>
                                        <option value="01" <?php echo (int)$ClientBudgetsResult->day_due == 1 ? 'selected' : '' ;?> >01</option>
                                        <option value="02" <?php echo (int)$ClientBudgetsResult->day_due == 2 ? 'selected' : '' ;?>>02</option>                                     
                                        <option value="03" <?php echo (int)$ClientBudgetsResult->day_due == 3 ? 'selected' : '' ;?>>03</option>   
                                        <option value="04" <?php echo (int)$ClientBudgetsResult->day_due == 4 ? 'selected' : '' ;?>>04</option>   
                                        <option value="05" <?php echo (int)$ClientBudgetsResult->day_due == 5 ? 'selected' : '' ;?>>05</option>   
                                        <option value="06" <?php echo (int)$ClientBudgetsResult->day_due == 6 ? 'selected' : '' ;?>>06</option>   
                                        <option value="07" <?php echo (int)$ClientBudgetsResult->day_due == 7 ? 'selected' : '' ;?>>07</option>   
                                        <option value="08" <?php echo (int)$ClientBudgetsResult->day_due == 8 ? 'selected' : '' ;?>>08</option>   
                                        <option value="09" <?php echo (int)$ClientBudgetsResult->day_due == 9 ? 'selected' : '' ;?>>09</option>   
                                        <option value="10" <?php echo (int)$ClientBudgetsResult->day_due == 10 ? 'selected' : '' ;?>>10</option>   
                                        <option value="11" <?php echo (int)$ClientBudgetsResult->day_due == 11 ? 'selected' : '' ;?>>11</option>   
                                        <option value="12" <?php echo (int)$ClientBudgetsResult->day_due == 12 ? 'selected' : '' ;?>>12</option>   
                                        <option value="13" <?php echo (int)$ClientBudgetsResult->day_due == 13 ? 'selected' : '' ;?>>13</option>   
                                        <option value="14" <?php echo (int)$ClientBudgetsResult->day_due == 14 ? 'selected' : '' ;?>>14</option>   
                                        <option value="15" <?php echo (int)$ClientBudgetsResult->day_due == 15 ? 'selected' : '' ;?>>15</option>   
                                        <option value="16" <?php echo (int)$ClientBudgetsResult->day_due == 16 ? 'selected' : '' ;?>>16</option>   
                                        <option value="17" <?php echo (int)$ClientBudgetsResult->day_due == 17 ? 'selected' : '' ;?>>17</option>   
                                        <option value="18" <?php echo (int)$ClientBudgetsResult->day_due == 18 ? 'selected' : '' ;?>>18</option>   
                                        <option value="19" <?php echo (int)$ClientBudgetsResult->day_due == 19 ? 'selected' : '' ;?>>19</option>   
                                        <option value="20" <?php echo (int)$ClientBudgetsResult->day_due == 20 ? 'selected' : '' ;?>>20</option>   
                                        <option value="21" <?php echo (int)$ClientBudgetsResult->day_due == 21 ? 'selected' : '' ;?>>21</option>   
                                        <option value="22" <?php echo (int)$ClientBudgetsResult->day_due == 22 ? 'selected' : '' ;?>>22</option>   
                                        <option value="23" <?php echo (int)$ClientBudgetsResult->day_due == 23 ? 'selected' : '' ;?>>23</option>   
                                        <option value="24" <?php echo (int)$ClientBudgetsResult->day_due == 24 ? 'selected' : '' ;?>>24</option>   
                                        <option value="25" <?php echo (int)$ClientBudgetsResult->day_due == 25 ? 'selected' : '' ;?>>25</option>
                                        <option value="26" <?php echo (int)$ClientBudgetsResult->day_due == 26 ? 'selected' : '' ;?>>26</option>
                                        <option value="27" <?php echo (int)$ClientBudgetsResult->day_due == 27 ? 'selected' : '' ;?>>27</option>
                                        <option value="28" <?php echo (int)$ClientBudgetsResult->day_due == 28 ? 'selected' : '' ;?>>28</option>
                                        <option value="29" <?php echo (int)$ClientBudgetsResult->day_due == 29 ? 'selected' : '' ;?>>29</option>
                                        <option value="30" <?php echo (int)$ClientBudgetsResult->day_due == 30 ? 'selected' : '' ;?>>30</option>
                                        <option value="31" <?php echo (int)$ClientBudgetsResult->day_due == 31 ? 'selected' : '' ;?>>31</option>
                                    </select> 
                                </div>    
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="readjustment_year">Ano Reajuste:</label>
                                    <input type="text" class="form-control form-control number" maxlength="4" id="readjustment_year" value="<?php echo isset($ClientBudgetsResult->readjustment_year) ? $ClientBudgetsResult->readjustment_year : date('Y');?>" name="readjustment_year" placeholder="9999">
                                </div> 

                                <div class="col-sm-2 mb-2">

                                    <label for="readjustment_month">Mês Reajuste:</label>

                                    <select class="form-control form-control" id="readjustment_month" name="readjustment_month">
                                        <option value="" selected>Selecione</option>
                                        <option value="01" <?php echo (int)date('m') === 1 ? 'selected' : '';?>>janeiro</option>
                                        <option value="02" <?php echo (int)date('m') === 2 ? 'selected' : '';?>>fevereiro</option>
                                        <option value="03" <?php echo (int)date('m') === 3 ? 'selected' : '';?>>março</option>
                                        <option value="04" <?php echo (int)date('m') === 4 ? 'selected' : '';?>>abril</option>
                                        <option value="05" <?php echo (int)date('m') === 5 ? 'selected' : '';?>>maio</option>
                                        <option value="06" <?php echo (int)date('m') === 6 ? 'selected' : '';?>>junho</option>
                                        <option value="07" <?php echo (int)date('m') === 7 ? 'selected' : '';?>>julho</option>
                                        <option value="08" <?php echo (int)date('m') === 8 ? 'selected' : '';?>>agosto</option>
                                        <option value="09" <?php echo (int)date('m') === 9 ? 'selected' : '';?>>setembro</option>
                                        <option value="10" <?php echo (int)date('m') === 10 ? 'selected' : '';?>>outubro</option>
                                        <option value="11" <?php echo (int)date('m') === 11 ? 'selected' : '';?>>novembro</option>                                        
                                        <option value="12" <?php echo (int)date('m') === 12 ? 'selected' : '';?>>dezembro</option>                                        
                                    </select>    

                                </div> 
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="readjustment_type">Tipo Reajuste:</label>

                                    <select class="form-control form-control" id="readjustment_type" name="readjustment_type">
                                        
                                        <option value="" selected>Selecione</option>                                        
                                        <?php

                                            /** Consulta a quantidade de registros */
                                            $financialReadjustmentsResult = $FinancialReadjustments->Combobox();

                                            /** Consulta os usuário cadastrados*/
                                            foreach ($financialReadjustmentsResult as $resultKey => $result){
                                        ?>

                                        <option value="<?php echo $result->financial_readjustment_id;?>" <?php echo (int)$ClientBudgetsResult->readjustment_type == (int)$result->financial_readjustment_id ? 'selected' : '';?>><?php echo $result->description;?> - <?php echo $months[$result->month];?>/<?php echo $result->year;?></option>

                                        <?php } ?>


                                    </select>    

                                </div>                             
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="readjustment_index">Índice %:</label>
                                    <input type="text" class="form-control form-control percentage" maxlength="8" id="readjustment_index" name="readjustment_index" placeholder="%" value="<?php echo $ClientBudgetsResult->readjustment_index;?>">
                                </div> 

                            </div>
                            
                            <div class="form-group row">
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="readjustment_value">Reajsute R$:</label>
                                    <input type="text" class="form-control form-control price" maxlength="10" id="readjustment_value" name="readjustment_value" placeholder="R$" value="<?php echo $ClientBudgetsResult->readjustment_value;?>">
                                </div>    
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="readjustment_budget">Valor Reajustado R$:</label>
                                    <input type="text" class="form-control form-control price" maxlength="10" id="readjustment_budget" name="readjustment_budget" placeholder="R$" value="<?php echo $ClientBudgetsResult->readjustment_budget;?>">
                                </div> 
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="often">Frequência:</label>
                                    <select class="form-control form-control" id="often " name="often">
                                        <option value="" selected>Selecione</option>
                                        <option value="1" <?php echo (int)$ClientBudgetsResult->often == 1 ? 'selected' : '';?>>01</option>
                                        <option value="2" <?php echo (int)$ClientBudgetsResult->often == 2 ? 'selected' : '';?>>02</option>                                     
                                        <option value="3" <?php echo (int)$ClientBudgetsResult->often == 3 ? 'selected' : '';?>>03</option>   
                                        <option value="4" <?php echo (int)$ClientBudgetsResult->often == 4 ? 'selected' : '';?>>04</option>   
                                        <option value="5" <?php echo (int)$ClientBudgetsResult->often == 5 ? 'selected' : '';?>>05</option>   
                                        <option value="6" <?php echo (int)$ClientBudgetsResult->often == 6 ? 'selected' : '';?>>06</option>   
                                        <option value="7" <?php echo (int)$ClientBudgetsResult->often == 7 ? 'selected' : '';?>>07</option>   
                                        <option value="8" <?php echo (int)$ClientBudgetsResult->often == 8 ? 'selected' : '';?>>08</option>   
                                        <option value="9" <?php echo (int)$ClientBudgetsResult->often == 9 ? 'selected' : '';?>>09</option>   
                                        <option value="10" <?php echo (int)$ClientBudgetsResult->often == 10 ? 'selected' : '';?>>10</option>   
                                        <option value="11" <?php echo (int)$ClientBudgetsResult->often == 11 ? 'selected' : '';?>>11</option>   
                                        <option value="12" <?php echo (int)$ClientBudgetsResult->often == 12 || (int)$ClientBudgetsResult->often == 0 ? 'selected' : '';?>>12</option>     
                                    </select> 
                                </div>  
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="date_start">Data Início:</label>
                                    <input type="text" class="form-control form-control date" maxlength="10" id="date_start" name="date_start" placeholder="99/99/9999" value="<?php echo isset($ClientBudgetsResult->date_start) ? date('d/m/Y', strtotime($ClientBudgetsResult->date_start)) : '';?>">
                                </div>    
                                
                                <div class="col-sm-2 mb-2">

                                    <label for="description">Descrição:</label>
                                    <input type="text" class="form-control form-control" maxlength="120" id="description" name="description" placeholder="Parcela nº ..." value="<?php echo $ClientBudgetsResult->description;?>">
                                </div>  
                                
                                <div class="col-sm-2 mb-3">

                                    <label for="financial_categories_id">Categoria:</label>
                                    <select class="form-control form-control" id="financial_categories_id" name="financial_categories_id">
                                        <option value="0" selected>Selecione</option>

                                        <?php
                                            /** Consulta todos os clientes do usuario logado */
                                            $FinancialCategoriesResult = $FinancialCategories->ComboBox('E');
                                            
                                            /** Lista os clientes do usuario logado */
                                            foreach($FinancialCategoriesResult as $FinancialCategoriesKey => $ResultFinancialCategories){ 

                                        ?>

                                        <option value="<?php echo $ResultFinancialCategories->financial_categories_id;?>" <?php echo (int)$ResultFinancialCategories->financial_categories_id === (int)$ClientBudgetsResult->financial_categories_id ? 'selected' : '';?>><?php echo $ResultFinancialCategories->description;?></option>
                                        
                                        <?php } ?>

                                    </select>

                                </div>                             

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-2">

                                    <label for="financial_accounts_id">Conta:</label>
                                    <select class="form-control form-control" id="financial_accounts_id" name="financial_accounts_id">
                                        <option value="0" selected>Selecione</option>

                                        <?php
                                            /** Consulta todos os clientes do usuario logado */
                                            $FinancialAccountsResult = $FinancialAccounts->All(0, 0);
                                            
                                            /** Lista os clientes do usuario logado */
                                            foreach($FinancialAccountsResult as $FinancialAccountsKey => $ResultAccounts){ 

                                        ?>

                                            <option value="<?php echo $ResultAccounts->financial_accounts_id;?>" <?php echo (int)$ClientBudgetsResult->financial_accounts_id === (int)$ResultAccounts->financial_accounts_id ? 'selected' : '';?>><?php echo $ResultAccounts->description;?></option>
                                        
                                        <?php } ?>

                                    </select>  

                                </div> 
                                
                                <div class="col-sm-3 ">
                                    
                                    <label for="btn-save">&nbsp;</label>
                                    <a href="#" class="btn btn-primary form-control form-control" id="btn-save" onclick="sendForm('#frmClientsBudgets', '', true, '', 0, '', 'Atualizando orçamento do cliente...', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> GRAVAR ORÇAMENTO</a>                               
                                </div> 

                            </div>

                        
                            <input type="hidden" name="TABLE" value="clients_budgets" />
                            <input type="hidden" name="ACTION" value="clients_budgets_save" />
                            <input type="hidden" name="FOLDER" value="action" />
                            <input type="hidden" name="products_id" id="products_id" value="" />
                            <input type="hidden" name="clients_budgets_id" id="clients_budgets_id" value="<?php echo $ClientBudgetsResult->client_budgets_id;?>" />
                    
                        </div>
                    </form>

                    <div class="col-lg-12"> 
                        <!-- Content Row -->
                        <div class="row" id="loadProducts"></div>        

                    </div>                     

                    <div class="col-lg-12"> 

                        <!-- Content Row -->
                        <div class="row" id="loadBudgests"></div>        

                    </div>  

                    <div class="col-lg-12"> 

                        <!-- Content Row -->
                        <div class="row" id="loadCommissions"></div>        

                    </div>                      
                    
                    <div class="col-lg-12"> 

                        <!-- Content Row -->
                        <div class="row" id="loadDocuments"></div>        

                    </div>  
                    
                    <div class="col-lg-12"> 

                        <!-- Content Row -->
                        <div class="row" id="loadTickets"></div>        

                    </div>                     

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

            /** Coloca o foco no primeiro campo */
            $('#budget').focus();

            /** Quando o valor do indice for informado e o foco sair do campo, calculo os novos valores */
            $('#readjustment_index').blur(function(){

                const formatNumber = new Intl.NumberFormat();
                
                /** Valores de entrada */                    
                let budget            = $('#budget').val().replace('.', '').replace(',', '.');
                let readjustmentIndex = $('#readjustment_index').val().replace('.', '').replace(',', '.');
                
                /** Calcula o valor a ser acrescido na mensalidade */
                let readjustmentValue = financial( budget * (readjustmentIndex / 100) );

                /** Soma o valor acrescido com o orçamento atual */
                let readjustmentBudget = financial( parseFloat(budget)+parseFloat(readjustmentValue) );

                /** carrega o valor acrescido */
                $('#readjustment_value').val(convertToReal(readjustmentValue, { moneySign: false }));

                /** Carrega o valor final da mensalidade */
                $('#readjustment_budget').val(convertToReal(readjustmentBudget, { moneySign: false }));

                /** Coloca o foco no campo de frequência */
                $('#often').focus();                

            });

            /** Quando o foco cair no campo da data inicia */
            $('#date_start').focus(function(){

                /** Carrega os dados do suposto lançamento */
                let day   = $('#day_due option:selected').val();                
                let month = $('#readjustment_month option:selected').val();
                let year  = $('#readjustment_year').val();

                /** Monta a nova data de inicio */
                $(this).val(day+'/'+month+'/'+year);

            });

            <?php

                if($ClientsResult->clients_id > 0){ ?>

                /** Carrega os orçamentos do cliente informado */
                request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_datagrid&clients_id='+$('#clients_id option:selected').val(), '', true, '', '', '#loadBudgests', 'Carregando orçamentos...', 'blue', 'circle', 'sm', true);

                /** Carrega as comossões dos orçamentos do cliente informado */
                request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_commissions_datagrid&clients_id='+$('#clients_id option:selected').val(), '', true, '', '', '#loadCommissions', 'Carregando comissões...', 'blue', 'circle', 'sm', true);                

                /** Carrega os produtos do cliente informado */
                request('FOLDER=view&TABLE=client_products&ACTION=client_products_datagrid&clients_id=<?php echo $ClientsResult->clients_id;?>', '', true, '', '', '#loadProducts', 'Carregando produtos...', 'blue', 'circle', 'sm', true);                        

                /** Carrega os documentos do cliente informado */
                request('FOLDER=view&TABLE=clients&ACTION=clients_documents_datagrid&clients_id=<?php echo $ClientsResult->clients_id;?>', '', true, '', '', '#loadDocuments', 'Carregando Documentos...', 'blue', 'circle', 'sm', true);                                    

                /** Carrega os boletos do cliente informado */
                request('FOLDER=view&TABLE=financial_movements&ACTION=financial_movements_tickets_list&clients_id=<?php echo $ClientsResult->clients_id;?>', '', true, '', '', '#loadTickets', 'Carregando boletos junto ao Sicoob...', 'blue', 'circle', 'sm', true);                                                

            <?php } ?>                

            /** Consulta o cliente selecionado */
            $('#clients_id').on('change', function () {

                /** Carrega os orçamentos do cliente informado */
                request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_form&clients_id='+$('#clients_id option:selected').val(), '', true, '', '', '#loadContent', 'Carregando orçamento do cliente', 'blue', 'circle', 'sm', true);
            });

            /** Carrega o valor do reajuste */
            $('#readjustment_type').on('change', function () {

                <?php
                    $arr = new stdClass();

                    /** Consulta os usuário cadastrados*/
                    foreach ($financialReadjustmentsResult as $resultKey => $result){

                        $arr->{$result->financial_readjustment_id} = new stdClass();
                        $arr->{$result->financial_readjustment_id}->value = number_format($result->readjustment, 4, ',', '.');
                        
                    }
                ?>

                /** Converte os valores selecionados em json */
                let arr = <?php echo json_encode($arr);?>;

                /** Carrega o valor selecionado */
                $('#readjustment_index').val(arr[$('#readjustment_type').val()]['value']);
                
            });            

            <?php if($ClientBudgetsResult->client_budgets_id > 0){?>
            /** Habilita o formulário caso esteja oculto */
            $('.collapse').collapse();  
            <?php } ?>
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