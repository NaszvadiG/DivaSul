<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Menus extends Default_controller
{
    function __construct()
    {
        //Define a tabela principal deste módulo
        $this->table_name = 'cms_menus';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Menus_model');
        $this->Menus_model->set_table_name($this->table_name);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Menus';
        $this->controller = 'menus';
    }

    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Where default
        $this->where = array(
            'site_id = '.$this->site_id
        );

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
            )
        );

        //retira campo ativo da busca
        unset($this->colunas_default[parent::COLUNA_ATIVO]);

        parent::listar();
    }

    function editar($id=NULL)
    {
        $dados = array();
        if ( $this->input->post('submit') )
        {
            $dados = $this->input->post();

            $this->form_validation->set_rules('menu[titulo]', 'Título', 'trim|required');
            if ( $this->form_validation->run() )
            {
                $dados['menu']['site_id'] = $this->session->userdata('site_id');
                $id = $this->Menus_model->salvar($dados['menu']);
                redirect('menus');
            }
            else
            {
                $dados['erro'] = validation_errors();
            }
        }
        elseif ( $id )
        {
            $dados['menu'] = $this->Menus_model->obter($id);
        }

        $this->load->view('menus_editar', $dados);
    }
}
?>
