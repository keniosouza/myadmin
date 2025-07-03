<?php
/**
* Classe FinancialConsolidations.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2023 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			16/08/2023
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class FinancialConsolidations
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialConsolidationsId = null;
	private $usersId = null;
	private $companyId = null;
	private $importDate = null;
	private $fileConsolidation = null;
	private $totalMovements = null;
	private $totalMovementsConsolidateds = null;
	private $totalMovementsNotFound = null;
	private $totalMovementsLocalized = null;
	private $totalMovementsUnpaid = null;
	private $totalMovementsAlreadyConsolidated = null;
	private $totalMovementsToBeConsolidateds = null;
	private $inconsistencies = null;
	private $type = null;
	private $field = null;
	private $lastId = null;
	private $errors = null;
	private $info = null;

	/** Construtor da classe */
	function __construct()
	{
		/** Cria o objeto de conexão com o banco de dados */
		$this->connection = new Mysql();
	}

	/** Carrega os campos de uma tabela */
	public function Describe()
	{

		/** Consulta SQL */
		$this->sql = "describe financial_consolidations";

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		$this->field = $this->stmt->fetchAll(\PDO::FETCH_OBJ);

		/** Declara o objeto */
		$resultDescribe = new \stdClass();
		$Field = '';

		/** Lista os campos da tabela para objetos */
		foreach($this->field as $UsersKey => $Result){

			/** Pega o nome do Field/Campo */
			$Field = $Result->Field;

			/** Carrega os objetos como null */
			$resultDescribe->$Field = null;

		}

		/** Retorna os campos declarados como vazios */
		return $resultDescribe;

	}

	/** Lista os registros do banco de dados com limitação */
	public function Get(int $financialConsolidationsId)
	{

		/** Parametros de entrada */
		$this->financialConsolidationsId = $financialConsolidationsId;

		/** Consulta SQL */
		$this->sql = 'select fc.financial_consolidations_id,
		                     fc.users_id,
							 fc.company_id,
							 fc.import_date,
							 fc.file_consolidation,
							 fc.total_movements,
							 fc.total_movements_to_be_consolidateds,
							 fc.total_movements_consolidateds,
							 fc.total_movements_not_found,
							 fc.total_movements_localized,
							 fc.total_movements_unpaid,
							 fc.total_movements_already_consolidated,
							 fc.inconsistencies,
							 fc.type
		              from financial_consolidations fc  					  
					  where fc.financial_consolidations_id = :financial_consolidations_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_consolidations_id', $this->financialConsolidationsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(? int $start, ? int $max)
	{
		/** Parametros de entrada */
		$this->start = $start;
		$this->max = $max;

		/** Verifico se há paginação */
		if($this->max){
        	$this->limit = "limit $this->start, $this->max";
        }

		/** Consulta SQL */
		$this->sql = 'select fc.financial_consolidations_id,
							 fc.users_id,
							 fc.company_id,
							 fc.import_date,
							 fc.file_consolidation,
							 fc.total_movements,
							 fc.total_movements_to_be_consolidateds,
							 fc.total_movements_consolidateds,
							 fc.total_movements_not_found,
							 fc.total_movements_localized,
							 fc.total_movements_unpaid,
							 fc.total_movements_already_consolidated,
							 fc.inconsistencies,
							 fc.type,
							 u.name_first,
							 u.name_last,
							 u.email
		 			  from financial_consolidations fc 
					  left join users u on fc.users_id = u.users_id 
					  order by fc.financial_consolidations_id desc
					  '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(financial_consolidations_id) as qtde
					  from financial_consolidations ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $financialConsolidationsId, int $usersId, int $companyId, string $fileConsolidation, int $totalMovements, int $totalMovementsToBeConsolidateds, int $totalMovementsNotFound, int $totalMovementsLocalized, int $totalMovementsUnpaid, int $totalMovementsAlreadyConsolidated, string $inconsistencies, int $type, int $totalMovementsConsolidateds)
	{
		/** Parametros */
		$this->financialConsolidationsId = $financialConsolidationsId;
		$this->usersId = $usersId;
		$this->companyId = $companyId;
		$this->fileConsolidation = $fileConsolidation;
		$this->totalMovements = $totalMovements;
		$this->totalMovementsToBeConsolidateds = $totalMovementsToBeConsolidateds;
		$this->totalMovementsNotFound = $totalMovementsNotFound;
		$this->totalMovementsLocalized = $totalMovementsLocalized;
		$this->totalMovementsUnpaid = $totalMovementsUnpaid;
		$this->totalMovementsAlreadyConsolidated = $totalMovementsAlreadyConsolidated;
		$this->inconsistencies = $inconsistencies;
		$this->type = $type;
		$this->totalMovementsConsolidateds = $totalMovementsConsolidateds;

		/** Verifica se é uma edição */
		if($this->financialConsolidationsId > 0){

			/** Consulta SQL */
			$this->sql = 'update financial_consolidations set total_movements_consolidateds = :total_movements_consolidateds
			              where financial_consolidations_id = :financial_consolidations_id';
						  
			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);						  	

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('total_movements_consolidateds', $this->totalMovementsConsolidateds);			
			$this->stmt->bindParam('financial_consolidations_id', $this->financialConsolidationsId);
			
			/** Executo o SQL */
			$this->stmt->execute();			

		}else{
	
			/** Consulta SQL */
			$this->sql = 'insert into financial_consolidations(financial_consolidations_id, 
																users_id, 
																company_id, 
																file_consolidation, 
																total_movements, 
																total_movements_to_be_consolidateds, 
																total_movements_not_found, 
																total_movements_localized, 
																total_movements_unpaid, 
																total_movements_already_consolidated, 
																inconsistencies, 
																type 
														) values (:financial_consolidations_id, 
																:users_id,
																:company_id,
																:file_consolidation,
																:total_movements,
																:total_movements_to_be_consolidateds,
																:total_movements_not_found,
																:total_movements_localized,
																:total_movements_unpaid,
																:total_movements_already_consolidated,
																:inconsistencies,
																:type)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			try{
				
				/** Inicia a transação */
				$this->connection->connect()->beginTransaction();		

				/** Preencho os parâmetros do SQL */
				$this->stmt->bindParam('financial_consolidations_id', $this->financialConsolidationsId);
				$this->stmt->bindParam('users_id', $this->usersId);
				$this->stmt->bindParam('company_id', $this->companyId);
				$this->stmt->bindParam('file_consolidation', $this->fileConsolidation);
				$this->stmt->bindParam('total_movements', $this->totalMovements);
				$this->stmt->bindParam('total_movements_to_be_consolidateds', $this->totalMovementsToBeConsolidateds);
				$this->stmt->bindParam('total_movements_not_found', $this->totalMovementsNotFound);
				$this->stmt->bindParam('total_movements_localized', $this->totalMovementsLocalized);
				$this->stmt->bindParam('total_movements_unpaid', $this->totalMovementsUnpaid);
				$this->stmt->bindParam('total_movements_already_consolidated', $this->totalMovementsAlreadyConsolidated);
				$this->stmt->bindParam('inconsistencies', $this->inconsistencies);
				$this->stmt->bindParam('type', $this->type);

				/** Executo o SQL */
				$this->stmt->execute();

				/** Recupera o ID da última transação */
				$this->setId($this->connection->connect()->lastInsertId());	
				
				/** Confirma a transação */
				$this->connection->connect()->commit();	
				
				/** Retorna o ID da transação */
				return (int)$this->getId();			

			}catch(\Exception $exception) {
											
				/** Desfaz a transação */
				$this->connection->connect()->rollback();

				/** Captura o erro */
				array_push($this->errors, $exception->getMessage());
				return false;
			}	
		}
	}

	/** Define o Último ID inserido */
	public function setId($lastId) : void
	{
		$this->lastId = $lastId;
	}

	/** Recupera o Último ID inserido */
	public function getId(): ? int
	{
		return (int)$this->lastId;
	}	

	/** Verifica se há erros a serem visualizadas */
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

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
