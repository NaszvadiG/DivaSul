<?php
class Busca extends MX_Controller
{
    private $path;
    private $imoveis_por_pagina = 12;

    function __construct()
    {
        parent::__construct();

        $this->path = SERVERPATH;
        $this->path .= 'arquivos/imoveis/';
        $this->load->model('Imoveis_model');
    }

    function buscar()
    {
        $dados = array();
        $dados['path'] = $this->path;

        $busca = $this->input->post('busca');
        if ( !is_array($busca) || count($busca) <= 1 )
        {
            $busca = array();
            if ( strlen($this->uri->segment(2)) > 0 )
            {
                $busca['titulo'] = $this->uri->segment(2);
            }
        }
        elseif ( !isset($busca['negocio_id']) )
        {
            $busca['negocio_id'] = 1;
        }
        $dados['busca'] = $busca;

        if ( count($busca) > 0 )
        {
            $params = array();
            $params['where'] = array();
            $params['where'][] = 'ativo = 1';
            $params['where'][] = "(id = '".trim($busca['titulo'])."' OR LOWER(referencia) = LOWER('".trim($busca['titulo'])."'))";
            $imoveis = $this->Imoveis_model->listar($params);
            if ( count($imoveis) == 1 )
            {
                redirect('imovel/'.$imoveis[0]['id'].'/'.$imoveis[0]['link']);
            }
            else
            {
                $pagina = $busca['pagina'] > 0 ? $busca['pagina'] : $this->uri->segment(2);
                $pagina = (int)$pagina > 1 ? $pagina : 1;
                $dados['pagina'] = $pagina;

                $params = array();
                $params['columns'] = array();
                $params['columns'][] = '*';
                $params['columns'][] = '(SELECT nome FROM site_cidades WHERE id = site_imoveis.cidade_id) AS cidade';
                $params['columns'][] = '(SELECT titulo FROM site_imoveis_categorias WHERE id = site_imoveis.categoria_id) AS categoria';
                $params['columns'][] = '(SELECT titulo FROM site_imoveis_negocios WHERE id = site_imoveis.negocio_id) AS negocio';
                $params['where'] = array();
                $params['where'][] = 'ativo = 1';
                if ( strlen($busca['titulo']) > 0 )
                {
                    $params['where'][] = "(LOWER(referencia) LIKE LOWER('%".trim($busca['titulo'])."%') OR LOWER(titulo) LIKE LOWER('%".trim($busca['titulo'])."%') OR LOWER(descricao) LIKE LOWER('%".trim($busca['titulo'])."%') OR LOWER(obs) LIKE LOWER('%".trim($busca['titulo'])."%'))";
                }
                if ( strlen($busca['negocio_id']) > 0 )
                {
                    $params['where'][] = "negocio_id = '".$busca['negocio_id']."'";
                }
                if ( strlen($busca['cidade_id']) > 0 )
                {
                    $params['where'][] = "cidade_id = '".$busca['cidade_id']."'";
                }
                if ( strlen($busca['bairro']) > 0 )
                {
                    $params['where'][] = "endereco LIKE '%".$busca['bairro']."%'";
                }
                if ( strlen($busca['categoria_id']) > 0 )
                {
                    $params['where'][] = "categoria_id = '".$busca['categoria_id']."'";
                }
                if ( strlen($busca['dormitorios']) > 0 )
                {
                    $params['where'][] = "dormitorios >= '".$busca['dormitorios']."'";
                }
                if ( strlen($busca['garagens']) > 0 )
                {
                    $params['where'][] = "vagas_garagem >= '".$busca['garagens']."'";
                }
                if ( strlen($busca['acima_de']) > 0 )
                {
                    $params['where'][] = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(valor),'r',''),'$',''),' ',''),'.',''),',','.') >= CAST('".$busca['acima_de']."' AS UNSIGNED)";
                }
                if ( strlen($busca['abaixo_de']) > 0 )
                {
                    $params['where'][] = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(valor),'r',''),'$',''),' ',''),'.',''),',','.') <= CAST('".$busca['abaixo_de']."' AS UNSIGNED)";
                }
                $params['limit'] = $this->imoveis_por_pagina;
                $params['offset'] = ($pagina-1)*$this->imoveis_por_pagina;
                $params['order_by'] = 'destaque DESC, ordem ASC, titulo ASC';
                $imoveis = $this->Imoveis_model->listar($params);
                $dados['imoveis'] = $imoveis;

                // Filtros (paginacao)
                unset($params['limit']);
                unset($params['offset']);
                $total_imoveis = $this->Imoveis_model->count($params);
                $dados['total_imoveis'] = $total_imoveis;
                $total_paginas = ceil($total_imoveis/$this->imoveis_por_pagina);
                $dados['total_paginas'] = $total_paginas;
                $tem_mais_paginas = ($pagina*$this->imoveis_por_pagina) < $total_imoveis;
                $dados['tem_mais_paginas'] = $tem_mais_paginas;

                $this->load->view('busca_listar', $dados);
            }
        }
        else
        {
            redirect(base_url());
        }
    }
}
?>