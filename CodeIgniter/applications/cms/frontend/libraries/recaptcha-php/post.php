<?php
/*
 * RECAPTCHA
 */
require_once('recaptcha-php-1.11/recaptchalib.php');
$privatekey = "6LfgsssSAAAAAMwXpI1i6982ZDNhyEEoeB_mwafi";
$resp = recaptcha_check_answer ($privatekey,
$_SERVER["REMOTE_ADDR"],
$_POST["recaptcha_challenge_field"],
$_POST["recaptcha_response_field"]);

if ( !$resp->is_valid )
{
    // não validou
    echo 'Error no codigo Captcha. Informe o texto da imagem corretamete.'.
    '<br /><a href="javascript:history.go(-1);">voltar</a>';
    die();
}
else
{
    // validou
    echo 'TUDO certo.'.
    '<br /><a href="javascript:history.go(-1);">voltar</a>';
}
?>
