<?php
class Gerenciador_de_arquivos extends CI_Controller
{
    private $site_id = 1;
    private $site_dir = '';
    private $module_url = '';

    function __construct()
    {
        parent::__construct();
        $this->load->helper('directory');
        $this->site_id = $this->session->userdata('site_id');
        $this->site_dir = current($this->db->select('dir')->from('cms_sites')->where('id', $this->site_id)->get()->row_array());
        $this->module_url = site_url($this->router->class).'/';
    }

    function index()
    {
        $dados = array();
        $dados['remover_em_lote'] = array();

        // Obtém o site (para obter o diretório /arquivos)

        // Remover arquivos
        if ( $this->input->post('remover') )
        {
            foreach ( (array)$this->input->post('remover') as $arquivo )
            {
                $dados['remover_em_lote'][] = $this->limpar_diretorio($arquivo);
            }
        }
        // Fim do remover arquivos

        // Aqui obtém os parâmetros da função
        $args = array();
        foreach ( func_get_args() as $k => $arg )
        {
            $args[] = urldecode($arg);
        }

        // Diretório /arquivos
        $arquivos_path = $this->limpar_barras(realpath(SERVERPATH.$this->site_dir.'/arquivos').'/');
        $arquivos_url = '/';
        if ( strlen($this->site_dir) > 0 ) { $arquivos_url .= $this->site_dir.'/'; }
        $arquivos_url .= 'arquivos/';
        $current_dir = $this->limpar_barras(implode('/',$args).'/');
        if ( $current_dir == '/' ) { $current_dir = ''; } // Se tá em /arquivos, não precisa desta barra dupla :)

        // Define umas variáveis para a view
        $dados['arquivos_path'] = $arquivos_path;
        $dados['arquivos_url'] = $arquivos_url;
        $dados['current_dir'] = $current_dir;

        // Navbar (breadcrumbs)
        $dados['breadcrumbs'] = '';
        $dados['breadcrumbs'] .= '<a href="'.$this->module_url.'">raiz</a>';
        if ( !empty($args) )
        {
            foreach ( $args as $item )
            {
                if ( $item == '..' )
                {
                    redirect($this->router->class);
                    exit;
                }

                // Não deixa procurar pastas anteriores a raiz
                $dados['breadcrumbs'] .= ' / <a href="'.$this->module_url.'index/'.$top.$item.'">'.$item.'</a>';
                $top .= $item.'/';
            }
        }
        $dados['breadcrumbs'] .= ' <a href="#" onclick="novapasta()"><img src="'.base_url('arquivos/css/icons/new_folder.png').'" alt="nova pasta" title="Criar novo diretório"/></a>';
        $dados['breadcrumbs'] .= '<br>';
        // fim Navbar (breadcrumbs)

        // Armazena na sessão o diretório atual
        $this->session->set_userdata('folder', $current_dir);

        // Ação de nova pasta
        if ( $_POST['action'] == 'novapasta' )
        {
            $novapasta = $_POST['novapasta'];
            $pathdir = $this->limpar_barras($arquivos_path.$current_dir.$novapasta);
            $pathdir = str_replace(' ', '\ ', $pathdir);
            $pathdir = str_replace('(', '\(', $pathdir);
            $pathdir = str_replace(')', '\)', $pathdir);
            if ( is_dir($pathdir) )
            {
                $dados['txt'] = 'O diretório: <b>'.$novapasta.'</b> já existe.';
            }
            else if ( mkdir($pathdir) )
            {
                $dados['txt'] = 'Criado diretório: <b>'.$novapasta.'</b> com sucesso!';
            }
            else
            {
                $dados['txt'] = 'Não foi possível criar o diretório: <b>'.$novapasta.'</b>.';
            }
        }

        // Ação de renomear
        if ( $_POST['action'] == 'renomear' )
        {
            $oldname = $arquivos_path.$current_dir.$_POST['oldname'];
            $newname = $arquivos_path.$current_dir.$_POST['newname'];
            $ok = rename("$oldname", "$newname");
            $dados['txt'] = $_POST['oldname'].' -> '.$_POST['newname'].' - '.($ok?'Renomeado com sucesso':'Falha ao renomear.');
        }

        // Ação de copiar
        if ( $_POST['action'] == 'copiar' )
        {
            $oldname = $arquivos_path.$current_dir.$_POST['oldname'];
            $newname = $arquivos_path.$current_dir.$_POST['newname'];

            if ( !is_dir($newname) )
            {
                // Copia o conteúdo de um diretório
                function full_copy($source, $target)
                {
                    if ( is_dir($source) )
                    {
                        $ok = mkdir($target, 0774);
                        $d = dir($source);
                        while ( ($entry = $d->read()) !== FALSE )
                        {
                            if ($entry=='.'||$entry=='..')
                            {
                                continue;
                            }
                            $Entry = $source.'/'.$entry;
                            if (is_dir($Entry))
                            {
                                full_copy($Entry, $target.'/'.$entry);
                                continue;
                            }
                            $ok = copy($Entry, $target.'/'.$entry);
                            $ok = chmod($target.'/'.$entry, 0774);
                        }
                        $d->close();
                    }
                    else
                    {
                        $ok = copy($source, $target);
                        $ok = chmod($target, 0774);
                    }
                    return $ok;
                }

                $ok = full_copy($oldname, $newname);
                $dados['txt'] = $_POST['oldname'].' -> '.$_POST['newname'].' - '.($ok?'Copiado com sucesso':'Falha ao copiar');
            }
            else
            {
                $dados['txt'] = '<b>'.$_POST['newname'].'</b> já existe.';
            }
        }

        // Envia arquivos (não é mais utilizado, agora tem o "multiupload")
        if ( $_FILES['arquivo'] )
        {
            $arquivo = $_FILES['arquivo'];
            $ok = move_uploaded_file($arquivo['tmp_name'], $arquivos_path.$current_dir.$arquivo['name']);
            chmod($arquivos_path.$current_dir.$arquivo['name'], 0644);
            $dados['txt'] .= $arquivo['name'].' - '.($ok?'<span style="color:green;">Enviado.':'<span style="color:red;">Falha!').'</span><br>';
        }

        // Lê o doretório corrente
        $path = $arquivos_path.$current_dir;
        $map = glob("{$path}/*", GLOB_BRACE);
        $dirs = array();
        $arquivos = array();
        // Percorre os items
        if ( is_array($map) && count($map) > 0 )
        {
            foreach ( $map as $item )
            {
                $item = basename($item);

                // Testa se e diretorio ou arquivo
                if ( is_dir($arquivos_path.$current_dir.$item) )
                {
                    $dirs[] = $item;
                }
                elseif( is_file($arquivos_path.$current_dir.$item) )
                {
                    $arquivos[] = $item;
                }
            }
        }

        // Reordena os diretórios
        if ( count($dirs) > 0 )
        {
            // asort   => 1,10,2,3,4,5,6,7,8,9
            // natsort => 1,2,3,4,5,6,7,8,9,10
            natsort($dirs);
        }
        // Reordena os arquivos
        if ( count($arquivos) > 0 )
        {
            natsort($arquivos);
        }

        // Passa valores pra view
        $dados['arquivos'] = $arquivos;
        $dados['dirs'] = $dirs;
        $dados['arquivos_path'] = SERVERPATH.$this->site_dir.'/arquivos/'.$current_dir.'/';

        // Carrega a view
        $this->load->view('gerenciador_de_arquivos', $dados);
    }

    /**
     * Editor de arquivos (CodeMirror)
     */
    function editar()
    {
        if ( $this->input->post('name') )
        {
            $dados = $_POST;
            $alterado = $this->gravar_arquivo($dados['name'], $dados['content']);
            redirect('gerenciador_de_arquivos');
        }

        $dados['name'] = base64_decode($_GET['file']);
        if ( !empty($dados['name']) )
        {
            $dados['content'] = $this->ler_arquivo($dados['name']);
            $this->load->view('gerenciador_de_arquivos_editar', $dados);
        }
    }

    /**
     * Remover arquivos (ação do botão "Excluir")
     */
    function remover($remover)
    {
        $dir = unserialize(base64_decode(urldecode($remover)));
        $dados = $this->limpar_diretorio($dir);
        $ok = count($dados['pasta']['falha']) == 0 && count($dados['arquivos']['falha']) == 0;

        echo $ok ? 'ok' : 'Falha ao remover.';
    }

    function limpar_diretorio($dir)
    {
        $dados = array();
        $dir = $this->limpar_barras($dir);
        if ( is_dir($dir) )
        {
            foreach ( (array)glob("{$dir}/*", GLOB_BRACE) as $file )
            {
                $file = $this->limpar_barras($file);
                if ( is_dir($file) )
                {
                    if ( $this->limpar_diretorio($file) )
                    {
                        $dados['pasta']['sucesso'][] = $file;
                    }
                    else
                    {
                        $dados['pasta']['falha'][] = $file;
                    }
                }
                elseif ( is_file($file) )
                {
                    if ( unlink($file) )
                    {
                        $dados['arquivo']['sucesso'][] = $file;
                    }
                    else
                    {
                        $dados['arquivo']['falha'][] = $file;
                    }
                }
            }

            $ok = rmdir($dir);
            if ( $ok )
            {
                $dados['pasta']['sucesso'][] = $dir;
            }
            else
            {
                $dados['pasta']['falha'][] = $dir;
            }
        }
        elseif ( is_file($dir) )
        {
            $ok = unlink($dir);
            if ( $ok )
            {
                $dados['arquivo']['sucesso'][] = $dir;
            }
            else
            {
                $dados['arquivo']['falha'][] = $dir;
            }
        }

        return $dados;
    }

    function limpar_barras($dir)
    {
        $dir = preg_replace('/(\/+)/','/',$dir);

        return $dir;
    }

    function ler_arquivo($arquivo)
    {
        return file_get_contents($arquivo);
    }

    function gravar_arquivo($arquivo, $conteudo)
    {
        return file_put_contents($arquivo, $conteudo);
    }
}
?>
