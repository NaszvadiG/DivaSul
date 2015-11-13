<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Sites extends Default_controller
{
    private $path;

    function __construct()
    {
        //Define a tabela principal deste módulo
        $this->table_name = 'cms_sites';
        parent::__construct($this->table_name);

        // Retira coluna ativo
        unset($this->colunas_default[parent::COLUNA_ATIVO]);

        // Carrega a model e define a tabela principal
        $this->load->model('Sites_model');
        $this->Sites_model->set_table_name($this->table_name);

        //define diretorio dos icones
        $this->path = SERVERPATH.'arquivos/icones_dos_sites/';
        if ( !is_dir($this->path) )
        {
            // Se não existe, cria-o
            mkdir($this->path);
        }

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Sites';
        $this->module = '';
        $this->controller = 'sites';
    }

    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // ORDER BY titulo
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string' // Tipo (integer, date, string, boolean), usado no WHERE...
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        $imagem = $_FILES['icone'];
        if ( $this->input->post('submit') )
        {
            $dados = $this->input->post();
        }
        elseif ( $id )
        {
            $dados['site'] = $this->Sites_model->obter($id);
            $dados['templates'] = montar_assoc($this->db->select('id, titulo')->where('site_id', $id)->get('cms_templates')->result_array());
        }

        // Validação
        $this->form_validation->set_rules('site[titulo]', 'Título', 'trim|required');
        $this->form_validation->set_rules('site[url]', 'Url', 'trim|required');
        if ( $this->form_validation->run() )
        {
            // Salva o icone do site
            if ( strlen($imagem['name']) > 0 )
            {
                $img = $imagem;
                $img['name'] = $dados['site']['id'].'.png';

                $ok = MY_Utils::redimensionar_imagem($imagem, $this->path.$img['name'], 16, 16);
                if ( $ok )
                {
                    $dados['site']['icone'] = $img['name'];
                }
            }

            $id = $this->Sites_model->salvar($dados['site']);
            redirect('sites');
        }
        else
        {
            $dados['erro'] = validation_errors();
        }

        $this->load->view('sites_editar', $dados);
    }

    function remover($id)
    {
        $this->Sites_model->remover($id);

        redirect('sites');
    }
}
?>
