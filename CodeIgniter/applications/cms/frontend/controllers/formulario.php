<?php
class Formulario extends MX_Controller
{
    // Valores do form
    private $dados = array();

    function __construct()
    {
        parent:: __construct();
        $this->load->model('Formulario_model');
    }

    /**
     * Parametros:
     * $params['assunto']            = assunto do email, default: Contato através do site da Univates
     * $params['de_nome']            = nome de quem envia, dica: Pode ser um dos campos (nome, por exemplo). Default: Univates Portais
     * $params['de_email']           = e-mail de quem envia, dica: Pode ser um dos campos (email, por exemplo). Default: site@univates.br
     * $params['para_nome']          = nome de destino, dica: Pode ser um dos campos (nome, por exemplo). Default: Univates Portais
     * $params['para_email']         = e-mail de destino, dica: Pode ser um dos campos (email, por exemplo). Default: site@univates.br
     * $params['cc']                 = e-mail de copia oculta separados por virgula
     * $params['campos']             = campos input separedos por virgula
     * $params['textarea']           = campos textarea separados por virgula
     * $params['campos_padrao']      = campos default do formulario bool
     * $params['texto_botao_enviar'] = label do botão enviar
     * $params['label_dentro']       = exibir nome do campo dentro do mesmo bool
     * $params['label_fora']         = exibir nome do campo antes do mesmo bool
     * $params['captcha']            = exibir validador captcha bool
     * $params['id_div_mensagens']   = ID da DIV onde irão as mensagens (sucesso, falha, campos obrigatórios...)
     */
    function index($params)
    {
        $this->load->library('MY_Captcha');

        $dados = array();

        // Campos padrão
        $campos = array();
        if ( $params['campos_padrao'] != 'false' )
        {
            $nome = array();
            $email = array();
            $city = array();
            $msg = array();

            // Campo Nome
            $nome['label'] ='Nome';
            $nome['id'] = 'nome';
            $nome['name'] = 'nome';
            $campos['input'][] = $nome;

            // E-mail
            $email['label'] = 'E-mail';
            $email['id'] = 'email';
            $email['name'] = 'email';
            $campos['input'][] = $email;

            // Cidade
            $cidade['label'] = 'Cidade';
            $cidade['id'] = 'cidade';
            $cidade['name'] = 'cidade';
            $campos['input'][] = $cidade;

            // Mensagem
            $msg['label'] = 'Mensagem';
            $msg['id'] = 'mensagem';
            $msg['name'] = 'mensagem';
            $campos['textarea'][] = $msg;
        }
        else
        {
            // Array com os campos do form
            foreach ( explode(',', $params['campos']) as $campo )
            {
                $input = array();
                $input['label'] = $campo;
                $input['id'] = MY_Utils::removeSpecialChars(strtolower($campo), true);
                $input['name'] = MY_Utils::removeSpecialChars(strtolower($campo), true);
                $campos['input'][] = $input;
            }
            foreach ( explode(',', $params['textarea']) as $campo )
            {
                $textarea = array();
                $textarea['label'] = $campo;
                $textarea['id'] = MY_Utils::removeSpecialChars(strtolower($campo), true);
                $textarea['name'] = MY_Utils::removeSpecialChars(strtolower($campo), true);
                $campos['textarea'][] = $textarea;
            }
        }
        $params['campos'] = $campos;

        // Se deu post
        if ( isset($_POST['enviar']) )
        {
            $dados['post'] = true;
            $dados['enviou'] = false;
            $this->dados = $_POST;

            $erros = $this->valida_campos($params['requeridos']);

            // Se não tem erros
            if ( count($erros) == 0 )
            {
                //retira campos de controle
                unset($this->dados['enviar']);
                unset($this->dados['recaptcha_challenge_field']);
                unset($this->dados['recaptcha_response_field']);

                // Assunto default
                if ( strlen($params['assunto']) == 0 )
                {
                    $params['assunto'] = 'Contato através do site da Univates';
                }

                // De / Para
                $params['de']['nome'] = $params['de_nome'];
                $params['de']['email'] = $params['de_email'];
                unset($params['de_nome']);
                unset($params['de_email']);
                $params['para']['nome'] = $params['para_nome'];
                $params['para']['email'] = $params['para_email'];
                unset($params['para_nome']);
                unset($params['para_email']);

                // Envia e-mail
                if ( $this->Formulario_model->enviar_email($this->dados, $params['campos'], $params['assunto'], $params['de'], $params['para'], $params['cc']) )
                {
                    $dados['enviou'] = true;
                }
            }
            else
            {
                $dados['erros'] = $erros;
            }
        }

        $dados['captcha'] = ($params['captcha'] == 'true'); // string to boolean ;)
        $dados['texto_botao_enviar'] = $params['texto_botao_enviar'];
        $dados['id_div_mensagens'] = $params['id_div_mensagens'];
        $dados['mensagem_de_sucesso'] = $params['mensagem_sucesso'];
        $dados['mensagem_de_falha'] = $params['mensagem_falha'];
        $dados['campos'] = $this->Formulario_model->get_campos($params, $this->dados);

        $this->load->view('formulario', $dados);
    }

    // Verificar campos requeridos:
    function valida_campos($requeridos)
    {
        $erros = array();

        // Se tem o parâmetro captcha, e for "true", valida o captcha
        if( isset($this->dados['recaptcha_response_field']) )
        {
            if ( !$this->my_captcha->validar() )
            {
                $erros[] = 'Preencha o corretamente o Captcha.';
            }
        }

        // Valida os campos
        $requeridos = (array)explode(',', $requeridos);
        foreach ( $requeridos as $k => $campo )
        {
            $requeridos[$k] = MY_Utils::removeSpecialChars(strtolower($campo));
        }
        foreach ( $this->dados as $campo => $valor )
        {
            $valor_do_campo = MY_Utils::removeSpecialChars(strtolower(trim($valor)), true);
            if ( (strlen($valor_do_campo) == 0) || ($valor_do_campo == $campo) )
            {
            
                if ( in_array($campo, $requeridos) )
                {
                    $label = ucfirst(str_replace('_', ' ', $campo));
                    $erros[] = 'O campo '.$label.' é de preenchimento obrigatório.';
                }
                elseif ( MY_Utils::removeSpecialChars(strtolower($campo)) == 'email' )
                {
                    vdie($email);
                }
                else
                {
                    $this->dados[$campo] = '';
                }
            }
        }

        return $erros;
    }
}
?>
