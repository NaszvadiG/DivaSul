<div class="page-header title-left">
    <div class="container"> 
        <div class="row">
            <div class="col-lg-12">
                <h1>Não encontrado</h1>
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(''); ?>">Principal</a></li>
                    <li>Produto não encontrado</li>
                </ul>
            </div>
        </div><!--end:row-->
    </div>
</div><!--end:page-title-->

<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="gallery-thumb">
                        O este produto não existe, <a href="<?php echo base_url('/'); ?>">clique aqui e volte para a página principal</a>.
                        </div><!--end:pro-images-->
                    </div>
                </div><!--end:row--> 
            </div><!--end:col left -->
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="sidebar">
                    <h4 class="widget-title">Veja Também</h4>
                    <ul class="list">
<?php
if ( is_array($produtos_aleatorios) && count($produtos_aleatorios) > 0 )
{
    foreach ( $produtos_aleatorios as $produto )
    {
        if ( strlen($produto['foto_capa']) == 0 )
        {
            $imagens = glob(SERVERPATH.'arquivos/produtos/'.$produto['id']."/{*_thumb.*}", GLOB_BRACE);
            if ( $imagens )
            {
                $produto['foto_capa'] = basename($imagens[0]);
            }
            else
            {
                $produto['foto_capa'] = '../sem-foto.jpg';
            }
        }
?>
                        <li>
                            <a href="<?php echo base_url('/produto/'.$produto['link']); ?>">
                                <div class="img_produto_aleatorio" style="background-image:url('<?php echo base_url('/arquivos/produtos/'.$produto['id'].'/'.$produto['foto_capa']); ?>')"></div>
                                <?php echo $produto['titulo']; ?>
                            </a>
                            <br>
                            <br>
                        </li>
<?php
    }
}
?>
                    </ul>
                    <div class="clear"></div>
                    <div class="clearfix"></div>    
                </div><!--end:Sidebar--> 
            </div><!--end:col right-->
        </div><!--end:row-->
    </div><!--end:container-->
</div><!--end:main-->

<div class="other-products">
    <div class="text-heading large-head">
        <h1>Produtos Relacionados</h1>
        <span>Não deixe de conferir também estes produtos!</span>
    </div>
    <div class="list-pro features-pro">
<?php
if ( is_array($produtos_relacionados) && count($produtos_relacionados) > 0 )
{
    foreach ( $produtos_relacionados as $produto )
    {
        if ( strlen($produto['foto_capa']) == 0 )
        {
            $imagens = glob(SERVERPATH.'arquivos/produtos/'.$produto['id']."/{*_thumb.*}", GLOB_BRACE);
            if ( $imagens )
            {
                $produto['foto_capa'] = basename($imagens[0]);
            }
            else
            {
                $produto['foto_capa'] = '../sem-foto.jpg';
            }
        }
?>
        <div class="s1-item">
            <div class="product-thumb">
                <a href="<?php echo base_url('/produto/'.$produto['link']); ?>"><img src="<?php echo base_url('/arquivos/produtos/'.$produto['id'].'/'.$produto['foto_capa']); ?>"></a>
            </div>
            <div class="p-info">
                <h4><?php echo $produto['titulo']; ?></h4>
                <span>&nbsp;</span>
                <nav>
                    <a href="<?php echo base_url('/produto/'.$produto['link']); ?>"><i class="fa fa-info-circle"></i> </a>
                    <?php /*<a href="#"><i class="fa fa-shopping-cart"></i> </a>*/ ?>
                    <a href="<?php echo base_url('/arquivos/produtos/'.$produto['id'].'/'.$produto['foto_capa']); ?>" class="prettyPhoto"><i class="fa fa-search"></i> </a>
                    <?php /*<a href="#"><i class="fa fa-star"></i> </a>*/ ?>
                </nav>  
            </div><!--end:p-info -->
        </div> <!--end:item-->
<?php
    }
}
?>
    </div>
</div><!--end:other products-->
<script type="text/javascript">
$(function()
{
    $('.cart .btn.fr').click(function(e)
    {
        e.stopPropagation();
        $('#box_orcamento').show();

        $(document).on('click', function(e)
        {
            if (!$(e.target).closest('#box_orcamento').length)
            {
                $('#fechar_box_orcamento').click();
            }
        });
    });
    $('#fechar_box_orcamento').click(function()
    {
        $('#box_orcamento').hide();
    });
    $('#form_orcamento').submit(function(event)
    {
        event.preventDefault();
        var produto_id = $('#form_orcamento').children()[0].value;
        var nome = $('#form_orcamento').children()[1].value;
        var email = $('#form_orcamento').children()[2].value;
        var telefone = $('#form_orcamento').children()[3].value;
        var mensagem = $('#form_orcamento').children()[4].value;
        $.ajax({
            type: 'POST',
            url:'<?php echo site_url('produto/orcamento'); ?>',
            data: {
                'orcamento':{
                   'produto_id' : produto_id,
                    'nome' : nome,
                    'email' : email,
                    'telefone' : telefone,
                    'mensagem' : mensagem
                }
            },
            success: function(data){
                if ( data.trim() == '1' )
                {
                    alert('Solicitação de orçamento enviado com sucesso! Lhe responderemos o mais breve possível.');
                    $('#fechar_box_orcamento').click();
                }
                else
                {
                    alert('Desculpe, mas não foi possível enviar sua mensagem. Tente novamente mais tarde.');
                }
            }
        });
    });
});
</script>
