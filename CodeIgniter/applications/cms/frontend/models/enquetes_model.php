<?php
class Enquetes_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'cms_enquetes';

        parent::__construct();
    }

    function gravar_voto($enquete_id, $resposta, $cod_aluno=null)
    {
        $ok = false;

        if ( !is_array($resposta) )
        {
            $resposta = array($resposta);
        }

        $ip = $_SERVER['HTTP_X_REAL_IP'] ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
        $opcao = MY_Utils::to_pg_array($resposta);

        $voto = array(
            'enquete_id' => $enquete_id,
            'opcao_id' => $opcao,
            'ip' => $ip,
            'cod_aluno' => $cod_aluno
        );

        $ok = $this->db->insert('cms_enquetes_votos', $voto);

        return $ok;
    }

    function obter_opcoes($enquete_id)
    {
        $opcoes = $this->db->select('*')->from('cms_enquetes_opcoes')->where('enquete_id', $enquete_id)->get()->result_array();

        return $opcoes;
    }

    function obter_resultados($enquete_id)
    {
        $opcoes = $this->obter_opcoes($enquete_id);

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
        $votos = current($this->db->select('COUNT(id)')->from('cms_enquetes_votos')->where("arraycontains(opcao_id::int[], '{".$opcao_id."}')")->get()->row_array());

        return $votos;
    }

    function pode_votar($enquete_id, $ip, $cod_aluno = null)
    {
        $ok = true;

        $enquete = $this->obter($enquete_id);

        $where = array(
            'enquete_id' => $enquete_id
        );
        if ( !is_null($cod_aluno) )
        {         
            $where['cod_aluno'] = (string)$cod_aluno;
        }
        else
        {
            $where['ip'] = $ip;
        }

        $votos = $this->db->select("(((NOW()-data_hora) - interval '{$enquete['intervalo_entre_votos']} seconds') > interval '0 second') as votos")->from('cms_enquetes_votos')->where($where)->get()->result_array();

        foreach ( (array)$votos as $voto )
        {
            if ( $voto['votos'] == 'f' )
            {
                $ok = false;
            }
        }

        return $ok;
    }

    function obter_total_votos($enquete_id)
    {
        $total_votos = current($this->db->select('COUNT(id)')->from('cms_enquetes_votos')->where('enquete_id', $enquete_id)->get()->row_array());

        return $total_votos;
    }
}
?>
