<?php

/** Importação de classes  */
use vendor\model\Schedules;
use vendor\model\SchedulesFiles;
use vendor\model\Users;
use vendor\model\Clients;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes  */
    $Schedules      = new Schedules();
    $SchedulesFiles = new SchedulesFiles();
    $Users          = new Users();
    $Clients        = new Clients();

    /** Parametros de entrada */
    $schedulesId = isset($_POST['schedules_id']) ? $Main->antiInjection($_POST['schedules_id']) : 0;
    $finished    = isset($_POST['finished'])     ? $Main->antiInjection($_POST['finished'])     : 'N';

    /** Verifica se o ID do projeto foi informado */
    if($schedulesId > 0){

        /** Consulta os dados do controle de acesso */
        $SchedulesResult = $Schedules->Get($schedulesId);

    }else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

        /** Carrega os campos da tabela */
        $SchedulesResult = $Schedules->Describe();

    }

    /** Controles  */
    $err = 0;
    $msg = "";

    try{

    ?>

        <div class="col-lg-12">


            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-8">
                            
                            <h5 class="card-title"><?php echo $finished == 'S' ? 'Finalizar agendamento' : ($schedulesId > 0 ? 'Editando dados do agendamento' : 'Cadastrar novo agendamento');?></h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=schedules&ACTION=schedules_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo agendamento">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=schedules&ACTION=schedules_datagrid', '#loadContent', true, '', '', '', 'Carregando agendamentos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar agendamentos cadastrados">

                                <i class="fas fa-plus-circle mr-1"></i>Agendamentos Cadastrados

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmSchedules" autocomplete="off">

                        <div class="form-group row">

                            <div class="col-sm-12 mb-2">                            

                            
                                <label for="cliente_id">Cliente:</label>

                                <select class="form-control form-control" id="clients_id" <?php echo $finished == "S" ? 'disabled' : '';?> name="clients_id">

                                    <option value="" selected>Selecione</option>
                                    <?php

                                        $ClientsResult = $Clients->All(0, 0, 0, 0, null);
                                        foreach($ClientsResult as $ClientsKey => $Result){
                                    ?>
                                        
                                        <option value="<?php echo $Result->clients_id;?>" <?php echo $Result->clients_id === $SchedulesResult->clients_id ? 'selected' : '';?>><?php echo $Result->client_name;?></option>

                                    <?php } ?>

                                </select>


                            </div>

                        </div>

                        <div class="form-group row">
                            
                            <div class="col-sm-4 mb-2">

                                <label for="description">Título:</label>
                                <input type="text" class="form-control form-control" maxlength="160" id="title" <?php echo $finished == "S" ? 'disabled' : '';?> name="title" value="<?php echo $SchedulesResult->title;?>" placeholder="Informe o título">
                            </div>

                            <div class="col-sm-4 mb-2">

                                <label for="local">Local:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="local" <?php echo $finished == "S" ? 'disabled' : '';?> name="local" value="<?php echo $SchedulesResult->local;?>" placeholder="Informe o local">
                            </div> 
                            
                            <div class="col-sm-2 mb-2">

                                <label for="date_scheduling">Data:</label>
                                <input type="text" class="form-control form-control date" maxlength="30" id="date_scheduling" <?php echo $finished == "S" ? 'disabled' : '';?> name="date_scheduling" value="<?php echo isset($SchedulesResult->date_scheduling) ? date('d/m/Y', strtotime($SchedulesResult->date_scheduling)) : '';?>" placeholder="Informe a data">
                            </div> 
                            
                            <div class="col-sm-2 mb-2">

                                <label for="hour_scheduling">Hora:</label>
                                <input type="text" class="form-control form-control hour" maxlength="30" id="hour_scheduling" <?php echo $finished == "S" ? 'disabled' : '';?> name="hour_scheduling" value="<?php echo $SchedulesResult->hour_scheduling;?>" placeholder="Informe a hora">
                            </div>                          

                        </div>
                        
                        <div class="form-group row">
                            
                            <div class="col-sm-12 mb-2">

                                <label for="description">Descrição:</label>
                                <textarea id="description" <?php echo $finished == "S" ? 'disabled' : '';?> name="description" class="form-control form-control" rows="5"  placeholder="Informe a descrição"><?php echo $SchedulesResult->description;?></textarea>
                            </div>
                        
                        </div>
                        
                        <div class="form-group row">

                            <div class="col-sm-12 mb-2">

                                <label for="selectFiles">Arquivos: <span class="text-danger">* Tamanho máximo do arquivo 5mb</span></label>
                                <input type="file" id="selectFiles" class="upload filestyle" accept="application/pdf" />
                                <div id="preview"></div>
                                <div id="results" class="row"></div>

                            </div>

                            

                            <?php
                                /** Verifica se a agenda possui anexos */
                                if($SchedulesFiles->Count($SchedulesResult->schedules_id) > 0){ ?>

                                    <div class="col-sm-12 mb-2"><h5>Arquivos do agendamento</h5></div>

                                <?php 


                                    /** Lista os anexos */
                                    $SchedulesFilesResult = $SchedulesFiles->All($SchedulesResult->schedules_id);
                                    foreach($SchedulesFilesResult as $SchedulesFilesKey => $Result){ ?>

                                        <!-- Lista os arquivos caso existam de agendamento -->
                                        <div class="col-sm-4 mb-2 d-flex">
                                            
                                            <div class="card w-100">
                                                <div class="card-body">
                                                    <h6 class="card-title"><?php echo $Result->name;?></h6>                                                
                                                </div>
                                                <div class="card-footer text-right">
                                                    <a href="#" class="btn btn-secondary btn-sm" onclick="request('FOLDER=action&ACTION=schedules_files_download&TABLE=schedules_files&schedules_files_id=<?php echo $Result->schedules_files_id;?>', '', true, 0, 0, '', 'Efetuando download do arquivo', 'random', 'circle', 'sm', true)"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download</a>
                                                </div>
                                            </div>                                    
                                                                            
                                        </div>

                                <?php

                                    }
                                    
                                }

                            ?>

                            

                        </div>

                        <?php
                            /** Verifica se é uma finalização de agendamento */
                            if($finished == 'S'){

                        ?>  

                        <div class="form-group row">
                            
                            <div class="col-sm-12 mb-2">

                                <label for="note"><b>Observação:</b> <span class="text-danger">* Obrigatório</span></label>
                                <textarea id="note" name="note" class="form-control form-control" rows="5" style="background-color:#FFF0E1"  placeholder="Informe uma observação para finalizar"><?php echo $SchedulesResult->note;?></textarea>
                            </div>
                        
                        </div> 
                        
                        <?php } ?>

                        <div class="form-group row">

                            <div class="col-sm-4">
                                
                                <label for="users_responsible_id">Responsável:</label>

                                <select class="form-control form-control" id="users_responsible_id" <?php echo $finished == "S" ? 'disabled' : '';?> name="users_responsible_id">

                                    <option value="" selected>Selecione</option>
                                    <?php

                                        $UsersResult = $Users->All(0, 0, $_SESSION['USERSCOMPANYID'], 0);
                                        foreach($UsersResult as $UsersKey => $Result){
                                    ?>
                                        
                                        <option value="<?php echo $Result->users_id;?>" <?php echo $Result->users_id === $SchedulesResult->users_responsible_id ? 'selected' : '';?>><?php echo $Main->decryptData($Result->name_first);?> <?php echo $Main->decryptData($Result->name_last);?></option>
    
                                    <?php } ?>

                                </select>

                            </div>
                            

                            <?php 

                                /** Verifica se o agendamento foi finalizado */                
                                if($SchedulesResult->users_finished_id == 0){
                            ?>
                            
                                <div class="col-sm-6">
                                    
                                    <label for="btn-save"></label>
                                    <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmSchedules', '', true, '', 0, '', '<?php echo $finished == 'S' ? 'Finalizar agendamento' : ($schedulesId > 0 ? 'Atualizando agendamento' : 'Cadastrando novo agendamento');?>', 'random', 'circle', 'sm', true)"> <?php echo $finished == 'S' ? '<i class="fas fa-check"></i>' :  '<i class="far fa-save"></i>';?> <?php echo $finished == 'S' ? 'Finalizar agendamento' : ((int)$schedulesId > 0 ? 'Salvar alterações do agendamento' : 'Cadastrar novo agendamento') ?></a>                               
                                </div>

                            <?php }else{ ?>

                                <div class="col-sm-4 mb-2">

                                    <label for="user_finished">Responsável por finalizar:</label>
                                    <input type="text" class="form-control form-control" maxlength="120" id="user_finished" <?php echo $finished == "S" ? 'disabled' : '';?> name="user_finished" value="<?php echo $SchedulesResult->user_finished_name_first .' '.$SchedulesResult->user_finished_name_last;?>" >
                                </div> 

                                <div class="col-sm-2 mb-2">

                                    <label for="date_finished">Data finalização:</label>
                                    <input type="text" class="form-control form-control date" maxlength="30" id="date_finished" disabled name="date_finished" value="<?php echo isset($SchedulesResult->date_finished) ? date('d/m/Y', strtotime($SchedulesResult->date_finished)) : '';?>">
                                </div> 

                                <div class="col-sm-2 mb-2">

                                    <label for="hour_finished">Hora finalização:</label>
                                    <input type="text" class="form-control form-control hour" maxlength="30" id="hour_finished" disabled name="hour_finished" value="<?php echo isset($SchedulesResult->date_finished) ? date('H:i', strtotime($SchedulesResult->date_finished)) : '';?>">
                                </div>                             

                            <?php } ?>
                            
                            <?php

                                if($finished == 'S'){
                            ?>
                            
                                <div class="col-sm-12">
                                    
                                    <label for="btn-save"></label>
                                    <a href="#" class="btn btn-info btn-user btn-block" id="btn-save" onclick="" data-toggle="tooltip" data-placement="top" title="Gerar novo agendamento a partir do atual"><i class="fas fa-redo-alt"></i> Gerar novo agendamento</a>                               
                                </div> 
                            
                            <?php } ?>
                            
                        </div>


                        <input type="hidden" name="TABLE" value="schedules" />
                        <input type="hidden" name="ACTION" value="schedules_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="schedules_id" value="<?php echo $schedulesId;?>" />
                        <input type="hidden" name="finished" value="<?php echo $finished;?>" />


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
            
            /** Upload de arquivos */
            uploadFiles('action', 'schedules_files', 'schedules_files_upload');

        });

        </script>

    <?php

    }catch(Exception $exception){

        /** Prepara a div com a informação de erro */
        $div  = '<div class="col-lg-12">';
        $div .= '   <div class="card shadow mb-12">';
        $div .= '       <div class="card-header py-3">';
        $div .= '           <h6 class="m-0 font-weight-bold text-primary">Erro(s) encontrados.</h6>';
        $div .= '       </div>';
        $div .= '       <div class="card-body">';
        $div .= '           <p>' . $exception->getFile().'<br/>'.$exception->getMessage().'</p>';
        $div .= '       </div>';
        $div .= '   </div>';
        $div .= '</div>';

        /** Preparo o formulario para retorno **/
        $result = [

            'cod' => 0,
            'data' => $div,
            'title' => 'Erro Interno',
            'type' => 'exception',

        ];

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
        exit;
    }

/** Caso o token de acesso seja inválido, informo */
}else{
	
	/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
	$authenticate = true;		

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}    