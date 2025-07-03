<?php

try{ 

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

    ?>

    <!DOCTYPE html>
        <html lang="pt-br"> 
            <header>
                <link href="css/print.css" rel="stylesheet"/>
            </header> 
            <style type="text/css">

                table{
                    font-family: Verdana, Geneva, Tahoma, sans-serif;
                    font-size: 12px !important;
                }

                table tr th{
                    font-size: 10px !important;
                } 
                
                table tr td{
                    font-size: 12px !important;
                }
                
                @page { size: 21cm 29.7cm portrait!important; }

            </style>
        
            <body>

                <div>

                
                <?php    
                
                                            
                    /** Inicio do objeto que irá armazenar as movimentações */
                    $movement = new stdClass();
    
                    /** Objeto responsável em armazenar os tipos de movimentação */
                    $movement->Type = new stdClass();
                    $movement->Type->Items = [];

                    /** Consulta as categorias de documentos cadastradas*/
                    $FinancialMovementsResult = $FinancialMovements->All($companyId, 0, 0, $FinancialMovementsValidate->getSearch(), $FinancialMovementsValidate->getType(), $FinancialMovementsValidate->getStatusSearch(), $FinancialMovementsValidate->getDateStart(), $FinancialMovementsValidate->getDateEnd());                                    
                    foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){                     

                        $movement->Type->Items[$i] = new stdClass();
                        $movement->Type->Items[$i]->Description[] = (int)$Result->financial_entries_id > 0 ? 'Entrada' : 'Saida';
                        $movement->Type->Items[$i]->Year[] = date('Y', strtotime($Result->movement_date_scheduled));
                        $movement->Type->Items[$i]->Month[] = date('m', strtotime($Result->movement_date_scheduled));
                        $movement->Type->Items[$i]->Value[] = $Result->movement_value;

                        /** Contabiliza os itens */
                        $i++;

                        /** Soma o total dos itens */
                        $total += $Result->movement_value;

                        /** Soma o total de itens com os juros */
                        $totalPago += ($Result->movement_value_paid+$Result->movement_value_fees); 
                        
                        /** Total as saídas */
                        if((int)$Result->financial_outputs_id > 0){

                            $totalOutputs += $Result->movement_value;
                            $totalOutputsPaid += $Result->movement_value_paid+$Result->movement_value_fees;                                
                            $qtdeOutputs++;
                        }

                        /** Total das entradas */
                        if((int)$Result->financial_entries_id > 0){

                            $totalEntries += $Result->movement_value;
                            $totalEntriesPaid += $Result->movement_value_paid+$Result->movement_value_fees;
                            $qtdeEntries++;
                        }                          

                    }

                    /** Controles */
                    $year = [];
                    $yearUniq = [];
                    $yearMonth = [];
                    $yearMonthValue = '0';
                    $yearMonthTotal = [];
                    $yearKey = [];

                    /** Lista os itens para capturar o ano */
                    for($i=0; $i < count($movement->Type->Items); $i++){

                        array_push($year, $movement->Type->Items[$i]->Year[0]);
                    }

                    /** Elimina os anos repetidos */
                    $yearUniq = array_unique($year);

                    /** Lista pelos anos informados */
                    foreach($yearUniq as $value){

                        /** Lista os itens e separa pelo tipo Saída para capturar o ano */
                        for($i=0; $i < count($movement->Type->Items); $i++){


                            /** Verifica se o ano informado é igual ao selecionado */
                            if($value == $movement->Type->Items[$i]->Year[0]){
                                
                                /** Verifica se o mês já esta junto ao seu ano respectivo */
                                if(!in_array($movement->Type->Items[$i]->Month[0], $yearMonth[$value])){

                                    /** Armazeno o mês que ainda não foi adicionado ao seu respectivo ano */
                                    $yearMonth[$value][] = $movement->Type->Items[$i]->Month[0];

                                }
                                                                
                            }

                        }

                    }

                    /** Pega os anos que são os indices */
                    $yearKey = array_keys($yearMonth);

                    $data = [];
                    $labelDateStart = '';
                    $labelDateEnd   = '';   
                    $labelO = '';
                    $labelE = '';

                    /** Define a orientação da impressão */
                    $width  = '680';
                    $height = '980'; 

                    foreach($yearMonth as $key => $value){

                        $j=0;
                        $totalO = '0';                                            
                        $totalE = '0';                    

                        foreach($value as $month){
                        
                            /** Pega o máximo de dias  */
                            $maxDayMOnth = date( 't', strtotime( $key.'-'.$month.'-01' ) );

                            /** Trata a data para retornar o valor correspondente */
                            $startDate = $key.'-'.$month.'-01';
                            $endData   = $key.'-'.$month.'-'.$maxDayMOnth;

                            /** Trata o label para o gráfico */
                            $label = $month.'/'.$key;

                            if($j == 0){

                                $labelDateStart = date('d/m/Y', strtotime($startDate)) ;
                            }

                            $labelDateEnd = date('d/m/Y', strtotime($endData));                        

                            /** Consulta o total de mês/ano pelo período data informada */

                            /** Verifica se o tipo informado é uma saída */
                            if($FinancialMovementsValidate->getType() == 'S'){

                                $rowO = $FinancialMovements->SumMonth($companyId, $startDate, $endData, 'O');
                                $totalReceived = $rowO->total_received;
                                $labelO = iconv("utf-8","iso-8859-1",'Total Saídas');

                                /** Contabiliza os totais */
                                $totalO += $rowO->total;                                                                      
                            
                            /** Verifica se o tipo informado é uma entrada */
                            }elseif($FinancialMovementsValidate->getType() == 'E'){

                                $rowE = $FinancialMovements->SumMonth($companyId, $startDate, $endData, 'E');
                                $totalReceived = $rowE->total_received;
                                $labelE = iconv("utf-8","iso-8859-1",'Total Entradas');

                                /** Contabiliza os totais */                                         
                                $totalE += $rowE->total;                            

                            /** Caso nenhum tipo tenha sido informado, consulta por entrada e saidas juntos */
                            }else{

                                $rowO = $FinancialMovements->SumMonth($companyId, $startDate, $endData, 'O');
                                $rowE = $FinancialMovements->SumMonth($companyId, $startDate, $endData, 'E');
                                $totalReceived = $rowE->total_received;

                                $labelO = iconv("utf-8","iso-8859-1",'Saídas');
                                $labelE = iconv("utf-8","iso-8859-1",'Entradas');

                                /** Contabiliza os totais */
                                $totalO += $rowO->total;                                            
                                $totalE += $rowE->total;                            
                            }

                            array_push($data, [$label, $rowE->total, $rowO->total, $totalReceived] ); 
                            $j++;                            

                        } 
                                                
                    }

                    /** Parametros para criação do gráfico */
                    $graph  = 'pie';

                    $data = array(
                        array('Entradas', $qtdeEntries),
                        array(iconv("utf-8","iso-8859-1",'Saídas'), $qtdeOutputs),
                    );

                    $colors = ['green', 'red'];

                    $title  = iconv("utf-8","iso-8859-1",'Movimentações financeiras');  

                    /** Cria o grafico com as dimensões informadas */
                    $plot = new PHPlot($width, $height);
                    $plot->SetImageBorderType('plain');

                    $plot->SetPlotType($graph);
                    $plot->SetDataType('text-data-single');
                    $plot->SetDataValues($data);

                    # Set enough different colors;
                    $plot->SetDataColors($colors);

                    # Main plot title:
                    $plot->SetTitle($title);

                    # Retira o formato 3d
                    $plot->SetShading(0);                    

                    # Build a legend from our data array.
                    # Each call to SetLegend makes one line as "label: value".
                    foreach ($data as $row)
                        $plot->SetLegend(implode(': ', $row));
                    # Place the legend in the upper left corner:
                    $plot->SetLegendPixels(5, 5);


                    $plot->SetPrintImage(False);  // Do not output the image
                    $plot->DrawGraph();

                    /** Envia a imagem para impressão */
                    echo "<img src=\"" . $plot->EncodeImage() . "\">\n"; 

                    /** Defino as prefências d eimpressão */
                    $preferences = new stdClass();
                    $preferences->page = new stdClass();
                    $preferences->page->orientation = $orientation;

                    /** Pega o buffer do arquivo */
                    $html = ob_get_contents();
                    
                    /** Limpa o buffer do arquivo */
                    ob_clean(); 
                    
                    /** Envia o html para impressão e retorno o caminho do pdf gerado*/
                    $file = $PdfGenerate->generate($html, 'temp', $companyId.md5(microtime()).'.pdf', $preferences); 
                    
                    /** Verifica se o PDF foi gerado */
                    if($file){

                        /** Preparo o formulario para retorno **/
                        $result = [

                            'cod' => 98,
                            'message' => 'Arquivo gerado com sucesso',
                            'title' => 'Atenção',
                            'file' => $file # Envia o arquivo para visualização

                        ];

                        /** Envio **/
                        echo json_encode($result);

                        /** Paro o procedimento **/
                        exit;

                    }else{

                        /** Mensagem de erro */
                        throw new Exception('Não foi possível gerar o arquivo de impressão');         
                    } ?>


                </div>

            </body>

        </html>

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
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}