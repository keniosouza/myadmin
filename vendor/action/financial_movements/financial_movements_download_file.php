<?php

/** Importação de classes  */
use vendor\model\Documents;
use vendor\controller\documents\DocumentsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $DocumentsValidate = new DocumentsValidate();
        $Documents = new Documents();

        /** Parametros de entrada  */
        $documentsId = isset($_POST['documents_id']) ? $Main->antiInjection( filter_input(INPUT_POST,'documents_id', FILTER_SANITIZE_SPECIAL_CHARS) ) : '';

        /** Validando os campos de entrada */
        $DocumentsValidate->setDocumentsId($documentsId); 

        /** Verifica se não existem erros a serem informados */
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        
        } else { # caso não existam erros, preparo o arquivo para download */

            /** Verifica se o ID do documento foi informado */
            if($DocumentsValidate->getDocumentsId() > 0){

                /** Consulta o documento informado */
                $row = $Documents->get($DocumentsValidate->getDocumentsId());

                /** Verifica se a consulta retornou algum resultado */
                if($row->documents_id > 0){
                
                    /** Caminho absoluto do arquivo */
                    $path = $DocumentsValidate->getDirGeral().'/'
                        .$DocumentsValidate->getDirFinancial().'/'
                        .$DocumentsValidate->getDirCompany().'/'
                        .date('Y', strtotime($row->date_register)).'/'
                        .date('m', strtotime($row->date_register)).'/'
                        .$row->archive;

                    /** Caminho do destino do arquivo a ser visualizado */
                    $pathDestiny = 'temp/'.$row->archive;

                    /** Verifica se o arquivo existe no diretório informado */
                    if(is_file($path)){

                        /** Move o arquivo para o diretório de destino */
                        if(copy($path, $pathDestiny)){
    
                            /** Verifica se o arquivo esta na pasta de detino */
                            if(is_file($pathDestiny)){

                                /** Envia o arquivo para download */
                                $result = [

                                    'cod' => ($row->extension == 'pdf' ? 98 : 97),
                                    'message' => 'Arquivo gerado com sucesso',
                                    'title' => 'Atenção',
                                    'file' => $pathDestiny,
                                    'nameFile' => $row->description.'.'.$row->extension

                                ];                         

                                /** Envio **/
                                echo json_encode($result);

                                /** Paro o procedimento **/
                                exit;                          

                            }else{ # Não foi possível localizar o arquivo para visualização

                                /** Informo */
                                throw new InvalidArgumentException('Não foi possível mover o arquivo para visualização', 0);
                            }

                        }else{ # Não foi possível mover o arquivo para visualização

                            /** Informo */
                            throw new InvalidArgumentException('Não foi possível mover o arquivo para visualização', 0);
                        }

                    }else{ # Nenhum documento localizado para esta solicitação

                        /** Informo */
                        throw new InvalidArgumentException('Nenhum documento localizado para esta solicitação', 0);
                    }

                }else{ # Nenhum documento localizado para esta solicitação

                    /** Informo */
                    throw new InvalidArgumentException('Nenhum documento localizado para esta solicitação', 0); 
                }

            }else{ # Nenhum documento informado para esta solicitação

                /** Informo */
                throw new InvalidArgumentException('Nenhum documento informado para esta solicitação', 0);             
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
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}