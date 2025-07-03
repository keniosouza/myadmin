<?php


function reg($str){

    return preg_match('/(insert)(into)*(c)/', 'ac');
    

}

print_r(reg('insert into maria'));
