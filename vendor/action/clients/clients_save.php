<?php

/** Importação de classes  */
use vendor\model\Clients;
use vendor\controller\clients\ClientsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $Clients = new Clients();
        $ClientsValidate = new ClientsValidate();

        /** Parametros de entrada  */
        $type                = isset($_POST['type'])                 ? (string)filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS)                 : '';
        $student             = isset($_POST['student'])              ? (string)filter_input(INPUT_POST, 'student', FILTER_SANITIZE_SPECIAL_CHARS)              : '';
        $clientsId           = isset($_POST['clients_id'])           ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_SPECIAL_CHARS)              : 0;
        $clientName          = isset($_POST['client_name'])          ? (string)filter_input(INPUT_POST, 'client_name', FILTER_SANITIZE_SPECIAL_CHARS)          : '';
        $fantasyName         = isset($_POST['fantasy_name'])         ? (string)filter_input(INPUT_POST, 'fantasy_name', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $document            = isset($_POST['document'])             ? (string)filter_input(INPUT_POST, 'document', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $zipCode             = isset($_POST['zip_code'])             ? (string)filter_input(INPUT_POST, 'zip_code', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $adress              = isset($_POST['adress'])               ? (string)filter_input(INPUT_POST, 'adress', FILTER_SANITIZE_SPECIAL_CHARS)               : '';
        $number              = isset($_POST['number'])               ? (string)filter_input(INPUT_POST, 'number', FILTER_SANITIZE_SPECIAL_CHARS)               : '';
        $complement          = isset($_POST['complement'])           ? (string)filter_input(INPUT_POST, 'complement', FILTER_SANITIZE_SPECIAL_CHARS)           : '';
        $district            = isset($_POST['district'])             ? (string)filter_input(INPUT_POST, 'district', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $city                = isset($_POST['city'])                 ? (string)filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS)                 : '';
        $stateInitials       = isset($_POST['state_initials'])       ? (string)filter_input(INPUT_POST, 'state_initials', FILTER_SANITIZE_SPECIAL_CHARS)       : '';
        $active              = isset($_POST['active'])               ? (string)filter_input(INPUT_POST, 'active', FILTER_SANITIZE_SPECIAL_CHARS)               : '';
        $reference           = isset($_POST['reference'])            ? (string)filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_SPECIAL_CHARS)            : '';
        $responsible         = isset($_POST['responsible'])          ? (string)filter_input(INPUT_POST, 'responsible', FILTER_SANITIZE_SPECIAL_CHARS)          : '';
        $responsibleDocument = isset($_POST['responsible_document']) ? (string)filter_input(INPUT_POST, 'responsible_document', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $email               = isset($_POST['email'])                ? (string)filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS)                : '';
        $contractDate        = isset($_POST['contract_date'])        ? (string)filter_input(INPUT_POST, 'contract_date', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
        $computers           = isset($_POST['computers'])            ? (int)filter_input(INPUT_POST, 'computers', FILTER_SANITIZE_NUMBER_INT)                  : '';
        $servers             = isset($_POST['servers'])              ? (int)filter_input(INPUT_POST, 'servers', FILTER_SANITIZE_NUMBER_INT)                    : '';

        /** Validando os campos de entrada */
        $ClientsValidate->setType($type);
        $ClientsValidate->setStudent($student);
        $ClientsValidate->setClientsId($clientsId);
        $ClientsValidate->setClientName($clientName);
        $ClientsValidate->setFantasyName($fantasyName);
        $ClientsValidate->setDocument($document);
        $ClientsValidate->setZipCode($zipCode);
        $ClientsValidate->setAdress($adress);
        $ClientsValidate->setNumber($number);
        $ClientsValidate->setComplement($complement);
        $ClientsValidate->setDistrict($district);
        $ClientsValidate->setCity($city);
        $ClientsValidate->setStateInitials($stateInitials);
        $ClientsValidate->setActive($active);
        $ClientsValidate->setReference($reference);
        $ClientsValidate->setResponsible($responsible);
        $ClientsValidate->setResponsibleDocument($responsibleDocument);
        $ClientsValidate->setEmail($email);
        $ClientsValidate->setContractDate($contractDate);
        $ClientsValidate->setComputers($computers);
        $ClientsValidate->setServers($servers);

        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados do cliente ou 
         * efetua o cadastro de um novo*/
        /** Verifico a existência de erros */
        if (!empty($ClientsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientsValidate->getErrors(), 0);        

        } else {


            /** Salva as alterações ou cadastra um novo usuário */
            if($Clients->Save($ClientsValidate->getClientsId(), 
                              $ClientsValidate->getClientName(), 
                              $ClientsValidate->getFantasyName(), 
                              $ClientsValidate->getDocument(), 
                              $ClientsValidate->getZipCode(), 
                              $ClientsValidate->getAdress(), 
                              $ClientsValidate->getNumber(), 
                              $ClientsValidate->getComplement(), 
                              $ClientsValidate->getDistrict(), 
                              $ClientsValidate->getCity(), 
                              $ClientsValidate->getStateInitials(), 
                              $ClientsValidate->getActive(), 
                              $ClientsValidate->getType(), 
                              $ClientsValidate->getStudent(), 
                              $ClientsValidate->getResponsible(), 
                              $ClientsValidate->getEmail(), 
                              '',
                              $ClientsValidate->getReference(),                              
                              $ClientsValidate->getResponsibleDocument(),
                              $ClientsValidate->getContractDate(),
                              $ClientsValidate->getComputers(),
                              $ClientsValidate->getServers())){                                 

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($ClientsValidate->getClientsId() > 0 ? 'Cliente atualizado com sucesso!' : 'Cliente cadastrado com sucesso!') .'</div>',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;            

            }else{//Caso ocorra algum erro, informo

                throw new InvalidArgumentException(($ClientsValidate->getClientsId() > 0 ? 'Não foi possível atualizar o cadastro da empresa' : 'Não foi possível cadastrar a nova empresa'), 0);	
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