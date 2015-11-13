<?php
class MY_Valida_data
{
    private $dia;
    private $mes;
    private $ano;

    public function __construct($data=null)
    {
        if ( strlen($data) == 10 )
        {
            $this->set_data($data);
        }
    }

    public function set_data($data)
    {
        list( $this->dia,
              $this->mes,
              $this->ano ) = explode('/', $data);
    }

    public function set_dia($dia)
    {
        $this->dia = $dia;
    }

    public function set_mes($mes)
    {
        $this->mes = $mes;
    }

    public function set_ano($ano)
    {
        $this->ano = $ano;
    }

    /**
     * Verifica se a data é válida
     * @return boolean
     */
    public function valida()
    {
        $valido = false;

        if ( checkdate($this->mes, $this->dia, $this->ano) )
        {
            $valido = true;;
        }

        return $valido;
    }
}
?>
