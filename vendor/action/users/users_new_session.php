<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\controller\users\UsersValidate;

try{

    /** Instânciamento de classes  */
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Parametros de entrada  */
    $userPassword = isset($_POST['user-password']) ? (string)filter_input(INPUT_POST, 'user-password', FILTER_SANITIZE_SPECIAL_CHARS) : '';

    /** Validando os campos de entrada */
    $UsersValidate->setEmail($Main->decryptData($_COOKIE['UserEmail']));//Autentica o usuário a partir do cookie com o e-mail e a senha informada
    $UsersValidate->setPassword($userPassword);

    /** Verifica se não existem erros a serem informados */
    if (!empty($UsersValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($UsersValidate->getErrors(), 0);        

    } else {
        

        /** Consulta o usuário junto ao banco de dados para verificar se é o primeiro acesso*/
        $UsersResult = $Users->Access($UsersValidate->getEmail(), $UsersValidate->getPassword(), 'N');

        /** Verifica se existem resultados */
        if( is_object($UsersResult) ){

            /** Verifica se o usuário foi localizado */ 
            if($UsersResult->users_id > 0){

                /** Verifica se a senha informada confere com seu hash */
                if ( password_verify($UsersValidate->getPassword(), $UsersResult->password) ) {                

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
                    
                    /** Atualizo o acesso junto ao cadastro do usuário, caso não seja o primeiro acesso */
                    if($Users->AccessInfo('new')){


                        /** Informa o resultado positivo **/
                        $result = [

                            'cod' => 96,
                            'title' => '',
                            'url' => '', # Define uma url especifica dentro da aplicação para carregar
                            'message' => 'Usuário autenticado com sucesso',
                            'sessionTime' => $Main->getSessionTime()

                        ];

                        
                    }else{/** Falha na gravação de log do usuário */

                        throw new InvalidArgumentException('Não foi possível efetuar o log de acesso do usuário', 0);
                    }

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;  
                
                /** Caso a senha não esteja correta informo */
                }else{

                    throw new InvalidArgumentException('Senha informada não confere, tente novamente.', 0);
                }
            
            /** Caso o usuário não tenha sido localizado, informo */
            }else{

                throw new InvalidArgumentException('Usuário não localizado, verifique sua senha.', 0);
            }   
        
        /** Caso o usuário não tenha sido localizado informo */
        }else{

            throw new InvalidArgumentException('Usuário não localizado, verifique sua senha.', 0);
        }

    }


}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Falha na autenticação',
        'type' => 'exception',
        'screensaver' => true,

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}