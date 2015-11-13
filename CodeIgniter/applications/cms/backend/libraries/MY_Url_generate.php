<?php
class MY_Url_generate
{
/*
Limpa caracteres especiais da String
*/
    public function clear($string)
    {
        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }
}
