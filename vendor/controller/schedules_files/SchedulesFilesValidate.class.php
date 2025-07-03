<?php
/**
* Classe SchedulesFilesValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/schedules_files
* @version		1.0
* @date		 	31/01/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\schedules_files;

/** Importação de classes */
use vendor\model\Main;

class SchedulesFilesValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $schedulesFilesId = null;
	private $schedulesId = null;
	private $usersId = null;
	private $companyId = null;
	private $file = null;
	private $name = null;
	private $active = null;
	private $dateFile = null;
	private $nameFile = null;
	private $nameFiles = null;
	private $dirGeral = null;
	private $dirCompany = null;
	private $dirYear = null;
	private $dirMonth = null;
	private $dirPermission = null;	

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

		/** Parametros para criação de diretórios */
		$this->dirGeral = "schedules";
		$this->dirCompany = ( isset($_SESSION['USERSCOMPANYID']) && $_SESSION['USERSCOMPANYID'] > 0 ? $this->Main->setzeros($_SESSION['USERSCOMPANYID'], 8) : 0 ) ;
		$this->dirYear = date('Y');
		$this->dirMonth = date('m');
		$this->dirPermission = 0777;		

	}

	/** Método trata campo schedules_files_id */
	public function setSchedulesFilesId(int $schedulesFilesId) : void
	{

		/** Trata a entrada da informação  */
		$this->schedulesFilesId = $schedulesFilesId > 0 ? (int)$this->Main->antiInjection($schedulesFilesId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->schedulesFilesId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhum arquivo para donwload informado');

		}

	}

	/** Método trata campo schedules_id */
	public function setSchedulesId(int $schedulesId) : void
	{

		/** Trata a entrada da informação  */
		$this->schedulesId = $schedulesId > 0 ? $this->Main->antiInjection($schedulesId) : 0;

		/** Verifica se a informação foi informada */
		if(empty($this->schedulesId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "schedules_id", deve ser informado');

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

	/** Método trata campo file */
	public function setFile(string $file) : void
	{

		/** Trata a entrada da informação  */
		$this->file = isset($file) ? $this->Main->antiInjection($file) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->file))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Selecione um arquivo para esta solicitação');

		}

	}

	/** Método trata campo name */
	public function setName(string $name) : void
	{

		/** Trata a entrada da informação  */
		$this->name = isset($name) ? $this->Main->antiInjection($name) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->name))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhum nome de arquivo informado para esta solicitação');

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

	/** Método trata campo date_file */
	public function setDateFile(string $dateFile) : void
	{

		/** Trata a entrada da informação  */
		$this->dateFile = isset($dateFile) ? $this->Main->antiInjection($dateFile) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateFile))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_file", deve ser informado');

		}

	}

	/** Método trata campo name_files de envio de arquivos */
	public function setNameFiles(string $nameFiles) : void
	{

		/** Trata a entrada da informação  */
		$this->nameFiles = isset($nameFiles) ? $this->Main->antiInjection($nameFiles) : null;

		/** Verifica se o arquivo foi enviado */
		if(!empty($this->nameFiles)){

			/** Verifica se o arquivo exite na pasta temporária */
			if(is_file('temp/'.$this->nameFiles)){

										
				/** Verifica se o ID da company foi informado */
				if( (int)$this->dirCompany > 0 ){

					/** Verifica se a pasta company existe */
					if( !is_dir('ged/'.$this->dirGeral.'/'.$this->dirCompany) ){

						/** Cria o diretório */
						mkdir('ged/'.$this->dirGeral.'/'.$this->dirCompany, $this->dirPermission);

					}

					/** Verifica se a pasta company/ano existe */
					if( !is_dir('ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear) ){

						/** Cria o diretório */
						mkdir('ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear, $this->dirPermission);

					}

					/** Verifica se a pasta company/ano/mês existe */
					if( !is_dir('ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Cria o diretório */
						mkdir('ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth, $this->dirPermission);

					}

					/** Verifica se a pasta de destino existe */
					if( is_dir('ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Pega a extensão do arquivo */
						$rev = explode(".", strrev($this->nameFiles));

						/** Pega a extensão do arquivo */
						$ext = strrev($rev[0]);

						/** Gera um nome de arquivo aleatorio */
						$this->nameFile = md5($this->Main->NewPassword()).'.'.$ext;                                        

						/** Move o arquivo para o diretório de destino */
						rename('temp/'.$this->nameFiles, 'ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->nameFile); 

						/** Verifica se o arquivo foi enviado corretamente */
						if( !is_file('ged/'.$this->dirGeral.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->nameFile) ){

							/** Caso o arquivo não tenha sido enviado corretamente, informo */
							array_push($this->errors, 'Não foi possível gravar o arquivo '.$this->nameFiles);
						}                                             

					}else{/** Caso a pasta destino não exista informo */

						/** Caso o arquivo não tenha sido enviado corretamente, informo */
						array_push($this->errors, 'Não foi possível gravar o arquivo '.$this->nameFiles .' - diretório não encontrado');
					}
					
				}else{/** Caso nenhuma empresa tenha sido informada, gravo o arquivo para o usuário */


					/** Verifica se a pasta ano existe */
					if( !is_dir('ged/'.$this->dirGeral.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Cria o diretório */
						mkdir('ged/'.$this->dirGeral.'/'.$this->dirYear, $this->dirPermission);
					}

					/** Verifica se a pasta ano/mês existe */
					if( !is_dir('ged/'.$this->dirGeral.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Cria o diretório */
						mkdir('ged/'.$this->dirGeral.'/'.$this->dirYear.'/'.$this->dirMonth, $this->dirPermission);
					}

					/** Verifica se a pasta de destino existe */
					if( is_dir('ged/'.$this->dirGeral.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Pega a extensão do arquivo */
						$rev = explode(".", strrev($this->nameFiles));

						/** Pega a extensão do arquivo */
						$ext = strrev($rev[0]);

						/** Gera um nome de arquivo aleatorio */
						$this->nameFile = md5($this->Main->NewPassword()).'.'.$ext;                                        

						/** Move o arquivo para o diretório de destino */
						rename('temp/'.$this->nameFiles, 'ged/'.$this->dirGeral.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->nameFile); 

						/** Verifica se o arquivo foi enviado corretamente */
						if( !is_file('ged/'.$this->dirGeral.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->nameFile) ){

							/** Caso o arquivo não tenha sido enviado corretamente, informo */
							array_push($this->errors, 'Não foi possível gravar o arquivo '.$this->nameFiles);							
						}                                        
							
					}else{/** Caso o diretório não tenha sido criado */

						/** Caso o arquivo não tenha sido enviado corretamente, informo */
						array_push($this->errors, 'Não foi possível gravar o arquivo '.$this->nameFiles .' - diretório não encontrado');
					} 

				}

			}else{/** Caso o arquivo não tenha sido localizado */

				/** Caso o arquivo não tenha sido enviado corretamente, informo */
				array_push($this->errors, 'Não foi possível gravar o arquivo '.$this->nameFiles .' - arquivo não encontrado');                           
			}			

		}

	}		

	/** Método retorna campo schedules_files_id */
	public function getSchedulesFilesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->schedulesFilesId;

	}

	/** Método retorna campo schedules_id */
	public function getSchedulesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->schedulesId;

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

	/** Método retorna campo active */
	public function getActive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->active;

	}

	/** Método retorna campo date_file */
	public function getDateFile() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateFile;

	}

	/** Método retorna campo novo arquivo */
	public function getNameFile() : ? string
	{

		/** Retorno da informação */
		return (string)$this->nameFile;

	}	

	/** Método retorna campo arquivo */
	public function getNameFiles() : ? string
	{

		/** Retorno da informação */
		return (string)$this->nameFiles;

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
