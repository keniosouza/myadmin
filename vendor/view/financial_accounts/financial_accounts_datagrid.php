<?php

/** Importação de classes  */
use vendor\model\FinancialAccounts;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){ 

    /** Instânciamento de classes  */
    $FinancialAccounts = new FinancialAccounts();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $FinancialAccounts->Count()->qtde;

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

        ?>
        
        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-8">
                            
                            <h5 class="card-title">Contas</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_accounts&ACTION=financial_accounts_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova conta">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableAccounts" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Cadastro</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Saldo R$</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $FinancialResult = $FinancialAccounts->All($start, $max, 0);
                                    foreach($FinancialResult as $FinancialKey => $Result){ 
                                ?>
                                    
                                    <tr class="<?php echo $Result->status != 1 ? 'text-danger' : '';?>">
                                        <td class="text-center" width="25"><?php echo $Main->setZeros($Result->financial_accounts_id, 4);?></td>
                                        <td><?php echo $Result->description ;?></td>
                                        <td width="160"><?php echo $Result->accounts_type_description ;?></td>
                                        <td class="text-center" width="120"><?php echo date("d/m/Y", strtotime($Result->accounts_date));?></td>                                    
                                        <td class="text-center" width="90"><?php echo $Result->status == 1 ? 'Ativo' : 'Inativo';?></td> 
                                        <td class="text-right font-weight-bold <?php echo $Result->current_balance > 0 ? 'text-success' : 'text-danger';?>" width="140"><?php echo number_format($Result->current_balance, 2, ',', '.') ;?></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=financial_balance_adjustment&ACTION=financial_balance_adjustment_historic&financial_accounts_id=<?php echo $Result->financial_accounts_id;?>', '#loadContent', true, '', '', '', 'Carregando histórico', 'blue', 'circle', 'sm', true)"><i class="fas fa-info"></i></button></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=financial_balance_adjustment&ACTION=financial_balance_adjustment_form&financial_accounts_id=<?php echo $Result->financial_accounts_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-sync"></i></button></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=financial_accounts&ACTION=financial_accounts_form&financial_accounts_id=<?php echo $Result->financial_accounts_id;?>&company_id=<?php echo $companyId;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-edit mr-1"></i></button></td>
                                    </tr>                                 

                                <?php } ?> 
                                                                    
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="8">

                                        <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=financial_accounts_datagrid&TABLE=financial_accounts', 'Aguarde'); ?>

                                    </td>
                                </tr>
                            </tfoot>                             

                        </table>

                    </div>
                </div>
            </div>

        </div>

    <?php
        
    }else{//Caso não tenha registros cadastrados, informo ?>
        
        <div class="col-lg-12">
        
            <!-- Informo -->
            <div class="card shadow mb-12">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Atenção</h6>
                </div>
                <div class="card-body">
        
                    <div class="row">
        
                        <div class="col-md-8 text-right">
                            <h4>Não foram cadastradas contas.</h4>
                        </div>
        
                        <div class="col-md-4 text-right">
        
                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_accounts&ACTION=financial_accounts_form&company_id=<?php echo $companyId;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
        
                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova conta
        
                            </button>
        
                        </div>
        
                    </div>
        
                </div>
            </div>
        
        </div>
        
    <?php }

/** Caso o token de acesso seja inválido, informo */
}else{

	/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
	$authenticate = true;		

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}      