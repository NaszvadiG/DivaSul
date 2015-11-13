<?php
class MY_Valida_cpf
{
    private $cpf;

    public function __construct($cpf=null)
    {
        if ( strlen($cpf) > 0 )
        {
            $this->set_cpf($cpf);
        }
    }

    public function set_cpf($cpf)
    {
        $this->cpf = preg_replace('/[^0-9]/', '', $cpf);
    }

    public function valida()
    {
        // Verifica se realmente foram digitados 11 digitos
        if ( !(strlen($this->cpf) > 0) && strlen($this->cpf) != 11 )
        {
            $isValid = false;
        }
        // Verifica se não é uma combinação inválida
        elseif ( ($this->cpf == '12345678909') ||
                 ($this->cpf == '00000000000') ||
                 ($this->cpf == '11111111111') ||
                 ($this->cpf == '22222222222') ||
                 ($this->cpf == '33333333333') ||
                 ($this->cpf == '44444444444') ||
                 ($this->cpf == '55555555555') ||
                 ($this->cpf == '66666666666') ||
                 ($this->cpf == '77777777777') ||
                 ($this->cpf == '88888888888') ||
                 ($this->cpf == '99999999999'))
        {
            $isValid = false;
        }
        // Faz a validação
        else
        {
            // Alocação de cada digito digitado no formulário, em uma celula de um vetor
            for ( $i=0; $i < 11; $i++ )
            {
                $cpf_temp[$i]=$this->cpf[$i];
            }

            // Calcula o penúltimo dígito verificador
            $acum = 0;
            for ( $i=0; $i < 9; $i++ )
            {
                $acum = $acum+($this->cpf[$i]*(10-$i));
            }

            $x = $acum;
            $x %= 11;
            if ( $x > 1 )
            {
                $acum = 11 - $x;
            }
            else
            {
                $acum = 0;
            }

            $cpf_temp[9] = $acum;

            // Calcula o último dígito verificador
            $acum = 0;
            for ( $i=0; $i < 10; $i++ )
            {
                $acum = $acum+($cpf_temp[$i]*(11-$i));
            }

            $x = $acum;
            $x %= 11;
            if ( $x > 1 )
            {
                $acum = 11 - $x;
            }
            else
            {
                $acum=0;
            }

            $cpf_temp[10] = $acum;

            // Este laço verifica se o cpf original é igual ao cpf gerado pelos dois laços acima
            for ( $i=0; $i < 11; $i++ )
            {
                if ( $this->cpf[$i] != $cpf_temp[$i] )
                {
                    $isValid = false;
                    $i = 10;
                    $z = 1;
                }
            }

            if ( $z != 1 )
            {
                $isValid = true;
            }
        }

        return $isValid;
    }
}
?>
