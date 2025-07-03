<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\controller\mail\Mail;
use vendor\controller\users\UsersValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */
        $Users = new Users();
        $Mail = new Mail();
        $UsersValidate = new UsersValidate();

        /** Parametros de entrada  */
        $usersId             = isset($_POST['users_id'])              ? (int)filter_input(INPUT_POST, 'users_id', FILTER_SANITIZE_NUMBER_INT)                    : 0 ;
        $clientsId           = isset($_POST['clients_id'])            ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)                  : 0;
        $companyId           = isset($_POST['company_id'])            ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_SPECIAL_CHARS)               : 0;
        $nameFirst           = isset($_POST['name_first'])            ? (string)filter_input(INPUT_POST, 'name_first', FILTER_SANITIZE_SPECIAL_CHARS)            : '';
        $nameLast            = isset($_POST['name_last'])             ? (string)filter_input(INPUT_POST, 'name_last', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $email               = isset($_POST['email'])                 ? (string)filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)                         : '';
        $birthDate           = isset($_POST['birth_date'])            ? (string)filter_input(INPUT_POST, 'birth_date', FILTER_SANITIZE_SPECIAL_CHARS)            : '';
        $genre               = isset($_POST['genre'])                 ? (string)filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS)                 : '';
        $active              = isset($_POST['active'])                ? (string)filter_input(INPUT_POST, 'active', FILTER_SANITIZE_SPECIAL_CHARS)                : '';
        $administrator       = isset($_POST['administrator'])         ? (string)filter_input(INPUT_POST, 'administrator', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $passwordTempConfirm = isset($_POST['password_temp_confirm']) ? (string)filter_input(INPUT_POST, 'password_temp_confirm', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $password            = isset($_POST['password_temp'])         ? (string)filter_input(INPUT_POST, 'password_temp', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $passwordTemp        = isset($_POST['password_temp'])         ? (string)filter_input(INPUT_POST, 'password_temp', FILTER_SANITIZE_SPECIAL_CHARS)         : '';

        /** Validando os campos de entrada */
        $UsersValidate->setUsersId($usersId);
        $UsersValidate->setClientsId($clientsId);
        $UsersValidate->setCompanyId($companyId);
        $UsersValidate->setNameFirst($nameFirst);
        $UsersValidate->setNameLast($nameLast);
        $UsersValidate->setEmail($email);
        $UsersValidate->setBirthDate($birthDate);
        $UsersValidate->setGenre($genre);
        $UsersValidate->setActive($active);
        $UsersValidate->setAdministrator($administrator);
        $UsersValidate->setPasswordTempConfirm($passwordTempConfirm);
        $UsersValidate->setPassword($password);
        $UsersValidate->setPasswordTemp($passwordTemp);

        /** Verifico a existência de erros */
        if (!empty($UsersValidate->getErrors())) {

            /** Preparo o formulario para retorno **/
            $result = [

                'cod' => 0,
                'title' => 'Atenção',
                'message' => '<div class="alert alert-danger" role="alert">'.$UsersValidate->getErrors().'</div>',

            ];

        } else {

            /** Verifica se o usuário já se encontra cadastrado */
            if($Users->CheckEmail($UsersValidate->getUsersId(), $UsersValidate->getClientsId(), $UsersValidate->getEmail()) > 0){

                /** Informo */
                throw new InvalidArgumentException('O e-mail informado já está sendo utilizado', 0); 
            }


            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Users->Save($UsersValidate->getUsersId(), $UsersValidate->getClientsId(), $UsersValidate->getCompanyId(), $UsersValidate->getNameFirst(), $UsersValidate->getNameLast(), $UsersValidate->getEmail(), $UsersValidate->getBirthDate(), $UsersValidate->getGenre(), $UsersValidate->getActive(), $UsersValidate->getAdministrator(), $UsersValidate->getPassword(), $UsersValidate->getPasswordTemp(), $UsersValidate->getPasswordTempConfirm())){

                
                /** Verifica se é para enviar e-mail de acesso ao usuário */
                if($UsersValidate->getPasswordTempConfirm() == 'S'){
                    
                    /** Trata a mensagem a ser enviada */
                    $body = str_replace('{[EMAIL]}', $UsersValidate->getEmail(), base64_decode($settings->app->mail->messages->new_user));
                    $body = str_replace('{[SENHA]}', $UsersValidate->getPassword(), $body);

                    /** Envia a mensagem */
                    $Mail->sendMail($settings->app->mail->host,# Servidor do e-mail
                                    $settings->app->mail->username,# Usuário do e-mail
                                    $settings->app->mail->password,# Senha do e-mail de envio
                                    $settings->app->mail->port,# Porta de envio
                                    $settings->app->mail->from->email,# E-mail de envio
                                    $settings->app->mail->from->name,# Nome de envio
                                    $UsersValidate->getEmail(),# E-mai destino
                                    $settings->app->mail->from->name,# Nome destino
                                    'Dados de acesso para ambiente de impressão de boletos',# Assunto do e-mail
                                    $body# Mensagem a ser enviada
                    );         
                }
                                
                /** Adição de elementos na array */
                $message = '<div class="alert alert-success" role="alert">'.($UsersValidate->getUsersId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso').'</div>';

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => $message,
                    'redirect' => '',

                ];

            } else {

                /** Adição de elementos na array */
                $message = '<div class="alert alert-success" role="alert">' . ($UsersValidate->getUsersId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro') .'</div>';

                /** Result **/
                $result = [

                    'cod' => 0,
                    'title' => 'Atenção',
                    'message' => $message,

                ];

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

} catch (Exception $exception) {

    /** Controle de mensagens */
    /*$message = '<span class="badge badge-primary">Detalhes.:</span> ' . 'código = ' . $exception->getCode() . ' - linha = ' . $exception->getLine() . ' - arquivo = ' . $exception->getFile() . '</br>';
    $message .= '<span class="badge badge-primary">Mensagem.:</span> ' . $exception->getMessage();*/

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 500,
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
