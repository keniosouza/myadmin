<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\UsersAcl;

/** Instânciamento de classes */
$Main = new Main();
$UsersAcl = new UsersAcl();

/** Carrega as configurações de paginação */
$config = $Main->LoadConfigPublic();

/** Parâmetros de paginação **/
$start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
$page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
$max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

/** Consulta a quantidade de registros */
$NumberRecords = $UsersAcl->Count()->qtde;

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
                        
                        <h5 class="card-title">Controle de Acessos</h5>
                    
                    </div>

                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=users_acl&ACTION=users_acl_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo controle de acesso">

                            <i class="fas fa-plus-circle mr-1"></i>Novo

                        </button>

                    </div>
                
                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableUsersAcl" width="100%" cellspacing="0">
                        
                        <thead>
                            <tr >
                                <th class="text-center">Nº</th>
                                <th class="text-center">Descrição</th>
                                <th class="text-center">Responsável</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            
                            <?php  
                            
                                /** Consulta os usuário cadastrados*/
                                $UsersAclResult = $UsersAcl->All($start, $max);
                                foreach($UsersAclResult as $UsersAclKey => $Result){ 
                            ?>
                                
                                <tr>
                                    <td class="text-center" width="60"><?php echo $Main->setZeros($Result->users_acl_id, 3);?></td>
                                    <td class="text-center"><?php echo $Result->description;?></td>
                                    <td class="text-center"><?php echo $Main->decryptData($Result->name_first);?></td>                                   
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=users_acl&ACTION=users_acl_form&users_acl_id=<?php echo $Result->users_acl_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-edit mr-1"></i></button></td>
                                </tr>                                 

                            <?php } ?> 
                                                                
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="4">

                                    <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=users_acl_datagrid&TABLE=users_acl', 'Aguarde'); ?>

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
                    <h4>Não foram cadastrados controles de acessos.</h4>
                </div>

                <div class="col-md-4 text-right">

                    <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=users_acl&ACTION=users_acl_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                        <i class="fas fa-plus-circle mr-1"></i>Cadastrar novo controle de acesso

                    </button>

                </div>

            </div>

        </div>
    </div>

    </div>

<?php } ?>