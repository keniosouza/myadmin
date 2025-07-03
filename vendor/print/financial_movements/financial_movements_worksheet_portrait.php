<?php

error_reporting(E_ALL);
ini_set('display_errors','On');

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Controles */
        $colorRow = null;

        /** Consulta as categorias de documentos cadastradas*/
        $FinancialMovementsResult = $FinancialMovements->All($companyId, 0, 0, $FinancialMovementsValidate->getSearch(), $FinancialMovementsValidate->getType(), $FinancialMovementsValidate->getStatusSearch(), $FinancialMovementsValidate->getDateStart(), $FinancialMovementsValidate->getDateEnd());                                        

        /**Carrega a biblioteca par agerar o arquivo*/
        chdir('vendor/library/Excel/phpxls');
        require_once 'Writer.php';
        chdir('..');

        /** Diretorio do arquivo temporário */
        $dir = '../../../temp/';

        /** Gera o nome do arquivo temporário */
        $file = md5($_SESSION['USERSID'].microtime()).'.xls';

        /** Campinho absoluto para gerar o arquivo */
        $path = $dir.$file;

        /**Instancia da classe*/		
        $workbook = new Spreadsheet_Excel_Writer($path);

        /**Defino o header da planilha*/
        $header =& $workbook->addFormat();
        $header->setBottom(2);//thick
        $header->setBold();
        $header->setFgColor('black');
        $header->setColor('white');
        $header->setFontFamily('Arial');
        $header->setSize(11);
        $header->setVAlign('vcenter');
        $header->setAlign('center');    

        //Criação da página
        $worksheet =& $workbook->addWorksheet("Lista");  

        //Definições das colunas
        $worksheet->setColumn(0,0,10);//Coluna inicial, coluna final, largura	
        $worksheet->setColumn(1,1,20);	
        $worksheet->setColumn(2,2,20);
        $worksheet->setColumn(3,3,40);
        $worksheet->setColumn(4,4,20);
        $worksheet->setColumn(5,5,20);
        $worksheet->setColumn(6,6,20);

        //Escrevendo o header da planilha
        $worksheet->setRow(0, 20, 0);
        $worksheet->writeString(0, 0, iconv("utf-8","iso-8859-1","Nº"), $header);//Linha, coluna, label, parametros	
        $worksheet->write(0, 1, iconv("utf-8","iso-8859-1","Agendamento"), $header);
        $worksheet->write(0, 2, iconv("utf-8","iso-8859-1","Pagamento"), $header);	
        $worksheet->write(0, 3, iconv("utf-8","iso-8859-1","Descrição"), $header);
        $worksheet->write(0, 4, iconv("utf-8","iso-8859-1","Tipo"), $header);
        $worksheet->write(0, 5, iconv("utf-8","iso-8859-1","Valor R$"), $header);
        $worksheet->write(0, 6, iconv("utf-8","iso-8859-1","Pago R$"), $header);

        $line=1;//Linha inicial
        $col=0;//Coluna inicial
        $colend=0;//Coluna final

        /** Lista os pedidos de acordo com o resultado da consulta informada */
        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){   
            
            //Define a cor da linha
            $colorRow  = ($line % 2) == 0 ? 9 : 22;
            $colorFont = ($line % 2) == 0 ? 58 : 0;

            //Defino o body/item da planilha alinhado ao centro
            $bodyCenter =& $workbook->addFormat();
            $bodyCenter->setColor($colorFont);
            $bodyCenter->setFontFamily('Arial');
            $bodyCenter->setSize(12);
            $bodyCenter->setAlign('top');
            $bodyCenter->setAlign('center');  
            $bodyCenter->setFgColor($colorRow);  
            
            //Defino o body/item da planilha alinhado a esquerda
            $bodyLeft =& $workbook->addFormat();
            $bodyLeft->setColor($colorFont);
            $bodyLeft->setFontFamily('Arial');
            $bodyLeft->setSize(12);
            $bodyLeft->setAlign('top');
            $bodyLeft->setAlign('left'); 
            $bodyLeft->setFgColor($colorRow);   
            
            //Defino o body/item da planilha alinhado a direita
            $bodyRight =& $workbook->addFormat();
            $bodyRight->setColor($colorFont);
            $bodyRight->setFontFamily('Arial');
            $bodyRight->setSize(12);
            $bodyRight->setAlign('top');
            $bodyRight->setAlign('right'); 
            $bodyRight->setFgColor($colorRow);         

            //Escrevendo o body da planilha			
            $worksheet->writeNumber($line, $col++, $Main->setZeros($Result->financial_movements_id, 3), $bodyCenter);
            $worksheet->writeString($line, $col++, date('d/m/Y', strtotime($Result->movement_date_scheduled)), $bodyCenter);
            $worksheet->writeString($line, $col++, isset($Result->movement_date_paid) ? date('d/m/Y', strtotime($Result->movement_date_paid)) : ($Main->CheckDay($Result->movement_date_scheduled) > 1 ? 'Em atraso' : ''), $bodyCenter);
            $worksheet->writeString($line, $col++, iconv("utf-8","iso-8859-1",$Result->description), $bodyLeft);
            $worksheet->writeString($line, $col++, iconv("utf-8","iso-8859-1",(int)$Result->financial_entries_id > 0 ? 'Entrada' : 'Saída'), $bodyCenter);
            $worksheet->writeString($line, $col++, number_format($Result->movement_value, 2, ',', '.'), $bodyRight);
            $worksheet->writeString($line, $col++, number_format(($Result->movement_value_paid+$Result->movement_value_fees), 2, ',', '.'), $bodyRight);
        
            //Obs: Utiliza-se writeString para escrever numeros em forma de string, 
            //senão o excel irá ignorar os zeros a esquerda	

            $line++;
            $colend=$col;
            $col=0;

        }
            
        //Libera a geração do arquivo da memória   
        $workbook->close();	

        /** Envia o arquivo para download */
        $result = [

            'cod' => 97,
            'file' => 'temp/'.$file,
            'nameFile' => 'Relatorio_Financeiro_Periodo_'.$dateStart.'_A_'.$dateEnd.'.xls'

        ];

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
        exit;  
        
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