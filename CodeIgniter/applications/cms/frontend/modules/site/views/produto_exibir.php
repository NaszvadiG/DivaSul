<script type="text/javascript" src="<?php echo base_url('arquivos/libs/jquery.mask.min.js'); ?>"/></script>
<div class="page-header title-left">
    <div class="container"> 
        <div class="row">
            <div class="col-lg-12">
                <h1><?php echo $produto['titulo']; ?></h1>
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(''); ?>">Principal</a></li>
<?php
if ( is_array($categorias) && count($categorias) > 0 )
{
    foreach ( $categorias as $cat )
    {
?>
                    <li><a href="<?php echo base_url('/produtos/'.$cat['link']); ?>"><?php echo $cat['titulo']; ?></a></li>
<?php
    }
}
?>
                    <li><?php echo $produto['titulo']; ?></li>
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
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="gallery-thumb">
<?php
$imagens = glob(SERVERPATH.'arquivos/produtos/'.$produto['id']."/{*_thumb.*}", GLOB_BRACE);
if ( !$imagens )
{
    $imagens[] = '../sem-foto.jpg';
}
?>
<ul id="preview" class="nav-inside">
<?php
foreach ( $imagens as $imagem )
{
    if ( is_file($imagem) )
    {
        $nome_miniatura = basename($imagem);
    }
    else
    {
        $nome_miniatura = $imagem;
    }
    $nome_imagem = str_replace('_thumb', '', $nome_miniatura);
    $foto_capa = str_replace('_thumb', '', $produto['foto_capa']) == $nome_imagem;
        //<img src="<?php echo base_url('/arquivos/produtos/'.$produto['id'].'/'.$nome_imagem)
?>
    <li>
        <a href="<?php echo base_url('/arquivos/produtos/'.$produto['id'].'/'.$nome_imagem); ?>" class="prettyPhoto mask" >
            <div id="zoom_produto" class="img_produto" style="background-image:url('<?php echo base_url('/produto/imagem/'.$produto['id'].'/'.urlencode(base64_encode($nome_imagem))); ?>')"></div>
        </a>
    </li>
<?php
}
?>
</ul>
<?php
if ( count($imagens) > 1 )
{
?>
<ul id="thumb" class="nav-inside">
<?php
    foreach ( $imagens as $imagem )
    {
        $nome_miniatura = basename($imagem);
        $nome_imagem = str_replace('_thumb', '', $nome_miniatura);
        $foto_capa = str_replace('_thumb', '', $produto['foto_capa']) == $nome_imagem;
?>
    <li>
        <img src="<?php echo base_url('/arquivos/produtos/'.$produto['id'].'/'.$nome_miniatura); ?>">
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
                        </div><!--end:pro-images-->
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="summary">
                            <h2><?php echo $produto['titulo']; ?></h2>
<?php
if ( strlen($produto['referencia']) > 0 )
{
?>
                            <small style="float:right">Ref.: <?php echo $produto['referencia']; ?></small>
<?php
}
?>
                            <p class="desc"><?php echo $produto['descricao']; ?></p>
                            <div class="cart">
                                <button class="btn btn-dark fr" type="button"> <i class="fa fa-shopping-cart"></i> Solicitar Orçamento </button>
                                <div id="box_orcamento" class="row">
                                    <div id="fechar_box_orcamento" title="Clique para fechar">X</div>
                                    <h1>Solicite um orçamento</h1><br>
                                    <form id="form_orcamento" action="" method="post">
                                        <input id="orc_produto" type="hidden" name="orcamento[produto_id]" value="<?php echo $produto['id']?>"/>
                                        <div class="row">
                                        <?php echo form_input(array(
                                            'id'=>'orc_nome',
                                            'name'=>'orcamento[nome]',
                                            'title'=>'Informe seu nome completo',
                                            'required'=>'true',
                                            'class'=>'col-lg-4 col-md-4 col-sm-6 col-xs-12',
                                            'placeholder'=>'Nome Sobrenome')); ?>
                                        <?php echo form_input(array(
                                            'id'=>'orc_email',
                                            'name'=>'orcamento[email]',
                                            'title'=>'Informe seu e-mail',
                                            'required'=>'true',
                                            'class'=>'col-lg-4 col-md-4 col-sm-6 col-xs-12',
                                            'placeholder'=>'email@email.com.br')); ?>
                                        <?php echo form_input(array(
                                            'id'=>'orc_telefone',
                                            'name'=>'orcamento[telefone]',
                                            'title'=>'Informe seu telefone',
                                            'class'=>'col-lg-4 col-md-4 col-sm-6 col-xs-12',
                                            'placeholder'=>'(00) 00000-0000')); ?>
                                        </div>
                                        <div class="row">
                                        <input type="numeric" id="orc_quantidade" name="orcamento[quantidade]" title="Informe a quantidade" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 numbersOnly" placeholder="qtd"/>
                                        <select id="orc_estado" name="orcamento[estado]" title="Informe seu estado" class="col-lg-2 col-md-2 col-sm-2 col-xs-2" placeholder="UF">
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS" selected="true">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                                                                    </select>
                                        <?php echo form_input(array(
                                            'id'=>'orc_cidade',
                                            'name'=>'orcamento[cidade]',
                                            'title'=>'Informe sua cidade',
                                            'class'=>'col-lg-8 col-md-8 col-sm-8 col-xs-8',
                                            'placeholder'=>'Cidade')); ?>
                                        </div>
                                        <div class="row">
                                        <textarea id="orc_mensagem" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" placeholder="Escreva aqui o seu pedido.">Olá,
Gostaria de mais informações sobre o produto: <?php echo $produto['titulo']; ?>.</textarea>
                                        </div>
                                        <div>
                                        <button class="btn btn-dark fr" type="submit"> <i class="fa fa-send"></i> Enviar </button>
                                        </div>
                                    </form>
                                </div>
                            </div><!--end:cart-->
                            <div class="product_meta">
                                <span class="posted_in"><i class="fa fa-folder"></i>    Categoria: <a href="<?php echo base_url('/produtos/'.$categoria['link']); ?>"><?php echo $categoria['titulo']; ?></a>.</span>
                                <span class="tagged_as"><i class="fa fa-tags"></i>  Tags: <a href="<?php echo base_url('/produto/'.$produto['link']); ?>"><?php echo $produto['titulo']; ?></a>.</span>
                            </div>
                            <div class="socials circle-icon fr">
                                <a href="https://www.facebook.com/serigrafiaes" class="facebook" target="_blank"> <i class="fa fa-facebook"></i> <span>facebook</span></a>
                            </div>
                        </div><!--end:summary-->
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
<script type="text/javascript" src="<?php echo base_url('/arquivos/libs/elevatezoom-master/jquery.elevateZoom-3.0.8.min.js'); ?>"></script>
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
        var produto_id = $('#orc_produto').val();
        var nome = $('#orc_nome').val();
        var email = $('#orc_email').val();
        var telefone = $('#orc_telefone').val();
        var estado = $('#orc_estado').val().toUpperCase();
        var cidade = $('#orc_cidade').val();
        var quantidade = $('#orc_quantidade').val();
        var mensagem = $('#orc_mensagem').val();
        if ( mensagem.trim().length == 0)
        {
            alert('Você precisa informar uma mensagem.');
        }
        else
        {
            $.ajax({
                type: 'POST',
                url:'<?php echo site_url('produto/orcamento'); ?>',
                data: {
                    'orcamento':{
                        'produto_id' : produto_id,
                        'nome' : nome,
                        'email' : email,
                        'telefone' : telefone,
                        'quantidade' : quantidade,
                        'estado' : estado,
                        'cidade' : cidade,
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
        }
    });

    //Lupa na img
    $('#zoom_produto').elevateZoom({
        zoomType: "inner",
        cursor: "crosshair",
        zoomWindowFadeIn: 500,
        zoomWindowFadeOut: 750
    }); 

    // Máscara no campo telefone
    $('#orc_telefone').mask('(00) 0000-00009');

    // Somente números no campo quantidade
    $('.numbersOnly').keyup(function()
    { 
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
});
</script>
