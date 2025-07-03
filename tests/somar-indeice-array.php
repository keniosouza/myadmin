<?php

$vendas = array(
    [ "Produto" => "chocolate", "Quantidade" => 300 ],
    [ "Produto" => "morango"  , "Quantidade" => 150 ],
    [ "Produto" => "chocolate", "Quantidade" => 400 ],
    [ "Produto" => "morango",   "Quantidade" => 200 ],
    [ "Produto" => "cacau",     "Quantidade" => 40  ],
    [ "Produto" => "chocolate", "Quantidade" => 250 ],
    [ "Produto" => "morango",   "Quantidade" => 125 ]
);

$venda_sabor = [];

//Para cada sabor soma as respectivas vendas.
foreach($vendas as $key=>$value){
  if (isset($venda_sabor[$value["Produto"]]))
    $venda_sabor[$value["Produto"]] += $value["Quantidade"];
  else
    $venda_sabor[$value["Produto"]] = $value["Quantidade"];
}

$vendas = [];

//Retornando para o formato original
foreach($venda_sabor as $key=>$value){
  $vendas[] = [ "Produto" => $key, "Quantidade" => $value ];
}

//Imprime o resultado
print_r($vendas);