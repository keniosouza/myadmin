<?php

/** Importação de classes  */
use vendor\model\FinancialEntries;
use vendor\controller\financial_entries\FinancialEntriesValidate;
use vendor\controller\pdf\PdfGenerate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $FinancialEntries = new FinancialEntries();
        $FinancialEntriesValidate = new FinancialEntriesValidate();
        $PdfGenerate = new PdfGenerate();

        /** Parametros de entrada  */
        $description = isset($_POST['description']) ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $FinancialEntriesValidate->setDescription($description);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialEntriesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialEntriesValidate->getErrors(), 0);        

        } else {

            /** Busco os registros */
            $resultFinancialEntriesReport = $FinancialEntries->Search($FinancialEntriesValidate->getDescription());
            
            /** Salva o registro junto ao banco de dados */
            if(count($resultFinancialEntriesReport) > 0){

                /** Gero o nome do arquivo */
                $path = rand(1, 1000) . '.pdf';

                /** Inicio a coleta de dados */
                ob_start();

                /** Inclusão do arquivo desejado */
                require 'vendor/view/pdf/pdf_financiel_entries_report.php';

                /** Prego a estrutura do arquivo */
                $html = ob_get_contents();

                /** Removo o arquivo incluido */
                ob_end_clean();

                /** Verifico se o arquivo foi criado */
                if ($PdfGenerate->generate($html, '/document/', $path, ''))
                {

                    /** Preparo o formulario para retorno **/
                    $result = [

                        'cod' => 1,
                        'message' => 'Arquivo gerado com sucesso',
                        'title' => 'Atenção',
                        'type' => 'exception',

                    ];

                }
                else
                {

                    /** Informo */
                    throw new InvalidArgumentException('Não foi possivel gerar relatório', 0);

                }

            }else{

                /** Informo */
                throw new InvalidArgumentException('Não foram localizados regitros', 0);
            }
            
        }

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
        'message' => '<div class="alert alert-danger" role="alert">' . $exception->getMessage() . '</div>',
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}