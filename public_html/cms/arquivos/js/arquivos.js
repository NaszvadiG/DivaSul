function novapasta()
{
    var ok = false;
    while ( !ok )
    {
        var novapasta = window.prompt("Nome para nova pasta", "nova_pasta");
        if ( novapasta )
        {
            var er = new RegExp(/^([a-zA-Z0-9_-]((\.)[a-zA-Z0-9]+)?){1,}$/);
            if ( er.test(novapasta) )
            {
                ok = true;
            }
            else
            {
                alert('Nome incorreto: '+novapasta);
            }
        }
        else
        {
            ok = true;
        }
    }

    if ( novapasta )
    {
        var hidform = document.getElementById('hiddenform');
        var action = document.getElementById('action');
        var novo = document.createElement('input');
        novo.setAttribute('name', 'novapasta');
        novo.setAttribute('value', novapasta);
        hidform.insertBefore(novo, action);
        action.setAttribute('value', 'novapasta');
        hidform.submit();
    }
}

function renomear(arquivo)
{
    var ok = false;
    while ( !ok )
    {
        var novoarquivo = window.prompt("Renomear "+arquivo+" para:", arquivo);
        if ( novoarquivo )
        {
            var er = new RegExp(/^([a-zA-Z0-9_-]((\.)[a-zA-Z0-9]+)?){1,}$/);
            if ( er.test(novoarquivo) )
            {
                ok = true;
            }
            else
            {
                alert('Nome incorreto: '+novoarquivo);
            }
        }
        else
        {
            ok = true;
        }
    }

    if ( novoarquivo )
    {
        if ( novoarquivo != arquivo )
        {
            var hidform=document.getElementById('hiddenform');
            var action=document.getElementById('action');
            var novo=document.createElement('input');
            novo.setAttribute('name', 'oldname');
            novo.setAttribute('value', arquivo);
            var novo2=document.createElement('input');
            novo2.setAttribute('name', 'newname');
            novo2.setAttribute('value', novoarquivo);
            hidform.insertBefore(novo, action);
            hidform.insertBefore(novo2, action);
            action.setAttribute('value', 'renomear');
            hidform.submit();
        }
    }
}

function copiar(arquivo)
{
    var ok = false;
    while ( !ok )
    {
        var novoarquivo = window.prompt("Copiar "+arquivo+" para:", arquivo);
        if ( novoarquivo )
        {
            var er = new RegExp(/^([a-zA-Z0-9_-]((\.)[a-zA-Z0-9]+)?){1,}$/);
            if ( er.test(novoarquivo) )
            {
                ok = true;
            }
            else
            {
                alert('Nome incorreto: '+novoarquivo);
            }
        }
        else
        {
            ok = true;
        }
    }

    if ( novoarquivo )
    {
        if ( novoarquivo != arquivo )
        {
            var hidform=document.getElementById('hiddenform');
            var action=document.getElementById('action');
            var novo=document.createElement('input');
            novo.setAttribute('name', 'oldname');
            novo.setAttribute('value', arquivo);
            var novo2=document.createElement('input');
            novo2.setAttribute('name', 'newname');
            novo2.setAttribute('value', novoarquivo);
            hidform.insertBefore(novo, action);
            hidform.insertBefore(novo2, action);
            action.setAttribute('value', 'copiar');
            hidform.submit();
        }
    }
}

function apagar(caminho_completo_do_arquivo_serializado, nome_arquivo, handler)
{
    if ( confirm('Tem certeza que deseja apagar "'+nome_arquivo+'"?') )
    {
        $.ajax({
            type: 'POST',
            url: '/cms/gerenciador_de_arquivos/remover/'+caminho_completo_do_arquivo_serializado,
            success: function(msg)
            {
                if ( msg == 'ok' )
                {
                    alert('Removido com sucesso!');
                    var tr = handler.parentNode.parentNode;
                    tr.parentNode.removeChild(tr);
                }
                else
                {
                    alert(msg);
                }
            }
        });
    }
}

function over(element)
{
    element.style.backgroundColor='#F1F1F1';
}

function out(element)
{
    element.style.backgroundColor='#FFF';
}

function add()
{
    var frm = document.getElementById('formPadrao');
    var elm = frm.getElementsByTagName('input');
    if ( elm[elm.length-2].value != '' )
    {
        var novo = document.createElement('input');
        novo.setAttribute('type', 'file');
        novo.setAttribute('name', 'arquivos[]');
        novo.setAttribute('size', '35');
        novo.setAttribute('onchange', 'add()');
        var br = document.createElement('br');
        frm.insertBefore(novo, elm[elm.length-1]);
        frm.insertBefore(br, elm[elm.length-1]);
    }
}

function verifica()
{
    var frm = document.getElementById('formPadrao');
    var elm = frm.getElementsByTagName('input')[0];
    if ( elm.value == '' )
    {
        alert('Escolha pelo menos 1 arquivo');
        return false;
    }

    return true;
}
