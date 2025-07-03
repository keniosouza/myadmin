<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();

        /** Parametros de filtro por company */
        $companyId = isset($_SESSION['USERSCOMPANYID']) ? $_SESSION['USERSCOMPANYID'] : 0;

        /** Parâmetros de paginação **/
        $start = isset($_POST['start']) ? (int)filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $page  = isset($_POST['page'])  ? (int)filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS)  : 0;
        $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

        /** Parametros de consulta */
        $search    = isset($_POST['search'])    ? (string)filter_input(INPUT_POST,'search',  FILTER_SANITIZE_SPECIAL_CHARS)    : '';
        $type      = isset($_POST['type'])      ? (string)filter_input(INPUT_POST,'type',  FILTER_SANITIZE_SPECIAL_CHARS)      : '';
        $status    = isset($_POST['status'])    ? (int)filter_input(INPUT_POST,'status',  FILTER_SANITIZE_NUMBER_INT)          : 0;
        $dateStart = isset($_POST['dateStart']) ? (string)filter_input(INPUT_POST,'dateStart',  FILTER_SANITIZE_SPECIAL_CHARS) : '01/'.date('m/Y');
        $dateEnd   = isset($_POST['dateEnd'])   ? (string)filter_input(INPUT_POST,'dateEnd',  FILTER_SANITIZE_SPECIAL_CHARS)   : date('d/m/Y');
        $days      = 5;/** Dias para novas notificações */
        
        /** Verifica se existe consulta informada para validar os campos */
        
        /** Verifica se a consulta foi informada */
        if( !empty($search) ){

            /** Valida os campos de entrada */
            $FinancialMovementsValidate->setSearch($search);

        }

        /** Verifica se o tipo da consulta foi informada */
        if( !empty($type) ){

            $FinancialMovementsValidate->setType($type);

        }

        /** Verifica se o status da consulta foi informada */
        if( $status > 0 ){

            /** Verifica se o status da consulta foi informada */
            $FinancialMovementsValidate->setStatusSearch($status);

        }


        /** Verifica se a data inicial da consulta foi informada */
        if( !empty($dateStart) ){

            $FinancialMovementsValidate->setDateStart($dateStart);

        }

        /** Verifica se a data final da consulta foi informada */
        if( !empty($dateEnd) ){

            $FinancialMovementsValidate->setDateEnd($dateEnd);

        }


        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } 

        /** Consulta a quantidade de registros */
        $NumberRecords = $FinancialMovements->Count($companyId, $FinancialMovementsValidate->getSearch(), $FinancialMovementsValidate->getType(), $FinancialMovementsValidate->getStatusSearch(), $FinancialMovementsValidate->getDateStart(), $FinancialMovementsValidate->getDateEnd())->qtde;

        /** Verifico a quantidade de registros localizados */
        if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

            ?>
                
            <div class="col-lg-12">

                <div class="card shadow mb-12">
                        
                    <div class="card-header">

                        <div class="row">
                        
                            <div class="col-md-8">
                                
                                <h5 class="card-title">Movimentações Financeiras</h5>
                            
                            </div>

                            <div class="col-md-4 text-right">

                                <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-plus-circle mr-1"></i>Nova Entrada

                                </button>

                                <button type="button" class="btn btn-danger btn-sm" onclick="request('FOLDER=view&TABLE=financial_outputs&ACTION=financial_outputs_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                    <i class="fas fa-minus mr-1"></i>Nova Saída

                                </button>                           

                            </div>
                        
                        </div>

                    </div>

                    <div class="card-body">

                        <form class="row" id="frmSearchFinancialMovements">

                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control w-100" id="search" name="search" value="<?php echo $FinancialMovementsValidate->getSearch();?>" placeholder="Pesquisar..." data-toggle="tooltip" data-placement="top" title="Informe sua consulta" >
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                            </div>

                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="active">Tipo: </label>-->
                                    <select class="form-control form-control w-100" id="type" name="type">
                                        <option value="" selected>Tipo</option>    
                                        <option value="E" <?php echo $FinancialMovementsValidate->getType() == 'E' ? 'selected' : '';?>>Entradas</option>
                                        <option value="S" <?php echo $FinancialMovementsValidate->getType() == 'S' ? 'selected' : '';?>>Saídas</option>                            
                                    </select> 
                                </div>
                            </div> 
                            
                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="status">Pago: </label>-->
                                    <select class="form-control form-control w-100" id="status" name="status">
                                        <option value="" selected>Pago?</option>    
                                        <option value="1" <?php echo $FinancialMovementsValidate->getStatusSearch() == 1 ? 'selected' : '';?>>Não</option>
                                        <option value="2" <?php echo $FinancialMovementsValidate->getStatusSearch() == 2 ? 'selected' : '';?>>Sim</option>
                                    </select> 
                                </div> 
                            </div>                   
                            
                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control date w-100" id="dateStart" name="dateStart" value="<?php echo !empty($FinancialMovementsValidate->getDateStart()) ? date('d/m/Y',strtotime($FinancialMovementsValidate->getDateStart())) : '';?>" placeholder="Data inicial" data-toggle="tooltip" data-placement="top" title="Informe a data inicial">
                                </div> 
                            </div> 
                            
                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control date w-100" id="dateEnd" name="dateEnd" value="<?php echo !empty($FinancialMovementsValidate->getDateEnd()) ? date('d/m/Y',strtotime($FinancialMovementsValidate->getDateEnd())) : date('d/m/Y');?>" placeholder="Data final" data-toggle="tooltip" data-placement="top" title="Informe a data final">
                                </div> 
                            </div>                                     
                            
                            <div class="col-md">
                                <button class="btn btn-primary w-100" type="button" onclick="validateForm('#frmSearchFinancialMovements', 'Consultando por favor aguarde...', '#loadContent', '', true)">Consultar</button>
                            </div>

                            <div class="col-md">
                                <button class="btn btn-info w-100" type="button" onclick="validateForm('#frmSearchFinancialMovements', 'Consultando por favor aguarde...', '', 'S', true)">Relatório</button>
                            </div>  
                            
                            <div class="col-md">
                                <button class="btn btn-warning w-100" type="button" onclick="loadParameters('#tableFinancialMovements', '#loadContent', 'TABLE=financial_movements&FOLDER=view&ACTION=financial_movements_datagrid_notify')">Notificar</button>
                            </div>                             

                            <input type="hidden" name="TABLE" value="financial_movements"/>
                            <input type="hidden" name="ACTION" value="financial_movements_datagrid"/>
                            <input type="hidden" name="FOLDER" value="view" />                                        

                        </form> 

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableFinancialMovements" width="100%" cellspacing="0">
                                
                                <thead>
                                    <tr >
                                        <th class="text-center"><input type="checkbox" id="selectAll" name="selectAll" data-toggle="tooltip" data-placement="top" title="Selecionar todos os itens" /></th>
                                        <th class="text-center">Referência</th>
                                        <th class="text-center">Agendamento</th>
                                        <th class="text-center">Pagamento</th>
                                        <th class="text-center">Cliente</th>
                                        <th class="text-center">Descrição</th>
                                        <th class="text-center">Tipo</th>
                                        <th class="text-center">Valor R$</th>
                                        <th class="text-center">Pago R$</th>
                                        <th class="text-center">Situação</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php  
                                    
                                        /** Consulta as movimentações cadastradas*/
                                        $FinancialMovementsResult = $FinancialMovements->All($companyId, 
                                                                                             $start, 
                                                                                             $max,
                                                                                             $FinancialMovementsValidate->getSearch(), 
                                                                                             $FinancialMovementsValidate->getType(),
                                                                                             $FinancialMovementsValidate->getStatusSearch(), 
                                                                                             $FinancialMovementsValidate->getDateStart(), 
                                                                                             $FinancialMovementsValidate->getDateEnd());                                    
                                        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){ 
                                    ?>
                                        
                                        <tr style="cursor: pointer" class="<?php echo $Result->status == 2 ? 'table-success' : ($Result->status == 3 ? 'text-decoration-line-through' : ($Main->CheckDay($Result->movement_date_scheduled) > 1 ? 'table-warning text-danger' : ''));?>">
                                            <td class="text-center" width="10">


                                                <?php 

                                                    /** Verifica se não existe data de notificação */
                                                    if( (!empty($Result->notification_date)) && ($Result->status == 1) ){

                                                        if( $Main->addDays($Result->notification_date, $days) < strtotime(date('Y-m-d'))){ ?>

                                                        <input type="checkbox" value="<?php echo $Result->financial_movements_id;?>" /> 
                                                <?php 
                                                        } else { ?>

                                                        <i class="fas fa-exclamation" data-toggle="tooltip" data-placement="top" onclick="viewNotify(650, 0, 'Notificação enviada dia <?php echo date('d/m/Y', strtotime($Result->notification_date));?> as <?php echo date('H:i:s', strtotime($Result->notification_date));?>', '<?php echo $Result->message;?>');" title="<?php echo date('d/m/Y', strtotime($Result->notification_date)); ?>"></i>

                                                <?php
                                                        }

                                                    }elseif( ($Result->status == 1) && (strtotime($Result->movement_date_scheduled) < strtotime(date('Y-m-d'))) ) { ?> 
                                                    
                                                        <input type="checkbox" value="<?php echo $Result->financial_movements_id;?>" /> 
                                                    
                                              <?php } elseif( ( ($Result->status == 1 || $Result->status == 2) ) && ($Result->client_budgets_commissions_id > 0) ) { 
                                                    
                                                        /** Mensagem sobre a comissão */
                                                        $message = '<div class="overflow-auto" style="height:400px">';
                                                        $message .= '<form id="frmCommissions">';
                                                        
                                                        $message .= '<table class="table table-bordered table-striped table-hover bg-white shadow-sm table-sm">';

                                                        $message .= ' <thead>';

                                                        $message .= ' <tr>';
                                                        $message .= '   <th colspan="2">Detalhes da movimentação</th>';
                                                        $message .= ' </tr>';                                                    
                                                        
                                                        $message .= ' </thead>';
                                                        $message .= ' <tbody>';
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td width="130px">Referência</td>';
                                                        $message .= '   <td>'.$Result->movement_reference.'</td>';
                                                        $message .= ' </tr>';

                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Vencimento</td>';
                                                        $message .= '   <td>'.( isset($Result->movement_date_scheduled) ? date('d/m/Y', strtotime($Result->movement_date_scheduled)) : null).'</td>';
                                                        $message .= ' </tr>'; 

                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Pago</td>';
                                                        $message .= '   <td>'.( isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : null).'</td>';
                                                        $message .= ' </tr>';                                                     
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Cliente</td>';
                                                        $message .= '   <td>'.$Result->reference.' - '.$Result->fantasy_name.'</td>';
                                                        $message .= ' </tr>';   
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Descrição</td>';
                                                        $message .= '   <td>'.$Result->description.'</td>';
                                                        $message .= ' </tr>'; 

                                                        $message .= ' </tbody>';                                                    

                                                        $message .= '</table>';

                                                        $message .= '<table class="table table-bordered table-striped table-hover bg-white shadow-sm table-sm">';

                                                        $message .= ' <thead>';

                                                        $message .= ' <tr>';
                                                        $message .= '   <th colspan="2">Detalhes para pagamento</th>';
                                                        $message .= ' </tr>';                                                    
                                                        
                                                        $message .= ' </thead>';
                                                        $message .= ' <tbody>';
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td width="130px">Valor R$</td>';
                                                        $message .= '   <td>'.number_format($Result->movement_value, 2, ',', '.').'</td>';
                                                        $message .= ' </tr>';

                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Pago R$</td>';
                                                        $message .= '   <td>'.number_format($Result->movement_value_paid, 2, ',', '.').'</td>';
                                                        $message .= ' </tr>'; 

                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Percentual %</td>';
                                                        $message .= '   <td>'.number_format($Result->value, 2, ',', '.').'</td>';
                                                        $message .= ' </tr>';                                                     
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Comissão R$</td>';
                                                        $message .= '   <td><input type="text" class="form-control form-control price" maxlength="60" id="commission_value_paid" name="commission_value_paid" value="'.number_format( ($Result->movement_value_paid / 100 * $Result->value), 2, ',', '.').'"></td>';
                                                        $message .= ' </tr>'; 
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Previsão</td>';
                                                        $message .= '   <td><input type="text" class="form-control form-control date" maxlength="60" id="commission_date_paid" name="commission_date_paid" value="'.( isset($Result->movement_date_paid) ? date("d/m/Y", mktime(0,0,0, (date('m', strtotime($Result->movement_date_paid))+1), date('d', strtotime($Result->movement_date_paid)), date('Y', strtotime($Result->movement_date_paid)))) : '').'"></td>';
                                                        $message .= ' </tr>';                                                     
                                                        
                                                        $message .= ' <tr>';
                                                        $message .= '   <td>Colaborador</td>';
                                                        $message .= '   <td>'.$Main->decryptData($Result->name_first).' '.$Main->decryptData($Result->name_last).'</td>';
                                                        $message .= ' </tr>'; 

                                                        $message .= ' </tbody>';                                                    

                                                        $message .= '</table>';
                                                        
                                                        if($Result->commission_date_paid === NULL){

                                                            $message .= '<input type="hidden" name="TABLE" value="clients_budgets"/>';
                                                            $message .= '<input type="hidden" name="ACTION" value="clients_budgets_commissions_confim_payment"/>';
                                                            $message .= '<input type="hidden" name="FOLDER" value="action" />';
                                                            $message .= '<input type="hidden" name="clients_budgets_commissions_id" value="'.$Result->client_budgets_commissions_id.'" />';

                                                            $message .= '<div class="col-sm-12">';
                                                            $message .= '   <button type="button" class="btn btn-primary btn-user btn-block mb-0" onclick="sendForm(\'#frmCommissions\', \'\', true, \'\', 0, \'#sendMovement\', \'Confirmando pagamento da comissão\', \'random\', \'circle\', \'sm\', true)">';
                                                            $message .= '       <i class="fa fa-check"></i> Confirmar Pagamento';
                                                            $message .= '    </button>';
                                                            $message .= '</div>';

                                                            $message .= '<div class="col-sm-12 text-center p-4" id="sendMovement"><br/></div>';

                                                        }


                                                        $message .= '</form>';
                                                        $message .= '<script type="text/javascript">$(document).ready(function(e) {loadMask(); $(\'#view-notify\').scrollTop(250) });</script>';
                                                        $message .= '</div>';


                                                ?> 

                                                <button class="btn btn-light" onclick="viewNotify(650, 450, 'Gerenciar comissão / <?php echo $Main->decryptData($Result->name_first);?>', '<?php echo base64_encode($message);?>', '', true);" data-toggle="tooltip" data-placement="top" title="<?php echo $Main->decryptData($Result->name_first).' '.$Main->decryptData($Result->name_last);?>"><i class="fas fa-user"></i></button>
                                                    
                                                                                                            
                                            <?php } ?>
                                            
                                            
                                            </td>                                            
                                            <td class="text-center" width="120"><?php echo $Result->movement_reference;?></td>   
                                            <td class="text-center" width="90"><?php echo date('d/m/Y', strtotime($Result->movement_date_scheduled)); ?></td>
                                            <td class="text-center" width="90"><?php echo isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : ((int)$Result->status == 3 ? 'Cancelado' :  ($Main->CheckDay($Result->movement_date_scheduled) > 1 ? $Main->diffDate($Result->movement_date_scheduled, date('Y-m-d')).' dia(s) de atraso' : '') );?></td>                                 
                                            <td class="text-left"><?php echo $Result->reference;?> - <?php echo $Result->fantasy_name;?></td>
                                            <td class="text-left"><?php echo $Result->description;?></td>
                                            <td class="text-center" width="90"><?php echo (int)$Result->financial_entries_id > 0 ? ($Result->status == 2 || $Result->status == 1 ? '<span class="badge badge-success">Entrada</span>' : 'Entrada') : ($Result->status == 2 ? 'Saída' : '<span class="badge badge-danger">Saída</span>');?></td>
                                            <td class="text-right" width="90"><?php echo number_format($Result->movement_value, 2, ',', '.');?></td>
                                            <td class="text-right" width="90"><?php echo number_format($Result->movement_value_paid, 2, ',', '.');?></td>
                                            <td class="text-center" width="90">
                                            <?php

                                                /** Verifica se o status é o 2=> Concluído */
                                                if((int)$Result->status == 2){

                                                    echo '<i class="fas fa-check"></i>';

                                                }

                                                /** Verifica se o status é o 3=> Cancelado */
                                                elseif((int)$Result->status == 3){

                                                    echo '<i class="far fa-frown"></i>';

                                                }

                                                /** Verifica o status atual e verifica se o agendamento não esta vencido */
                                                elseif( ((int)$Result->status == 1) && ($Main->CheckDay($Result->movement_date_scheduled) > 1) )  {

                                                    /** Verifica se não existe a data de pagamento do agendamento */
                                                    if( empty($Result->movement_date_paid) ){

                                                        echo '<i class="fas fa-exclamation-triangle"></i>';

                                                    }

                                                }

                                                /** Caso o item ainda esteja na fase inicial e não tenha atrasos, informo que o mesmo esta em andamento */
                                                else{

                                                    echo '<i class="fas fa-walking"></i>';
                                                }
                                                

                                            ?>

                                            </td>


                                            <!--<td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=financial_movements&ACTION=financial_movements_form&financial_movements_id=<?php echo $Result->financial_movements_id;?>', '#loadContent', true, '', '', '', 'Carregando movimentação', 'blue', 'circle', 'sm', true)"><i class="fa fa-cog mr-1"></i></button></td>-->
                                            <td class="text-center" width="20">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-cog mr-1"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="#" onclick="request('FOLDER=view&TABLE=financial_movements&ACTION=financial_movements_form&financial_movements_id=<?php echo $Result->financial_movements_id;?>', '#loadContent', true, '', '', '', 'Carregando movimentação', 'blue', 'circle', 'sm', true)"><i class="fas fa-check-square"></i> Gerenciar</a>
                                                        <a class="dropdown-item" href="#" onclick="request('FOLDER=view&TABLE=financial_movements&ACTION=financial_movements_form_upload&financial_movements_id=<?php echo $Result->financial_movements_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-file-upload"></i> Postar Arquivo</a> 
                                                        <a class="dropdown-item" href="#" onclick="request('FOLDER=view&TABLE=financial_movements&ACTION=financial_movements_ticket_view&financial_movements_id=<?php echo $Result->financial_movements_id;?>', '', true, '', '', '', 'Carregando boleto gerado', 'blue', 'circle', 'sm', true)"><i class="fas fa-barcode"></i> Visualizar Boleto</a>                                                   
                                                        <a class="dropdown-item" href="#" onclick="request('FOLDER=view&TABLE=financial_movements&ACTION=financial_movements_ticket_consult&reference=<?php echo $Result->movement_reference;?>', '', true, '', '', '', 'Consultando boleto/Sicoob', 'blue', 'circle', 'sm', true)"><i class="fas fa-barcode"></i> Consultar Boletos Sicoob</a>
                                                    </div>
                                                </div>                                            
                                            </td>
                                        </tr>                                 

                                    <?php } ?> 
                                                                        
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="11">

                                            <?php echo $NumberRecords > $max ? $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=financial_movements_datagrid&TABLE=financial_movements', 'Aguarde', '#frmSearchFinancialMovements') : ''; ?>

                                        </td>
                                    </tr>
                                </tfoot>                              

                            </table>

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
                    $('#search').focus();  
                                        
                    /** Ao pressionar enter no compa de consulta, dispara a mesma */
                    $('#search').keypress(function(event){

                        /** Oculta o tooltip */
                        $('[data-toggle="tooltip"]').tooltip('hide');                        
                            
                        var keycode = (event.keyCode ? event.keyCode : event.which);
                        
                        if(keycode == '13'){
                            
                            //Envia a solicitação de consulta
                            validateForm('#frmSearchFinancialMovements', 'Consultando por favor aguarde...', '#loadContent', '', true)                               
                        }                                            
                        event.stopPropagation();                        
                    });	
                    
                    /** Ao pressionar enter no compa de consulta, dispara a mesma */
                    $('#dateEnd').keypress(function(event){

                        /** Oculta o tooltip */
                        $('[data-toggle="tooltip"]').tooltip('hide');                          
                            
                        var keycode = (event.keyCode ? event.keyCode : event.which);
                        
                        if(keycode == '13'){
                            
                            //Envia a solicitação de consulta
                            validateForm('#frmSearchFinancialMovements', 'Consultando por favor aguarde...', '', 'S', true)                                
                        }                                            
                        event.stopPropagation();                        
                    });	
                    
                    /** Ao pressionar enter no compa de consulta, dispara a mesma */
                    $('#selectAll').click(function(event){                    
                        
                        /** Remove o tooltip do item */
                        $('[data-toggle="tooltip"]').tooltip('hide');
                        checkAll('#tableFinancialMovements', '#selectAll');
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
                                <h4>Não foram localizada entradas.</h4>
                            </div>
            
                            <div class="col-md-4 text-right">
            
                                <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
            
                                    <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova entrada
            
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

}catch(Exception $exception){

/** Preparo o formulario para retorno **/
$result = [

    'cod' => 500,
    'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
    'title' => 'Atenção',
    'type' => 'exception',
	'authenticate' => $authenticate

];

/** Envio **/
echo json_encode($result);

/** Paro o procedimento **/
exit;
}    