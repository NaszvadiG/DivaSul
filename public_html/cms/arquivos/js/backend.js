/**
 * Exibe o fundo escuro e o loading
 */
function show_loading()
{
    document.getElementById('loading').style.display="block";
}

/**
 * Oculta o fundo escuro e o loading
 */
function hide_loading()
{
    document.getElementById('loading').style.display="none";
}

/**
 * Exibe o fundo escuro e o mini loading
 */
function show_mini_loading()
{
    document.getElementById('mini_loading').style.display="block";
}

/**
 * Oculta o fundo escuro e o mini loading
 */
function hide_mini_loading()
{
    document.getElementById('mini_loading').style.display="none";
}

$(window).load(function()
{
    // Exibe modal
    if ( $('.modal .modal-body p').length > 0 )
    {
        $('.modal').show(250);
    }
    // Fecha a mensagem de erro
    $('body').on('click', '.modal .modal-footer button', function()
    {
        $(this).parent().parent().parent().parent().remove();
    });
    $('body').on('click', '.modal .modal-header button', function()
    {
        $(this).parent().parent().parent().parent().remove();
    });

    // Imagens/galerias
    if ( $("a[rel^='prettyPhoto']").length > 0 )
    {
       $("a[rel^='prettyPhoto']").prettyPhoto({social_tools:false});
    }

    // Campos data/hora
    if ( $('.campo_data').length > 0 )
    {
        $('.campo_data').datepicker(
        {
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
            dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            prevText : 'Anterior',
            nextText : 'Próximo',
            showAnim : 'slideDown',
            showButtonPanel: false
        });
    };
    if ( $('.campo_data').length > 0 )
    {
        $('.campo_hora').timepicker(
        {
            hourText: 'Hora',
            minuteText: 'Minuto',
            minutes: {
                starts: 0,
                ends: 59,
                interval: 5
            },
            rows: 4,
            showHours: true,
            showMinutes: true,
            showAnim : 'slideDown',
            showButtonPanel: true,
            showCloseButton: true,
            closeButtonText: 'OK',
            showNowButton: true,
            nowButtonText: 'Agora',
            showDeselectButton: false,
            deselectButtonText: 'Deselecionar'
        });
    }
});
