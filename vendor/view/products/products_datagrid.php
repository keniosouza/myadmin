<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\Products;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes */
    $Main = new Main();
    $Products = new Products();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $Products->Count();

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
                            
                            <h5 class="card-title">Produtos</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=products&ACTION=products_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo cliente">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableProducts" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Cadastro</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Referência</th>
                                    <th class="text-center">Versão</th>
                                    <th class="text-center">Release</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                                <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $ProductsResult = $Products->All($start, $max);
                                    foreach($ProductsResult as $ProductsKey => $Result){ 
                                ?>
                                    
                                    <tr>
                                        <td class="text-center" width="25"><?php echo $Main->setZeros($Result->products_id,4);?></td>
                                        <td class="text-center" width="30"><?php echo date('d/m/Y', strtotime($Result->date_register));?></td>
                                        <td class="text-left"><?php echo $Result->description;?></td>
                                        <td class="text-left"><?php echo $Result->reference;?></td>
                                        <td class="text-center" width="25"><?php echo $Result->version;?></td>
                                        <td class="text-center" width="25"><?php echo $Result->version_release;?></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=products&ACTION=products_form&products_id=<?php echo $Result->products_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="far fa-edit mr-1"></i></button></td>
                                    </tr> 
                                    
                                <?php } ?> 
                                                                    
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="7">

                                            <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=products_datagrid&TABLE=products', 'Aguarde'); ?>

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
                        <h4>Não foram cadastrados Produtos.</h4>
                    </div>


                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=products&ACTION=products_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar novo produto

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