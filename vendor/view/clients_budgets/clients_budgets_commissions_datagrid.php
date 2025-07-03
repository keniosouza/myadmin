<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\model\ClientBudgetsCommissions;
use vendor\controller\client_budgets\ClientBudgetsCommissionsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    
        
        /** Pega o ano atual e o mês atual subtraindo um mês para carregar as comissões a serem pagas */
        $ano = date('Y');
        $mes = date('m')-1;        

        /** Instânciamento de classes  */
        $Users = new Users();
        $ClientBudgetsCommissions = new ClientBudgetsCommissions(); 
        $ClientBudgetsCommissionsValidate = new ClientBudgetsCommissionsValidate();  
        
        /** Parametros de filtro por company */
        $companyId = isset($_SESSION['USERSCOMPANYID']) ? $_SESSION['USERSCOMPANYID'] : 0;        

        /** Parametros de entrada */
        $clientsId = isset($_POST['clients_id']) ? (int)$Main->antiInjection(filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)) : 0;

        /** Parametros de consulta */
        $usersId   = isset($_POST['users_id'])  ? (int)filter_input(INPUT_POST,'users_id',  FILTER_SANITIZE_NUMBER_INT)        : 0;
        $dateStart = isset($_POST['dateStart']) ? (string)filter_input(INPUT_POST,'dateStart',  FILTER_SANITIZE_SPECIAL_CHARS) : date("d/m/Y", strtotime("$ano-$mes-01"));
        $dateEnd   = isset($_POST['dateEnd'])   ? (string)filter_input(INPUT_POST,'dateEnd',  FILTER_SANITIZE_SPECIAL_CHARS)   : date("t/m/Y", strtotime("$ano-$mes-01"));        

        /** Parâmetros de paginação **/
        $start = isset($_POST['start']) ? (int)filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $page  = isset($_POST['page'])  ? (int)filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS)  : 0;
        $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;        

        /** Controles */
        $total = null;

        /** Verifica se o cliente foi informado */
        if($clientsId > 0){

            $ClientBudgetsCommissionsValidate->setClientsId($clientsId);
        }

        /** Verifica se o usuario foi informado */
        if($usersId > 0){

            $ClientBudgetsCommissionsValidate->setUsersId($usersId);
        }        

        /** Verifica se a data inicial da consulta foi informada */
        if( !empty($dateStart) ){

            $ClientBudgetsCommissionsValidate->setDateStart($dateStart);

        }

        /** Verifica se a data final da consulta foi informada */
        if( !empty($dateEnd) ){

            $ClientBudgetsCommissionsValidate->setDateEnd($dateEnd);

        }


        /** Verifica se não existem erros a serem informados */
        if (!empty($ClientBudgetsCommissionsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsCommissionsValidate->getErrors(), 0);        

        }         

        /** Conta a quantidade de registros */
        $NumberRecords = $ClientBudgetsCommissions->Count($ClientBudgetsCommissionsValidate->getClientsId(), 
                                                          $ClientBudgetsCommissionsValidate->getUsersId(),
                                                          $ClientBudgetsCommissionsValidate->getDateStart(),
                                                          $ClientBudgetsCommissionsValidate->getDateEnd());

        /** Verifica se existem orçamentos a serem listados */
        if($NumberRecords > 0){
    ?>

            <div class="col-lg-12 mb-4">  
                
                <div class="card shadow mb-12">
                        
                    <div class="card-header">          

                        <div class="row">
                            <div class="col-md-9 mb-2">

                                <h4>Comissões</h4>

                            </div>                          
                        </div>

                    </div>    

                    <div class="card-body">  
                        
                    
                        <form class="row" id="frmSearchCommissions">


                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="active">Tipo: </label>-->
                                    <select class="form-control form-control" id="users_id " name="users_id">
                                        <option value="" selected>Selecione</option>

                                        <?php 
                                            $UsersResult = $Users->All(0 ,0, $companyId, null);
                                            foreach($UsersResult as $UsersKey => $Result){ 
                                        ?>
                                        <option value="<?php echo $Result->users_id;?>" <?php echo $ClientBudgetsCommissionsValidate->getUsersId() == $Result->users_id ? 'selected' : '' ;?> ><?php echo $Main->decryptData($Result->name_first);?> <?php echo $Main->decryptData($Result->name_last);?></option>

                                        <?php } ?>
                                    </select> 
                                </div>
                            </div>                                              
                            
                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control date w-100" id="dateStart" name="dateStart" data-required="S" value="<?php echo !empty($ClientBudgetsCommissionsValidate->getDateStart()) ? date('d/m/Y',strtotime($ClientBudgetsCommissionsValidate->getDateStart())) : '';?>" placeholder="Data inicial" data-toggle="tooltip" data-placement="top" title="Informe a data inicial">
                                </div> 
                            </div> 
                            
                            <div class="col-md">
                                <div class="form-group">
                                    <!--<label for="fantasy_name">Pesquisa: </label>-->
                                    <input type="text" class="form-control date w-100" id="dateEnd" name="dateEnd" data-required="S" value="<?php echo !empty($ClientBudgetsCommissionsValidate->getDateEnd()) ? date('d/m/Y',strtotime($ClientBudgetsCommissionsValidate->getDateEnd())) : '';?>" placeholder="Data final" data-toggle="tooltip" data-placement="top" title="Informe a data final">
                                </div> 
                            </div>  
                            
                            <div class="col-md">
                                <button class="btn btn-success w-100" type="button" onclick="validateForm('#frmSearchCommissions', 'Consultando por favor aguarde...', '#loadContent', '', true)"> <i class="fas fa-search mr-1"></i> Consultar </button>
                            </div>  
                            
                            <div class="col-md">
                                <button class="btn btn-primary w-100" type="button" onclick="validateForm('#frmSearchCommissions', 'Consultando por favor aguarde...', '', 'S', true)"> <i class="fas fa-print mr-1"></i> Relatório</button>
                            </div>    
                            
                            <div class="col-md">
                                
                                <?php

                                    $form  = '<form id="frmCommissionsConfirm">';
                                    $form .= '  <div class="form-group">';
                                    $form .= '      <input type="text" class="form-control form-control date" maxlength="12" id="commission_date_paid" name="commission_date_paid">';
                                    $form .= '  </div>';                                    

                                    $form .= '  <input type="hidden" name="TABLE" value="clients_budgets"/>';
                                    $form .= '  <input type="hidden" name="ACTION" value="clients_budgets_commissions_confim_payment"/>';
                                    $form .= '  <input type="hidden" name="FOLDER" value="action" />';                                    

                                    $form .= '  <div class="form-group">';
                                    $form .= '      <button type="button" class="btn btn-primary btn-user btn-block mb-0" onclick="sendForm(\'#frmCommissionsConfirm\', \'\', true, \'\', 0, \'#sendMessage\', \'Confirmando pagamento da comissão\', \'random\', \'circle\', \'sm\', true)">';
                                    $form .= '          <i class="fa fa-check"></i> Confirmar Pagamento';
                                    $form .= '      </button>';
                                    $form .= '  </div>';

                                    $form .= '  <div class="col-sm-12 text-center p-4" id="sendMessage"><br/></div>';

                                    $form .= '</form>';
                                    $form .= '<script type="text/javascript">$(document).ready(function(e) {loadMask(); $(\'#commission_date_paid\').focus() });</script>';

                                ?>

                                <button class="btn btn-info w-100" type="button" onclick="viewNotify(450, 90, 'Informe a data de pagamento', '<?php echo base64_encode($form);?>', null, true, true, '#tblCommission', '#sendMessage', '#frmCommissionsConfirm')"> <i class="fas fa-check mr-1"></i> Confirmar </button>
                            </div>                              

                            <input type="hidden" name="TABLE" value="clients_budgets"/>
                            <input type="hidden" name="ACTION" value="clients_budgets_commissions_datagrid"/>
                            <input type="hidden" name="FOLDER" value="view" />                                        

                        </form>                     

                        <table id="tblCommission" class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm mb-4">

                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="selectAll" name="selectAll" data-toggle="tooltip" data-placement="top" title="Selecionar todos os itens" /></th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Agend.</th>
                                    <th class="text-center">Pag.</th>
                                    <th class="text-center">Descrição</th>                                    
                                    <th class="text-center">Valor R$</th>
                                    <th class="text-center">%</th>
                                    <th class="text-center">Comissão R$</th>
                                    <th class="text-center">Colaborador</th>
                                    <th class="text-center">Previsão</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                    $ClientBudgetsCommissionsResult = $ClientBudgetsCommissions->All($start, 
                                                                                                      $max, 
                                                                                                      $ClientBudgetsCommissionsValidate->getClientsId(), 
                                                                                                      $ClientBudgetsCommissionsValidate->getUsersId(),
                                                                                                      $ClientBudgetsCommissionsValidate->getDateStart(),
                                                                                                      $ClientBudgetsCommissionsValidate->getDateEnd());
                                    foreach($ClientBudgetsCommissionsResult as $ClientsKey => $Result){ 

                                        $total += ($Result->movement_value / 100 * $Result->value);
                                ?>
                

                                <tr class=" <?php  echo  $Result->commission_date_paid != NULL ? 'table-success' : '';?> ">
                                    <td class="text-center" width="30"><?php echo ( ($Result->commission_date_paid === NULL) && ($Result->movement_date_paid != NULL) ) ? '<input type="checkbox" name="client_budgets_commissions_id[]" value="'.$Result->client_budgets_commissions_id.'" />' : ($Result->commission_date_paid != NULL ? '<i class="fas fa-check" data-toggle="tooltip" data-placement="top" ></i>' : '');?></td>
                                    <td class="text-left"><?php echo $Result->reference;?> - <?php echo $Result->fantasy_name;?></td>
                                    <td class="text-center" width="70"><?php echo isset($Result->movement_date_scheduled) ? date('d/m/Y', strtotime($Result->movement_date_scheduled)) : '';?></td>
                                    <td class="text-center" width="70"><?php echo isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : '';?></td>
                                    <td><?php echo $Result->description;?></td>                                    
                                    <td class="text-right"  width="160"><?php echo number_format($Result->movement_value, 2, ',', '.');?></td>
                                    <td class="text-right"  width="30"><?php echo number_format($Result->value, 2, ',', '.');?></td>
                                    <td class="text-right"  width="160"><?php echo $Result->commission_value_paid != NULL ? number_format($Result->commission_value_paid, 2, ',', '.') : number_format( ($Result->movement_value / 100 * $Result->value), 2, ',', '.');?></td>
                                    <td class="text-center" width="70"><?php echo $Main->decryptData($Result->name_first);?></td>                                
                                    <td class="text-center" width="70"><?php echo isset($Result->movement_date_paid) ? date("d/m/Y", mktime(0,0,0, (date('m', strtotime($Result->movement_date_paid))+1), date('d', strtotime($Result->movement_date_paid)), date('Y', strtotime($Result->movement_date_paid)))) : '';?></td> 
                                    <td class="text-center" width="10">
                                        <?php

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
                                            $message .= '   <td>'.date('d/m/Y', strtotime($Result->movement_date_scheduled)).'</td>';
                                            $message .= ' </tr>'; 

                                            $message .= ' <tr>';
                                            $message .= '   <td>Pago</td>';
                                            $message .= '   <td>'.date('d/m/Y', strtotime($Result->movement_date_paid)).'</td>';
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

                                            // $message .= ' <tr>';
                                            // $message .= '   <td>Pago R$</td>';
                                            // $message .= '   <td>'.number_format($Result->movement_value, 2, ',', '.').'</td>';
                                            // $message .= ' </tr>'; 

                                            $message .= ' <tr>';
                                            $message .= '   <td>Percentual %</td>';
                                            $message .= '   <td>'.number_format($Result->value, 2, ',', '.').'</td>';
                                            $message .= ' </tr>';                                                     
                                            
                                            $message .= ' <tr>';
                                            $message .= '   <td>Comissão R$</td>';
                                            $message .= '   <td><input type="text" class="form-control form-control price" maxlength="60" id="commission_value_paid" name="commission_value_paid" value="'.number_format( ($Result->movement_value / 100 * $Result->value), 2, ',', '.').'"></td>';
                                            $message .= ' </tr>'; 
                                            
                                            $message .= ' <tr>';
                                            $message .= '   <td>Previsão</td>';
                                            $message .= '   <td><input type="text" class="form-control form-control date" maxlength="60" id="commission_date_paid" name="commission_date_paid" value="'.date("d/m/Y", mktime(0,0,0, (date('m', strtotime($Result->movement_date_paid))+1), date('d', strtotime($Result->movement_date_paid)), date('Y', strtotime($Result->movement_date_paid)))).'"></td>';
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

                                        <button class="btn btn-light" onclick="viewNotify(650, 350, '<?php echo $Result->commission_date_paid === NULL ? 'Previsão de comissão' : 'Pagamento efetuado' ;?>', '<?php echo base64_encode($message);?>', '', true);" data-toggle="tooltip" data-placement="top" title="<?php echo $Result->commission_date_paid === NULL ? 'Previsão' : 'Pago' ;?> - <?php echo $Result->commission_date_paid === NULL ? (isset($Result->movement_date_paid) ? date("d/m/Y", mktime(0,0,0, (date('m', strtotime($Result->movement_date_paid))+1), date('d', strtotime($Result->movement_date_paid)), date('Y', strtotime($Result->movement_date_paid)))) : '') : date('d/m/Y', strtotime($Result->commission_date_paid));?>"><i class="fas fa-eye"></i></button>

                                    </td>
                                </tr>

                                <?php } ?>
                            </tbody>

                            <?php
                                /** Verifica se o cliente foi informado */
                                if($ClientBudgetsCommissionsValidate->getClientsId()){

                            ?>
                            
                                    <tfoot>
                                        <tr>
                                            <td colspan="10" align="right"><?php echo number_format($total, 2, ',', '.');?></td>
                                        </tr>
                                    </tfoot>

                            <?php } else { ?>

                                <tfoot>
                                    <tr>
                                        <td colspan="10">

                                            <?php echo $NumberRecords > $max ? $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_commissions_datagrid', 'Aguarde', '#frmSearchCommissions') : ''; ?>

                                        </td>
                                    </tr>
                                </tfoot>     
                                
                            <?php } ?>

                        </table>

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
                        checkAll('#tblCommission', '#selectAll');
                    });
                    
                });

            </script>              

<?php

        }else{ 

            /** Informo */
            throw new InvalidArgumentException('Não há comissões cadastradas. Clique sobre o orçamento desejado para gerar as comissões', 0);             
        }

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
        'message' => '<div class="alert alert-danger mt-2" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
        'authenticate' => $authenticate		

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}