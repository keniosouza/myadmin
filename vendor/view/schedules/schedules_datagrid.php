<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\Schedules;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes */
    $Main = new Main();
    $Schedules = new Schedules();

    /** Consulta a quantidade de registros */
    $NumberRecords = $Schedules->Count()->qtde;

    /** Cores do card */
    $colors = [ 'success', 'info', 'warning', 'danger', 'secondary'];

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;


    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-8">
                            
                            <h5 class="card-title">Agenda(s)</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=schedules&ACTION=schedules_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova agenda">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableSchedules" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Título</th>
                                    <th class="text-center">Local</th>
                                    <th class="text-center">Responsável</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Situação</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $SchedulesResult = $Schedules->All($start, $max);
                                    foreach($SchedulesResult as $SchedulesKey => $Result){ 
                                ?>
                                    
                                    <tr class="<?php echo $Result->finished > 0 ? 'bg-warning' : '';?> <?php echo $Result->situation == 'A' ? 'text-dark' : '';?>">
                                        <td class="text-center" width="60"><?php echo $Main->setZeros($Result->schedules_id, 3);?></td>
                                        <td class="text-left"><?php echo $Result->title;?></td>
                                        <td class="text-left"><?php echo $Result->local;?></td>
                                        <td class="text-left"><?php echo !empty($Result->name_first) ? $Main->decryptData($Result->name_first) : '';?></td>
                                        <td class="text-center" width="160"><?php echo date("d/m/Y", strtotime($Result->date_scheduling));?></td>                                    
                                        <td class="text-center" width="160"><?php echo $Result->situation == "A" ? "Ativo" : "Finalizado";?></td> 
                                        <td class="text-center" width="20"><button type="button" <?php echo $Result->situation == "F" ? 'disabled' : '';?> class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=schedules&ACTION=schedules_form&schedules_id=<?php echo $Result->schedules_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="far fa-edit mr-1"></i></button></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=schedules&ACTION=schedules_form&schedules_id=<?php echo $Result->schedules_id;?>&finished=S', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-check mr-1"></i></button></td>
                                    </tr>                                 

                                <?php } ?> 
                                                                    
                            </tbody>
                            
                            <tfoot>
                                <tr>
                                    <td colspan="8">

                                        <?php echo $NumberRecords > $max ? $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=schedules_datagrid&TABLE=schedules', 'Aguarde') : ''; ?>

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
                        <h4>Não foram cadastradas agendas.</h4>
                    </div>

                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=schedules&ACTION=schedules_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova agenda

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