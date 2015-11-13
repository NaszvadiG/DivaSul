<?php
include_once(APPPATH.'controllers/default_controller.php');
class Componentes extends Default_controller
{
    function __construct()
    {
        //Define a tabela principal deste módulo
        $this->table_name = 'cms_componentes';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Componentes_model');
        $this->Componentes_model->set_table_name($this->table_name);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Componentes';
        $this->module = '';
        $this->controller = 'componentes';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Define o site_id
        $this->where = 'site_id = '.$this->site_id;

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'tamanho' => null, // Largura da coluna
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string' // Tipo (integer, date, string, boolean), usado no WHERE...
            ),
            array(
                'descricao' => 'Script',
                'tamanho' => NULL,
                'coluna' => 'path',
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Posição',
                'tamanho' => NULL,
                'coluna' => 'posicao',
                'coluna_filtravel' => true,
                'tipo' => 'string'
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        if ( $this->input->post('submit') )
        {
            $dados = $this->input->post();
        }
        elseif ( !empty($id) )
        {
            $dados['componente'] = $this->Componentes_model->obter($id);
            $dados['componente_paginas'] = $this->Componentes_model->obter_paginas($id);
        }
        else
        {
            $dados['componente'] = array();
        }

        $dados['paginas'] = $this->Componentes_model->listar_paginas();

        $this->form_validation->set_rules('componente[titulo]', 'Título', 'trim|required');
        $this->form_validation->set_rules('componente[path]', 'Módulo', 'trim|required|path_filter');
        $this->form_validation->set_rules('componente[posicao]', 'Posição', 'trim|required');
        if ( $this->form_validation->run() )
        {
            $dados = $this->input->post();
            $id = $this->Componentes_model->salvar($dados['componente']);
            $this->Componentes_model->salvar_posicao($id, $this->input->post('componente_paginas'));
            redirect('componentes');
        }
        else
        {
            $dados['erro'] = validation_errors();
        }

        $this->load->view('componentes_editar', $dados);
    }

    function delete($id)
    {
        $this->Componentes_model->delete($id);
        redirect('componentes');
    }

    function get_last_content($id)
    {
        $componente = $this->Componentes_model->obter($id);
        echo $componente['content_last'];
    }

    function get_last_path($id)
    {
        $componente = $this->Componentes_model->obter($id);
        echo $componente['path_last'];
    }

    function remover($id)
    {
        $ok = $this->Componentes_model->remover_paginas($id);
        return parent::remover($id);
    }
}
?>