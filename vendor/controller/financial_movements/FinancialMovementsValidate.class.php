<?php
/**
* Classe FinancialMovementsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_movements
* @version		1.0
* @date		 	14/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_movements;

/** Importação de classes */
use vendor\model\Main;

class FinancialMovementsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialMovementsId = null;
	private $financialAccountsId = null;
	private $financialEntriesId = null;
	private $financialOutputsId = null;
	private $usersId = null;
	private $companyId = null;
	private $clientsId = null;
	private $description = null;
	private $movementValue = null;
	private $movementValuePaid = null;
	private $movementDate = null;
	private $movementDateScheduled = null;
	private $movementDatePaid = null;
	private $movementValueFees = null;
	private $status = null;
	private $note = null;
	private $movementUserConfirmed = null;
	private $file = null;
	private $name = null;
	private $base64 = null;
	private $dirTemp = null;
	private $dirUser = null;
	private $dirPermission;
	private $dirGeral = null;
	private $dirCompany = null;
	private $dirFinancial = null;
	private $dirYear = null;
	private $dirMonth = null;	
	private $path = null;
	private $ext = null;
	private $archive = null;
	private $search = null;
	private $type = null;
	private $dateStart = null;
	private $dateEnd = null;
	private $reference = null;
	private $maturity = null;
	private $printType = null;
	private $ournumber = null;
	private $sanitize = null;
	private $input = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

		/** Diretório do usuario */
		$this->dirTemp = "temp";
		$this->dirGeral = "ged";
		$this->dirFinancial = "financial";
		$this->dirUser = $this->Main->setzeros($_SESSION['USERSID'], 8);		
		$this->dirCompany = isset($_SESSION['USERSCOMPANYID']) && $_SESSION['USERSCOMPANYID'] > 0 ? $this->Main->setzeros($_SESSION['USERSCOMPANYID'], 8) : 0;
		$this->dirYear = date('Y');
		$this->dirMonth = date('m');
		$this->dirPermission = 0777;
			
	}

	/** Método trata campo financial_movements_id */
	public function setFinancialMovementsId(int $financialMovementsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialMovementsId = isset($financialMovementsId) ? (int)$this->Main->antiInjection($financialMovementsId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->financialMovementsId == 0)
		{
			
			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma "Movimentação" informada para esta solicitação');

		}

	}

	/** Método trata campo financial_accounts_id */
	public function setFinancialAccountsId(int $financialAccountsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialAccountsId = isset($financialAccountsId) ? $this->Main->antiInjection($financialAccountsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->financialAccountsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "financial_accounts_id", deve ser informado');

		}

	}

	/** Método trata campo financial_outputs_id */
	public function setFinancialTypeId(int $financialOutputsId, int $financialEntriesId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialOutputsId = isset($financialOutputsId) ? (int)$this->Main->antiInjection($financialOutputsId) : 0;
		$this->financialEntriesId = isset($financialEntriesId) ? (int)$this->Main->antiInjection($financialEntriesId) : 0;

		/** Verifica se a informação foi informada */
		if( ($this->financialOutputsId == 0) && ($this->financialEntriesId == 0) )
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma "Entrada ou Saída" informados para esta solicitação');

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

	/** Método trata campo clients_id */
	public function setClientsId(int $clientsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsId = isset($clientsId) ? $this->Main->antiInjection($clientsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->clientsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "clients_id", deve ser informado');

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
			array_push($this->errors, 'O campo "description", deve ser informado');

		}

	}

	/** Método trata campo ournumber */
	public function setOurNumber(string $ournumber) : void
	{

		/** Trata a entrada da informação  */
		$this->ournumber = isset($ournumber) ? $this->Main->antiInjection($ournumber) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->ournumber))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Nosso Número", deve ser informado');

		}

	}	

	/** Método trata campo reference */
	public function setReference(string $reference) : void
	{

		/** Trata a entrada da informação  */
		$this->reference = isset($reference) ? $this->Main->antiInjection($reference) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->reference))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "referência", deve ser informada');

		}

	}	

	/** Método trata campo maturity */
	public function setMaturity(string $maturity) : void
	{

		/** Trata a entrada da informação  */
		$this->maturity = isset($maturity) ? $this->Main->antiInjection($maturity) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->maturity))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "vencimento", deve ser informado');

		}

	}	

	/** Método trata campo movement_value */
	public function setMovementValue(string $movementValue) : void
	{

		/** Trata a entrada da informação  */
		$this->movementValue = isset($movementValue) ? (float)$this->Main->MoeadDB($this->Main->antiInjection($movementValue)) : 0;		

		/** Verifica se a informação foi informada */
		if( (empty($this->movementValue)) || ($this->movementValue == '0') )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "Valor da movimentação", deve ser informado');

		}

	}

	/** Método trata campo movement_value_paid */
	public function setMovementValuePaid(string $movementValuePaid) : void
	{

		/** Trata a entrada da informação  */
		$this->movementValuePaid = isset($movementValuePaid) ? (float)$this->Main->MoeadDB($this->Main->antiInjection($movementValuePaid)) : 0;

		/** Verifica se a informação foi informada */
		if( (empty($this->movementValuePaid)) || ($this->movementValuePaid == '0') )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Valor a ser pago", deve ser informado');

		}

	}

	/** Método trata campo movement_date */
	public function setMovementDate(string $movementDate) : void
	{

		/** Trata a entrada da informação  */
		$this->movementDate = isset($movementDate) ? $this->Main->antiInjection($movementDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->movementDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "movement_date", deve ser informado');

		}

	}

	/** Método trata campo movement_date_scheduled */
	public function setMovementDateScheduled(string $movementDateScheduled) : void
	{

		/** Trata a entrada da informação  */
		$this->movementDateScheduled = isset($movementDateScheduled) ? $this->Main->antiInjection($movementDateScheduled) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->movementDateScheduled))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "data do agendamento/vencimento", deve ser informado');

		}

	}

	/** Método trata campo movement_date_paid */
	public function setMovementDatePaid(string $movementDatePaid) : void
	{

		/** Trata a entrada da informação  */
		$this->movementDatePaid = isset($movementDatePaid) ? (string)$this->Main->antiInjection($movementDatePaid) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->movementDatePaid))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data Pagamento", deve ser informado');

		}

	}

	/** Método trata campo movement_value_fees */
	public function setMovementValueFees(string $movementValueFees) : void
	{

		/** Trata a entrada da informação  */
		$this->movementValueFees = isset($movementValueFees) ? $this->Main->antiInjection($movementValueFees) : null;

		/** Verifica se a informação foi informada */
		/*if(empty($this->movementValueFees))
		{

			/** Adição de elemento */
			/*array_push($this->errors, 'O campo "movement_value_fees", deve ser informado');

		}*/

	}

	/** Método trata campo status */
	public function setStatus(int $status) : void
	{

		/** Trata a entrada da informação  */
		$this->status = isset($status) ? $this->Main->antiInjection($status) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->status))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "status", deve ser informado');

		}

	}

	/** Método trata campo note */
	public function setNote(string $note) : void
	{

		/** Trata a entrada da informação  */
		$this->note = isset($note) ? $this->Main->antiInjection($note) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->note))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Observação", deve ser informado');

		}

	}

	/** Método trata campo movement_user_confirmed */
	public function setMovementUserConfirmed(int $movementUserConfirmed) : void
	{

		/** Trata a entrada da informação  */
		$this->movementUserConfirmed = isset($movementUserConfirmed) ? $this->Main->antiInjection($movementUserConfirmed) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->movementUserConfirmed))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "movement_user_confirmed", deve ser informado');

		}

	}

	/** Método trata campo file, para upload de arquivos */
	public function setFile(string $file) : void
	{

		/** Trata a entrada da informação  */
		$this->file = isset($file) ? (string)$this->Main->antiInjection($file) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->file))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhum "Arquivo" enviado para esta solicitação');

		}else{

			/** Pega o base64 do arquivo */
			$this->base64 = explode(",", $this->file);

			/** Diretório do usuario */
			$this->dirUser = $this->Main->setzeros($_SESSION['USERSID'], 6);

			/** Verifica se a pasta do usuário não existe */
			if( !is_dir($this->dirTemp.'/'.$this->dirUser) ){  
				
				/** Cria a pasta do usuário */
				mkdir($this->dirTemp.'/'.$this->dirUser, $this->dirPermission);            

			}

			/** Grava o arquivo na pasta temporária */
			$fp = fopen($this->dirTemp.'/'.$this->dirUser.'/'.$this->name, 'w');
				  fwrite($fp, base64_decode($this->base64[1]));
				  fclose($fp);


			/** Verifica se o arquivo foi enviado, caso não tenha sido enviado informo */
			if(!is_file('temp/'.$this->dirUser.'/'.$this->name)){

				/** Adição de elemento */
				array_push($this->errors, 'Não foi possível mover o arquivo para pasta temporaria');				
			}		
		}

	}	

	/** Método trata campo name, para upload de arquivos */
	public function setName(string $name) : void
	{

		/** Trata a entrada da informação  */
		$this->name = isset($name) ? (string)$this->Main->antiInjection($name) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->name))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhum "Nome de Arquivo" enviado para esta solicitação');

		}

	}
	
	/** Método trata campo path, para guarda de arquivos */
	public function setPath(string $path) : void
	{

		/** Trata a entrada da informação  */
		$this->path = isset($path) ? (string)$this->Main->antiInjection($path) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->path))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhum "Caminho de Arquivo" informado para esta solicitação');

		}else{

			/** Verifica se o ID da movimentação foi informado */
			if($this->financialMovementsId > 0){

				/** Verifica se o arquivo informado existe na pasta temporária */
				if(is_file($this->path)){

					/** Verifica se a pasta financial existe */
					if( !is_dir($this->dirGeral.'/'.$this->dirFinancial) ){

						/** Cria o diretório */
						mkdir($this->dirGeral.'/'.$this->dirFinancial, $this->dirPermission);

					}					

					/** Verifica se a pasta company existe */
					if( !is_dir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany) ){

						/** Cria o diretório */
						mkdir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany, $this->dirPermission);

					}

					/** Verifica se a pasta company/ano existe */
					if( !is_dir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear) ){

						/** Cria o diretório */
						mkdir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear, $this->dirPermission);

					}

					/** Verifica se a pasta company/ano/mês existe */
					if( !is_dir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Cria o diretório */
						mkdir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth, $this->dirPermission);  

					}
										
					/** Verifica se a pasta de destino existe */
					if( is_dir($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth) ){

						/** Pega a extensão do arquivo */
						$rev = explode(".", strrev($path));

						/** Pega a extensão do arquivo */
						$this->ext = strrev($rev[0]);

						/** Gera um nome de arquivo aleatorio */
						$this->archive = md5($this->Main->NewPassword()).'.'.$this->ext; 
								
						/** Move o arquivo para o diretório de destino */
						rename($this->path, $this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->archive); 

						/** Verifica se o arquivo foi enviado corretamente */
						if( !is_file($this->dirGeral.'/'.$this->dirFinancial.'/'.$this->dirCompany.'/'.$this->dirYear.'/'.$this->dirMonth.'/'.$this->archive) ){

							/** Informo */
							array_push($this->errors, 'Não foi possível mover o arquivo para pasta de destino');
						}
								
					}else{

						/** Informo */
						array_push($this->errors, 'Nenhuma pasta disponível para esta solicitação');						
					}  


				}else{/** Caso o arquivo não exista na pasta temporária, informo */

					/** Informo */
					array_push($this->errors, 'Nenhum arquivo enviado para esta solicitação');
				}


			}else{/** Caso o id da mivimentação não tenha sido informada */

				/** Informo */
				array_push($this->errors, 'Nenhuma movimentação financeira informada para este arquivo');
			}			
		}

	}	


	/** Valida os campos de entrada/consulta */

	/** Método trata campo search, para consulta de movimentações */
	public function setSearch(string $search){

		/** Trata a entrada da informação  */
		$this->search = isset($search) ? (string)$this->Main->antiInjection($search) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->search))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Pesquisa", deve ser informado');

		}

	}

	/** Método trata campo type, para consulta de movimentações */
	public function setType($type){

		/** Trata a entrada da informação  */
		$this->type = isset($type) ? (string)$this->Main->antiInjection($type) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->type))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Tipo entrada ou saída", deve ser informado');

		}
	}

	/** Método trata campo dateStart, para consulta de movimentações */
	public function setDateStart(string $dateStart){

		/** Trata a entrada da informação  */
		$this->dateStart = isset($dateStart) ? (string)$this->Main->dataDB($this->Main->antiInjection($dateStart)) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->dateStart))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data inicial", deve ser informado');
		
		/** Verifica se a data informada é válida */
		}elseif(!$this->Main->validateDate($dateStart)){

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data inicial", deve de conter uma data válida');
		}
	}

	/** Método trata campo dateEnd, para consulta de movimentações */
	public function setDateEnd(string $dateEnd){

		/** Trata a entrada da informação  */
		$this->dateEnd = isset($dateEnd) ? (string)$this->Main->dataDB($this->Main->antiInjection($dateEnd)) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->dateEnd))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data final", deve ser informado');

		/** Verifica se a data informada é válida */
		}elseif(!$this->Main->validateDate($dateEnd)){

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data final", deve de conter uma data válida');
		}
	}

	/** Método trata campo dateEnd, para consulta de movimentações */
	public function setPrintType(int $printType){

		/** Trata a entrada da informação  */
		$this->printType = $printType > 0 ? (int)$this->Main->antiInjection($printType) : 0;

		/** Verifica se a informação foi informada */
		if($this->printType == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O tipo de impressão deve ser informado');

		}
	}	

	/** Método trata campo status, para consulta de movimentações */
	public function setStatusSearch(string $status){

		/** Trata a entrada da informação  */
		$this->status = isset($status) ? (int)$this->Main->antiInjection($status) : 0;

		/** Verifica se a informação foi informada */
		if($this->status == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Status pago", deve ser informado');

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

	/** Método retorna campo financial_movements_id */
	public function getFinancialMovementsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialMovementsId;

	}

	/** Método retorna campo financial_accounts_id */
	public function getFinancialAccountsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialAccountsId;

	}

	/** Método retorna campo financial_entries_id */
	public function getFinancialEntriesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialEntriesId;

	}

	/** Método retorna campo financial_outputs_id */
	public function getFinancialOutputsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialOutputsId;

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

	/** Método retorna campo clients_id */
	public function getClientsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo ournumber */
	public function getOurNumber() : ? string
	{

		/** Retorno da informação */
		return (string)$this->ournumber;

	}	

	/** Método retorna campo movement_value */
	public function getMovementValue() : ? string
	{

		/** Retorno da informação */
		return (string)$this->movementValue;

	}

	/** Método retorna campo movement_value_paid */
	public function getMovementValuePaid() : ? float
	{

		/** Retorno da informação */
		return (float)$this->movementValuePaid;

	}

	/** Método retorna campo movement_date */
	public function getMovementDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->movementDate;

	}

	/** Método retorna campo movement_date_scheduled */
	public function getMovementDateScheduled() : ? string
	{

		/** Retorno da informação */
		return (string)$this->movementDateScheduled;

	}

	/** Método retorna campo movement_date_paid */
	public function getMovementDatePaid() : ? string
	{

		/** Retorno da informação */
		return (string)$this->Main->DataDB($this->movementDatePaid);

	}

	/** Método retorna campo movement_value_fees */
	public function getMovementValueFees() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->movementValueFees);

	}

	/** Método retorna campo status */
	public function getStatus() : ? int
	{

		/** Retorno da informação */
		return (int)$this->status;

	}

	/** Método retorna campo note */
	public function getNote() : ? string
	{

		/** Retorno da informação */
		return (string)$this->note;

	}

	/** Método retorna campo movement_user_confirmed */
	public function getMovementUserConfirmed() : ? int
	{

		/** Retorno da informação */
		return (int)$this->movementUserConfirmed;

	}

	/** Método retorna campo name do arquivo */
	public function getName() : ? string
	{

		/** Retorno da informação */
		return (string)$this->name;

	}
	
	/** Método retorna o diretório temporário do arquivo */
	public function getDirTemp() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirTemp;

	}
	
	/** Método retorna o diretório geral do arquivo */
	public function getDirGeral() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirGeral;

	}	

	/** Método retorna o diretório temporário do arquivo */
	public function getDirUser() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dirUser;

	}	
	
	/** Método retorna o arquivo a ser gravado */
	public function getArchive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->archive;

	}	

	/** Método retorna o arquivo a ser gravad */
	public function getExt() : ? string
	{

		/** Retorno da informação */
		return (string)$this->ext;

	}	
	
	/** Método retorna o campo search */
	public function getSearch() : ? string
	{
		
		/** Retorno da informação */
		return (string)$this->search;

	}

	/** Método retorna o campo type */
	public function getType() : ? string
	{

		/** Retorno da informação */
		return (string)$this->type;

	}

	/** Método retorna o campo status */
	public function getStatusSearch() : ? int
	{

		/** Retorno da informação */
		return (int)$this->status;		

	}

	/** Método retorna o campo dateStart */
	public function getDateStart() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateStart;

	}

	/** Método retorna o campo dateEnd */
	public function getDateEnd() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateEnd;

	}

	/** Método retorna o campo reference */
	public function getReference() : ? string
	{

		/** Retorno da informação */
		return (string)$this->reference;

	}	

	/** Método retorna o campo maturity */
	public function getMaturity() : ? string
	{

		/** Retorno da informação */
		return (string)$this->maturity;

	}		

	/** Método retorna o campo PrintType */
	public function getPrintType() : ? int
	{

		/** Retorno da informação */
		return (int)$this->printType;

	}	

	/** Retorna possiveis erros */
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
