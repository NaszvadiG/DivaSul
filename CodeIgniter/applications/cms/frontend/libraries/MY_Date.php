<?php
class MY_Date
{
    // troca "/" por "-" e vice-versa
    public function ajusta_data($data)
    {
        if ( strpos($data, '-') )
        {
            list(
                $ano,
                $mes,
                $dia
            ) = explode('-', $data);

            return $dia.'/'.$mes.'/'.$ano;
        }
        elseif ( strpos($data, '/') )
        {
            list(
                $dia,
                $mes,
                $ano
            ) = explode('/', $data);

            return $ano.'-'.$mes.'-'.$dia;
        }
        else
        {
            return false;
        }
    }

    // formato 2008-06-18
    public function idade($nascimento)
    {
        $hoje = date("Y-m-d"); //pega a data d ehoje
        $aniv = explode("-", $nascimento); //separa a data de nascimento em array, utilizando o símbolo de - como separador
        $atual = explode("-", $hoje); //separa a data de hoje em array
        $idade = $atual[0] - $aniv[0];

        if ( $aniv[1] > $atual[1] ) //verifica se o mês de nascimento é maior que o mês atual
        {
            $idade--; //tira um ano, já que ele não fez aniversário ainda
        }
        elseif($aniv[1] == $atual[1] && $aniv[2] > $atual[2]) //verifica se o dia de hoje é maior que o dia do aniversário
        {
            $idade--; //tira um ano se não fez aniversário ainda
        }

        return $idade; //retorna a idade da pessoa em anos
    }

    // formata a data vinda do banco de dados (timestamp) para [0]=18/06/2008 e [1]10:30
    public function timestamp2date($timestamp)
    {
        $aux = explode(' ', $timestamp);
        $data = explode('-', $aux[0]);
        $return[] = $data[2].'/'.$data[1].'/'.$data[0];
        $hora = explode('.', $aux[1]);
        $hora = explode(':', $hora[0]);
        $return[] = $hora[0].':'.$hora[1];

        return $return;
    }

    //verifica se a data é valida - aceita 18/06/2008 e 18-06-2008
    public function verificadata($data)
    {
        $bissexto = 1;
        if (strpos($data,'-')) $delimiter='-';
        if (strpos($data,'/')) $delimiter='/';
        if (!isset($delimiter)) return false;
        list($dia,$mes,$ano)=explode($delimiter,$data);
        if ( ($ano > 1800) &&
             ($ano < 2008) &&
             ($mes <= 12) &&
             ($mes >= 1) &&
             ($dia >= 1))
        {
            if ( $mes == 2 )
            {
                if ( ($ano % 4 != 0) ||
                     (($ano % 100 == 0) &&
                      ($ano % 400 != 0)))
                {
                    $bissexto = 0;
                }
                if ( ($bissexto == 1) && ($dia <= 29) )
                {
                    return true;
                }
                if ( ($bissexto != 1) && ($dia <= 28) )
                {
                    return true;
                }
            }
            elseif ( $mes==4 ||
                     $mes==6 ||
                     $mes==9 ||
                     $mes==11 )
            {
                if  ( $dia <= 30 )
                {
                    return true;
                }
            }
            else
            {
                if ( $dia <= 31 )
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Converte para extenso
     * @param $nro_mes - 1-12
     * @return String
     */
    public function meses($nro_mes)
    {
        switch($nro_mes)
        {
            case 1:
                $mes = 'Janeiro';
                break;
            case 2:
                $mes = 'Fevereiro';
                break;
            case 3:
                $mes = 'Mar&ccedil;o';
                break;
            case 4:
                $mes = 'Abril';
                break;
            case 5:
                $mes = 'Maio';
                break;
            case 6:
                $mes = 'Junho';
                break;
            case 7:
                $mes = 'Julho';
                break;
            case 8:
                $mes = 'Agosto';
                break;
            case 9:
                $mes = 'Setembro';
                break;
            case 10:
                $mes = 'Outubro';
                break;
            case 11:
                $mes = 'Novembro';
                break;
            case 12:
                $mes = 'Dezembro';
                break;
        }

        return $mes;
    }

    /**
     * 1-> Segunda-feira
     * 2-> Terça-feira
     * ...
     * 6-> Sábado
     * 7-> Domingo
     */
    public function dia_da_semana($dia)
    {
        $dias_da_semana = array(
            1=>'Segunda-feira',
            2=>'Terça-feira',
            3=>'Quarta-feira',
            4=>'Quinta-feira',
            5=>'Sexta-feira',
            6=>'Sábado',
            7=>'Domingo'
        );

        return $dias_da_semana[$dia];
    }
}
?>
