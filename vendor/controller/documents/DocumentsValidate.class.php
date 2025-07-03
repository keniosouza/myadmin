<?php
/**
* Classe DocumentsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/documents
* @version		1.0
* @date		 	04/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\documents;

/** Importação de classes */
use vendor\model\Main;

class DocumentsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $documentsId = null;
	private $documentsDraftsId = null;
	private $documentsCategorysId = null;
	private $usersId = null;
	private $companyId = null;
	private $financialMovementsId = null;
	private $description = null;
	private $dateRegister = null;
	private $archive = null;
	private $extension = null;
	private $active = null;
	private $file = null;
	private $name = null;
	private $dirTemp = null;
	private $dirGeral = null;
	private $dirDocuments = null;
	private $dirUser = null;	
	private $dirCompany = null;
	private $dirYear = null;
	private $dirMonth = null;
	private $dirPermission = null;
	private $markings = null;
	private $base64 = null;	
	private $path = null;
    private $tag = [];
	private $tags = null;
    private $mask = [];  
	private $masks = null;
    private $required = [];
    private $format = [];	
	private $input = [];
	private $sanitize = [];
	private $list = null;
	private $dirFinancial = null;
	private $clientsId = null;
	private $titles = null;

	
	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

		/** Carrega as configurações */
		$config = $this->Main->LoadConfigPublic();

		/** Diretório do usuario */
		$this->dirTemp = "temp";
		$this->dirGeral = $config->app->ged;//Caminho aonde serão gravados os arquivos
		$this->dirFinancial = "financial";
		$this->dirDocuments = "documents";
		$this->dirCompany = (isset($_SESSION['USERSCOMPANYID'])) && ((int)$_SESSION['USERSCOMPANYID'] > 0) ? $this->Main->setzeros($_SESSION['USERSCOMPANYID'], 8) : 0;
		$this->dirUser = $this->Main->setzeros($_SESSION['USERSID'], 8);
		$this->dirYear = date('Y');
		$this->dirMonth = date('m');
		$this->dirPermission = 0777;
		$this->markings = new \stdClass();		

	}

	/** Método trata campo documents_id */
	public function setDocumentsId(int $documentsId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsId = isset($documentsId) ? (int)$this->Main->antiInjection($documentsId) : 0;

		/** Verifica se a informação foi informada */
		if($this->documentsId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "ID do documento", deve ser informado');

		}else{

			/** Descrição do arquivo */
			$this->markings->descricao = $this->description;

			/** Listas as marcações do arquivos */
			for($k=0; $k<count($this->tag); $k++){

				/** Tags */
				$masks = $this->mask[$k];
				$this->tags = $this->tag[$k];
				
				/** Prepara o objeto das marcações */
				$this->markings->$masks = new \stdClass();

				/** Carrega as marcações do arquivo */
				$this->markings->$masks->value = $this->tags;
				$this->markings->$masks->format =  $this->format[$k];
				$this->markings->$masks->required =  $this->required[$k];

			}		
		}

	}

	/** Método trata campo documents_drafts_id */
	public function setDocumentsDraftsId(int $documentsDraftsId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsDraftsId = isset($documentsDraftsId) ? $this->Main->antiInjection($documentsDraftsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->documentsDraftsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "documents_drafts_id", deve ser informado');

		}

	}

	/** Método trata campo documents_categorys_id */
	public function setDocumentsCategorysId(int $documentsCategorysId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsCategorysId = isset($documentsCategorysId) ? (int)$this->Main->antiInjection($documentsCategorysId) : 0;

		/** Verifica se a informação foi informada */
		if( ((int)$this->documentsCategorysId < 0) && ($this->documentsId == 0))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Selecione uma categoria');

		}

	}

	/** Método trata campo users_id */
	public function setUsersId(int $usersId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersId = isset($usersId) ? $this->Main->antiInjection($usersId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_id", deve ser informado');

		}

	}

	/** Método trata campo clients_id */
	public function setClientsId(int $clientsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsId = $clientsId > 0 ? (int)$this->Main->antiInjection($clientsId) : 0;

		// /** Verifica se a informação foi informada */
		// if(empty($this->clientsId))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "clients_id", deve ser informado');

		// }

	}

	/** Método trata campo company_id */
	public function setCompanyId(int $companyId) : void
	{

		/** Trata a entrada da informação  */
		$this->companyId = isset($companyId) ? $this->Main->antiInjection($companyId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->companyId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "company_id", deve ser informado');

		}

	}

	/** Método trata campo financial_movements_id */
	public function setFinancialMovementsId(int $financialMovementsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialMovementsId = isset($financialMovementsId) ? $this->Main->antiInjection($financialMovementsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->financialMovementsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "financial_movements_id", deve ser informado');

		}

	}

	/** Método trata campo description */
	public function setDescription(string $description) : void
	{

		/** Trata a entrada da informação  */
		$this->description = isset($description) ? $this->Main->antiInjection($description) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->description))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Descrição", deve ser informado');

		}

	}

	/** Método trata campo date_register */
	public function setDateRegister(string $dateRegister) : void
	{

		/** Trata a entrada da informação  */
		$this->dateRegister = isset($dateRegister) ? $this->Main->antiInjection($dateRegister) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateRegister))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_register", deve ser informado');

		}

	}

	/** Método trata campo archive */
	public function setArchive(string $archive) : void
	{

		/** Trata a entrada da informação  */
		$this->archive = isset($archive) ? $this->Main->antiInjection($archive) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->archive))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "archive", deve ser informado');

		}

	}

	/** Método trata campo extension */
	public function setExtension(string $extension) : void
	{

		/** Trata a entrada da informação  */
		$this->extension = isset($extension) ? $this->Main->antiInjection($extension) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->extension))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "extension", deve ser informado');

		}

	}

	/** Método trata campo active */
	public function setActive(string $active) : void
	{

		/** Trata a entrada da informação  */
		$this->active = isset($active) ? $this->Main->antiInjection($active) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->active))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "active", deve ser informado');

		}

	}

	/** Método trata campo tag */
	public function setTag(array $tag) : void
	{		

		/** Trata a entrada da informação  */
		$this->tag = count($tag) > 0 ? $this->Main->antiInjectionArray($tag) : [];

		/** Verifica se a informação foi informada */
		if(count($this->tag) == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma marcação informado para o arquivo selecionado');

		}else{

			for($i=0; $i<count($this->tag); $i++){

                /** Verifica se o campo obrigatório foi informado */
                if( $this->required[($i+1)] == 'S' ){
                   
                    /** Leganda dos tippos de marcações 
                     * 
                     *  1 => Texto
                     *  2 => Número
                     *  3 => Data
                     *  4 => Monetário
                     *  5 => CPF
                     *  6 => CNPJ
                     *  7 => CEP
                     *  8 => Telefone
                     *  9 => Celular
                     *  10 => E-mail
                     *  11 => OAB
                     *  12 => RG
                     * 
                    */                    

                    /** Verifica o formato do campo para validar o mesmo */
                    switch ((int)$this->format[$i]){
                                        
                        /** Texto */
                        case 1:

                            /** Caso a informação não tenha sido informada, informo */
                            if( empty($this->tag[$i]) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');                                                                           
                            }

                        break;                    

                        /** Número */
                        case 2:

                            /** Caso a número não tenha sido informada, informo */
                            if( (int)$this->tag[$i] == 0){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');                                                
                            }                        

                        break;

                        /** Data */
                        case 3:                            
                            
                            /** Caso a data não tenha sido informada ou esteja em formato inválido, informo */
                            if( (empty($this->tag[$i])) || (!$this->Main->validaData($this->tag[$i])) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado ou esta em um formato inválido');								         
                            }   

                        break;
                        
                        /** Montetário */
                        case 4:

                            /** Caso o valor não tenha sido informado ou esteja em formato inválido, informo */
                            if( (!is_float($this->Main->MoeadDB($this->tag[$i]))) || ($this->tag[$i] == '0,00') ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');           
                            }                          

                        break;

                        /** CPF */
                        case 5:

                            /** Caso o CPF não tenha sido informada ou esteja em formato inválido, informo */
                            if( !$this->Main->cpfj($this->tag[$i]) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado, ou está em um formato inválido');           
                            } 

                        break;

                        
                        /** CNPJ */
                        case 6:

                            /** Caso o CNPJ não tenha sido informada ou esteja em formato inválido, informo */
                            if( !$this->Main->cpfj($this->tag[$i]) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado, ou está em um formato inválido');
                            } 

                        break;
                        
                        /** CEP */
                        case 7:

                            /** Caso o CEP não tenha sido informado, informo */
                            if( (empty($this->Main->clearDoc($this->tag[$i]))) || ((int)$this->Main->clearDoc($this->tag[$i]) == 0) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');            
                            }

                        break;


                        /** Telefone */
                        case 8:

                            /** Caso o Telefone não tenha sido informado, informo */
                            if( (empty($this->Main->clearDoc($this->tag[$i]))) || ((int)$this->Main->clearDoc($this->tag[$i]) == 0) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');                                                                       
                            }                        

                        break;
                        
                        /** Celular */
                        case 9:

                            /** Caso o Celular não tenha sido informado, informo */
                            if( (empty($this->Main->clearDoc($this->tag[$i]))) || ((int)$this->Main->clearDoc($this->tag[$i]) == 0) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');            
                            }                           

                        break;


                        /** Email */
                        case 10:

                            /** Caso o E-mail não tenha sido informado, informo */
                            if( (empty($this->Main->clearDoc($this->tag[$i]))) || (!$this->Main->validarEmail($this->tag[$i])) ){

                                /** Trata a mensagem de resposta */
                                array_push($this->errors, 'O campo "'.$this->Main->treatMask($this->mask[$i]).'", deve ser informado');               
                            }                           

                        break;                    
                                                                
                    }
                }  				
			}
		}
	}

	/** Método trata campo tag */
	public function setMask(array $mask) : void
	{

		/** Trata a entrada da informação  */
		$this->mask = count($mask) > 0 ? $this->Main->antiInjectionArray($mask) : [];

		/** Verifica se a informação foi informada */
		if(count($this->mask) == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "mask", deve ser informado');

		}

	}	

	/** Método trata campo tag */
	public function setRequired(array $required) : void
	{

		/** Trata a entrada da informação  */
		$this->required = count($required) > 0 ? $this->Main->antiInjectionArray($required) : [];

		/** Verifica se a informação foi informada */
		if(count($this->required) == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "required", deve ser informado');

		}

	}	

	/** Método trata campo tag */
	public function setFormat(array $format) : void
	{

		/** Trata a entrada da informação  */
		$this->format = count($format) > 0 ? $this->Main->antiInjectionArray($format) : [];

		/** Verifica se a informação foi informada */
		if(count($this->format) == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "format", deve ser informado');

		}

	}	

	/** Método trata campo tag */
	public function setPath(string $path) : void
	{

		/** Trata a entrada da informação  */
		$this->path = isset($path) ? (string)$this->Main->antiInjection($path) : null;

		/** Verifica se a informação não foi informada */
		if( (empty($this->path)) && ($this->documentsId == 0) )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "Arquivo", deve ser informado');

		}else if( (!empty($this->path)) && ($this->documentsId == 0) ){/** Caso o arquivo tenha sido informado, gravo o arquivo na pasta correta */


			/** Verifica se o arquivo existe na pasta temporária */
			if( (is_file($this->path)) && ($this->documentsId == 0) ){
				
				/** Verifica se a pasta documents existe */
				if( !is_dir($this->dirGeral.'/'.$this->dirDocuments) ){

					/** Cria o diretório */
					mkdir($this->dirGeral.'/'.$this->dirDocuments, $this->dirPermission);

				}

				/** Verifica se a pasta company existe */
				if( !is_dir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany) ){

					/** Cria o diretório */
					mkdir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany, $this->dirPermission);

				}

				/** Verifica se a pasta company/ano existe */
				if( !is_dir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear) ){

					/** Cria o diretório */
					mkdir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear, $this->dirPermission);

				}

				/** Verifica se a pasta company/ano/mês existe */
				if( !is_dir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth) ){

					/** Cria o diretório */
					mkdir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth, $this->dirPermission);  

				}
							
				/** Verifica se a pasta de destino existe */
				if( is_dir($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth) ){

					/** Pega a extensão do arquivo */
					$rev = explode(".", strrev($this->path));

					/** Pega a extensão do arquivo */
					$this->extension = strrev($rev[0]);

					/** Gera um nome de arquivo aleatorio */
					$this->archive = md5($this->Main->NewPassword()).'.'.$this->extension; 
					
					/** Descrição do arquivo */
					$this->markings->descricao = $this->description;

					/** Listas as marcações do arquivos */
					for($k=0; $k<count($this->tag); $k++){

						/** Tags */
						$masks = $this->mask[$k];
						$this->tags = $this->tag[$k];
						
						/** Prepara o objeto das marcações */
						$this->markings->$masks = new \stdClass();

						/** Carrega as marcações do arquivo */
						$this->markings->$masks->value = $this->tags;
						$this->markings->$masks->format =  $this->format[$k];
						$this->markings->$masks->required =  $this->required[$k];

					}
					
					/** Move o arquivo para o diretório de destino */
					rename($path, $this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->archive); 

					/** Verifica se o arquivo foi enviado corretamente, caso não tenha sido, informo */
					if( !is_file($this->dirGeral.'/'.$this->dirDocuments.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->archive) ){

						/** Adição de elemento */
						array_push($this->errors, 'Não foi possível mover o arquivo');						
					}

				}else{

					/** Adição de elemento */
					array_push($this->errors, 'Não foi possível mover o arquivo, diretório não encontrado');					
				}

			}else{

				/** Adição de elemento */
				array_push($this->errors, 'O "Arquivo", deve ser informado');								
			}				
		}
	}	


	/** Método trata campo file */
	public function setFile(string $file) : void
	{

		/** Trata a entrada da informação  */
		$this->file = isset($file) ? (string)$this->Main->antiInjection($file) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->file))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Selecione o arquivo');

		}else{

			/** Pega o base64 do arquivo */
			$this->base64 = explode(",", $this->file);

			/** Verifica se a pasta do usuário não existe, caso não exista, cria a mesma */
			if( !is_dir($this->dirTemp.'/'.$this->dirUser) ){  
				
				/** Cria a pasta do usuário */
				mkdir($this->dirTemp.'/'.$this->dirUser, $this->dirPermission); 
				
				/** Verifica se a pasta do usuário foi criada */
				if( !is_dir($this->dirTemp.'/'.$this->dirUser) ){

					/** Caso não tenha sido criada informo */
					/** Adição de elemento */
					array_push($this->errors, 'Não foi possível carregar criar o ditetório');

				}else{

					/** Grava o arquivo na pasta temporária */
					$fp = fopen($this->dirTemp.'/'.$this->dirUser.'/'.$this->name, 'w');
						  fwrite($fp, base64_decode($this->base64[1]));
						  fclose($fp);
				}

			}else{

				/** Grava o arquivo na pasta temporária */
				$fp = fopen($this->dirTemp.'/'.$this->dirUser.'/'.$this->name, 'w');
					  fwrite($fp, base64_decode($this->base64[1]));
					  fclose($fp);				
			}	
		}		
	}

	/** Método trata campo file */
	public function setName(string $name) : void
	{

		/** Trata a entrada da informação  */
		$this->name = isset($name) ? (string)$this->Main->antiInjection($name) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->name))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Selecione o arquivo');
		}
	}

	/** Método trata campo titles */
	public function setTitles(string $titles) : void
	{

		/** Trata a entrada da informação  */
		$this->titles = isset($titles) ? (string)$this->Main->antiInjection($titles) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->titles))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhum título selecionado para esta solicitação');
		}
	}	
	
	/** Sanitiza array */
	public function setSanitizeArray(array $input)
	{

		/** Trata a entrada da informação  */
		$this->sanitize = count($input) > 0 ? $input : [];

		/** Limpa array de input */
		$this->input = array();

		/** Verficia se foram informado itens */
		if( count($this->sanitize) > 0 ){

			foreach($this->sanitize as $value){
			
				array_push($this->input, addslashes($this->Main->antiInjection($value)));
			}
		}

		/** Retorna a array tratada */
		return $this->input;
	}

	/** Método trata campo setDirYear */
	public function setDirYear(string $dateRegister) : void
	{
		/** Retorna o ano/diretório do arquivo  */
		$this->dirYear = date('Y', strtotime($dateRegister));

	}

	/** Método trata campo setDirYear */
	public function setdirMonth(string $dirMonth) : void
	{
		/** Retorna o ano/diretório do arquivo  */
		$this->dirMonth = date('m', strtotime($dirMonth));

	}	
	
	/** Método retorna campo DirDocuments */
	public function getDirDocuments() : ? string
	{
		
		/** Retorna o ano/diretório do arquivo  */
		return (string)$this->dirDocuments;
	}	

	/** Método retorna campo DirYear */
	public function getDirYear() : ? string
	{
		
		/** Retorna o ano/diretório do arquivo  */
		return (string)$this->dirYear;
	}
	
	/** Método retorna campo dirMonth */
	public function getDirMonth() : ? string
	{
		
		/** Retorna o ano/diretório do arquivo  */
		return (string)$this->dirMonth;
	}	

	/** Método retorna campo DirCompany */
	public function getDirCompany() : ? string
	{
		
		/** Retorna o ano/diretório do arquivo  */
		return (string)$this->dirCompany;
	}		


	/** Método retorna campo documents_id */
	public function getDocumentsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsId;
	}

	/** Método retorna campo clients_id */
	public function getClientsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsId;
	}

	/** Método retorna campo documents_drafts_id */
	public function getDocumentsDraftsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsDraftsId;
	}

	/** Método retorna campo documents_categorys_id */
	public function getDocumentsCategorysId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsCategorysId;
	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;
	}

	/** Método retorna campo company_id */
	public function getCompanyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->companyId;
	}

	/** Método retorna campo financial_movements_id */
	public function getFinancialMovementsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialMovementsId;
	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;
	}

	/** Método retorna campo date_register */
	public function getDateRegister() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateRegister;
	}

	/** Método retorna campo titles */
	public function getTitles() : ? string
	{

		/** Retorno da informação */
		return (string)$this->titles;
	}	

	/** Método retorna campo archive */
	public function getArchive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->archive;

	}

	/** Método retorna campo extension */
	public function getExtension() : ? string
	{

		/** Retorno da informação */
		return (string)$this->extension;

	}

	/** Método retorna campo active */
	public function getActive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->active;

	}

	/** Método retorna campo tag */
	public function getTag() : ? string
	{

		/** Retorno da informação */
		return (string)$this->tag;

	}

	/** Método retorna campo file */
	public function getFile() : ? string
	{

		/** Retorno da informação */
		return (string)$this->file;

	}
	
	/** Método retorna campo name */
	public function getName() : ? string
	{

		/** Retorno da informação */
		return (string)$this->name;

	}	

	/** Método retorna campo dirGeral */
	public function getDirGeral() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirGeral;

	}
	
	/** Método retorna campo dirTemp */
	public function getDirTemp() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirTemp;

	}	

	/** Método retorna campo dirUser */
	public function getDirUser() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirUser;

	}	

	/** Método retorna campo markings */
	public function getMarkings() : ? object
	{

		/** Retorno da informação */
		return (object)$this->markings;

	}

	/** Método retorna campo dirFinancial */
	public function getDirFinancial() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirFinancial;

	}

	/** Método retorna o campo Path de documentos financeiros */
	public function getPathDocFinancial() : ? string
	{

		/** Caminho absoluto do arquivo */
		$this->path = $this->getDirGeral()."/".$this->getDirFinancial()."/".$this->getDirCompany()."/".$this->getDirYear()."/".$this->getDirMonth()."/";		

		/** Retorno da informação */
		return (string)$this->path;

	}	
	
	/** Método retorna o campo Path */
	public function getPath() : ? string
	{

		/** Caminho absoluto do arquivo */
		$this->path = $this->getDirGeral()."/".$this->getDirDocuments()."/".$this->getDirCompany()."/".$this->getDirYear()."/".$this->getDirMonth()."/";		

		/** Retorno da informação */
		return (string)$this->path;

	}

	public function getErrors(): ? string
	{

		/** Verifico se deve informar os erros */
		if (count($this->errors)) {

	   		/** Verifica a quantidade de erros para informar a legenda */
	   		$this->info = count($this->errors) > 1 ? '<center>Os seguintes erros foram encontrados</center>' : '<center>O seguinte erro foi encontrado</center>';

	    	/** Lista os erros  */
	    	foreach ($this->errors as $keyError => $error) {

	        	/** Monto a mensagem de erro */
	        	$this->info .= '</br>' . ($keyError + 1) . ' - ' . $error;

    		}

    		/** Retorno os erros encontrados */
    		return (string)$this->info;

		} else {

    		return false;

		}

	}

	/** destrutor da classe */
	public function __destruct(){}	
}
