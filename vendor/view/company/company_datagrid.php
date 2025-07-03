<?php

/** Importação de classes */
use vendor\model\Company;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){   

    /** Instânciamento de classes */
    $Company = new Company();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $Company->Count()->qtde;

    /** Cores do card */
    $colors = [ 'success', 'info', 'warning', 'danger', 'secondary'];

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-8">
                            
                            <h5 class="card-title">Empresas</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=company&ACTION=company_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova empresa">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableCompany" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Nome Fantasia</th>
                                    <th class="text-center">CPF / CNPJ</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $CompanyResult = $Company->All(0, 0);
                                    foreach($CompanyResult as $CompanyKey => $Result){ 
                                ?>
                                    
                                    <tr class="<?php echo $Result->active != 'S' ? 'text-danger' : '';?>">
                                        <td class="text-center" width="25"><?php echo $Main->setZeros($Result->company_id,4);?></td>
                                        <td class="text-left"><?php echo $Result->fantasy_name;?></td>
                                        <td class="text-center" width="190"><?php echo $Main->formatarCPF_CNPJ($Result->document);?></td>                                   
                                        <td class="text-center" width="90px"><?php echo $Result->active == 'S' ? 'Sim' : 'Não';?></td>  
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=company&ACTION=company_form&company_id=<?php echo $Result->company_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="far fa-edit mr-1"></i></button></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=users&ACTION=users_datagrid&company_id=<?php echo $Result->company_id;?>', '#loadContent', true, '', '', '', 'Carregando usuários', 'blue', 'circle', 'sm', true)"><i class="fas fa-users"></i></i></button></td>
                                    </tr> 
                                    
                                <?php } ?> 
                                                                    
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="6">

                                        <?php echo $Main->pagination($NumberRecords, (int)$start, (int)$max, (int)$page, 'FOLDER=view&ACTION=company_datagrid&TABLE=company', 'Aguarde'); ?>

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
                        <h4>Não foram cadastrados Empresas.</h4>
                    </div>


                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=company&ACTION=company_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova empresa

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