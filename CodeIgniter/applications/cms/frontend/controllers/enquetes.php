<?php
class Enquetes extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Enquetes_model');
    }

    function exibir($params=array())
    {
        $enquete_id = $params['enquete_id'];

        $dados = array();
        $dados['enquete'] = $this->Enquetes_model->obter($enquete_id);
        $dados['opcoes'] = $this->Enquetes_model->obter_opcoes($enquete_id);
        $dados['resultados'] = $this->Enquetes_model->obter_resultados($enquete_id);

        $this->form_validation->set_rules('resposta', 'Resposta', 'trim|required');
        $this->form_validation->set_message('required', 'É necessário escolher ao menos uma opção.');

        $dados['enquete']['pag_resultados'] = strlen($params['pag_resultados']) > 0 ? $params['pag_resultados'] : 'enquete/resultados';

        // Agora:
        $agora = date('Y-m-d H:i:00');

        // Se está inativa
        if ( $dados['enquete']['ativo'] != 't' )
        {
            $dados['erro'][] = 'Desculpe, mas esta enquete não está mais ativa.';
            $dados['disabled'] = true;
        }
        // Se ainda não começou
        elseif ( $agora < $dados['enquete']['dt_inicio'] )
        {
            $dados['erro'][] = 'Desculpe, mas esta enquete ainda não iniciou. Aguarde até: '.MY_Utils::formata_data_hora($dados['enquete']['dt_inicio']);
            $dados['disabled'] = true;
        }
        // Se já encerrou
        elseif ( (strlen($dados['enquete']['dt_fim']) > 0) && ($agora > $dados['enquete']['dt_fim']) )
        {
            $dados['erro'][] = 'Desculpe, mas esta enquete encerrou em: '.MY_Utils::formata_data_hora($dados['enquete']['dt_fim']);
            $dados['disabled'] = true;
        }
        // Se deu POST (votar)
        elseif ( $this->input->post('votar') )
        {
            // Obtém a(s) resposta(s) do POST
            $resposta = $this->input->post('resposta');

            if ( $this->form_validation->run() )
            {
                if ( $this->pode_votar($enquete_id) )
                {
                    $cod_aluno = $this->obter_codigo_aluno();

                    $ok = $this->Enquetes_model->gravar_voto($enquete_id, $resposta, $cod_aluno);
                    if ( $ok )
                    {
                        $dados['info'][] = 'Voto computado com sucesso!';
                    }
                    else
                    {
                        $dados['erro'][] = 'Desculpe, mas não foi possível computar seu voto. Tente novamente mais tarde.';
                    }
                }
                else
                {
                    $dados['erro'][] = 'Você já votou nesta enquete.';
                }
            }
            else
            {
                $dados['erro'][] = '<b>Não foi possível computar seu voto.</b>';
                $dados['erro'][] = validation_errors();
            }
        }

        $this->load->view('enquete', $dados);
    }

    function pode_votar($enquete_id)
    {
        $ip = $_SERVER['HTTP_X_REAL_IP'] ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
        $cod_aluno = $this->obter_codigo_aluno();

        return $this->Enquetes_model->pode_votar($enquete_id, $ip, $cod_aluno);
    }

    function resultados($params)
    {
        $enquete_id = $params['enquete_id'];
        $botao_voltar = $params['botao_voltar'];

        $dados = array();
        $dados['enquete'] = $this->Enquetes_model->obter($enquete_id);
        $dados['enquete']['pag_enquete'] = strlen($params['pag_enquete']) > 0 ? $params['pag_enquete'] : 'enquete';
        $dados['resultados'] = $this->Enquetes_model->obter_resultados($enquete_id);
        $dados['total_votos'] = $this->Enquetes_model->obter_total_votos($enquete_id);
        $dados['botao_voltar'] = $botao_voltar;

        $this->load->view('enquete_resultados', $dados);
    }

    function obter_codigo_aluno()
    {
        // Quem votou
        if ( $this->session->userdata('usuario_id') )
        {
            $cod_aluno = (int)$this->session->userdata('usuario_id');
        }
        // Universo
        if ( $_SESSION['universounivates']['user'] )
        {
            $cod_aluno = (int)$_SESSION['universounivates']['user'];
        }
        // Definido na sessão
        if ( $_SESSION['user']['codAluno'] )
        {
            $cod_aluno = (int)$_SESSION['user']['codAluno'];
        }

        return $cod_aluno;
    }
}
?>
