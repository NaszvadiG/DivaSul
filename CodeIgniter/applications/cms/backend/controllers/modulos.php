<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Modulos extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'cms_modulos';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Modulos_model');
        $this->Modulos_model->set_table_name($this->table_name);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Módulos';
        $this->module = '';
        $this->controller = 'modulos';
	
        // Remove a coluna Ativo
        unset($this->colunas_default[parent::COLUNA_ATIVO]);
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string' // Tipo (integer, date, string, boolean), usado no WHERE...
            ),
            array(
                'descricao' => 'Descrição',
                'coluna' => 'descricao',
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Path',
                'coluna' => 'path',
                'coluna_filtravel' => true,
                'tipo' => 'string'
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        $dados = array();
        if ( !$this->input->post('submit') && $id )
        {
            $dados['modulo'] = $this->Modulos_model->obter($id);
        }
        else
        {
            $dados = $this->input->post();
        }

        // Validação
        $this->form_validation->set_rules('modulo[titulo]', 'Título', 'trim|required');
        $this->form_validation->set_rules('modulo[descricao]', 'Descrição', 'trim|required');
        $this->form_validation->set_rules('modulo[path]', 'Path', 'trim|required');

        if ( $this->input->post('submit') &&
             $this->form_validation->run() )
        {
            $modulo = $dados['modulo'];
            $id = $this->Modulos_model->salvar($modulo);

            if ( strlen($id) > 0 )
            {
                redirect('modulos');
            }
            else
            {
                $dados['erro'] = 'Desculpe, mas não foi possível inserir na base de dados.';
            }
        }
        else
        {
            $dados['erro'] = validation_errors();
        }

        // Verifica se já existe o código fonte
        $dados['fonte_existente'] = false;
        $arr = explode('/', $dados['modulo']['path']);
        if ( count($arr) == 1 )
        {
            if ( is_file(APPPATH.'controllers/'.$arr[0].'.php') )
            {
                $dados['fonte_existente'] = true;
            }
        }
        elseif ( count($arr) == 2 )
        {
            if ( is_file(APPPATH.'modules/'.$arr[0].'/controllers/'.$arr[1].'.php') )
            {
                $dados['fonte_existente'] = true;
            }
        }
        else
        {
            $dados['erro'] = 'Diretório inválido!';
        }

        $this->load->view('modulos_editar', $dados);
    }

    function remover($id)
    {
        $this->Modulos_model->remover($id);
        parent::remover($id);
    }

    function baixar($id)
    {
        if ( $id )
        {
            $modulo = $this->Modulos_model->obter($id);

            // Verifica se já existe o código fonte
            $arr = explode('/', $modulo['path']);
            if ( count($arr) == 1 )
            {
                if ( is_file(APPPATH.'controllers/'.$arr[0].'.php') )
                {
                    $zipfile = '/tmp/'.MY_Utils::removeSpecialChars($modulo['titulo']).'.zip';

                    // cria zip, adiciona arquivos...
                    $zip = new ZipArchive();
                    if ( $zip->open($zipfile, ZIPARCHIVE::CREATE) !== TRUE )
                    {
                        exit("Não foi possível criar .zip: <$zipfile>\n");
                    }

                    // Lê os arquivos e armazena em um array
                    $filenames = ler_diretorio($directory);
                    foreach ( $filenames as $filename )
                    {
                        $file = $filename;
                        $arquivo = substr($file, -3);
                        $zip->addFile($filename, $filename);
                    }
                    $zip->close();
                }
            }
            elseif ( count($arr) == 2 )
            {
                if ( is_file(APPPATH.'modules/'.$arr[0].'/controllers/'.$arr[1].'.php') )
                {
                    $dir = APPPATH.'modules/'.$arr[0].'/';
                    $filename = MY_Utils::removeSpecialChars($modulo['titulo']).'.zip';
                    $zipfile = '/tmp/'.$filename;

                    // Lê os arquivos e comprime
                    comprimir_diretorio($dir, $zipfile);

                    if ( !is_file($zipfile) )
                    {
                        exit("Não foi possível criar .zip: <$zipfile>\n");
                    }
                    else
                    {
                        header("Pragma: public");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                        header("Cache-Control: public");
                        header("Content-Description: File Transfer");
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: attachment; filename=\"".$filename."\"");
                        header("Content-Transfer-Encoding: binary");
                        header("Content-Length: ".filesize($zipfile));
                        readfile($zipfile);
                        unlink($zipfile);
                        return;
                    }
                }
            }
        }

        //redirect('modulos');
    }
}
?>
