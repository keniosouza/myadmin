<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\controller\users\UsersValidate;

try{

    /** Verifica se o usuário foi devidamente identificado */
    if( (isset($_SESSION['USERSID']))  &&  ($_SESSION['USERSID'] > 0) ){    

        /** Instânciamento de classes  */
        $Users = new Users();
        $UsersValidate = new UsersValidate();

        /** Parametros de entrada  */
        $passwordInform   = isset($_POST['password-inform'])  ? (string)filter_input(INPUT_POST, 'password-inform', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
        $passwordConfirm  = isset($_POST['password-confirm']) ? (string)filter_input(INPUT_POST, 'password-confirm', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $UsersValidate->setPassword($passwordInform);
        $UsersValidate->setPasswordConfirm($passwordConfirm);


        /** Verifica se não existem erros a serem informados */
        if (!empty($UsersValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($UsersValidate->getErrors(), 0);        

        } else {        

            /** Atualiza a senha do usuário */
            if( $Users->UpdatePassword($passwordInform) ){

                /** Atualizo o acesso junto ao cadastro do usuário */
                if($Users->AccessInfo( 'new' ) ){

                    /** Informa o primeiro acesso */
                    $_SESSION['USERSACCESSFIRST'] = date('Y-m-d H:i:s');

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 202 ,
                        'title' => '',
                        'url' => 'home',
                        'message' => 'Usuário autenticado com sucesso',

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit; 
                    
                }else{/** Falha na gravação de log do usuário */

                    throw new InvalidArgumentException('Não foi possível efetuar o log de acesso do usuário', 0);
                }

            }else{

                throw new InvalidArgumentException('Não foi possível cadastrar a senha de acesso, tente novamente dentro de alguns minutos ou entre em contato com o suporte técnico', 0);
            }

        }


    }else{/** Informa que o usuário não foi identificado */

        throw new InvalidArgumentException('Usuário não identificado para esta solicitação', 0);
    }
    

}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Falha na autenticação',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}