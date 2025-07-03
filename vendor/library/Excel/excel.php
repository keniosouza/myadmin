<?php
		/**Carrega a biblioteca par agerar o arquivo*/
		chdir('phpxls');
		require_once 'Writer.php';
		chdir('..');	
		
		/**Instancia da classe*/		
		$workbook = new Spreadsheet_Excel_Writer(date("Y-m-d").'-'.rand(999,99999).'.xls');
		
		/**Defino o header da planilha*/
		$header =& $workbook->addFormat();
		$header->setBottom(2);//thick
		$header->setBold();
		$header->setBgColor('black');
		$header->setFgColor(22);
		$header->setColor('black');
		$header->setFontFamily('Arial');
		$header->setSize(8);
		$header->setAlign('center');
		
		//Criação da página
		$worksheet =& $workbook->addWorksheet("Lista");
				
		//Defino o body da planilha
		$body =& $workbook->addFormat();
		$body->setColor('black');
		$body->setFontFamily('Arial');
		$body->setSize(8);
		
		//Definições das colunas
		$worksheet->setColumn(0,0,30);//Coluna inicial, coluna final, largura	
		$worksheet->setColumn(1,1,30);	
		$worksheet->setColumn(2,2,5);
		$worksheet->setColumn(3,3,10);
		$worksheet->setColumn(4,5,12);		
		
		//Escrevendo o header da planilha
		$worksheet->write(0, 0, "NOME", $header);//Linha, coluna, label, parametros	
		$worksheet->write(0, 1, "E-MAIL", $header);
		$worksheet->write(0, 2, "SEXO", $header);	
		$worksheet->write(0, 3, "NASCIMENTO", $header);
		$worksheet->write(0, 4, "CPF", $header);

		$line=1;//Linha inicial
		$col=0;//Coluna inicial
		
		for($i=0;$i<10;$i++)
		{	  
				
				//Escrevendo o body da planilha			
				$worksheet->write($line, $col++, "Kenio de Souza", $body);
				$worksheet->write($line, $col++, "kenio_souza@hotmail.com", $body);
				$worksheet->write($line, $col++, "M", $body);
				$worksheet->write($line, $col++, "07/09/1977", $body);
				$worksheet->writeString($line, $col++, rand(0,99999999999), $body);
				//Obs: Utiliza-se writeString para escrever numeros em forma de string, 
				//senão o excel irá ignorar os zeros a esquerda	
				
				$line++;
				$col=0;					
		}
		
		//Envio o arquivo para download
		//$workbook->send(date("Y-m-d").'-'.rand(999,99999).'.xls');
		$workbook->close();		