<?php

/** Importação de classes  */
use vendor\model\SchedulesFiles;
use vendor\controller\schedules_files\SchedulesFilesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $SchedulesFiles = new SchedulesFiles();
        $SchedulesFilesValidate = new SchedulesFilesValidate();

        /** Parametros de entrada  */
        $schedulesFilesId = isset($_POST['schedules_files_id']) ? (int)filter_input(INPUT_POST, 'schedules_files_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;  

        /** Validando os campos de entrada */
        $SchedulesFilesValidate->setSchedulesFilesId($schedulesFilesId);

        /** Verifica se existem erros a serem informados, 
         * caso não haja erro(s) informo*/
        if (!empty($SchedulesFilesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($SchedulesFilesValidate->getErrors(), 0);                  

        }else{/** Se não houver erros, efetuo o download do arquivo */

            
            /** Localiza o arquivo informado */
            $SchedulesFilesResult = $SchedulesFiles->Get($SchedulesFilesValidate->getSchedulesFilesId());

            /** Verifica se a consulta retornou algum resultado */
            if($SchedulesFilesResult->schedules_files_id > 0){

                /** Informações do caminho do arquivo */
                $dirGeral = "ged/schedules";
                $dirCompany = isset($_SESSION['USERSCOMPANYID']) && $_SESSION['USERSCOMPANYID'] > 0 ? "/".$Main->setzeros($_SESSION['USERSCOMPANYID'], 8) : '';
                $dirYear = "/".date('Y', strtotime($SchedulesFilesResult->date_file));/** Pega o ano do arquivo */
                $dirMonth = "/".date('m', strtotime($SchedulesFilesResult->date_file));/** Pega o mês do arquivo */
                $file = "/".$SchedulesFilesResult->file;
                $fileTemp = "temp/".$SchedulesFilesResult->name.".zip";
                
                /** Cria o caminho absoluto do arquivo */
                $path = $dirGeral.$dirCompany.$dirYear.$dirMonth.$file; 
                
                /** Cria o caminho absoluto do arquivo temporário */
                $pathTemp = 'temp/'.$SchedulesFilesResult->file;
                
                /** Verifica se o arquivo existe no diretório */
                if( is_file($path) ){

                    /** Move o arquivo para o diretório de destino */
                    copy($path, $pathTemp); 
                    
                    /** Verifica se o arquivo foi copiado para a pasta temporária */
                    if( is_file($pathTemp) ){

                        /** Compacta o arquivo para download */
                        $zip = new ZipArchive(); 
                        $zip->open($fileTemp, 
                        ZipArchive::CREATE); 
                        $zip->addFile($pathTemp); 
                        $zip->close(); 

                        /** Removo o arquivo incluido */
                        ob_clean();

                        /** Verifica se o arquivo foi gerado no diretório */
                        if(is_file($fileTemp)){

                            
                            /** Informa o resultado positivo **/
                            $result = [

                                'cod' => 97,
                                'zipfile' => $fileTemp

                            ];

                            /** Envio **/
                            echo json_encode($result);

                            /** Paro o procedimento **/
                            exit;                    

                        }else{

                            throw new InvalidArgumentException("<ol><li>Não foi possível efetuar o download do arquivo ".$SchedulesFilesResult->name."</li></ol>", 0);
                        } 
                        
                    }else{

                        throw new InvalidArgumentException("<ol><li>Não foi possível localizar o arquivo ".$SchedulesFilesResult->name."</li></ol>", 0);
                    }
                    
                }else{

                    throw new InvalidArgumentException("<ol><li>Não foi possível localizar o arquivo para download ".$SchedulesFilesResult->name."</li></ol>", 0);
                }


            }else{

                /** Trata a mensagem de resposta */
                $list = "<ol><li>Nenhum arquivo localizado para esta solicitação</li></ol>";

                /** Informo */
                throw new InvalidArgumentException($list, 0);            
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
        'message' => '<div class="alert alert-danger" role="alert">' . $exception->getMessage() .'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}