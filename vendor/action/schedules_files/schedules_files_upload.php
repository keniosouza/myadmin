<?php

/** Importação de classes  */
use vendor\controller\schedules_files\SchedulesFilesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $SchedulesFilesValidate = new SchedulesFilesValidate();    

        /** Parametros de entrada  */
        $file = isset($_POST['file']) ? (string)filter_input(INPUT_POST, 'file', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $name = isset($_POST['name']) ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $SchedulesFilesValidate->setFile($file);
        $SchedulesFilesValidate->setName($name);  
        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) efetuo o upload do arquivo */
        if (!empty($SchedulesFilesValidate->getErrors())) {
            
            /** Informo */
            throw new InvalidArgumentException($SchedulesValidate->getErrors(), 0);

        }else{

            /** Pega o base64 do arquivo */
            $base64 = explode(",", $SchedulesFilesValidate->getFile());

            /** Grava o arquivo na pasta temporária */
            $fp = fopen('temp/'.$SchedulesFilesValidate->getName(), 'w');
                fwrite($fp, base64_decode($base64[1]));
                fclose($fp);

            /** Verifica se o arquivo foi enviado */
            if(is_file('temp/'.$SchedulesFilesValidate->getName())){

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'nameFile' => $SchedulesFilesValidate->getName()

                ];

                /** Envio **/
                echo json_encode($result);       

                /** Paro o procedimento **/
                exit;  
                
            }else{/** Caso o arquivo não tenha sido enviado */


                /** Informa o resultado negativo **/
                $result = [

                    'cod' => 0,
                    'nameFile' => $SchedulesFilesValidate->getName()

                ];

                /** Envio **/
                echo json_encode($result);       

                /** Paro o procedimento **/
                exit; 
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