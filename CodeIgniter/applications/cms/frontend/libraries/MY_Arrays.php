<?php
class MY_Arrays
{
    /**
     * Converte um array do PHP para um array do psql
     */
    public function to_pg_array($phpArray)
    {
        $set = $phpArray;
        settype($set, 'array'); // can be called with a scalar or array
        $result = array();
        foreach ( $set as $t )
        {
            if ( is_array($t) )
            {
                $result[] = $this->to_pg_array($t);
            }
            else
            {
                // Escapa as aspas duplas
                $t = str_replace('"', '\\"', $t);
                // Cola aspas nos valores não numéricos
                if ( !is_numeric($t) )
                {
                    $t = "'" . $t . "'";
                }
                $result[] = $t;
            }
        }

        //adiciona / antes das aspas
        $result =  str_replace('\'', '"', $result);


        // Adiciona as "{}" e retorna como string
        return '{' . implode(",", $result) . '}';
    }

    /**
     * Converte um array do psql para um array do PHP
     */
    public function to_php_array($psqlArray, &$output, $limit = false, $offset = 1)
    {
        if( false === $limit )
        {
            $limit = strlen($text)-1;
            $output = array();
        }

        if( '{}' != $text )
        do
        {
            if ( '{' != $text{$offset} )
            {
                preg_match("/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/", $text, $match, 0, $offset);
                $offset += strlen($match[0]);
                $output[] = ('"' != $match[1]{0} ? $match[1] : stripcslashes(substr($match[1], 1, -1)));

                if ( '},' == $match[3] )
                {
                    return $offset;
                }
            }
            else
            {
                $offset = to_php_array( $text, $output[], $limit, $offset+1 );
            }
        }
        while ( $limit > $offset );

        return $output;
    }
}
?>
