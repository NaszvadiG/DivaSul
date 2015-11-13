<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Enquetes extends Default_controller
{
    function __construct()
    {
        //Define a tabela principal deste módulo
        $this->table_name = 'cms_enquetes';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Enquetes_model');
        $this->Enquetes_model->set_table_name($this->table_name);

        // Altera o tamnho da coluna das ações
        $this->colunas_default[parent::COLUNA_ACOES]['tamanho'] = 75;
        // Ações
        $this->acoes = array_merge(array(
            array(
                'descricao' => 'Ver resultados', // Descrição
                'acao' => 'resultados', // Função do controller que será chamada (ação)
                'icone' => 'arquivos/css/icons/resultados.png' // Imagem do botão
            )
        ), $this->acoes);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Enquetes';
        $this->controller = 'enquetes';
    }

    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Filtra pelas enquetes do site
        $this->where = array('site_id = '.$this->site_id);

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string' // Tipo (integer, date, string, boolean), usado no WHERE...
            ),
            array(
                'descricao' => 'Data de início', // Descrição (texto impresso na tela)
                'tamanho' => 110, // Largura da coluna
                'align' => 'center', // Alinhamento da coluna
                'coluna' => 'data_formatada', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'dt_inicio', // Coluna utilizada para ordenar/filtrar
                'sql' => "TO_CHAR(dt_inicio, 'DD/MM/YYYY HH24:MI:SS')", // Coluna utilizada no SQL para obter os dados
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'date' // Tipo (integer, date, string, boolean), usado no WHERE...
            ),
            array(
                'descricao' => 'Data de encerramento', // Descrição (texto impresso na tela)
                'tamanho' => 110, // Largura da coluna
                'align' => 'center', // Alinhamento da coluna
                'coluna' => 'data_formatada_fim', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'dt_fim', // Coluna utilizada para ordenar/filtrar
                'sql' => "TO_CHAR(dt_fim, 'DD/MM/YYYY HH24:MI:SS')", // Coluna utilizada no SQL para obter os dados
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'date' // Tipo (integer, date, string, boolean), usado no WHERE...
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        if ( $this->input->post('submit') )
        {
            $dados = $this->input->post();
            $respostas = array();
            for ( $i=0; $i < count($dados['respostas']['descricao']); ++$i )
            {
                $respostas[$i]['descricao'] = $dados['respostas']['descricao'][$i];
                if ( $dados['respostas_com_cores_personalizadas'] == 't' )
                {
                    $respostas[$i]['cor'] = $dados['respostas']['cor'][$i];
                }
            }
            $dados['respostas'] = $respostas;
        }
        elseif ( $id )
        {
            $dados['enquete'] = $this->Enquetes_model->obter($id);
            $dados['respostas'] = $this->Enquetes_model->obter_respostas($id);
        }

        // Site ID
        $dados['enquete']['site_id'] = (int)$this->site_id;

        // Campo multipla_resposta
        $dados['enquete']['multipla_resposta'] = ($dados['enquete']['multipla_resposta'] == 't' ? 't' : 'f');

        // Campo multiplicar_tempo
        $dados['multiplicar_tempo'] = ($dados['multiplicar_tempo'] > 0 ) ? $dados['multiplicar_tempo'] : '1';

        // Campo intervalo_entre_votos (tempo entre um voto e outro (por IP))
        $dados['multipla_resposta'] = ($dados['multipla_resposta'] > 0 ? $dados['multipla_resposta'] : '1');
        $dados['enquete']['intervalo_entre_votos'] = ($dados['enquete']['intervalo_entre_votos'] > 0 ? $dados['enquete']['intervalo_entre_votos'] : '0');
        $dados['enquete']['intervalo_entre_votos'] = (int)$dados['enquete']['intervalo_entre_votos']*(int)$dados['multiplicar_tempo'];

        // Validação
        $this->form_validation->set_rules('enquete[titulo]', 'Título', 'trim|required');
        $this->form_validation->set_rules('enquete[descricao]', 'Descrição', 'trim|required');
        $this->form_validation->set_rules('enquete[dt_inicio]', 'Data de início', 'trim|required');
        $this->form_validation->set_rules('enquete[hora_inicio]', 'Hora de início', 'trim|required');
        $this->form_validation->set_rules('enquete[ativo]', 'Ativo', 'trim|required');
        if ( $this->input->post('submit') )
        {
            if ( $this->form_validation->run() )
            {
                $dados['enquete']['dt_inicio'] = MY_Utils::montar_timestamp($dados['enquete']['dt_inicio'], $dados['enquete']['hora_inicio']);
                $dados['enquete']['dt_fim'] = MY_Utils::montar_timestamp($dados['enquete']['dt_fim'], $dados['enquete']['hora_fim']);
                unset($dados['enquete']['hora_inicio']);
                unset($dados['enquete']['hora_fim']);

                $id = $this->Enquetes_model->salvar($dados['enquete']);
                if ( $id )
                {
                    $ok = $this->Enquetes_model->salvar_respostas($id, $dados['respostas']);

                    if ( $ok )
                    {
                        redirect('enquetes');
                    }
                    else
                    {
                        $dados['erro'] = 'Não foi possível inserir as opções de resposta';
                    }
                }
                else
                {
                    $dados['erro'] = 'Não foi possível inserir o registro.';
                }
            }
            else
            {
                $dados['erro'] = validation_errors();
            }
        }

        $this->load->view('enquetes_editar', $dados);
    }

    function resultados($enquete_id)
    {
        $dados = array();
        $dados['enquete'] = $this->Enquetes_model->obter($enquete_id);
        $dados['total_votos'] = $this->Enquetes_model->obter_total_votos($enquete_id);
        $dados['resultados'] = $this->Enquetes_model->obter_resultados($enquete_id);

        $this->load->view('enquetes_resultados', $dados);
    }

    function remover($enquete_id)
    {
        $this->Enquetes_model->remover($enquete_id);
        parent::remover();
    }
}
?>
