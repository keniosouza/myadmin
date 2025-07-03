<?php
/** Carregamento da classe de gerar PDF */
require_once('vendor/library/mpdf/vendor/autoload.php');  

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

        /** Parametros de entrada  */
        $search      = isset($_POST['search'])    ? (string)filter_input(INPUT_POST,'search',  FILTER_SANITIZE_SPECIAL_CHARS)    : '';
        $type        = isset($_POST['type'])      ? (string)filter_input(INPUT_POST,'type',  FILTER_SANITIZE_SPECIAL_CHARS)      : '';
        $status      = isset($_POST['status'])    ? (int)filter_input(INPUT_POST,'status',  FILTER_SANITIZE_SPECIAL_CHARS)       : 0;
        $dateStart   = isset($_POST['dateStart']) ? (string)filter_input(INPUT_POST,'dateStart',  FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $dateEnd     = isset($_POST['dateEnd'])   ? (string)filter_input(INPUT_POST,'dateEnd',  FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $printType   = isset($_POST['printType']) ? (int)filter_input(INPUT_POST,'printType',  FILTER_SANITIZE_SPECIAL_CHARS)    : 0;
        $orientation = null;
        $dir         = 'temp/';

        /** Controles */
        $i=0;
        $total            = '0.00';
        $totalGeneral     = '0.00';
        $totalReceivables = '0.00';
        $totalOutputs     = '0.00';
        $totalEntries     = '0.00';
        $qtdeOutputs      = '0.00';
        $qtdeEntries      = '0.00'; 
        $totalOutputsPaid = '0.00';
        $totalEntriesPaid = '0.00';
        $header           = null;

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

        /** Verifico a existência de erros */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Mensagem de erro */
            throw new Exception($FinancialMovementsValidate->getErrors(), 0);             

        } else {

            /** Consulta a quantidade de registros */
            $NumberRecords = $FinancialMovements->Count($companyId, $FinancialMovementsValidate->getSearch(), $FinancialMovementsValidate->getType(), $FinancialMovementsValidate->getStatusSearch(), $FinancialMovementsValidate->getDateStart(), $FinancialMovementsValidate->getDateEnd())->qtde;

            /** Verifico a quantidade de registros localizados */
            if ($NumberRecords > 0){        
                
                /** Aumenta o uso de memória */
                ini_set('memory_limit','512M');

                /** Inicio do relatório */

                /** Instancia da classe Mpdf */
                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8',
                    'orientation' => 'L'
                ]);              

                /** Prepara o cabeçalho */
                $header .= '    <table width="100%" style="margin:none; font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse: collapse">';   
                $header .= '       <tr>';             
                $header .= '          <td style="text-align: center; width: 95px"><img src="img/logo2.png" style="max-width:140px; padding: 2px"/></td>';  
                $header .= '          <td colspan="6">';
                $header .= '            <h2>MOVIMENTAÇÕES FINANCEIRAS - '.($FinancialMovementsValidate->getStatusSearch() == 1 ? 'NÃO PAGOS' : ($FinancialMovementsValidate->getStatusSearch() == 2 ? 'PAGOS' : '')).'</h2>';

                /** Verifica se o período de consulta foi informado */
                if(!empty($FinancialMovementsValidate->getDateStart())){

                    $header .= '            <h3>PERÍODO DE CONSULTA: '.$dateStart.' a '.$dateEnd.'</h3>';

                }

                $header .= '          </td>';  
                $header .= '       </tr>';  
                $header .= '    </table>';

                $header .= '    <table width="100%" style="margin:none; font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse: collapse;">'; 
                $header .= '       <tr style="background-color: #333;">';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 90px">REFERÊNCIA</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 90px">VENCIMENTO</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 90px">PAGAMENTO</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center">DESCRIÇÃO</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center">CLIENTE</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">VALOR R$</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">MORA R$</td>';
                $header .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">TOTAL R$</td>';                
                $header .= '       </tr>';
                $header .= '    </table>';

                /** Define i cabeçalho do relatório */
                $mpdf->SetHTMLHeader($header);

                /** Define o rodapé do relatório */
                $mpdf->SetHTMLFooter('
                <table width="100%">
                    <tr>                        
                        <td width="100%" align="center">{PAGENO}/{nbpg}</td>
                    </tr>
                </table>');

                /** Adicionar as margens da página */
                $mpdf->AddPageByArray([
                    'margin-top' => 28
                ]);                 

                /** Inicio do corpo do relatório */
                $body = '<table width="100%" style="margin:none; font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse: collapse; ">';
                $body .= '   <tbody>';

                /** Consulta as movimentações cadastradas*/
                $FinancialMovementsResult = $FinancialMovements->All($companyId, 0, 0, $FinancialMovementsValidate->getSearch(), $FinancialMovementsValidate->getType(), $FinancialMovementsValidate->getStatusSearch(), $FinancialMovementsValidate->getDateStart(), $FinancialMovementsValidate->getDateEnd());                                    
                foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){   
                    
                    $body .= '       <tr style="'.($i % 2 == 0 ? 'background-color: #f2f2f2;' : '').'">';                                         
                    $body .= '          <td style="text-align: center; width: 90px">'.$Result->movement_reference.'</td>';
                    $body .= '          <td style="text-align: center; width: 90px">'.date('d/m/Y', strtotime($Result->movement_date_scheduled)).'</td>';
                    $body .= '          <td style="text-align: center; width: '.($FinancialMovementsValidate->getStatusSearch() == 1 ? '120' : '90').'px">'.(isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : ($Main->CheckDay($Result->movement_date_scheduled) > 1 ? $Main->diffDate($Result->movement_date_scheduled, date('Y-m-d')).' dia(s) de atraso' : '')).'</td>';
                    $body .= '          <td style="text-align: left">'.$Result->description.'</td>';
                    $body .= '          <td style="text-align: left">'.$Result->fantasy_name.'</td>';
                    $body .= '          <td style="text-align: right; width: 60px">'.number_format($Result->movement_value, 2, ',', '.').'</td>';
                    $body .= '          <td style="text-align: right; width: 60px">'.( isset($Result->movement_value_paid) ?  number_format(($Result->movement_value_paid-$Result->movement_value), 2, ',', '.') : '').'</td>';
                    $body .= '          <td style="text-align: right; width: 60px">'.( isset($Result->movement_value_paid) ? number_format($Result->movement_value_paid, 2, ',', '.') : number_format($Result->movement_value, 2, ',', '.')).'</td>';                    
                    $body .= '       </tr>';
                    $i++;                    

                    /** Contabiliza o total geral */
                    $totalGeneral += isset($Result->movement_value_paid) ? $Result->movement_value_paid : $Result->movement_value;
                }

                $body .= '   </tbody>';
                $body .= '</table>';

                $body .= '<br/>';
                $body .= 'Total Geral R$ '.number_format($totalGeneral, 2, ',', '.');

                /** Acrescenta os dados ao corpo do relatório */
                $mpdf->WriteHTML($body);

                /** Nome que será dado ao relatório */
                $nameFile = 'IMPRESSAO-RELATORIO-MOVIMENTACAO-FINANCEIRA-'. date('d-m-Y-H-i-s').'.pdf';

                /** Salva o relatório em uma pasta temporária */
                $mpdf->Output($dir.$nameFile);  

                /** Verifica se o arquivo foi gerado com sucesso */
                if(is_file($dir.$nameFile)){

                    /** Se não houver erros
                     * envio o relatório para a tela
                     */
                    $result = [

                        'cod' => 98,
                        'title' => 'Visualizando o arquivo do boleto',
                        'file' => $dir.$nameFile

                    ]; 

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;  

                } else {

                    /** Informo */
                    throw new InvalidArgumentException('Não foi possivel gerar o relatório', 0);                      
                }
                
            } else {

                /** Informo */
                throw new InvalidArgumentException('Não há registros a serem listados', 0);                  
            }

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