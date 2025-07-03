<?php

/** Importação de classes  */
use vendor\model\Schedules;
use vendor\model\SchedulesFiles;
use vendor\controller\schedules\SchedulesValidate;
use vendor\controller\schedules_files\SchedulesFilesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $Schedules = new Schedules();
        $SchedulesFiles = new SchedulesFiles();
        $SchedulesValidate = new SchedulesValidate();
        $SchedulesFilesValidate = new SchedulesFilesValidate();

        /** Parametros de entrada  */
        $schedulesId        = isset($_POST['schedules_id'])         ? (int)filter_input(INPUT_POST, 'schedules_id', FILTER_SANITIZE_SPECIAL_CHARS)         : 0;
        $clientsId          = isset($_POST['clients_id'])           ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_SPECIAL_CHARS)           : 0;
        $usersResponsibleId = isset($_POST['users_responsible_id']) ? (int)filter_input(INPUT_POST, 'users_responsible_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $title              = isset($_POST['title'])                ? (string)filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $local              = isset($_POST['local'])                ? (string)filter_input(INPUT_POST, 'local', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $dateScheduling     = isset($_POST['date_scheduling'])      ? (string)filter_input(INPUT_POST, 'date_scheduling', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $hourScheduling     = isset($_POST['hour_scheduling'])      ? (string)filter_input(INPUT_POST, 'hour_scheduling', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
        $description        = isset($_POST['description'])          ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)       : '';
        $note               = isset($_POST['note'])                 ? (string)filter_input(INPUT_POST, 'note', FILTER_SANITIZE_SPECIAL_CHARS)              : '';
        $finished           = isset($_POST['finished'])             ? (string)filter_input(INPUT_POST, 'finished', FILTER_SANITIZE_SPECIAL_CHARS)          : 'N';
        $nameFiles          = isset($_POST['name_files'])           ? (string)filter_input(INPUT_POST, 'name_files', FILTER_SANITIZE_SPECIAL_CHARS)        : '';  

        /** Verifica se é uma finalização */
        if($finished == 'S'){

            /** Validando os campos de entrada */
            $SchedulesValidate->setNote($note);

            /** Verifica se há erros a serem informados */
            if (!empty($SchedulesValidate->getErrors())) {

                /** Informo */
                throw new InvalidArgumentException($SchedulesValidate->getErrors(), 0);        
        
            }              

        }else{     

            /** Validando os campos de entrada */
            $SchedulesValidate->setSchedulesId($schedulesId);
            $SchedulesValidate->setClientsId($clientsId);
            $SchedulesValidate->setTitle($title);
            $SchedulesValidate->setLocal($local);
            $SchedulesValidate->setDateScheduling($dateScheduling);
            $SchedulesValidate->setHourScheduling($hourScheduling); 
            $SchedulesValidate->setDescription($description);       
            $SchedulesValidate->setUsersResponsibleId($usersResponsibleId);    
            $SchedulesValidate->setFinished($finished);        

            /** Verifica se não existem erros a serem informados, 
             * caso não haja erro(s) salvo os dados do agendamento ou 
             * efetua o cadastro de um novo*/
            if (!empty($SchedulesValidate->getErrors())) {

                /** Informo */
                throw new InvalidArgumentException($SchedulesValidate->getErrors(), 0);                  

            }else{

                /** Salva as alterações ou cadastra um novo usuário */
                if($Schedules->Save($SchedulesValidate->getSchedulesId(), $SchedulesValidate->getClientsId(), $SchedulesValidate->getUsersResponsibleId(), $SchedulesValidate->getTitle(), $SchedulesValidate->getlocal(), $SchedulesValidate->getDescription(), $Main->DataDB($SchedulesValidate->getDateScheduling()), $SchedulesValidate->getHourScheduling(), $SchedulesValidate->getFinished(), $SchedulesValidate->getNote())){                               
                    
                    /** Verifica se existem arquivos a serem cadastrados */
                    if( !empty($nameFiles) ){


                        /** Verifica se é não é uma edição */
                        if( (int)$schedulesId === 0){

                            /** Recupera o último ID inserido */
                            $schedulesId = $Schedules->getId();

                        }else{/** Caso seja edição, recupero o ID informado */

                            $schedulesId = $SchedulesValidate->getSchedulesId();
                        }

                        /** Envia o arquivo para ser gravado */
                        $SchedulesFilesValidate->setNameFiles($nameFiles);

                        /** Verifica se não existem erros a serem informados, 
                         * caso não haja erro(s) salvo o arquivo do agendamento */
                        if (!empty($SchedulesFilesValidate->getErrors())) {

                            /** Informo */
                            throw new InvalidArgumentException($SchedulesValidate->getErrors(), 0);                  

                        }else{                          
                            
                            /** Salva as informações do arquivo */
                            if( !$SchedulesFiles->Save((int)$schedulesId, $SchedulesFilesValidate->getNameFile(), $SchedulesFilesValidate->getNameFiles()) ){

                                /** Informo */
                                throw new InvalidArgumentException('Não foi possível gravar o arquivo', 0); 
                            }                        
                        }
                    
                    }                                
                    
                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 200,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert">' . ($schedulesId > 0 ? 'Agendamento atualizado com sucesso!' : 'Agendamento cadastrado com sucesso!') .'</div>',

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;            

                }else{//Caso ocorra algum erro, informo

                    throw new InvalidArgumentException(($schedulesId > 0 ? 'Não foi possível atualizar o agendamento' : 'Não foi possível cadastrar o novo agendamento'), 0);	
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