<?
    function generate_url($title){
         return url_title(convert_accented_characters($title),'dash',TRUE);
    }

    function meses($m){
        $arr = array('', 'janeiro', 'fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro');
        return $arr[(int) $m];
    }

