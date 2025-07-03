<?php

/** Importação de classes  */
use vendor\model\Schedules;
use vendor\model\ClientProducts;
use vendor\model\FinancialMovements;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    
        
        
        /** Controles */
        $diasParaVencimento = 5;
        $totalBar = null;

        /** Instânciamento de classes  */
        $Schedules = new Schedules();
        $ClientProducts = new ClientProducts();
        $FinancialMovements = new FinancialMovements();        

        /** Consulta as saídas pendentes */
        $result = $FinancialMovements->amountOutput($_SESSION['USERSCOMPANYID']);

        /** Resumos */
        $amountOutput     = $result->amount_output; #Quantidade de saídas pendentes
        $totalValueOutput = $result->total_value_output; #Valor total de saídas pendentes





        /**************** INICIO ENTRADAS PENDENTES ****************/

        /** Consulta as entradas pendentes */
        $result = $FinancialMovements->amountEntrie($_SESSION['USERSCOMPANYID'], true, 0);

        /** Resumos entradas pendentes */
        $amountEntriePendent = $result->amount_entrie; #Quantidade de entradas pendentes
        $totalValueEntriePendent = $result->total_value_entrie+$result->total_value_entrie_fees; #Valor total de entradas pendentes  
        
        /**************** FIM ENTRADAS PENDENTES ****************/





        /**************** INICIO ENTRADAS PAGAS ****************/

        /** Consulta as entradas pagas */
        $result = $FinancialMovements->amountEntrie($_SESSION['USERSCOMPANYID'], false, 0);

        /** Resumos entradas pagas */
        $amountEntrieConfirmed = $result->amount_entrie; #Quantidade de entradas pagas
        $totalValueEntrieConfirmed = $result->total_value_entrie+$result->total_value_entrie_fees; #Valor total de entradas pagas  
        
        /**************** FIM ENTRADAS PAGAS ****************/        


        

        /**************** INICIO VERIFICA ENTRADAS EM ATRASO NOS PRÓXIMOS 5 DIAS ****************/

        /** Consulta as entradas em atraso */
        $result = $FinancialMovements->amountEntrie($_SESSION['USERSCOMPANYID'], true, $diasParaVencimento);

        /** Resumos entradas em atraso */
        $amountEntrieDelay = $result->amount_entrie; #Quantidade de entradas em atraso
        $totalValueEntrieDelay = $result->total_value_entrie+$result->total_value_entrie_fees; #Valor total de entradas em atraso  
        
        /**************** INICIO VERIFICA ENTRADAS EM ATRASO NOS PRÓXIMOS 5 DIAS ****************/   



        
        /** Consulta se existem agendamento sem baixa */
        $SchedulesResult = $Schedules->LowPending(); 



        
        /**************** INICIO GRAFICO ÁREA ENTRADAS ****************/
        
        /** Controles */
        $months = [ '01' => 'Jan', 
                    '02' => 'Fev', 
                    '03' => 'Mar', 
                    '04' => 'Abr', 
                    '05' => 'Mai', 
                    '06' => 'Jun', 
                    '07' => 'Jul', 
                    '08' => 'Ago', 
                    '09' => 'Set', 
                    '10' => 'Out', 
                    '11' => 'Nov', 
                    '12' => 'Dez' 
                ];
        
        $queryDate = [];
        
        /** Legendas */
        for($i=11; $i>0; $i--){

            /** Trata a data de acordo com um periodo de um(1) ano */
            array_push($queryDate, mktime(0, 0, 0, (int)date('m')-$i, 1, (int)date('Y')));         

        }

        /** Trata a data de acordo com um periodo de um(1) ano */
        array_push($queryDate, mktime(0, 0, 0, (int)date('m'), (int)date('d'), (int)date('Y')));

        $chartAreaEntrie = new stdClass();
        
        foreach($queryDate as $value){

            $result = $FinancialMovements->searchDateEntrie(date('Y',$value).'-'.date('m',$value).'-01', date('Y', $value).'-'.date('m', $value).'-'.date('t', $value), $_SESSION['USERSCOMPANYID']);

            $chartAreaEntrie->label[] = $months[date('m', $value)].'/'.date('Y', $value);
            $chartAreaEntrie->value[] = isset($result->total_paid) ? $result->total_paid : '0.00';

            /** Contabiliza o total */
            $totalBar += isset($result->total_paid) ? $result->total_paid : '0';
        }

        /**************** FIM GRAFICO ÁREA ENTRADAS ****************/







        /**************** INICIO GRAFICO DONUT ENTRADAS ****************/

        /** Periodo de consulta */
        $startDate = date('Y-m-d', mktime(0, 0, 0, (int)date('m')-11, 1, (int)date('Y')));
        $endDate   = date('Y-m-d');

        /** Consulta as entradas de uma empresa pelas categorias */
        $FinancialMovementsResult = $FinancialMovements->searchEntriesCategories($_SESSION['USERSCOMPANYID'], $startDate, $endDate);
        
        /** Controles */
        $items = [];
        $newItems = [];

        /** Prepara o retorno a ser apresentado */
        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){ 

            array_push($items, [
                "entriesId"=>$Result->financial_entries_id,
                "categorieId"=>$Result->financial_categories_id,
                "categorieDescription"=>$Result->categorie, 
                "total"=>$Result->total
            ]);
        }  
        
        /** Separa as categorias com seus respectivos totais */
        for($i=0; $i<count($items); $i++){

            if( in_array($items[$i]['categorieDescription'], $newItems) ){

                $newItems[$items[$i]['categorieDescription']] += $items[$i]['total'];

            }else{

                $newItems[$items[$i]['categorieDescription']] += $items[$i]['total'];
            }
            
        }

        /** Carrega os labels */
        $labels   = array_keys($newItems);
        
        /** carreg os totais */
        $totals = array_values($newItems);


        /**************** FIM GRAFICO DONUT ENTRADAS ****************/



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

?>

        <div class="col-lg-12">

            <div class="row">

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        (<?php echo $amountEntriePendent;?>) Entradas pendentes</div>
                                    <div class="h5 mb-0 font-weight-bold text-primary-800">R$ <?php echo number_format($totalValueEntriePendent, 2, ',', '.');?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        (<?php echo $amountEntrieConfirmed;?>) Entradas confirmadas</div>
                                    <div class="h5 mb-0 font-weight-bold text-success-800">R$ <?php echo number_format($totalValueEntrieConfirmed, 2, ',', '.');?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        (<?php echo $amountEntrieDelay;?>) Entradas a vencer nos próximos <?php echo $diasParaVencimento;?> dias
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-600">
                                        R$ <?php echo number_format($totalValueEntrieDelay, 2, ',', '.');?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-400"></i>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Agenda</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-600">Existem <?php echo $SchedulesResult->qtde;?> agendamento(s) sem finalização</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                        

            </div>


            <div class="row">

                <div class="col-xl-6 col-lg-7">

                    <!-- Area Chart -->
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Projeção entradas(anual)</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Bar Chart -->
                <div class="col-xl-6 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Projeção entradas(anual)</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="myBarChart"></canvas>
                            </div>
                        </div>
                    </div> 
                </div> 
                
            </div>

            <div class="row">

                <!-- Donut Chart -->
                <div class="col-xl-6 col-lg-7">

                    <div class="card shadow mb-4">

                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">                    
                            <h6 class="m-0 font-weight-bold text-primary">Serviços realizados(anual)</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie p-4">
                                <canvas id="myPieChart"></canvas>
                            </div>                    
                        </div>
                    </div>
                
                </div> 


                <div class="col-xl-6 col-lg-7">

                    <div class="card shadow mb-4">

                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">                    
                            <h6 class="m-0 font-weight-bold text-primary">Reajustes do mês - <?php echo ucfirst($month[date('m')]);?></h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">

                            <div class="overflow-auto p-2" style="height: 277px;">

                                <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm">

                                    <thead>
                                    <tr>
                                        <th>Ref.</th>
                                        <th>Venc.</th>
                                        <th>Nome Fantasia</th>
                                        <th class="text-center" colspan="2"></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                <?php

                                    /** Consulta os clientes que estão no mês do reajuste */
                                    $ClientProductsResult = $ClientProducts->Readjustment($month[date('m')]);
                                    foreach($ClientProductsResult as $ClientsKey => $Result){
                                ?>

                                    <tr>
                                        <td><?php echo $Result->reference;?></td>
                                        <td class="text-center" width="30"><?php echo $Result->due_date;?></td>
                                        <td><?php echo $Result->fantasy_name;?></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_form&clients_id=<?php echo $Result->clients_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Gerenciar orçamento"><i class="fas fa-ellipsis-v"></i></button></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_form&clients_id=<?php echo $Result->clients_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Editar dados do cliente"><i class="far fa-edit"></i></button></td>                                        
                                    </tr>                                


                                <?php } ?>

                                    <tbody>
                                </table>

                            </div>
                 
                        </div>
                    </div>
                
                </div>                 

            </div>


        </div>

        <script type="text/javascript">

            /** Operações ao carregar a página */
            $(document).ready(function(e) {

                chartAreaentries(<?php echo json_encode($chartAreaEntrie->label)?>, <?php echo json_encode($chartAreaEntrie->value)?>);
                chartPieentries(<?php echo json_encode($labels);?>, <?php echo json_encode($totals);?>);
                chartBarentries(<?php echo json_encode($chartAreaEntrie->label)?>, <?php echo json_encode($chartAreaEntrie->value)?>, <?php echo array_sum($chartAreaEntrie->value);?>);
                
                
            })

        </script> 
<?php 

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
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}