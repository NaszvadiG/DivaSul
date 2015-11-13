/**
 * Fun��o gen�rica de mascara
 * # => somente numeros
 * & => somente letras minusculas
 * * => qualquer coisa
 */
function mascara_generica(campo, evento, formato)
{
    if ( !teclaEspecial(evento) && (campo.value.length < formato.length ) )
    {
        var letra = formato.charAt(campo.value.length);
        if( letra == '#')
        {
            return mascaraSomenteNumeros(evento);
        }
        else if(letra == '&')
        {
            return mascaraSomenteMinusculo(evento);
        }
        else if(letra == '*')
        {
            return true;
        }
        else
        {
            campo.value += formato.charAt(campo.value.length);
            return false;
        }
    }
    else
    {
        return false;
    }
}



/**
 * Fun��o respons�vel pela m�scara nos campos de hora (##:##)
 */
function mascaraHora(campoHora, evento)
{
    if ( !teclaEspecial(evento) && (campoHora.value.length == 2) )
    {
        campoHora.value += ':';
    }

    return mascaraSomenteNumeros(evento);
}

/**
 * Fun��o respons�vel pela m�scara nos campos de data (##/##/####)
 */
function mascaraData(campoData, evento)
{
    if ( !teclaEspecial(evento) && (campoData.value.length == 2 || campoData.value.length == 5) )
    {
        campoData.value += '/';
    }

    return mascaraSomenteNumeros(evento);
}

/**
 * Fun��o respons�vel pela m�scara nos campos de CNPJ (##.###.###/####-##)
 */
function mascaraCnpj(campoCnpj, evento)
{
    if ( !teclaEspecial(evento) && (campoCnpj.value.length == 2 || campoCnpj.value.length == 6) )
    {
        campoCnpj.value += '.';
    }
    else if ( !teclaEspecial(evento) && (campoCnpj.value.length == 10) )
    {
        campoCnpj.value += '/';
    }
    else if ( !teclaEspecial(evento) && (campoCnpj.value.length == 15) )
    {
        campoCnpj.value += '-';
    }

    return mascaraSomenteNumeros(evento);
}

/**
 * Fun��o respons�vel pela m�scara nos campos de CPF (###.###.###-##)
 */
function mascaraCpf(campoCpf, evento)
{
    if ( !teclaEspecial(evento) && (campoCpf.value.length == 3 || campoCpf.value.length == 7) )
    {
        campoCpf.value += '.';
    }
    else if ( !teclaEspecial(evento) && (campoCpf.value.length == 11) )
    {
        campoCpf.value += '-';
    }

    return mascaraSomenteNumeros(evento);
}

/**
 * Fun��o que verifica se a tecla digitada n�o � um n�mero ou uma tecla especial
 */
function mascaraSomenteNumeros(evento)
{
    var ret = false;

    tecla = obtemTecla(evento);

    // Tecla especial ou n�mero
    if ( teclaEspecial(evento) || ((tecla >= 48) && (tecla <= 57)) )
    {
        ret = true;
    }

    return ret;
}

/**
 * Fun��o que verifica se a tecla digitada � uma letra min�scula
 */
function mascaraSomenteMinusculo(evento)
{
    var ret = false;

    tecla = obtemTecla(evento);

    // Tecla especial ou n�mero
    if ( teclaEspecial(evento) || ((tecla >= 97) && (tecla <= 122)) )
    {
        ret = true;
    }
    else
    {
        alert('Por favor, somente letras min�sculas.');
    }

    return ret;
}

/**
 * Fun��o que verifica se o evento n�o � alguma tecla "especial"
 */
function teclaEspecial(evento)
{
    var ret = false;

    tecla = obtemTecla(evento);

    //     backspace          tab             delete            esc             enter          caps lock        ou setas    ou -   ou _
    if ( ((tecla == 8) || (tecla == 9) || (tecla == 46) || (tecla == 27) || (tecla == 13)) || (tecla == 20) ||
         ((tecla >= 37) && (tecla <= 40)) || (tecla == 45) || (tecla == 95) )
    {
        ret = true;
    }

    return ret;  
}

/**
 * Fun��o que obt�m o "keyCode" do evento
 */
function obtemTecla(evento)
{
    var tecla;

    /*if ( evento.which )  
    {    
        tecla = evento.which
        alert("which" + " - " + tecla);   
    }    
    else*/
    if ( evento.keyCode ) // Netscape/Firefox/Opera/Chrome
    {    
        tecla = evento.keyCode;
        //alert("keyCode" + " - " + tecla);   
    }     
    else
    {
        tecla = evento.charCode;
        //alert("charCode" + " - " + tecla);
    }

    return tecla;
}
