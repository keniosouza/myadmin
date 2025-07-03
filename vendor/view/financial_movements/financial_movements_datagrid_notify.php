<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();

        /** Carrega as configurações de paginação */
        $config = $Main->LoadConfigPublic();

        /** Parametros de filtro por company */
        $companyId = isset($_SESSION['USERSCOMPANYID']) ? $_SESSION['USERSCOMPANYID'] : 0;

        /** Parametros de consulta */
        $parameters = isset($_POST['parameters']) ? (string)filter_input(INPUT_POST,'parameters',  FILTER_SANITIZE_SPECIAL_CHARS) : '';        

        /** Consulta a quantidade de registros */
        $NumberRecords = $FinancialMovements->CountNotify($companyId, $parameters)->qtde;

        /** Controles */
        $total = null;
        $financialMovementsId = [];

        /** Verifico a quantidade de registros localizados */
        if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

            ?>
                
            <div class="col-lg-12">

                <div class="card shadow mb-8">
                        
                    <div class="card-header">

                        <div class="row">
                        
                            <div class="col-md-8">
                                
                                <h5 class="card-title">Movimentações Financeiras Em Atraso - Notificar</h5>
                            
                            </div>

                            <div class="col-md-4 text-right">

                                <button class="btn btn-primary" id="btnCheckNotpaid">Iniciar Verificação</button>
                                <button class="btn btn-secondary" id="btnNotify">Iniciar Notificação</button>

                            </div>                             
                        
                        </div>

                    </div>

                    <div class="card-body">

                        
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableFinancialMovements" width="100%" cellspacing="0">
                                
                                <thead>
                                    <tr >
                                        <th class="text-center">Referência</th>
                                        <th class="text-center">Agendamento</th>
                                        <th class="text-center">Atraso</th>
                                        <th class="text-center">Cliente</th>
                                        <th class="text-center">Descrição</th>
                                        <th class="text-center">Valor R$</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php  
                                    
                                        /** Consulta as movimentações cadastradas*/
                                        $FinancialMovementsResult = $FinancialMovements->Notify($companyId, $parameters);                                    
                                        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){ 

                                            /** Contabiliza as movimentações */
                                            $total += $Result->movement_value;

                                            /** Armazena a referencia para consulta */
                                            array_push($financialMovementsId, $Result->financial_movements_id);
                                    ?>
                                        
                                        <tr>
                                            <td class="text-center" width="120"><?php echo $Result->movement_reference;?></td>
                                            <td class="text-center" width="90"><?php echo date('d/m/Y', strtotime($Result->movement_date_scheduled));?></td> 
                                            <td class="text-center" width="160"><?php echo isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : ((int)$Result->status == 3 ? 'Cancelado' :  ($Main->CheckDay($Result->movement_date_scheduled) > 1 ? $Main->diffDate($Result->movement_date_scheduled, date('Y-m-d')).' dia(s) de atraso' : '') );?></td>                                                                           
                                            <td class="text-left"><?php echo $Result->reference;?> - <?php echo $Result->fantasy_name;?></td>
                                            <td class="text-left"><?php echo $Result->description;?></td>                                            
                                            <td class="text-right" width="90"><?php echo number_format($Result->movement_value, 2, ',', '.');?></td>                                        
                                            <td class="text-center"><span id="response-<?php echo $Result->financial_movements_id;?>"></span></td>
                                        </tr>                                 

                                    <?php } ?> 
                                                                        
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="9">Total de movimentações: <?php echo $NumberRecords;?> - Total Geral R$ <?php echo number_format($total, 2, ',', '.');?></td>
                                    </tr>
                                </tfoot>                              

                            </table>

                        </div>

                    </div>

                </div>

            </div> 

            <?php

             /** Verifica se existem lançamentos a serem consultados */                               
             if(count($financialMovementsId)){ ?>
            
                <script type="text/javascript">

                    /** Carrega as mascaras dos campos inputs */
                    $(document).ready(function(e) { 
                        
                        /** Verifica junto ao Sicoob */
                        $('#btnCheckNotpaid').on('click', function () {

                            /** Envia os itens a serem consultados */
                            sendSicoobNotify('FOLDER=action&TABLE=financial_movements&ACTION=financial_movements_ticket_consult', <?php echo json_encode($financialMovementsId);?>, null, true, '', '', '', 'Verificando a movimentação nº ', 'blue', 'circle', 'sm', true, 0);

                        });	 
                        
                        /** Notifica o cliente */
                        $('#btnNotify').on('click', function () {

                            /** Envia os itens a serem consultados */
                            sendSicoobNotify('FOLDER=action&TABLE=financial_movements&ACTION=financial_movements_notify', <?php echo json_encode($financialMovementsId);?>, null, true, '', '', '', 'Notificando a movimentação nº ', 'blue', 'circle', 'sm', true, 0);

                        });	                        
                        
                    });

                </script>
            
            <?php } ?>

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
            
                            <div class="alert alert-warning" role="alert">
                                Não há movimentações para a solicitação informada
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