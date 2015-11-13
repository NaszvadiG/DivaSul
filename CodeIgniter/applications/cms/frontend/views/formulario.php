<link rel="stylesheet" type="text/css" href="../arquivos/css/botoes.css" />
<link rel="stylesheet" type="text/css" href="../arquivos/css/mensagens.css" />

<?php
$texto_botao_enviar = $texto_botao_enviar ? $texto_botao_enviar : 'OK';
if ( $post )
{
    $msg = '';
    $class = '';
    if ( $enviou )
    {
        $class = 'ok';
        if ( strlen($mensagem_de_sucesso) > 0 )
        {
            $msg = $mensagem_de_sucesso;
        }
        else
        {
            $msg = 'Sua menssagem foi enviada com sucesso.';
        }
    }
    else
    {
        $class = 'erro';
        $msg = '<b>';
        if ( strlen($mensagem_de_falha) > 0 )
        {
            $msg .= $mensagem_de_falha;
        }
        else
        {
            $msg .= 'Falha ao enviar sua menssagem';
        }
        $msg .= '</b>';
        if ( is_array($erros) && count($erros) > 0 )
        {
            foreach ( $erros as $erro )
            {
                $msg .= '<br />'.$erro;
            }
        }
    }

    if ( !(strlen($id_div_mensagens) > 0) )
    {
        $id_div_mensagens = 'errors';
        echo '<div id="'.$id_div_mensagens.'" class="msg '.$class.'">'.$msg.'</div>';
    }

    echo '<script type="text/javascript">
            var div = document.getElementById(\''.$id_div_mensagens.'\');
            div.onclick=function(){this.className=\'\';this.innerHTML=\'\'};
            div.className=\'msg '.$class.' \'+div.className;
            div.innerHTML=\''.$msg.'\';
        </script>';
}
?>

<form action="" method="POST" onsubmit="return obter_tempo();">

<?php
// Campos
if ( count($campos['input']) > 0 )
{
    foreach ( $campos['input'] as $input )
    {
        if ( $input['label_fora'] == 'true' )
        {
            echo '<div class="form_label">'.$input['label'].'</div>';
        }

        $value   = $input['value'];
        $onclick = '';
        $onblur  = '';
        if ( $input['label_dentro'] == 'true' )
        {
            $value = (strlen($input['value']) > 0) ? $input['value'] : $input['label'];
            $onclick = 'onfocus="if(this.value==\''.$input['label'].'\')this.value=\'\'"';
            $onblur = 'onblur="if(this.value==\'\')this.value=\''.$input['label'].'\'"';
        }
        echo '<input
            type="text"
            class="input"
            id="'.$input['id'].'"
            name="'.$input['name'].'"
            value="'.$value.'"'.
            $onclick.
            $onblur.'
        />';
    }
}

// Text areas
if ( count($campos['textarea']) > 0 )
{
    foreach ( $campos['textarea'] as $textarea )
    {
        if ( $input['label_fora'] == 'true' )
        {
            echo '<div class="form_label">'.$textarea['label'].'</div>';
        }

        $value   = $textarea['value'];
        $onclick = '';
        $onblur  = '';
        if ( $input['label_dentro'] == 'true' )
        {
            $value = (strlen($textarea['value']) > 0) ? $textarea['value'] : $textarea['label'];
            $onclick = 'onfocus="limpaCampoComentario(this, \''.$textarea['label'].'\');"';
            $onblur = 'onblur="preencheCampoComentario(this, \''.$textarea['label'].'\');"';
        }
        echo '<textarea
            class="textarea"
            id="'.$textarea['id'].'"
            name="'.$textarea['name'].'"'.
            $onclick.
            $onblur.'
        >'.$value.'</textarea>';
    }
}
?>
    <br />
    <br />

<?php
if ( $captcha )
{
    if ( $input['label_fora'] == 'true' )
    {
        echo '<div class="form_label">Captcha:</div>';
    }
    //Exibe validados captcha
    $this->my_captcha->exibir_form();
}
?>
    <input type="submit" id="submit" name="enviar" value="<?php echo $texto_botao_enviar; ?>"/>
</form>

<script type="text/javascript" language="JavaScript"> 
function limpaCampoComentario(campo, comparar) 
{ 
    if ( campo.value == comparar ) 
    { 
        campo.value=''; 
    } 
} 
 
function preencheCampoComentario(campo, comparar) 
{ 
    if ( campo.value.length < 1 ) 
    { 
        campo.value=comparar; 
    } 
} 
</script>
