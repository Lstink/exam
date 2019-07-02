<?php



function encryptCBC($data,$key='yyy',$iv='yyyyyyyy')
{
    return openssl_encrypt($data,'DES-CBC',$key,0,$iv);
}

function decryptCBC($data,$key='yyy',$iv='yyyyyyyy')
{
    return openssl_decrypt($data,'DES-CBC',$key,0,$iv);
}