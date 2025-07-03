<?php
/**
 * Classe Main.class.php
 * @filesource
 * @autor		Kenio de Souza
 * @copyright	Copyright 2016 Serenity Informatica
 * @package		model
 * @subpackage	model.class
 * @version		1.0
 */

/** Defino o local onde a classe esta localizada **/
namespace vendor\model;

use Dompdf\FrameDecorator\Block;

class Main
{

    private $string = null;
    private $long = null;
    private $elements = null;
    private $usuario_publico_id = null;
    private $dataType = null;
    private $pwd = null;
    private $hash = null;
    private $cost = null;
    private $page = null;
    private $numberRecords = null;
    private $pagination = null;
    private $paginationColumns = null;
    private $start = null;
    private $max = null;
    private $pageTotal = null;
    private $nav = null;
    private $queryString = null;
    private $message = null;
    private $config = null;
    private $method = null;
    private $firstKey = null;
    private $secondKey = null;
    private $data = null;
    private $input = null;
    private $form = null;
    private $imageUrl = null;
    private $token = null;
    private $sessionTime = null;
    private $parameterName = null;
    private $str = null;
    private $findme = null;
    private $pos = null;
    private $days = null;
    private $dateStart = null;
    private $dateEnd = null;
    private $interval = null;
    private $month = null;


    function __construct()
    {
        /** Carrega as configurações de paginação */
        $this->config = $this->LoadConfigPublic();

        /** Parametros para descriptografar dados */
        $this->method      = $this->config->{'app'}->{'security'}->{'method'};
        $this->firstKey    = $this->config->{'app'}->{'security'}->{'first_key'};        
        $this->secondKey   = $this->config->{'app'}->{'security'}->{'second_key'}; 
        $this->hash        = $this->config->{'app'}->{'security'}->{'hash'};  
        
        /** Parametro do tempo de sessão do usuário */
        $this->sessionTime = $this->config->{'app'}->{'session_time'}; 
        
    }

    /** Retorna o metodo de criptografia */
    private function getMethod()
    {

        return $this->method;
    }

    /** Retorna a primeira chave de criptografia */
    private function getFirstKey()
    {

        return $this->firstKey;
    } 
    
    //** Retorna a segunda chave de criptografia */
    private function getSecondKey()
    {

        return $this->secondKey;
    }  
    
    /** Retorna o tempo de sessão */
    public function getSessionTime() : int
    {

        return (int)$this->sessionTime;
    }
    
    /** Retorna a string descriptografada */
    public function decryptData(string $data) : string
    {

        /** Parametro de entrada */
        $this->data = $data;

        /** Verifica se a string a se descriptografada foi informada */
        if(!empty($this->data)){

            return $this->securedDecrypt($this->getFirstKey(), $this->getMethod(), $this->data);

        }else{

            return $this->data;
        }
        
    }

    /** Retorna a string criptografada */
    public function encryptData(string $data) : string
    {

        /** Parametro de entrada */
        $this->data = $data;

        /** Verifica se a string a se criptografada foi informada */
        if(!empty($this->data)){

            return $this->securedEncrypt($this->getFirstKey(), $this->getSecondKey(), $this->getMethod(), $this->data);

        }else{

            return $this->data;
        }
        
    }
    
    
    /** Verifica se o token do usuário é válido */
    public function verifyToken() : bool
    {

        /** Verifica se o token foi inicializado e não esta vazio */
        if( isset($_SESSION['USERSTOKEN']) && !empty($_SESSION['USERSTOKEN']) ){

            /** Caso o token tenha sido inicializado e não esteja vazio, verifica-se o mesmo é válido */
            $this->token = explode('-', $this->decryptData($_SESSION['USERSTOKEN']));

            /** Verifica se o hash é válido */
            if( ($this->token[0] === $this->hash) && ($this->token[1] === $_SESSION['USERSID']) && ($this->token[2] == session_id()) ){

                
                /**Caso o usuario não esteja logado informo*/
                if( (!isset($_SESSION['USERSID'])) || ((int)$_SESSION['USERSID'] == 0) )
                {
                    /**Elimina as sessões atuais*/
                    session_destroy();

                    /**Gera um novo session_id*/
                    session_regenerate_id();            

                    return false;
                }
                    
                /**Caso o usuário esteja logado no sistema e o tempo de sessão tenha excedido o permitido */
                elseif($this->checkTime($_SESSION['USERSSTARTTIME']) > $this->sessionTime)
                {
                    /**Destruo as sessões atuais*/
                    @session_destroy();

                    /**Gera um novo session_id*/
                    @session_regenerate_id();             
                    
                    return false;

                /** Caso esteja tudo certo, libera o acesso */
                }else{               

                    /** Renova o tempo   da sessão do usuário */
                    $_SESSION['USERSSTARTTIME'] = date("Y-m-d H:i:s");

                    return true;

                }

            }else{

                /**Elimina as sessões atuais*/
                @session_destroy();

                /**Gera um novo session_id*/
                @session_regenerate_id();

                return false;
            }


        }else{

            /**Elimina as sessões atuais*/
            @session_destroy();

            /**Gera um novo session_id*/
            @session_regenerate_id();
            
            return false;
        }

    }
    
    /** Inicializo a sessão */
    public function SessionStart()
    {

        @session_start();

    }

    /** Finalizo a sessão */
    public function SessionDestroy()
    {

        @session_destroy();

    }

    /** Função para carregar as informações */
    public function LoadConfigPublic()
    {

        /** Carrego o arquivo de configuração */
        return (object)json_decode(file_get_contents('config/config.json'));

    }


    /** Antiinjection */
    public function antiInjectionArray($ar)
    {
        
        /** Verifica se a array foi informada */
        if( is_array($ar) ){

            $str = [];
            
            foreach($ar as $value){

                array_push($str, $this->antiInjection( $value ));

            }

            return $str;

        }else{

            return $ar;
        }
    }


    /** Antiinjection */
    public function antiInjection($string, string $long = '')
    {

        /** Parâmetros de entrada */
        $this->string = $string;
        $this->long = $long;

        /** Verifico o tipo de entrada */
        if (is_array($this->string)) {

            /** Retorno o texto sem formatação */
            $this->antiInjectionArray($this->string);

        } elseif (strcmp($this->long, 'S') === 0) {

            /** Retorno a string sem tratamento */
            return $this->string;

        } else {

            /** Remoção de espaçamentos */
            $this->string = trim($this->string);

            /** Remoção de tags PHP e HTML */
            $this->string = strip_tags($this->string);

            /** Adição de barras invertidas */
            $this->string = addslashes($this->string);

            /** Evita ataque XSS */
            $this->string = htmlspecialchars($this->string);

            /** Elementos do SQL Injection */
            $elements = array(
                ' drop ' ,
                ' select ' ,
                ' delete ' ,
                ' update ' ,
                ' insert ' ,
                ' alert ' ,
                ' destroy ' ,
                ' * ' ,
                ' database ' ,
                ' drop ' ,
                ' union ' ,
                ' TABLE_NAME ' ,
                ' 1=1 ' ,
                ' or 1 ' ,
                ' exec ' ,
                ' INFORMATION_SCHEMA ' ,
                ' like ' ,
                ' COLUMNS ' ,
                ' into ' ,
                ' VALUES ' ,
                ' from ' ,
                ' undefined '
            );

            /** Transformo as palavras em array */
            $palavras = explode(' ', str_replace(',', '', $this->string));

            /** Percorro todas as palavras localizadas */
            foreach ($palavras as $keyPalavra => $palavra)
            {

                /** Percorro todos os elementos do SQL Injection */
                foreach ($elements as $keyElement => $element)
                {

                    /** Verifico se a palavra esta na lista negra */
                    if (strcmp(strtolower($palavra), strtolower($element)) === 0) {

                        /** Realizo a troca da marcação pela palavra qualificada */
                        $this->string = str_replace($palavra, '', $this->string);

                    }

                }

            }

            /** Retorno o texto tratado */
            return $this->string;

        }


    }

    /** Criptografa uma string */
    public function securedEncrypt($first_key, $second_key, $method, $str)
    {
        /** String a ser criptografada */ 
        $data =  $str;
          
        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);
            
        $first_encrypted = openssl_encrypt($data,$method,$first_key, OPENSSL_RAW_DATA ,$iv);   
        $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
                
        $output = base64_encode($iv.$second_encrypted.$first_encrypted);   
        
        return $output;       
    }
    
   /** Descriptografa uma string */ 
    public function securedDecrypt($first_key, $method, $input)
    {
        /** String a ser descriptografada */         
        $mix = base64_decode($input);
             
        $iv_length = openssl_cipher_iv_length($method);
                
        $iv = substr($mix,0,$iv_length);
        $first_encrypted = substr($mix,$iv_length+64);
        
        /** Descriptografa string */
        $output = openssl_decrypt($first_encrypted,$method,$first_key,OPENSSL_RAW_DATA,$iv);
        
        return $output;
    }   
    
 

    /** paginação */
    public function pagination(int $numberRecords, int $start, int $max, int $page, string $queryString, string $message, string $form=null)
    {   

        /** Quantidade de registros junto ao banco de dados */
        $this->numberRecords = $numberRecords;
        $this->start = $start;
        $this->max = $max;
        $this->page = $page;
        $this->queryString = $queryString;
        $this->message = $message;
        $this->form = $form;
        
        /** Número de colunas para a paginação */
        $this->pagination = $this->LoadConfigPublic()->{'app'}->{'datagrid'}->{'pagination'};

        /** Define o número de colunas de acordo com a quantidade de registros */
        $this->paginationColumns = ceil($this->numberRecords / $this->max);

        /** Verifica se é para gerar a paginação */
        if($this->paginationColumns > 1){

            /** Prepara a paginação de registros */
            $this->nav = '<nav>';
            $this->nav .= '<ul class="pagination justify-content-center">'; 

            /** Verifica se o número de colunas de paginação é superior a quantidade de paginas na tela */
            if($this->paginationColumns > $this->pagination){

                $this->nav .= '    <li class="page-item '. ($this->page == 0 ? "disabled" : "" ).'">';
                $this->nav .= '        <a class="page-link" href="#" onclick="request(\''.$this->queryString.'&start='.( ($this->start/$this->max).'&page='.($this->page-1) ).'&\'+$(\''.$this->form.'\').serialize(), \'#loadContent\', true, \'\', 0, \'\', \''.$this->message.'\', \'random\', \'circle\', \'sm\', true);">Anterior</a>';
                $this->nav .= '    </li>';  
                
            }

            /** Lista o número de paginas e seus respectivos links */
            $i=0;
            for($p = ($this->page*$this->pagination); $p < $this->paginationColumns; $p++){ 

                $this->nav .= '        <li class="page-item '.( ($p*$max) == $this->start ? 'active' : '' ).'"><a class="page-link" href="#" onclick="request(\''.$this->queryString.'&start='.($p*$this->max).'&page='.$this->page.'&\'+$(\''.$this->form.'\').serialize(), \'#loadContent\', true, \'\', 0, \'\', \''.$this->message.'\', \'random\', \'circle\', \'sm\', true);">'.($p+1).'</a></li>';

                if(($i+1) == $this->pagination){

                    break;
                }

                $i++;

            } 
                
            /** Verifica se o número de colunas de paginação é superior a quantidade de paginas na tela */
            if($this->paginationColumns > $this->pagination){

                $this->nav .= '        <li class="page-item '.( ($p+1) == $this->paginationColumns ? "disabled" : "" ).'">';
                $this->nav .= '             <a class="page-link" href="#" onclick="request(\''.$this->queryString.'&start='.( ($p*$this->max)+$this->max.'&page='.($this->page+1) ).'&\'+$(\''.$this->form.'\').serialize(), \'#loadContent\', true, \'\', 0, \'\', \''.$this->message.'\', \'random\', \'circle\', \'sm\', true);">Próximo</a>';
                $this->nav .= '        </li>';

            }

            $this->nav .= '     </ul>';
            $this->nav .= '</nav>';  
            
        }

        /** Retorna o objeto de paginação */
        return $this->nav;
    }

    public function CentimeterToPoint($centimeter)
    {

        return $centimeter * 28.34645669;

    }    

    /** Removedor de mascaras */
    public function removeMask($string)
    {

        /** Elementos para serem removidos da String */
        $this->elements = ['(', ')', '.', '-', '/'];

        /** Parâmetros de entrada */
        $this->string = $string;

        /** Remoção dos elementos */
        $this->string = str_replace($this->elements, '', $this->string);

        return $this->string;

    }

    /** Formata as mascaras de uma marcação */
    public function treatMask($str)
    {
        
        /** Verifica se a string a ser tratada foi informada */
        if($str){
        
            return ucwords(str_replace("_", " ", $str));

        }

    }


    /** Validador de CPF */
    public function validarCpf($string) {

        // Extrai somente os números
        $this->string = preg_replace( '/[^0-9]/is', '', $this->string);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($this->string) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $this->string)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $this->string[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($this->string[$c] != $d) {
                return false;
            }
        }
        return true;

    }


    /** Limpa documento */
    function ClearDoc($doc)
    {
        $d = str_replace(".", "", $doc);	
        $d = str_replace("/", "", $d);	
        $d = str_replace("-", "", $d);
        $d = str_replace("_", "", $d);
        $d = str_replace("(", "", $d);
        $d = str_replace(")", "", $d);
        $d = str_replace(" ", "", $d);
        
        return $d;	
    }    

    /** Validar Email */
    public function validarEmail($string)
    {

        /** Parâmetros dentrada */
        $this->string = $string;

        /** Verifico se o email esta válido */
        if (filter_var($this->string, FILTER_VALIDATE_EMAIL))
        {

            return true;

        }
        else
        {

            return false;

        }

    }

    /** Formata campo CPF/CNPJ */
    public function formatarCPF_CNPJ($campo){

        $tam = strlen($campo);
        
        if($tam == 11)//Verifico se é um CPF
        {
            $part1 = substr($campo, 0, 3);
            $part2 = substr($campo, 3, 3);
            $part3 = substr($campo, 6, 3);
            $part4 = substr($campo, 9, 2);
            
            $return = $part1.'.'.$part2.'.'.$part3.'-'.$part4;//Monto o cpf formatado
            
        }
        
        elseif($tam == 14)//Verifico se é um CNPJ
        {
            $part1 = substr($campo, 0, 2);
            $part2 = substr($campo, 2, 3);
            $part3 = substr($campo, 5, 3);
            $part4 = substr($campo, 8, 4);
            $part5 = substr($campo, 12, 2);
            
            $return = $part1.'.'.$part2.'.'.$part3.'/'.$part4.'-'.$part5;//Monto o cpf formatado			
        }
        
        else
        {
            $return = $campo;
        }	
        
        return $return;		

    }    

    /** Validador de Datas */
    public function validaData($string){
        

        if($string){

            /** Parâmetros de entrada */
            $this->string = $string;

            $this->string = explode("/","$string"); // fatia a string $dat em pedados, usando / como referência
            $d = $this->string[0];
            $m = $this->string[1];
            $y = $this->string[2];

            // verifica se a data é válida!
            // 1 = true (válida)
            // 0 = false (inválida)
            $res = checkdate($m,$d,$y);
            if ($res == 1){

                return true;

            } else {

                return false;

            }

        }
    }

    /** Soma dias a uma data */
    public function addDays($data, $days)
    {

        /** Parametros de entrada */
        $this->data = $data;
        $this->days = $days;

        return strtotime($this->data. ' + '.$this->days.' days');
    }

    /** Retorna true caso a data de inicio seja menor que a data final */
    public function trueDate($dateStart, $dateEnd)
    {

        $date1 = new \DateTime($dateStart);
        $date2 = new \DateTime($dateEnd);    
        
        $bool = $date1 < $date2;

        return $bool;
    }

    /** A retorna a diferença de dias entre datas */
    public function numberDays($dateStart, $dateEnd)
    {

        //Retorna a diferença entre datas em dias corridos
        return strtotime($dateStart) - strtotime($dateEnd);        
    }

    /** A retorna a diferença de dias entre datas */
    public function diffDate($dateStart, $dateEnd)
    {

        /** Parametros de entrada */            
        $this->dateStart = new \DateTime($dateStart);
        $this->dateEnd = new \DateTime($dateEnd);
        $this->interval = $this->dateStart->diff($this->dateEnd);

        //Retorna a diferença entre datas em dias corridos
        return $this->interval->days;        
    }  
    
    
    /**Retorna o tempo entre datas*/
    public function checkTime($datahora)
    {
        /** Prepara a data de entrada para ser tratada */
        $a_ano      = substr($datahora, 0,4);
        $a_mes      = substr($datahora, 5,2);
        $a_dia      = substr($datahora, 8,2);
        $a_hora     = substr($datahora, 11,2);
        $a_minuto   = substr($datahora, 14,2);
        $a_segundos = substr($datahora, 17,2);
        
        // Obtém um timestamp Unix para a data informada
        $dataacesso = mktime($a_hora, $a_minuto, $a_segundos, $a_mes, $a_dia, $a_ano);
        
        // Pego a data atual 
        $dataatual  = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
        
        $return = (($dataatual - $dataacesso)/60);//Pego a diferença entre o tempo
        
        return ceil($return);//Retorno a quantidade de minutos
    }


    /** Retorna o base64 de uma imagem a partir de sua URL */
    public function imageB64($imageUrl)
    {

        /** Verifica se a url da imagem foi informada para efetuar o procedimento */
        if($imageUrl){

            /** Parametros de entrada */
            $this->imageUrl = $imageUrl;
            
            /** Carrega a url de uma determinada imagem */
            $path = $this->imageUrl;

            /** Recupera a extensão da imagem */
            $type = pathinfo($path, PATHINFO_EXTENSION);

            /** Carrega o buffer da imagem */
            $data = file_get_contents($path);
            
            /** Devolve a imagem em base64 já com as definições de visualização */
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            return $base64;

        }

    }

    /**Trata valor para gravar no banco*/
    public function MoeadDB($value)
    {
        if($value){
        
            $v = str_replace(".", "", $value);
            $v = str_replace(",", ".", $v);
            
            return (float)$v;

        }
        
    }    

    /**Trata data no banco*/
    public function DataDB($value)
    {
        if($value){

            $d = explode("/", $value);
            $date = $d[2].'-'.$d[1].'-'.$d[0];
            
            return (string)$date;

        }else{

            return false;
        }
        
    }

    /**Retorna o tempo entre datas em dias*/
    public function CheckDay($datahora)
    {
        $a_ano      = substr($datahora, 0,4);
        $a_mes      = substr($datahora, 5,2);
        $a_dia      = substr($datahora, 8,2);
        $a_hora     = substr($datahora, 11,2);
        $a_minuto   = substr($datahora, 14,2);
        $a_segundos = substr($datahora, 17,2);
        
        // Obtém um timestamp Unix para a data informada
        $dataacesso = mktime((int)$a_hora, (int)$a_minuto, (int)$a_segundos, (int)$a_mes, (int)$a_dia, (int)$a_ano);
        
        // Pego a data atual 
        $dataatual  = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
        
        $return = (($dataatual - $dataacesso)/(60 * 60 * 24));//Pego a diferença entre o tempo
        
        return ceil($return);//Retorno a quantidade de dias
    }    
    

    /** Gera uma senha aleatoriamente */
    public function NewPassword()
    {
        /**SEQUENCIAS ALEATORIAS DE LETRAS E NUMEROS*/
        $pwdtex = substr(str_shuffle('abcdefghijklmnpqrstuvxzwy'),0,3);
        $pwdint = substr(str_shuffle('123456789'),0,3);
        $pwdcar = substr(str_shuffle('@!_'),0,1);
        
        /**PEGO A DATA E HORA ATUAL + OS MICROSEGUNDOS E CONVERTO PARA MD5*/
        $data = substr(md5(date("dmYHis").substr(sprintf("%0.1f",microtime()),-1)),0,1);        
        $pwd = str_shuffle($pwdtex.$pwdint.$data.$pwdcar);//Gero a nova senha aleatoriamente		
        
        return $pwd;
    } 
    
    /** Valida CPF/CNPJ */
    public function cpfj($document) {
        $l = strlen($document = str_replace(array(".","-","/"),"",$document));
        if ((!is_numeric($document)) || (!in_array($l,array(11,14))) || (count(count_chars($document,1))==1)) {
            return false;
        }
        $cpfj = str_split(substr($document,0,$l-2));
        $k = 9;
        $s = 0;
        for ($j=0;$j<2;$j++) {
            for ($i=(count($cpfj));$i>0;$i--) {
                $s += $cpfj[$i-1] * $k;
                $k--;
                $l==14&&$k<2?$k=9:1;
            }
            $cpfj[] = $s%11==10?0:$s%11;
            $s = 0;
            $k = 9;
        }    
        return $document==join($cpfj);
    } 

    public function setzeros($valor, $qtde)
    {
        $result = $valor;
        $tamanho = strlen($valor);
        $valor = "";
        for ($i=0; $i < $qtde-$tamanho;$i++)
        {
            $valor = "0" . $valor;
        }
        $result = $valor . $result;
        return $result;
    }    
    
    /** Validar data */
    public function validateDate($date, $format = 'd/m/Y')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    } 
    
    /** Validar hora */
    public function validateHour($date, $format = 'H:i')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    } 
    
    
    //Verificando se uma string possui letras e numeros
    public function checkNumbers($senha)
    {
        //Converte a entrada para minusculo
        $str = strtolower($senha);
        
        /** Verifico se a senha possui letras e numeros */
        if((preg_match('/[0-9]/', $str)) && (preg_match('/[a-z]/', $str)))
        return true;
        else
        return false;
        
    } 
    
    /** Encontra a posição da primeira ocorrência de uma string  */
    public function strPos(string $str, string $findme) : bool
    {

        /** Parametros de entrada */
        $this->str = $str;
        $this->findme = $findme;

        /** Verifica a primeira ocorrência da string */
        $this->pos = strpos($str, $findme);

        /** Verifica se a string foi localizada */
        if($this->pos === false){
            return false;
        }else{
            return true;
        }
    }
    
    /** Valida a senha */
    public function validatePasswordStrength($pwd) {
        
        
        /** Verifica se a senha de acesso precisa ter 
         * letras e números e pelo menos 
         * uma letra maiúscula ou minúscula 
         * e ter no mínimo oito(8) dígitos */
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w$@]{8,}$/', $pwd);

    } 
    
    /** Gera um password hash */
    public function passwordHash($pwd){

        /** Parametros de entradas */
        $this->pwd = $pwd;

        /** Verifica se a senha foi informada */
        if($this->pwd){

            $hash = PASSWORD_DEFAULT;/** Padrão de criptogrfia */
            $cost = array("cost"=>10);/** Nível de criptografia */  

            /** Gera o hash da senha */
            return password_hash($this->pwd, $hash, $cost);
            
        }

    }


    /** Remove caractes especiais */
    public function cleanSpecialCharacters($string)
    {
        //Minusculas
        $n = str_replace("ã", "a", $string);
        $n = str_replace("à", "a", $n);

        $n = str_replace("á", "a", $n);
        $n = str_replace("é", "e", $n);
        $n = str_replace("è", "e", $n);
        $n = str_replace("ê", "e", $n);
        $n = str_replace("í", "i", $n);
        $n = str_replace("î", "i", $n);
        $n = str_replace("õ", "o", $n);
        $n = str_replace("ó", "o", $n);
        $n = str_replace("ò", "o", $n);
        $n = str_replace("ô", "o", $n);
        $n = str_replace("ú", "u", $n);
        $n = str_replace("ù", "u", $n);
        $n = str_replace("ç", "c", $n);
        
        //Maiusculas
        $n = str_replace("Ã", "A", $n);
        $n = str_replace("À", "A", $n);
        $n = str_replace("Á", "A", $n);
        $n = str_replace("É", "E", $n);
        $n = str_replace("È", "E", $n);
        $n = str_replace("Ê", "E", $n);
        $n = str_replace("Í", "I", $n);
        $n = str_replace("Î", "I", $n);
        $n = str_replace("Õ", "O", $n);
        $n = str_replace("Ó", "O", $n);
        $n = str_replace("Ò", "O", $n);
        $n = str_replace("Ô", "O", $n);
        $n = str_replace("Ú", "U", $n);
        $n = str_replace("Ù", "U", $n);
        $n = str_replace("Ç", "C", $n);
        
        //Caracteres Especiais
        $n = str_replace("º", " ", $n);
        $n = str_replace("ª", " ", $n);
        $n = str_replace("&", " ", $n);
        $n = str_replace("/", "-", $n);
        
        
        return $n;
    }
    
    /** Substitui espações vazios por underline */
    public function setUnderline($str){

        return str_replace(" ", "_", $str);

    }
       

    /** Função que trata os nomes de funcoes */
    public function nameFunction($str){

        //TRATA A VARIAVEL
        $var = explode(" ", strtolower($str));
        

        $j=0;
        $n=null;
        foreach($var as $value){
            
            if($j == 0){

                $n .= $value;
                $j++;

            }elseif($j > 0){

                $n .= ucwords($value);
                $j++;
            }
        }

        return $n;

        unset($j);
        unset($n);	

    }  


    /** Função que trata as variaveis */
    public function trataString($str){

        //TRATA A VARIAVEL
        $var = explode("_", strtolower(  str_replace("-", "_", str_replace(" ", "_", $str) ) ) );
        

        $j=0;
        $n=null;
        foreach($var as $value){
            
            if($j == 0){

                $n .= $value;
                $j++;

            }elseif($j > 0){

                $n .= ucwords($value);
                $j++;
            }
        }

        return $n;

        unset($j);
        unset($n);	

    }  

    /** Função que trata os nomes das class */
    public function trataClass($str){

        //TRATA A VARIAVEL
        $var = explode("_", strtolower($str));
        

        $j=0;
        $n=null;
        foreach($var as $value){

            $n .= ucwords($value);
            $j++;
        }

        return $n.".class.php";

        unset($j);
        unset($n);	

    } 
    
    /** Função que trata os nomes das class */
    public function nameClass($str){

        //TRATA A VARIAVEL
        $var = explode("_", strtolower($str));
        

        $j=0;
        $n=null;
        foreach($var as $value){

            $n .= ucwords($value);
            $j++;
        }

        return $n;

        unset($j);
        unset($n);	

    }     
    
    /** Gera o arquivo que não existe */
    public function createFile($REQUEST, $FILE){


        $lenght  = 0;
        $buffer  = "";
        $not     = array('TABLE', 'ACTION', 'FOLDER', 'PHPSESSID');
        $table   = "";
        $virgula = [];
        $i       = 0;
        
        foreach($REQUEST as $key => $value){
        
        
            /** Verifica se o tamanho da string é maior que o armazenado */
            if(strlen($key) > $lenght){
        
                $lenght = strlen($key);
            }

            /** Captura o nome da tabela */
            if($key == 'TABLE'){

                $table  = $value;
            }
        
            if(!in_array($key, $not)){
                
                if($i > 0){
                    array_push($virgula, ", ");
                }

                $i++;
            }            
        
        }

        $i = 1;
                
        /** Inicio do arquivo */
        $buffer .= "<?php\r\r";

        $buffer .= '/** Importação de classes  */'."\r";
        $buffer .= "use vendor\model\\".$this->nameClass($table).";\r";
        $buffer .= "use vendor\controller\\".$table."\\".$this->nameClass($table)."Validate;\r\r";

        $buffer .= "try{\r\r";

        $buffer .= '    /** Instânciamento de classes  */'."\r";
        $buffer .= "    $".$this->nameClass($table)." = new ".$this->nameClass($table)."();\r";
        $buffer .= "    $".$this->nameClass($table)."Validate = new ".$this->nameClass($table)."Validate();\r\r";

        $buffer .= '    /** Parametros de entrada  */'."\r\n";
        
        foreach($REQUEST as $key => $value){
        
            if(!in_array($key, $not)){
        
                //echo '\$'.$value;
                $buffer .= '    $'.str_pad($this->trataString($key),$lenght, " ").'= isset($_POST[\''.$key.'\']) ? filter_input(INPUT_POST,\''.$key.'\', FILTER_SANITIZE_SPECIAL_CHARS) : '.(is_numeric($key) ? '0' : "''").";\r\n";
            }
        }

        $buffer .= "\r\r";
        $buffer .= "    /** Validando os campos de entrada */\r";
        foreach($REQUEST as $key => $value){
        
            if(!in_array($key, $not)){
        
                //echo '\$'.$value;
                $buffer .= "    $".$this->nameClass($table)."Validate->set".ucfirst($this->trataString($key))."(\$".$this->trataString($key).");\r";
            }
        }
        
        $buffer .= "\r\r";
        $buffer .= "    /** Verifico a existência de erros */\r";  
        $buffer .= "    if (!empty(\$".$this->nameClass($table)."Validate->getErrors())) {\r\r";      

        $buffer .= "        /** Preparo o formulario para retorno **/\r";
        $buffer .= "        \$result = [\r\r";
        $buffer .= "            'cod' => 0,\r";
        $buffer .= "            'title' => 'Atenção',\r";
        $buffer .= "            'message' => '<div class=\"alert alert-danger\" role=\"alert\">'.\$".$this->nameClass($table)."Validate->getErrors().'</div>',\r\r";
        $buffer .= "        ];\r\r";

        $buffer .= "    } else {\r\r";

        $buffer .= "        /** Efetua um novo cadastro ou salva os novos dados */\r";
        $buffer .= "        if (\$".$this->nameClass($table)."->Save("."$".$this->nameClass($table)."Validate->get".ucfirst($this->trataString($table))."Id(), ";
        
        foreach($REQUEST as $key => $value){
        
            if(!in_array($key, $not)){

                if($this->trataString($key) != $this->trataString($table)."Id"){
            
                    //echo '\$'.$value;
                    $buffer .= "$".$this->nameClass($table)."Validate->get".ucfirst($this->trataString($key))."()".$virgula[$i];
                    $i++;
                }                
            }
        } 
        $buffer .= ")){\r\r";       
        $buffer .= "            /** Prepara a mensagem de retorno - sucesso */\r";
        $buffer .= "            \$message = '<div class=\"alert alert-success\" role=\"alert\">'.(\$".$this->nameClass($table)."Validate->get".ucfirst($this->trataString($table))."Id() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso').'</div>';\r\r";

        $buffer .= "            /** Result **/\r";
        $buffer .= "            \$result = [\r\r";
        $buffer .= "                'cod' => 200,\r";
        $buffer .= "                'title' => 'Atenção',\r";
        $buffer .= "                'message' => \$message,\r";
        $buffer .= "                'redirect' => '',\r\r";
        $buffer .= "            ];\r\r";

        $buffer .= "        } else {\r\r";
        
        $buffer .= "            /** Prepara a mensagem de retorno - erro */\r";
        $buffer .= "            \$message = '<div class=\"alert alert-success\" role=\"alert\">'.(\$".$this->nameClass($table)."Validate->get".ucfirst($this->trataString($table))."Id() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro') .'</div>';\r\r";

        $buffer .= "            /** Result **/\r";
        $buffer .= "            \$result = [\r\r";
        $buffer .= "                'cod' => 0,\r";
        $buffer .= "                'title' => 'Atenção',\r";
        $buffer .= "                'message' => \$message,\r";
        $buffer .= "                'redirect' => '',\r\r";
        $buffer .= "            ];\r\r";  
        
        $buffer .= "        }\r\r";
        $buffer .= "    }\r\r";

        $buffer .= "    /** Envio **/\r";
        $buffer .= "    echo json_encode(\$result);\r\r";
    
        $buffer .= "    /** Paro o procedimento **/\r";
        $buffer .= "    exit;\r\r";

        

        $buffer .= "}catch(Exception \$exception){\r\n\r\n"; 
               
        $buffer .= "    /** Preparo o formulario para retorno **/\r\n";
        $buffer .= "    \$result = [\r\n\r\n";
        
        $buffer .= "        'cod' => 0,\r\n";
        $buffer .= "        'message' => \$exception->getMessage(),\r\n";
        $buffer .= "        'title' => 'Erro Interno',\r\n";
        $buffer .= "        'type' => 'exception',\r\n\r\n";
        
        $buffer .= "    ];\r\n\r\n";
        
        $buffer .= "    /** Envio **/\r\n";
        $buffer .= "    echo json_encode(\$result);\r\n\r\n";
        
        $buffer .= "    /** Paro o procedimento **/\r\n";
        $buffer .= "    exit;\r\n";   
        $buffer .= "}";  

        /** Verifica se os diretorios existem */
        if(!file_exists('vendor/'.$REQUEST['FOLDER'].'/'.$REQUEST['TABLE'])){

            /** Cria os diretorios dos arquivos */
            mkdir('vendor/'.$REQUEST['FOLDER'].'/'.$REQUEST['TABLE'], 0777, true);
        }

        $fp = fopen($FILE, 'w+');
        fwrite($fp, $buffer);
        fclose($fp);

    }

    /** Função para montar o nome de uma tabela a partir de uma biblioteca */
    public function nameTable($library){

        $table = "";

        $str = str_split($library);

        for($i=0; $i<count($str); $i++){

            
            if(ctype_upper($str[$i])){

                if($i == 0){

                    $table .= $str[$i]."_";

                }else{

                    $table .= strtoupper($str[$i]);

                }

            }else{

                $table .= strtoupper($str[$i]);

            }

        }

        return  $table;
        exit;        
    }

    /** Função que determina o tipo de campo */
    public function typeField(int $dataType){

        /** Parametro de entrada */
        $this->dataType = $dataType;

        if($this->dataType > 0){

            switch ($this->dataType){

                case 8: return 'INTEGER'; break;
                case 7: return 'SMALL'; break;
                case 10: return 'FLOAT'; break;
                case 27: return 'DOUBLE'; break;
                case 16: return 'NUMERIC'; break;
                case 37: return 'VARCHAR'; break;
                case 12: return 'DATE'; break;
                case 13: return 'TIME'; break;
                case 35: return 'TIMESTAMP'; break;
                case 261: return 'BLOB'; break;
            }

        }

    }

    /** Função que retorna o tipo de variavel */
    public function typeVar(string $parameterName){

        /** Parametros de entrada */
        $this->parameterName = $parameterName;

        /** Verifica se o parametro foi informado */
        if(!empty($this->parameterName)){

            switch ($this->parameterName){

                case 'INTEGER': return 'int'; break;
                case 'SMALL': return 'string'; break;
                case 'FLOAT': return 'float'; break;
                case 'DOUBLE': return 'double'; break;
                case 'NUMERIC': return 'int'; break;
                case 'VARCHAR': return 'string'; break;
                case 'DATE': return 'string'; break;
                case 'TIME': return 'string'; break;
                case 'TIMESTAMP': return 'string'; break;
                case 'BLOB': return 'string'; break;
            }            

        }

    }

    /** Retorna o mês informado pelo seu número */
    public function returnMonth(int $month){


        /** Parametros de entrada */
        $this->month = (int)$month;

        /** Verifica o mês informado */
        switch($this->month){

            case 1 : return 'Janeiro'; break;
            case 2 : return 'Fevereiro'; break;
            case 3 : return 'Março'; break;
            case 4 : return 'Abril'; break;
            case 5 : return 'Maio'; break;
            case 6 : return 'Junho'; break;
            case 7 : return 'Julho'; break;
            case 8 : return 'Agosto'; break;
            case 9 : return 'Setembro'; break;
            case 10 : return 'Outubro'; break;
            case 11 : return 'Novembro'; break;
            case 12 : return 'Dezembro'; break;

        }        

    }

    function __destruct(){ }


}

