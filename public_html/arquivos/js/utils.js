/**
 * Função que recebe uma string e retorna somente os números dela.
 */
function retornaSomenteNumeros(oldText)
{
    var newText = '';
    var caracter;

    for ( var i=0; i < oldText.length; i++ )
    {
        caracter = parseInt(oldText.charAt(i));
        if ( !isNaN(caracter) )
        {
            newText += caracter;
        }
    }

    return newText;
}

/**
 * Função que recebe uma string e retorna somente as letras minúsculas dela.
 */
function retornaSomenteMinusculo(oldText, blank)
{
    var newText = '';
    var caracter;

    var array_letras_minusculas = new Array(
        '', '-', '_', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
    );
    oldText = retira_acentos(oldText);

    for ( var i=0; i < oldText.length; i++ )
    {
        caracter = oldText.charAt(i).toLowerCase().replace(' ', blank);
        if ( in_array(caracter, array_letras_minusculas) )
        {
            newText += caracter;
        }
    }

    return newText;
}

/**
 * Obviamente, remove os acentos.
 */
function retira_acentos(palavra)
{
    com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ';
    sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC';
    nova='';

    for ( i=0; i < palavra.length; i++ )
    {
        if ( com_acento.search(palavra.substr(i,1)) >= 0 )
        {
            nova += sem_acento.substr(com_acento.search(palavra.substr(i,1)), 1);
        }
        else
        {
            nova += palavra.substr(i, 1);
        }
    }

    return nova;
}

/**
 * Retorna um boolean informando se existe ou não a string no array
 * @param needle String procurada
 * @param haystack Array
 */
function in_array(needle, haystack)
{
    var found = false;
    var length = haystack.length;

    for ( var i=0; i < length; i++ )
    {
        if ( haystack[i] == needle )
        {
            found = true;
        }
    }

    return found;
}

/**
 * Função que formata campos de CNPJ ##.###.###/####-##.
 */
function formataCnpj(campoCnpj)
{
    var cnpj = retornaSomenteNumeros(campoCnpj.value);

    if ( cnpj.length == 14 )
    {
        cnpj = cnpj.substr(0, 2)+'.'+cnpj.substr(2,3)+'.'+cnpj.substr(5,3)+'/'+cnpj.substr(8,4)+'-'+cnpj.substr(12,2);
    }
    else
    {
        cnpj = '';
    }

    campoCnpj.value = cnpj;
}

/**
 * Função que formata campos de CPF ###.###.###-##.
 */
function formataCpf(campoCpf)
{
    var cpf = retornaSomenteNumeros(campoCpf.value);

    if ( cpf.length == 11 )
    {
        cpf = cpf.substr(0, 3)+'.'+cpf.substr(3,3)+'.'+cpf.substr(6,3)+'-'+cpf.substr(9,2);
    }
    else
    {
        cpf = '';
    }

    campoCpf.value = cpf;
}

/**
 * Função que formata um campo para somente números.
 */
function formataSomenteNumeros(campo)
{
    campo.value = retornaSomenteNumeros(campo.value);
}

/**
 * Função que formata campos de data em ##/##/####.
 */
function formataData(campoData)
{
    var data = retornaSomenteNumeros(campoData.value);

    if ( data.length == 8 )
    {
        data = data.substr(0, 2)+'/'+data.substr(2,2)+'/'+data.substr(4,4);
    }
    else
    {
        data = '';
    }

    campoData.value = data;
}

/**
 * Função que formata campos de hora em ##:##.
 */
function formataHora(campoHora)
{
    var hora = retornaSomenteNumeros(campoHora.value);

    if ( hora.length == 4 )
    {
        hora = hora.substr(0, 2)+':'+hora.substr(2,2);
    }
    else if ( hora.length == 3 )
    {
        hora = '0'+hora.substr(0, 1)+':'+hora.substr(1,2);
    }
    else if ( hora.length == 2 )
    {
        hora = '00:'+hora.substr(0,2);
    }
    else if ( hora.length == 1 )
    {
        hora = '00:0'+hora.substr(0,1);
    }
    else
    {
        hora = '';
    }

    if ( (hora.length > 0) && !validar_hora(hora) )
    {
        hora = '';
        alert('Hora inválida!');
        campoHora.focus();
    }

    campoHora.value = hora;
}

function adicionar_css(css)
{
    var head = document.getElementsByTagName('head')[0];
    var s = document.createElement('style');
    s.setAttribute('type', 'text/css');
    if ( s.styleSheet )
    {
        // IE
        s.styleSheet.cssText = css;
    }
    else
    {
        // the world
        s.appendChild(document.createTextNode(css));
    }
    head.appendChild(s);
}
