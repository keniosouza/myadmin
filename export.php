<?php

require_once('./vendor/autoload.php');

session_start();

error_reporting(E_ALL);
ini_set('display_errors','On');

/** Importação de classes */
use vendor\model\Main;
use vendor\model\Clients;
use vendor\model\ClientProducts;

try {

    $Clients = new Clients();
    $ClientProducts = new ClientProducts();

    /** Instânciamento de classes */
    $Main = new Main;

    $handle = fopen("CONTRATOS.csv", "r");
    $row = 0;
    $i=1;
    while ($line = fgetcsv($handle, 1000, ",")) {
        
        if ($row++ == 0) {
            continue;
        }

        $data = explode(';', $line[0]);

        if(!empty($data[1])){

            /** Verifica se o cliente já está cadastrado */
            $clientsId = (int)$Clients->GetName(@utf8_encode(trim($data[1])));

            if($clientsId == 0){

                /** Se o cliente não existir, grava o mesmo */
                $Clients->Save(0, 
                            @utf8_encode($data[1]), 
                            @utf8_encode($data[1]), 
                            '', 
                            '', 
                            '', 
                            '', 
                            '', 
                            '', 
                            '', 
                            $data[2], 
                            'S', 
                            'J', 
                            '',
                            @utf8_encode($data[4]),
                            @utf8_encode($data[5]),
                            @utf8_encode($data[6]),
                            $Main->setzeros($data[0], 3));


            }elseif($clientsId > 0){

                switch($data[6]){

                    case 'SISTEMAS': $productsId = 99; break;
                    case 'PROVIMENTO 74': $productsId = 8; break;
                    case 'BACKUP 74': $productsId = 9; break;
                    case 'SITE': $productsId = 7; break;
                }
                
                if($ClientProducts->Save(0, $clientsId, $productsId, null, 'Contratação '.$data[6], @utf8_encode($data[7]), $data[8], (int)$data[3])){

                    echo $data[0].' - '.@utf8_encode($data[1]) . ' - cadastrado com sucesso!<br/>';
                }else{

                    echo $data[0].' - '.@utf8_encode($data[1]) . ' - Não foi possível efetuar o cadastro!<br/>';
                }
            }

        }
                        
        $i++;
    }

    fclose($handle);

} catch (Exception $exception) {

    echo $exception->getMessage();

}