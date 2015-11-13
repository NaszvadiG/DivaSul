<?php
class Enquetes_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'cms_enquetes';
        parent::__construct();
    }

    function obter_respostas($enquete_id)
    {
        $opcoes = $this->db->select('*')->from('cms_enquetes_opcoes')->where('enquete_id', $enquete_id)->get()->result_array();

        return $opcoes;
    }

    function salvar_respostas($enquete_id, $respostas)
    {
        $ok = false;

        // "Limpa" as opções de resposta
        $respostas_old = $this->db->select('*')->from('cms_enquetes_opcoes')->where('enquete_id', $enquete_id)->get()->result_array();

        // Insere as opções de resposta da enquete
        foreach ( $respostas as $k => $resposta )
        {
            // Verifica se não é um campo em branco...
            if ( strlen(trim($resposta['descricao'])) > 0 )
            {
                $resp = array(
                    'enquete_id' => $enquete_id,
                    'descricao' => $resposta['descricao'],
                    'cor' => $resposta['cor']
                );

                if ( $respostas_old[$k]['id'] > 0 )
                {
                    $ok = $this->db->update('cms_enquetes_opcoes', $resp, array('id'=>$respostas_old[$k]['id']));
                }
                else
                {
                    $ok = $this->db->insert('cms_enquetes_opcoes', $resp);
                }
            }
            elseif ( $respostas_old[$k]['id'] > 0 )
            {
                $this->db->where('id', $respostas_old[$k]['id']);
                $ok = $this->db->delete('cms_enquetes_opcoes');
            }

            unset($respostas_old[$k]['id']);
        }

        // Apaga
        foreach ( (array)$respostas_old as $resp )
        {
            $this->db->where('id', $resp['id']);
            $ok = $this->db->delete('cms_enquetes_opcoes');
        }

        return $ok;
    }

    function obter_resultados($enquete_id)
    {
        $opcoes = $this->obter_respostas($enquete_id);

        $resultados = array();
        foreach ( (array)$opcoes as $opcao )
        {
            $resultados[$opcao['id']] = array(
                'descricao' => $opcao['descricao'],
                'cor' => $opcao['cor'],
                'votos' => $this->obter_votos_opcao($opcao['id'])
            );
        }

        return $resultados;
    }

    function obter_votos_opcao($opcao_id)
    {
        $votos = current($this->db->select('COUNT(id)')->from('cms_enquetes_votos')->where('opcao_id', $opcao_id)->get()->row_array());

        return $votos;
    }

    function obter_total_votos($enquete_id)
    {
        $total_votos = current($this->db->select('COUNT(id)')->from('cms_enquetes_votos')->where('enquete_id', $enquete_id)->get()->row_array());

        return $total_votos;
    }

    function remover($enquete_id)
    {
        $this->db->delete('cms_enquetes_votos', array('enquete_id' => $enquete_id));
        $this->db->delete('cms_enquetes_opcoes', array('enquete_id' => $enquete_id));
        $this->db->delete($this->table_name, array('id' => $enquete_id));
    }
}
?>
