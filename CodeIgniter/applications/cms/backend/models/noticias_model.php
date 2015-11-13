<?php
/**
 * Classe para a tabela cms_noticias
 */
class Noticias_model extends Default_model
{
    // Limites das imagens
    private $thumb_w = 602;
    private $thumb_h = 224;
    private $image_w = 1024;
    private $image_h = 1024;
    private $max_destaques = 3;

    /**
     * Construtor
     */
    function __construct()
    {
        parent::__construct();
        $this->gravar_log = true;
    }

    /**
     * Reescrito função de obter notícias
     * Traz junto as:
     *  imagens;
     *  e se for site_id = 1 traz também:
     *  categorias;
     *
     * @param integer $id - Código da notícia
     * @return array
     */
    function obter($id)
    {
        $noticia = parent::obter($id);
        $noticia['imagens'] = $this->obter_imagens($id);
        $noticia['categorias_ids'] = $this->obter_categorias($id);

        return $noticia;
    }

    /**
     * Obtém as imagens de uma notícia
     *
     * @param integer $notícia_id - Código da notícia
     * @return array
     */
    function obter_imagens($noticia_id='')
    {
        $imagens = array();
        if ( strlen($noticia_id) > 0 )
        {
            // Pega as imagens da notícia
            $imagens = $this->db->select('*')->from('cms_noticias_imagens')->where('noticia_id', $noticia_id)->get()->result_array();
        }

        return $imagens;
    }

    /**
     * Reescrito função de salvar notícia
     * Salva também as imagens e categorias_ids
     * Obs.: Retorna Exception em caso de erro.
     *
     * @param array $noticia
     * @param string $path
     * @param string $path_temporario
     * @return integer $id - Código da notícia
     */
    function salvar($noticia, $path, $path_temporario)
    {
        $imagens = $noticia['imagens'];
        $categorias_ids = $noticia['categorias_ids'];
        unset($noticia['imagens']);
        unset($noticia['categorias_ids']);
        $id = parent::salvar($noticia);
        if ( $id )
        {
            // Remove categorias antigas
            $this->db->delete('cms_noticias_rel_categorias', array('noticia_id'=>$id));
            // Relaciona a notícia com as categorias
            if ( is_array($categorias_ids) && count($categorias_ids) > 0 )
            {
                foreach ( $categorias_ids as $categoria_id )
                {
                    if ( !$this->db->insert('cms_noticias_rel_categorias', array('noticia_id'=>$id, 'categoria_id'=>$categoria_id)) )
                    {
                        throw new Exception('Não foi possível vincular a notícia '.$noticia['titulo'].'('.$id.') com a categoria '.$categoria_id.'.');
                    }
                }
            }

            // Salva as imagens
            if ( is_array($imagens) && count($imagens) > 0 )
            {
                $this->salvar_imagens($id, $imagens, $path, $path_temporario); 
            }
        }

        return $id;
    }

    /**
     * Redimensiona e salva as imagens
     */
    function salvar_imagens($noticia_id, $imagens, $path, $path_temporario)
    {
        $site_path = SERVERPATH.$path;
        foreach ( $imagens as $imagem )
        {
            // Se a imagem existe
            if ( is_file($imagem['arquivo']) )
            {
                // Paths
                $imagem['dir'] = ($imagem['dir']) ? $imagem['dir'] : 'noticias/'.$noticia_id;
                $path = $site_path.$imagem['dir'];

                // Se não existir e não conseguir criar o path, erro!
                if ( !is_dir($site_path.'noticias') && !mkdir($site_path.'noticias') )
                {
                    throw new Exception('Não foi possível criar o diretório('.$site_path.'noticias) para as imagens.');
                }
                if ( !is_dir($path) && !mkdir($path) )
                {
                    throw new Exception('Não foi possível criar o diretório('.$path.') para as imagens.');
                }
                $path .= '/';

                // Quebra o nome da imagem e a extensão
                $nome_imagem = array_pop(explode('/', $imagem['arquivo']));
                $array = explode('.', $nome_imagem);array_pop($array);
                $nome_imagem = implode('.', $array);
                $ext = MY_Utils::obter_extensao_imagem($imagem['arquivo']);
                $nome_imagem = MY_Utils::removeSpecialChars($nome_imagem);

                // Redimensiona a imagem
                $img = array(
                    'name' => $nome_imagem.'.'.($ext?$ext:'jpg'),
                    'tmp_name' => $imagem['arquivo']
                );
                if ( !MY_Utils::redimensionar_imagem($img, $path.$img['name'], $this->image_h, $this->image_w) )
                {
                    throw new Exception('Não foi possível redimensionar a imagem: '.$img['name']);
                }

                // Redimensiona a miniatura da imagem
                $img_thumb = array(
                    'name' => 'thumb_'.$nome_imagem.'.'.$ext,
                    'tmp_name' => $imagem['arquivo']
                );
                if ( !MY_Utils::redimensionar_imagem($img_thumb, $path.$img_thumb['name'], $this->thumb_h, $this->thumb_w) )
                {
                    throw new Exception('Não foi possível redimensionar a miniatura: '.$img_thumb['name']);
                }

                // Confere se gravou certo as imagens
                if ( !is_file($path.$img['name']) )
                {
                    throw new Exception('Não foi possível armazenar a imagem: '.$path.$img['name']);
                }
                else if ( !is_file($path.$img_thumb['name']) )
                {
                    throw new Exception('Não foi possível armazenar a imagem: '.$path.$img['name']);
                }
                else
                {
                    // Se a imagem em questão for capa, tira a capa anterior
                    if ( $imagem['capa'] == 1 )
                    {
                        $imagem['capa'] = 1;
                        $where = array(
                            'capa' => 1,
                            'noticia_id' => $noticia_id
                        );
                        if ( !$this->db->update('cms_noticias_imagens', array('capa'=>0), $where) )
                        {
                            throw new Exception('Não foi possível trocar imagem capa.');
                        }
                    }
                    else
                    {
                        $imagem['capa'] = 0;
                    }

                    // Tudo ok, salva na base
                    $dados_img = array(
                        'noticia_id' => $noticia_id,
                        'criado_por' => $this->usuario_id,
                        'arquivo' => $img['name'],
                        'arquivo_thumb' => $img_thumb['name'],
                        'credito' => $imagem['credito'],
                        'legenda' => $imagem['legenda'],
                        'dir' => $imagem['dir'],
                        'capa' => $imagem['capa']
                    );
                    if ( !$this->db->insert('cms_noticias_imagens', $dados_img) )
                    {
                        throw new Exception('Não foi possível salvar os dados da imagem.');
                    }
                }

                // Apaga a imagem temporária
                if ( !unlink($imagem['arquivo']) )
                {
                    throw new Exception('Não foi possível remover a imagem temporária: '.$nome_imagem.'.'.$ext);
                }
            }
            else
            {
                if ( $imagem['id'] )
                {
                    if ( $imagem['capa'] == 1 )
                    {
                        $imagem['capa'] = 1;
                    }
                    else
                    {
                        $imagem['capa'] = 0;
                    }

                    // Atualiza crédito/legenda/capa
                    $dados_img = array(
                        'noticia_id' => $noticia_id,
                        'criado_por' => $this->usuario_id,
                        'credito' => $imagem['credito'],
                        'legenda' => $imagem['legenda'],
                        'capa' => $imagem['capa']
                    );
                    if ( !$this->db->update('cms_noticias_imagens', $dados_img, array('id'=>$imagem['id'])) )
                    {
                        throw new Exception('Não foi possível salvar os dados da imagem.');
                    }
                }
                else
                {
                    throw new Exception('Não foi possível obter a imagem: '.$imagem['arquivo']);
                }
            }
        }
        // Chegando aqui sem dar Exception, está tudo OK ;)
    }

    /**
     * Lista as categorias das notícias
     *
     * @param boolean $obter_pai - Se true, traz o titulo da categoria pai, ou invéz do ID
     * @return array
     */
    function listar_categorias($obter_pai=false)
    {
        if ( !$obter_pai )
        {
            $categorias = $this->db->select('*')->from('cms_noticias_categorias')->where('site_id', $this->site_id)->order_by('parent_id DESC, titulo ASC')->get()->result_array();
        }
        else
        {
            $categorias = $this->db->select('cat.*, pai.titulo as categoria_pai')->from('cms_noticias_categorias cat')->join('cms_noticias_categorias pai', 'cat.parent_id = pai.id', 'left')->where('cat.site_id', $this->site_id)->order_by('cat.parent_id DESC, cat.titulo ASC')->get()->result_array();
        }

        return $categorias;
    }

    /**
     * Obtém uma categoria
     *
     * @param integer $categoria_id - Código da categoria - cms_noticias_categorias.id
     * @return array $categoria - Array associativo com os dados da categoria
     */
    function obter_categoria($categoria_id)
    {
        $categoria = $this->db->select('*')->from('cms_noticias_categorias')->where('id', $categoria_id)->get()->row_array();

        return $categoria;
    }

    /**
     * Obtém os vínculos com categorias de uma notícia
     *
     * @param integer $notícia_id - Código da notícia - cms_noticas.id
     * @return array
     */
    function obter_categorias($noticia_id)
    {
        $categorias = array();
        $dados = $this->db->select('nc.id')->from('cms_noticias_categorias nc')->join('cms_noticias_rel_categorias rc', 'nc.id = rc.categoria_id', 'inner')->where('rc.noticia_id', $noticia_id)->order_by('titulo ASC')->get()->result_array();
        foreach ( $dados as $val )
        {
            $categorias[] = current($val);
        }

        return $categorias;
    }

    /**
     * Salva uma categoria
     *
     * @param array $categoria
     * @return integer $id - Código do registro inserido/atualizado
     */
    function salvar_categoria($categoria)
    {
        $this->set_table_name('cms_noticias_categorias');
        $id = parent::salvar($categoria);

        return $id;
    }

    /**
     * Remove uma categoria
     *
     * @param integer $categoria_id
     * @return boolean
     */
    function remover_categoria($categoria_id)
    {
        $this->set_table_name('cms_noticias_categorias');
        $ok = parent::remover($categoria_id);

        return $ok;
    }

    /**
     * Função que altera a ordem da notícia
     *
     * @param integer $notícia_id - Código da notícia - cms_noticas.id
     * @param integer $ordem - Ordem da notícia - tabela: cms_noticas_destaques.ordem
     * @return boolean de sucesso ou falha
     */
    function alterar_ordem($noticia_id, $ordem)
    {
        // Remove destaque anterior (ordem desejada)
        $ok = $this->db->delete('cms_noticias_destaques', array('ordem' => $ordem, 'site_id' => $this->site_id));
        // Remove destaque anterior (ordem antiga desta notícia)
        $ok = $this->db->delete('cms_noticias_destaques', array('noticia_id' => $noticia_id, 'site_id' => $this->site_id));

        // Se a ordem for menor de 5 (máximo destaque) adiciona-a na ordenação
        if ( $ordem <= $this->max_destaques )
        {
            $ok = $this->db->insert('cms_noticias_destaques', array('site_id' => $this->site_id, 'ordem' => $ordem, 'noticia_id'=>$noticia_id));
        }

        return $ok;
    }

    /**
     * Função que remove uma notícia
     * Obs.: Retorna Exception em caso de erro.
     *
     * @param integer $id - Código da notícia - cms_noticas.id
     * @return boolean de sucesso ou falha. (ou throw em caso de algum erro)
     */
    function remover($id)
    {
        // Remove categorias antigas
        if ( !$this->db->delete('cms_noticias_rel_categorias', array('noticia_id'=>$id)) )
        {
            throw new Exception('Não foi possível vínculos com categorias.');
        }

        // Remove as imagens da notícia
        $imagens = $this->db->select('id')->from('cms_noticias_imagens')->where('noticia_id', $id)->get()->result_array();
        foreach ( (array)$imagens as $imagem )
        {
            if ( !$this->remover_imagem($imagem['id']) )
            {
                throw new Exception('Não foi possível remover a imagem '.$imagem['id'].'.');
            }
        }

        return parent::remover($id);
    }

    /**
     * Função que remove uma imagem.
     * Deleta a miniatura, a imagem e remove da base o registro.
     *
     * @param integer $id - Código da imagem - cms_noticias_imagens.id
     * @return boolean de sucesso ou falha.
     */
    function remover_imagem($id)
    {
        $imagem = $this->db->select('noticia_id, arquivo, arquivo_thumb')->from('cms_noticias_imagens')->where('id', $id)->get()->row_array();
        $img = SERVERPATH.'arquivos/noticias/'.$imagem['noticia_id'].'/'.$imagem['arquivo'];
        $img_thumb = SERVERPATH.'arquivos/noticias/'.$imagem['noticia_id'].'/'.$imagem['arquivo_thumb'];
        // Remove a imagem e a miniatura
        if (is_file($img_thumb)){$ok=unlink($img_thumb);}
        if (is_file($img)      ){$ok=unlink($img);}
        $ok = $this->db->delete('cms_noticias_imagens', array('id'=>$id));

        return $ok;
    }
}
?>
