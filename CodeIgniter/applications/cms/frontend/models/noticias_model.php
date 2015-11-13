<?php
class Noticias_model extends Default_model
{
    private $tabela_cms_noticias = 'cms_noticias';
    private $tabela_cms_noticias_destaques = 'cms_noticias_destaques';
    private $tabela_cms_noticias_imagens = 'cms_noticias_imagens';
    private $tabela_cms_sites = 'cms_sites';

    function __construct()
    {
        parent::__construct();
    }

    function obter_destaque($limit)
    {
        $site_id = $this->data->site['id'];

        $noticias = array();
        $this->db->select('nd.ordem, i.dir, i.arquivo_thumb, n.id, n.titulo, n.intro, n.alias, n.criado');
        $this->db->from($this->tabela_cms_noticias.' n');
        $this->db->join($this->tabela_cms_noticias_destaques.' nd', 'nd.noticia_id = n.id', 'LEFT');

        $this->db->join($this->tabela_cms_noticias_imagens.' i', 'i.noticia_id = n.id AND i.capa=1', 'LEFT');
        $this->db->where('n.site_id', $site_id );
        $this->db->where('nd.ordem >',0);
        $this->db->where('n.ativo','1');
        $this->db->order_by('nd.ordem ASC, n.criado DESC');
        $this->db->limit($limit);
        $noticias = $this->db->get()->result_array();

        return $noticias;
    }

    function obter($id)
    {
        $noticia = array();

        $this->db->select('*');
        $this->db->from($this->tabela_cms_noticias);
        $this->db->where('id',$id);
        $noticia = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from($this->tabela_cms_noticias_imagens);
        $this->db->where('noticia_id',$id);
        $this->db->order_by('capa DESC');
        $noticia['imagens'] = $this->db->get()->result_array();

        return $noticia;
    }
}
?>
