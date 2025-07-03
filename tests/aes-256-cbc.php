<?php

function secured_encrypt($first_key, $second_key, $method, $str)
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


function secured_decrypt($first_key, $method, $input)
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

/** Chaves */
$method = 'aes-256-cbc';
$first_key = '1B0B043A1C185F261D';
$second_key = '1B0B043A0C05422C1E0A';

/** Texto sem criptografia */
$str = 'kenio@outlook.com';

/** Texto criptografado */
$strEncrypt = secured_encrypt($first_key, $second_key, $method, $str);

/** Texto descriptografado */
$strDencrypt = secured_decrypt($first_key, $method, $strEncrypt);
//$strDencrypt = secured_decrypt($first_key, $method, $str);

/** Texto a ser criptografado */
echo 'Texto a ser criptografado :: '.$str . '<br/><br/>';

/** Escrevendo texto criptografado */
echo 'Texto criptografado :: '.$strEncrypt . '<br/><br/>';

/** Escrevendo texto  descriptografado */
echo 'Texto descriptografado :: '.$strDencrypt;
/*echo '<hr/>';

$compressed = gzdeflate('Kenio de Souza Pereira da Silva Borges Landeiro', 9);
echo $compressed;
echo '<hr/>';
echo gzinflate($compressed);*/