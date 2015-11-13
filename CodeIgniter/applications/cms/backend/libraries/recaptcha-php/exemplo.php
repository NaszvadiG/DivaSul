<html>
    <head>
        <title>Teste - RECAPTHCA</title>
    </head>
    <body>
        <form name="form" action="post.php" method="POST">
<?php
require_once('recaptcha-php-1.11/recaptchalib.php');
$publickey = "6LfgsssSAAAAACHOAQmEcllbqlHU64KjlMdorRpt";
echo recaptcha_get_html($publickey);
?>
            <input type="submit" name="enviar" value="Enviar">
        </form>
    </body>
</html>
