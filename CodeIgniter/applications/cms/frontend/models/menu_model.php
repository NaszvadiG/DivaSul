<?php
class Menu_model extends Default_model
{
    function listar_itens($menu_id)
    {
        $site_id = $this->data->site['id'];
        $itens = $this->db->select('*')->from('cms_paginas')->where(array('menu_id'=>$menu_id, 'site_id' => $site_id))->order_by('ordem ASC')->order_by('titulo')->get()->result_array();

        return $itens;
    }
}
?>
