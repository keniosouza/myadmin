<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\Clients;
use vendor\model\Products;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){  

    /** Instânciamento de classes */
    $Main = new Main();
    $Clients = new Clients();
    $Products = new Products();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Meses do ano */
    $month = ['01' => 'janeiro',
              '02' => 'fevereiro',
              '03' => 'março',
              '04' => 'abril',
              '05' => 'maio',
              '06' => 'junho',
              '07' => 'julho',
              '08' => 'agosto',
              '09' => 'setembro',
              '10' => 'outubro',
              '11' => 'novembro',
              '12' => 'dezembro'
            ];    

    /** Parâmetros de paginação **/
    $typeSearch = strtolower(isset($_POST['type-search']) ? (int)$Main->antiInjection( filter_input(INPUT_POST,'type-search',  FILTER_SANITIZE_SPECIAL_CHARS) ) : 7);
    $search     = strtolower(isset($_POST['search'])      ? (string)$Main->antiInjection( filter_input(INPUT_POST,'search',  FILTER_SANITIZE_SPECIAL_CHARS) )   : $month[date('m')]);
    $start      = strtolower(isset($_POST['start'])       ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )       : 0);
    $page       = strtolower(isset($_POST['page'])        ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )        : 0);
    $max        = $config->{'app'}->{'datagrid'}->{'rows'};

    /** Consulta a quantidade de registros */
    $NumberRecords = $Clients->Count($typeSearch, $search)->qtde;

    /** Cores do card */
    $colors = [ 'success', 'info', 'warning', 'danger', 'secondary'];

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-3">
                            
                            <h5 class="card-title">Clientes</h5>
                        
                        </div>

                        <div class="col-md-7">
                            
                            <form id="frmSearch" autocomplete="off" onsubmit="return false;">
                                <div class="input-group">

                                    <div class="form-outline mr-1">
                                        <select id="type-search" name="type-search" class="form-control">
                                            <option selected>Selecione</option>
                                            <option value="1" <?php echo $typeSearch == 1 || (int)$typeSearch == 0 ? 'selected' : '';?>>Referência</option>
                                            <option value="2" <?php echo $typeSearch == 2 ? 'selected' : '';?>>Nome</option>
                                            <option value="3" <?php echo $typeSearch == 3 ? 'selected' : '';?>>Responsável</option>
                                            <option value="4" <?php echo $typeSearch == 4 ? 'selected' : '';?>>Email</option>
                                            <option value="5" <?php echo $typeSearch == 5 ? 'selected' : '';?>>Estado</option>
                                            <option value="6" <?php echo $typeSearch == 6 ? 'selected' : '';?>>Sistema</option>
                                            <option value="7" <?php echo $typeSearch == 7 ? 'selected' : '';?>>Reajuste</option>
                                        </select>
                                    </div>

                                    <div class="form-outline mr-1">
                                        <input type="text" id="search" name="search" class="form-control" placeholder="Informe sua consulta" value="<?php echo $search;?>" data-required="S" />                            
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="validateForm('#frmSearch', 'Enviando consulta, aguarde...', '#loadContent');">
                                        <i class="fas fa-search"></i>
                                    </button>

                                </div>

                                <input type="hidden" name="TABLE" value="clients" />
                                <input type="hidden" name="ACTION" value="clients_datagrid" />
                                <input type="hidden" name="FOLDER" value="view" />   
                                                             
                            </form>

                        </div>                        

                        <div class="col-md-2 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo cliente">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableclients" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Ref.</th>
                                    <th class="text-center">Venc.</th>
                                    <th class="text-center">Nome Fantasia</th>
                                    <th class="text-center">Responsável</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center" colspan="2"></th>
                                </tr>
                            </thead>

                                <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $ClientsResult = $Clients->All($start, $max, $typeSearch, $search);
                                    foreach($ClientsResult as $ClientsKey => $Result){ 
                                ?>
                                    
                                    <tr class="<?php echo $Result->active != 'S' ? 'text-danger' : '';?>">                                                                                
                                        <td class="text-center" width="60"><?php echo $Result->reference;?></td>
                                        <td class="text-center" width="30"><?php echo $Result->due_date;?></td>
                                        <td class="text-left"><?php echo $Result->fantasy_name;?></td>
                                        <td class="text-left"><?php echo $Result->responsible;?></td>
                                        <td class="text-left"><?php echo $Result->email;?></td>                                   
                                        <td class="text-center" width="90px"><?php echo $Result->active == 'S' ? 'Sim' : 'Não';?></td>  
                                        <td class="text-center" width="20"><?php if($Result->active == 'S'){?><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_form&clients_id=<?php echo $Result->clients_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Gerenciar orçamento"><i class="fas fa-ellipsis-v"></i></button><?php } ?></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_form&clients_id=<?php echo $Result->clients_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Editar dados do cliente"><i class="far fa-edit"></i></button></td>
                                    </tr> 
                                    
                                <?php } ?> 
                                                                    
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="8">

                                            <?php echo $Main->pagination($NumberRecords, $start, $max, $page, '', 'Aguarde', '#frmSearch'); ?>

                                        </td>
                                    </tr>
                                </tfoot>                             

                            </table>


                    </div>
                </div>
            </div>

        </div>

        <script type="text/javascript">

            /** Operações ao carregar a página */
            $(document).ready(function(e) {  
                
                /** Inicia a tela com o foco no campo de consulta */
                $('#search').focus();
                
                /** Quando selecionar um tipo de consulta limpa o campo que informa */
                $('#type-search').on('change', function () {
                    
                    if($(this).val() == '7'){

                        let month = ['janeiro', 
                                     'fevereiro',
                                     'março',
                                     'abril',
                                     'maio',
                                     'junho',
                                     'julho',
                                     'agosto',
                                     'setembro',
                                     'outubro',
                                     'novembro',
                                     'dezembro'
                                    ];

                        const date = new Date();
                        $('#search').val(month[date.getMonth()]);
                        $('#search').focus();

                    } else {
                    
                        $('#search').val('');
                        $('#search').focus();

                    }

                });

                /** ao pressionar o enter dispara uma consulta */
                $('input[name="search"]').keypress(function(event){
                    
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    
                    if(keycode == '13'){
                        
                        //Envia a solicitação de consulta
                        validateForm('#frmSearch', 'Enviando consulta, aguarde...', '#loadContent');                        
                            
                    }
                                            
                    event.stopPropagation();
                    
                });                
            });

        </script>                  

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
                        <h4>Não foram cadastrados Clientes.</h4>
                    </div>


                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar novo cliente

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