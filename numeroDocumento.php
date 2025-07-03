<?php

require_once('./vendor/autoload.php');;

/** Importação de classes */
use vendor\model\FinancialMovements;
use vendor\model\Main;

error_reporting(E_ALL);
ini_set('display_errors','On');

/** Instânciamento de classes */
$Main = new Main;
$FinancialMovements = new FinancialMovements;
$FinancialMovementsResult = $FinancialMovements->NoReference(1, 'Backup');

$financialMovementsId = [];
$clientsId = [];
$clientesReference = [];
$description = [];
$mes = [];
$desc = null;
$pos  = null;
$year = 'B74';

foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){


    $desc = explode(' - ', $Result->description);
    $pos  = explode('/', $desc[1]);

    array_push($financialMovementsId, $Result->financial_movements_id);
    array_push($clientsId, $Result->clients_id);
    array_push($clientesReference, $Result->reference);
    array_push($description, $desc[0]);
    array_push($mes, $pos[0]);

}

for($i=0; $i<count($financialMovementsId); $i++){

    $FinancialMovements->UpdateReference($financialMovementsId[$i], $year.'/'.$clientesReference[$i].'-'.$Main->setZeros($mes[$i], 2));

    // echo $year.'/'.$clientesReference[$i].'-'.$Main->setZeros($mes[$i], 2).'<br/>';

}