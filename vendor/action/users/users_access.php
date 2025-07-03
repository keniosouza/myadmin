<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\controller\users\UsersValidate;

try{

    /** Instânciamento de classes  */
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Parametros de entrada  */
    $userEmail      = isset($_POST['user-email'])      ? (string)filter_input(INPUT_POST, 'user-email', FILTER_SANITIZE_SPECIAL_CHARS)      : '';
    $userPassword   = isset($_POST['user-password'])   ? (string)filter_input(INPUT_POST, 'user-password', FILTER_SANITIZE_SPECIAL_CHARS)   : '';
    $rememberAccess = isset($_POST['remember_access']) ? (string)filter_input(INPUT_POST, 'remember_access', FILTER_SANITIZE_SPECIAL_CHARS) : '';


    /** Validando os campos de entrada */
    $UsersValidate->setEmail($userEmail);
    $UsersValidate->setPassword($userPassword);

    /** Verifica se não existem erros a serem informados */
    if (!empty($UsersValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($UsersValidate->getErrors(), 0);        

    } else {

        /** Consulta o usuário junto ao banco de dados para verificar se é o primeiro acesso*/
        $UsersResult = $Users->Access($UsersValidate->getEmail(), $UsersValidate->getPassword(), 'S');

        /** Verifica se não existem resultados */
        if( !is_object($UsersResult) ){

            /** Consulta o usuário junto ao banco de dados */
            $UsersResult = $Users->Access($userEmail, $userPassword, '');
            
            /** Verifica se não existem resultados */
            if( !is_object($UsersResult) ){

                throw new InvalidArgumentException('Usuário não localizado, verifique o seu e-mail e senha.', 0);
            }

        }

        /** Caso existam resultados */
        if( is_object($UsersResult) ){

            /** Verifica se o usuário foi localizado */ 
            if($UsersResult->users_id > 0){

                /** Carrega as configurações de criptografia */
                $config = $Main->LoadConfigPublic();

                /** Parametros para descriptografar dados */
                $method = $config->{'app'}->{'security'}->{'method'};
                $firstKey = $config->{'app'}->{'security'}->{'first_key'};                

                /** Inicia a sessão do usuário */
                $Main->SessionStart();

                /** Cria as sessões pessoais necessárias para o usuário */
                $_SESSION['USERSID'] = (int)$UsersResult->users_id;                
                $_SESSION['USERSACLID'] = (int)$UsersResult->users_acl_id;
                $_SESSION['USERSEMAIL'] = (string)$UsersResult->email;
                $_SESSION['USERSNAMEFIRST'] = $Main->decryptData((string)$UsersResult->name_first);
                $_SESSION['USERSACCESSFIRST'] = (string)$UsersResult->access_first;
                $_SESSION['USERSACCESSLAST'] = (string)$UsersResult->access_last;

                /** Cria as sessões da empresa a qual o usuário está vinculado */
                $_SESSION['USERSCOMPANYID'] = (int)$UsersResult->company_id;
                $_SESSION['USERSCOMPANYFANTASYNAME'] = (string)$UsersResult->fantasy_name;
                $_SESSION['USERSCOMPANYNAME'] = (string)$UsersResult->company_name;
                $_SESSION['USERSCOMPANYDOCUMENT'] = (string)$UsersResult->document; 
                
                /** Cria o Token do usuário */
                $_SESSION['USERSTOKEN'] = $Main->encryptData($config->app->security->hash.'-'.(int)$UsersResult->users_id.'-'.session_id());

                /** Inicialização da sessão do usuário */
                $_SESSION['USERSSTARTTIME'] = date("Y-m-d H:i:s");   
                
                /** Gera o cookie do usuário com duração de um dia */
                setcookie("UserEmail", $Main->encryptData($UsersResult->email), time() + (86400 * 30), "/"); // 86400 = 1 day
                
                if($rememberAccess == 'S'){
                    
                    setcookie("UserPassword", $Main->encryptData($UsersValidate->getPassword()), time() + (86400 * 30), "/"); // 86400 = 1 day
                    setcookie("RememberAccess", $rememberAccess, time() + (86400 * 30), "/"); // 86400 = 1 day

                }
                                
                /** Verifica se não é o primeiro acesso para validar a senha */
                if( (!empty($UsersResult->password)) && (!empty($UsersResult->access_first)) ){

                    /** Verifica se a senha informada confere com seu hash */
                    if ( !password_verify($UsersValidate->getPassword(), $UsersResult->password) ) {
    
                        throw new InvalidArgumentException('Autenticação falhou, verifique o seu e-mail e senha.', 0);                        
                    }  

                }

                /** Verifica se não é o primeiro acesso */
                if($UsersResult->password_temp_confirm == 'N'){

                    /** Atualizo o acesso junto ao cadastro do usuário, caso não seja o primeiro acesso */
                    if($Users->AccessInfo('new')){


                        /** Informa o resultado positivo **/
                        $result = [

                            'cod' => 202,
                            'title' => '',
                            'url' => '', # Define uma url especifica dentro da aplicação para carregar
                            'message' => 'Usuário autenticado com sucesso',

                        ];

                        
                    }else{/** Falha na gravação de log do usuário */

                        throw new InvalidArgumentException('Não foi possível efetuar o log de acesso do usuário', 0);
                    }

                }else{/** Caso seja o primeiro acesso, informo o redirecionamento */

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 301,
                        'title' => '',
                        'url' => 'first-access',/** Caso seja preciso encaminhar para uma url especifica */
                        'message' => 'É necessário o cadastro da senha definitiva',

                    ];
                }

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;                 

            }else{//Caso o usuário não tenha sido localizado, informo

                throw new InvalidArgumentException('Usuário não localizado, verifique o seu e-mail e senha.', 0);
            }   
            
        }

    }


}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Falha na autenticação',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}