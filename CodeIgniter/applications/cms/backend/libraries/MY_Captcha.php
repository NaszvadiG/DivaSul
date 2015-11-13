<?php
/**
 * Classe de Recaptcha para formulários de e-mails
 */
class MY_Captcha
{
    private $publickey  = "6LfgsssSAAAAACHOAQmEcllbqlHU64KjlMdorRpt";
    private $privatekey = "6LfgsssSAAAAAMwXpI1i6982ZDNhyEEoeB_mwafi";

    function __construct()
    {
        require('recaptcha-php/recaptcha-php-1.11/recaptchalib.php');
    }

/*
    function exibir_form()
    {

        echo '<style>
                #recaptcha_widget{ position:relative;width:320px;height:125px;border:2px solid #999;-moz-border-radius:3px;border-radius:3px; background-image:url(http://www.univates.br/media/captcha/fundo.png);}
                #recaptcha_image {margin-top:5px;margin-left:9px;}
                #recaptcha_response_field{margin:5px 5px !important;}
              </style>';
        echo "<script type=\"text/javascript\"> var RecaptchaOptions = {theme : 'custom', custom_theme_widget: 'recaptcha_widget'};</script>";
        echo '<div id="recaptcha_widget" style="display:none">

   <div id="recaptcha_image"></div>
   <div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrect please try again</div>

   <span class="recaptcha_only_if_image">Digite as palavras acima:</span>
   <span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>

   <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />

   <div style="position:absolute;top:70px;left:220px;">
        <a href="javascript:Recaptcha.reload()"><img src="http://www.univates.br/media/captcha/refresh.png"></a>
   </div>
   <div style="position:absolute;top:85px;left:220px;height:14px;" class="recaptcha_only_if_image">
        <a href="javascript:Recaptcha.switch_type(\'audio\')"><img src="http://www.univates.br/media/captcha/audio.png"></a>
   </div>
   <div style="position:absolute;left:220px;top:99px;height:15px">
        <a href="javascript:Recaptcha.showhelp()"><img src="http://www.univates.br/media/captcha/help.png"></a>
   </div>

 </div>

 <script type="text/javascript"
    src="http://www.google.com/recaptcha/api/challenge?k='.$this->publickey.'">
 </script>
 <noscript>
   <iframe src="http://www.google.com/recaptcha/api/noscript?k='.$this->publickey.'"
        height="300" width="500" frameborder="0"></iframe><br>
   <textarea name="recaptcha_challenge_field" rows="3" cols="40">
   </textarea>
   <input type="hidden" name="recaptcha_response_field"
        value="manual_challenge">
 </noscript>';
        //echo recaptcha_get_html($this->publickey);

    }
    function exibir_form()
    {
        echo "<script type=\"text/javascript\">
                var RecaptchaOptions = { theme : 'clean'}; //white
              </script>";
        echo recaptcha_get_html($this->publickey);
    }
*/

    function exibir_form()
    {
        echo <<<HTML
<style type="text/css">
div#recaptcha_area
{
    border:1px solid #000;
    border-radius: 6px;
    -webkit-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    -moz-border-radius: 6px;
}

div#recaptcha_area table#recaptcha_table
{
    background-color: #E1E1E1;
    border-radius: 6px;
    -webkit-border-radius: 6px;
    -ms-border-radius: 6px;
    -o-border-radius: 6px;
    -moz-border-radius: 6px;
}

.recaptchatable .recaptcha_r1_c1,
.recaptchatable .recaptcha_r2_c1,
.recaptchatable .recaptcha_r2_c2,
.recaptchatable .recaptcha_r3_c1,
.recaptchatable .recaptcha_r3_c2,
.recaptchatable .recaptcha_r3_c3,
.recaptchatable .recaptcha_r7_c1,
.recaptchatable .recaptcha_r4_c2,
.recaptchatable .recaptcha_r8_c1
{
    background: none;
}

.recaptchatable .recaptcha_r4_c4
{
    background-image:url('http://www.univates.br/media/recaptcha/logo_univates.png');
    background-repeat:no-repeat;
    background-position:5px;
}
</style>
<script type="text/javascript">
var RecaptchaOptions =
{
    theme : 'white',
    lang  : 'pt',
};
</script>
HTML;

        echo recaptcha_get_html($this->publickey);
        echo <<<HTML
<script type="text/javascript" language="JavaScript">
document.getElementById('recaptcha_reload').src='http://www.univates.br/media/recaptcha/refresh.png';
document.getElementById('recaptcha_switch_audio').src='http://www.univates.br/media/recaptcha/audio.png';
document.getElementById('recaptcha_switch_img').src='http://www.univates.br/media/recaptcha/text.png';
document.getElementById('recaptcha_whatsthis').src='http://www.univates.br/media/recaptcha/help.png';
</script>
HTML;
    }

    function validar( )
    {
        $resp = recaptcha_check_answer(
            $this->privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST['recaptcha_challenge_field'],
            $_POST['recaptcha_response_field']
        );
        if ( !$resp->is_valid )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
?>
