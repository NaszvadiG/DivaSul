<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Paises extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_paises';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Paises_model');

        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Voltar para estados',
               'atributos_html' => array(
                   'class' => 'button search',
                   'title' => 'Voltar para estados',
                   'style' => 'float:right;',
                   'href' => base_url('site/estados/listar')
               )
            )
        );

        // Desabilita paginacao
        $this->exibir_coluna_ordem = false;
        // Remove coluna ativo
        unset($this->colunas_default[self::COLUNA_ATIVO]);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Países';
        $this->module = 'site';
        $this->controller = 'paises';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar()
    {
        // Aplica um ORDER BY na listagem
        $this->ordens = array('nome ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Nome', // Descrição (texto impresso na tela)
                'coluna' => 'nome', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Sigla', // Descrição (texto impresso na tela)
                'coluna' => 'sigla', // Coluna no array de dados ($this->registros)
                'tipo' => 'string'
            )
        );

        parent::listar($dados);
    }

    function editar($id=null)
    {
        // Array de dados para a view
        $dados = array();

        // Obtém os dados
        if ( $id )
        {
            // se não tem post e tem id, obtém da base de dados
            $dados['pais'] = $pais_old = $this->Paises_model->obter($id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Se existe procede, se não exibe erro!
            $dados = $this->input->post();

            // Validação
            $this->form_validation->set_rules('pais[nome]', 'Nome', 'trim|required');
            if ( $this->form_validation->run() )
            {
                if ( $this->Paises_model->salvar($dados['pais']) > 0 )
                {
                    redirect('site/paises/listar');
                }
                else
                {
                    $dados['erro'] = 'Não foi possível salvar o registro.';
                }
            }
            else
            {
                $dados['erro'] = validation_errors();
            }
        }

        $this->load->view('paises_editar', $dados);
    }

    /**
     * Remove um tipo de pais
     * @param $id Código do pais
     */
    function remover($id)
    {
        // Carrega a model
        $this->table_name = 'site_paises';
        $this->Paises_model->set_table_name($this->table_name);

        if ( $this->Paises_model->remover($id) )
        {
            redirect('site/paises/listar');
        }
        else
        {
            $this->editar($id);
        }
    }
}