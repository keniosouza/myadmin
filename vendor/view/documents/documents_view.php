<?php

/** Importação de classes  */
use vendor\model\Documents;
use vendor\controller\documents\DocumentsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){       

        /** Instânciamento de classes  */
        $Documents = new Documents();
        $DocumentsValidate = new DocumentsValidate();

        /** Parametros de entrada  */
        $documentsId = isset($_POST['documents_id']) ? (int)filter_input(INPUT_POST,'documents_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $DocumentsValidate->setDocumentsId($documentsId);

        /** Verifica se não existem erros a serem informados */
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        } else { 

            /** Verifica se o ID informado é válido */
            if($DocumentsValidate->getDocumentsId() > 0){        

                /** Consulto o documento informado */
                $DocumentsResult = $Documents->Get($DocumentsValidate->getDocumentsId());

                /** Verifico se a consulta retornou algum resultado */
                if((int)$DocumentsResult->documents_id > 0){   
                    
                    /** Carrega as configurações */
                    $config = $Main->LoadConfigPublic();                     

                    /** Diretório do arquivo */
                    $dirGeral = $config->app->ged;//Caminho aonde serão gravados os arquivos
                    $dirDocument = (int)$DocumentsResult->financial_movements_id > 0 ? 'financial' : "documents";
                    $dirCompany = isset($_SESSION['USERSCOMPANYID']) && $_SESSION['USERSCOMPANYID'] > 0 ? $Main->setzeros($_SESSION['USERSCOMPANYID'], 8) : 0;
                    $dirYear = date('Y', strtotime($DocumentsResult->date_register));
                    $dirMonth = date('m', strtotime($DocumentsResult->date_register));                    
                            
                    /** Caminho absoluto do arquivo */
                    $path = $dirGeral."/".$dirDocument."/".$dirCompany."/".$dirYear."/".$dirMonth."/".$DocumentsResult->archive;

                    /** Caminho absoluto do novo arquivo temporário*/
                    $newfile = $DocumentsValidate->getDirTemp().'/'.$DocumentsValidate->getDirUser().'/'.$DocumentsResult->archive;  
                    
                    /** Diretorio temporário do usuário */
                    $dirUser = $DocumentsValidate->getDirTemp().'/'.$DocumentsValidate->getDirUser();

                    /** Verifica se o arquivo existe no diretório */
                    if(is_file($path))
                    {

                        /** Verifica se a pasta do usuário não existe */
                        if( !is_dir($dirUser) ){  
                            
                            /** Cria a pasta do usuário */
                            mkdir($dirUser);            

                        }
                        
                        /** Movendo o arquivo para a pasta temporária */                
                        copy($path, $newfile);  
                        
                        /** Verifica se o arquivo foi movido para a pasta temporária */
                        if($newfile){

                            /** Preparo o formulario para retorno **/
                            $result = [

                                'cod' => 98,
                                'title' => 'Visualizando o arquivo nº '.$Main->setzeros($DocumentsResult->documents_id, 6),
                                'file' => $newfile,

                            ];

                            /** Envio **/
                            echo json_encode($result);

                            /** Paro o procedimento **/
                            exit;

                        }else{

                            /** Informo */
                            throw new InvalidArgumentException("Não foi possível carregar o arquivo", 0);                     
                        }
                        
                    }else{/** Caso o arquivo não exista, informo */

                        /** Informo */
                        throw new InvalidArgumentException("Não foi possível localizar o arquivo", 0);  
                    }

                }else{/** Caso o documento não tenha sido localizado */

                    /** Informo */
                    throw new InvalidArgumentException("Nenhum documento informado para esta solicitação", 0);            
                }
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