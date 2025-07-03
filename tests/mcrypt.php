<?php

/** Efetua a criptografa */
function encryptOpenSsl($str, $key){

    $l = strlen($key);
    if ($l < 16)
        $key = str_repeat($key, ceil(16/$l));

    if ($m = strlen($str)%8)
        $str .= str_repeat("\x00",  8 - $m);    

    return mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $data, MCRYPT_MODE_ECB);

}

/** Retira a criptografia */
function decryptOpenSsl($str, $key){

    $l = strlen($key);
    if ($l < 16)
        $key = str_repeat($key, ceil(16/$l));    

    return mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, MCRYPT_MODE_ECB);
}


/** Texto a ser criptografado */
$data = 'Souza Consultoria Tecnologica';

/** Chave da criptografia */
$key = '123123';

$strEncrypt = encryptOpenSsl($data, $key);
$strDencrypt = decryptOpenSsl($strEncrypt, $key);


echo 'Texto sem criptografia :: '. $data .'<br/>';
echo 'Texto com criptografia :: '. $strEncrypt . '<br/>';
echo 'Texto descriptografado :: '. $strDencrypt;