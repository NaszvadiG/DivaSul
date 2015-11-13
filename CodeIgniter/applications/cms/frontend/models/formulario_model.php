<?php
class Formulario_model extends CI_Model
{
    function get_campos($dados = null, $valores_form=null)
    {
        $dados['label_fora'] = $dados['label_fora'] ? $dados['label_fora'] : 'true';
        $dados['label_dentro'] = $dados['label_dentro'] ? $dados['label_dentro'] : 'true';

        $inputs = $dados['campos']['input'];
        $textareas = $dados['campos']['textarea'];

        // Monta o array de campos input
        if ( !empty($inputs) )
        {
            foreach ($inputs as $campo )
            {
                // Ajusta dados, add nome sem caracteres especiais tb
                $input = array();
                $input['label'] = $campo['label'];
                $input['id'] = $campo['name'];
                $input['name'] = $campo['name'];
                $input['label_fora'] = $dados['label_fora'];
                $input['label_dentro'] = $dados['label_dentro'];
                $input['value'] = $valores_form[$campo['name']];
                $campos['input'][] = $input;
            }
        }

        // Monta o array de campos textarea
        if ( !empty($textareas) )
        {
            foreach ( $textareas as $textarea )
            {
                $campo = array();
                $campo['label'] = $textarea['label'];
                $campo['id'] = $textarea['name'];
                $campo['name'] = $textarea['name'];
                $campo['label_fora'] = $dados['label_fora'];
                $campo['label_dentro'] = $dados['label_dentro'];
                $campo['value'] = $valores_form[$textarea['name']];
                $campos['textarea'][] = $campo;
            }
        }

        return $campos;
    }

    /**
     * Envia o(s) email(s)
     */
    function enviar_email($dados=array(), $campos = array(), $assunto, $de, $para, $cc='')
    {
        $ok = false;

        // 'CC'
        $copias = array();
        foreach ( (array)explode(',', $cc) as $email )
        {
            if ( MY_Utils::email_valido($email) )
            {
                $copias[] = $email;
            }
        }

        // "De" default
        $de_email = MY_Utils::removeSpecialChars(strtolower($de['email']), true);
        // Se o rementente tá no form
        foreach ( $campos['input'] as $campo )
        {
            if ( $de['nome'] == $campo['label'] )
            {
                $de['nome'] = $dados[$campo['name']];
            }
            if ( $de['email'] == $campo['label'] )
            {

                $de['email'] = $dados[$campo['name']];
            }
        }
        // Se o nome do rementente foi preenchido
        if ( strlen($de['nome']) == 0 || in_array($de['nome'], $campos) )
        {
            $de['nome'] = 'Sinaliza';
        }
        // Se o email do rementente foi preenchido corretamente
        if ( strlen($de['email']) == 0 || !MY_Utils::email_valido($de['email']) )
        {
            $de['email'] = 'contato@site.com.br';
        }

        // "Para" default
        $para_email = MY_Utils::removeSpecialChars(strtolower($para['email']), true);
        // Se o destinatário tá no form
        if ( in_array($para['email'], (array)$campos) )
        {
            $para_nome = MY_Utils::removeSpecialChars(strtolower($para['nome']), true);
            $para['nome'] = $dados[$para_nome];
            $para['email'] = $dados[$para_email];
        }
        // Se o nome do destinatário foi preenchido
        if ( strlen($para['nome']) == 0 || in_array($para['nome'], $campos) )
        {
            $para['nome'] = 'Sinaliza';
        }
        // Se o email do destinatário foi preenchido corretamente
        if ( strlen($para['email']) == 0 || !MY_Utils::email_valido($para['email']) )
        {
            $para['email'] = 'contato@site.com.br';
        }

        $mensagem = 'Dados preenchidos:<br />';
        $vazio = true;

        // Campos
        foreach ( $campos['input'] as $k => $campo )
        {
            // Une os valores preenchidos
            $valor = $dados[$campo['name']];
            if ( (strlen($valor) > 0) && ($valor != $campo['name']) )
            {
                $mensagem .= $campo['label'] .' : '.$valor.'<br />';
                $vazio = false;
            }
        }
        // Textarea
        foreach ( $campos['textarea'] as $k => $campo )
        {
            // Une os valores preenchidos
            $valor = $dados[$campo['name']];
            if ( (strlen($valor) > 0) && ($valor != $campo['name']) )
            {
                $mensagem .= $campo['label'] .' :<br />'.$valor.'<br />';
                $vazio = false;
            }
        }

        // Envia email
        if ( !$vazio )
        {
            $this->load->library('MY_PHPMailer');
            $mail = new MY_PHPMailer();
            $mail->SetFrom($de['email'], mb_encode_mimeheader($de['nome']));
            $mail->AddAddress($para['email'], mb_encode_mimeheader($para['nome']));
            foreach ( $copias as $email )
            {
                // Nem funciona esses CC, nem BCC... :(
                $mail->AddBCC($email, mb_encode_mimeheader($para['nome']));
            }
            $mail->Subject = mb_encode_mimeheader($assunto);
            $mail->MsgHTML(str_replace("\n", '<br />', $mensagem));
            $ok = $mail->Send();
        }

        return $ok;
    }
}
?>
