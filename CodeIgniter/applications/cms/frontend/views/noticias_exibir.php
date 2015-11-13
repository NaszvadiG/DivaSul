<?php
$compartilhar = '
<div class="col-md-1 col-sm-1" id="compartilhar">
    <a title="Compartilhar no Facebook" href="http://www.facebook.com/share.php?u='.base_url('noticias/'.$noticia['id'].'-'.$noticia['alias']).'" target="_blank" class="icon-facebook"></a>
    <a title="Compartilhar no Twitter"href="https://twitter.com/share?url='.base_url('noticias/'.$noticia['id'].'-'.$noticia['alias']).'" target="_blank" class="icon-twitter"></a>
    <a title="Compartilhar no Pinterest"href="http://www.pinterest.com/pin/create/button/?url='.base_url('noticias/'.$noticia['id'].'-'.$noticia['alias']).'&amp;description='.$noticia['titulo'].'&amp;media='.base_url('media/'.$noticia['imagens'][0]['dir'].'/'.$noticia['imagens'][0]['arquivo']).'" target="_blank" class="icon-pinterest"></a>
    <a title="Compartilhar no Google Plus"href="https://plus.google.com/share?url='.base_url('noticias/'.$noticia['id'].'-'.$noticia['alias']).'?cbox='.base_url('media/'.$noticia['imagens'][0]['dir'].'/'.$noticia['imagens'][0]['arquivo']).'" target="_blank" class="icon-gplus"></a>
</div>';
?>

<style type="text/css">
#noticia{margin-top:20px}
.news_title{font-size:17px;margin:0px 0px 30px 0px;letter-spacing:0px;text-transform:initial;font-weight:normal;}
.box-galeria figure {margin-bottom: 10px !important; }
.box-galeria figure { margin-bottom: 30px; }
#box-multimidia figure {-webkit-transition: 0.1s opacity; -moz-transition: 0.1s opacity; transition: 0.1s opacity;}
#box-multimidia figure:hover{opacity: 0.8;}
@media (min-width: 1024px)
{
    figure.col-md-6.col-sm-6.col-xs-12.image-border.sm:nth-child(2n+1) { padding-left: 6px; }
    figure.col-md-6.col-sm-6.col-xs-12.image-border.sm:nth-child(2n+0) { padding-right: 6px; }
}
@media (max-width: 1023px)
{
    #box-multimidia figure.sm div.janela,
    #box-multimidia figure.md div.janela { height: 226px; }
}
figure { float: left; }
.box-galeria figure { margin-bottom: 30px; }
figure div.janela { width: 100%; height: auto; }
figure.md div.janela { height: 210px; width: auto; }
figure.sm div.janela { height: 110px; width: auto; }
figure.lg div.janela { height: 380px; width: auto; }
figure.xs div.janela { height: 60px; width: auto; }
figure.image-border div.janela { border: 1px solid #CCCCCC; padding: 5px; }
figure div.janela>div{ overflow: hidden; height: 100%; }
figure div.janela img{ width: 100%; display: block; }
figure.lg div.janela img, section.content figure.md div.janela img, section.content figure.sm div.janela img, section.content figure.xs div.janela img { width: 100px; min-width: 100%; min-height: 100%; }
</style>

<div id="noticia" class="col-md-12 clearfix">
    <div class="row">
<?php
if( is_array($noticia['imagens']) && count($noticia['imagens']) > 0 )
{
?>    
        <div class="col-md-7 col-sm-11">
            <h3 class="news_title"><?=$noticia['titulo']?></h3>
            <?php echo $noticia['texto']; ?>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="clearfix box-galeria" id="box-multimidia">
                <div class="row">
<?php
$destaque = $noticia['imagens'][0];
unset($noticia['imagens'][0]);
echo '<figure class="col-md-12 col-sm-6 col-xs-12 image-border md">';
echo     '<div class="janela">';
echo         '<div> <a href="'.base_url('/arquivos/'.$destaque['dir'].'/'.$destaque['arquivo']).'" class="popup-galeria" target="_blank"> <img src="'.base_url('/arquivos/'.$destaque['dir'].'/'.$destaque['arquivo_thumb']).'"> </a> </div>';
echo     '</div>';
echo '</figure>';
?>

<?php
if( count($noticia['imagens']) > 0)
{
    foreach($noticia['imagens'] as $img)
    {
        echo '<figure class="col-md-6 col-sm-6 col-xs-12 image-border sm">';
        echo     '<div class="janela">';
        echo         '<div> <a href="'.base_url('/arquivos/'.$img['dir'].'/'.$img['arquivo']).'" class="popup-galeria" target="_blank"> <img src="'.base_url('/arquivos/'.$img['dir'].'/'.$img['arquivo_thumb']).'"> </a> </div>';
        echo     '</div>';
        echo '</figure>';
    }
}
?>
            </div>
        </div>
        <div class="col-xs-12"><i class="icon-zoom-in"></i>Clique para ampliar</div>
<?php    
}
else
{
?>
        <div class="col-md-7 col-sm-11">
            <h3 class="news_title"><?=$noticia['titulo']?></h3>
            <?php echo $noticia['texto']; ?>
        </div>
<?php
}
?>
    </div>
</div>

<script type="text/javascript">
/*controle da galeria de fotos*/
document.addEventListener("DOMContentLoaded", function(event) 
{ 
    var index = 1;
    var atual = 1;
    var atual_slider = 1;
    var total = <?=count($noticia['imagens'])?>;

        /*calc inicial*/
        $('.box-gal-thumbs').css('width', parseInt((parseInt($('.box-galeria-thumb img').width()) + 20) * total) +'px');

        function atualizar_foto(foto_index)
        {
            $('.img-gal-destaque img').attr('src', $('.box-galeria-thumb img[data-index="'+foto_index+'"]').attr('data-foto'));
            $('.box-gal-legenda').html( $('.box-galeria-thumb img[data-index="'+foto_index+'"]').attr('title') );
            $('.box-galeria-thumb img.gal-ativo').removeClass('gal-ativo');
            $('.box-galeria-thumb img[data-index="'+foto_index+'"]').addClass('gal-ativo');
            $('#gal-index').html($('.box-galeria-thumb img[data-index="'+foto_index+'"]').attr('data-index'));
            atual = foto_index;
            atual_slider = atual;

            var margin = parseInt((parseInt($('.box-galeria-thumb img').width()) + 20) * (atual-1));
            $('.box-gal-thumbs').css('margin-left', '-'+margin+'px');
        }

        function atualizar_setas()
        {
            if( $('.gal-foto-atual').is(":hover") )
            {
                console.log('hover foto');
                if(atual != 1)
                {
                    $('.gal-prev').css('opacity','1');
                }
                else
                {
                    $('.gal-prev').css('opacity','0');   
                }
                if(atual != total)
                {
                    $('.gal-next').css('opacity','1');
                }
                else
                {
                    $('.gal-next').css('opacity','0');
                }
            }
        }

        /*click thumb*/
        $('.box-galeria-thumb img').click(function(){
            atualizar_foto( $(this).attr('data-index') );            
        });

        /*hover setas*/      
        $('.gal-foto-atual').hover(
          function() {
            atualizar_setas();
        }, function() {
            $('.gal-prev').css('opacity','0');
            $('.gal-next').css('opacity','0');
        }
        );

        /*setas*/
        $('.gal-next').click(function(){
            if(atual != total)
            {
                atualizar_foto(++atual);
                atualizar_setas();
            }
        });
        $('.gal-prev').click(function(){
            if(atual != 1)
            {
                atualizar_foto(--atual);
                atualizar_setas();
            }
        });
        
        /*rolar thumbs*/
        $('.gal-seta-next').click(function(){
            if(atual_slider != total)
            {
                var nr_thumbs = 2;
                if(atual_slider == (total-1))
                {
                    nr_thumbs = 1;
                }
                atual_slider = parseInt(atual_slider) + nr_thumbs;
                var margin = parseInt((parseInt($('.box-galeria-thumb img').width()) + 20) * (atual_slider-1));
                $('.box-gal-thumbs').css('margin-left', '-'+margin+'px');            
                console.log('thumb:'+atual_slider+' margin:'+ margin);

            }
        });
        $('.gal-seta-prev').click(function(){
            if(atual_slider > 1)
            {
                var nr_thumbs = 2;
                if(atual_slider == 2)
                {
                    nr_thumbs = 1;
                }
                atual_slider = parseInt(atual_slider) - nr_thumbs;
                var margin = parseInt((parseInt($('.box-galeria-thumb img').width()) + 20) * (atual_slider-1));
                $('.box-gal-thumbs').css('margin-left', '-'+margin+'px');            
                console.log('thumb:'+atual_slider+' margin:'+ margin);
            }
        });


    });
</script>
