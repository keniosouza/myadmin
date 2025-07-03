<?php
/** Carregamento da classe de gerar PDF */
require_once('vendor/library/mpdf/vendor/autoload.php');

/** Importação de classes  */
use vendor\model\Users;
use vendor\model\ClientBudgetsCommissions;
use vendor\controller\client_budgets\ClientBudgetsCommissionsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

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
        $dateStart = isset($_POST['dateStart']) ? (string)filter_input(INPUT_POST,'dateStart',  FILTER_SANITIZE_SPECIAL_CHARS) : '01/'.date('m/Y');
        $dateEnd   = isset($_POST['dateEnd'])   ? (string)filter_input(INPUT_POST,'dateEnd',  FILTER_SANITIZE_SPECIAL_CHARS)   : date('d/m/Y');           

        /** Controles */
        $i=0;
        $total = null;
        $previsaoValor = null;
        $header = null;

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
            $header .= '            <h2>COMISSÕES</h2>';

            /** Verifica se o período de consulta foi informado */
            if(!empty($ClientBudgetsCommissionsValidate->getDateStart())){

                $header .= '            <h3>PERÍODO DE CONSULTA: '.$dateStart.' a '.$dateEnd.'</h3>';

            }

            $header .= '          </td>';  
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

            $ClientBudgetsCommissionsResult = $ClientBudgetsCommissions->All(0, 
                                                                             0, 
                                                                             $ClientBudgetsCommissionsValidate->getClientsId(), 
                                                                             $ClientBudgetsCommissionsValidate->getUsersId(),
                                                                             $ClientBudgetsCommissionsValidate->getDateStart(),
                                                                             $ClientBudgetsCommissionsValidate->getDateEnd());
            foreach($ClientBudgetsCommissionsResult as $ClientsKey => $Result){ 


                if($i == 0){

                    $body .= '       <tr style="background-color: #333;">';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; ">CLIENTE</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 90px">VENCIMENTO</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 90px">PAGAMENTO</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; ">DESCRIÇÃO</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">VALOR R$</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">%</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">COMISSÃO R$</td>';                
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 90px">COLABORADOR</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 60px">PREVISÃO</td>';
                    $body .= '          <td style="color: #FFF; padding: 4px; text-align: center; width: 20px">PAGO</td>';
                    $body .= '       </tr>'; 
                    
                }


                $body .= '       <tr style="'.($i % 2 == 0 ? 'background-color: #f2f2f2;' : '').'">';
                $body .= '          <td style="text-align: left;">'.$Result->fantasy_name.'</td>';
                $body .= '          <td style="text-align: center; width: 90px">'.(isset($Result->movement_date_scheduled) ? date('d/m/Y', strtotime($Result->movement_date_scheduled)) : '').'</td>';
                $body .= '          <td style="text-align: center; width: 90px">'.(isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : '').'</td>';
                $body .= '          <td style="text-align: left; ">'.$Result->description.'</td>';
                $body .= '          <td style="text-align: right; width: 60px">'.number_format($Result->movement_value, 2, ',', '.').'</td>';
                $body .= '          <td style="text-align: right; width: 60px">'.number_format($Result->value, 2, ',', '.').'</td>';
                $body .= '          <td style="text-align: right; width: 90px">'.($Result->commission_value_paid != NULL ? number_format($Result->commission_value_paid, 2, ',', '.') : number_format( ($Result->movement_value / 100 * $Result->value), 2, ',', '.')).'</td>';                
                $body .= '          <td style="text-align: center; width: 90px">'.$Main->decryptData($Result->name_first).'</td>';
                $body .= '          <td style="text-align: center; width: 90px">'.( $Result->commission_date_paid != NULL ? date('d/m/Y', strtotime($Result->commission_date_paid)) : (isset($Result->movement_date_paid) ? date("d/m/Y", mktime(0,0,0, (date('m', strtotime($Result->movement_date_paid))+1), date('d', strtotime($Result->movement_date_paid)), date('Y', strtotime($Result->movement_date_paid)))) : '')).'</td>';
                $body .= '          <td style="text-align: center; width: 90px">'.( $Result->commission_date_paid != NULL ? 'SIM' : 'NÃO').'</td>';
                $body .= '       </tr>';  
                $i++;   
                
                $previsaoValor += ($Result->movement_value / 100 * $Result->value);

                /** Contabiliza o total geral */
                $total += ($Result->commission_value_paid);
            }

            $body .= '   </tbody>';
            $body .= '</table>';

            $body .= '<br/>';
            $body .= 'Total Previsão R$ '.number_format($previsaoValor, 2, ',', '.'); 
            $body .= '<br/>'; 
            $body .= 'Total Pago R$ '.number_format($total, 2, ',', '.');
        
            /** Acrescenta os dados ao corpo do relatório */
            $mpdf->WriteHTML($body);            
            
            /** Nome que será dado ao relatório */
            $nameFile = 'IMPRESSAO-RELATORIO-MCOMISSAO-'. date('d-m-Y-H-i-s').'.pdf';

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