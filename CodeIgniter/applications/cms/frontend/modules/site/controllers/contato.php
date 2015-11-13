<?php
class Contato extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function enviar($params=array())
    {
        $ok = false;

        $email = $params['email'];
        if ( strlen($email) == 0 )
        {
            $email = 'ArthurLehdermann@gmail.com';
        }
  
        $contato = $_POST['contato'];
        If ( strlen($contato['nome']) > 0 && strlen($contato['email']) > 0 )
        {
            $this->load->library('MY_PHPMailer');
            $mail = new MY_PHPMailer();
            $mail->SetFrom($contato['email'], mb_encode_mimeheader($contato['nome']));
            $mail->AddAddress($email, mb_encode_mimeheader('Serigrafai El Shaddai'));
            $assunto = mb_encode_mimeheader('[Imobiliária Reichert] Contato através do site');
            $mensagem = 'Olá,<br>';
            $mensagem .= $contato['nome'].' entrou em contato pelo site:<br>';
            $mensagem .= '<br>';
            $mensagem .= 'Nome: '.$contato['nome'];
            $mensagem .= '<br>';
            $mensagem .= 'E-mail: '.$contato['email'];
            $mensagem .= '<br>';
            $mensagem .= 'Telefone: '.$contato['telefone'];
            $mensagem .= '<br>';
            $mensagem .= 'Mensagem: '.$contato['mensagem'];
            $mensagem .= '<br>';
            $mail->MsgHTML(str_replace("\n", '<br />', $mensagem));
            $ok = $mail->Send();
        }

        if ( $ok )
        {
            redirect(base_url('fale-conosco/sucesso'));
        }
        else
        {
            redirect(base_url('fale-conosco/falha'));
        }
    }
}