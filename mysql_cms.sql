-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tempo de Geração: 10/11/2015 às 17:40
-- Versão do servidor: 5.6.27
-- Versão do PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `mysql_cms`
--
--
-- Funções
--
CREATE FUNCTION `tem_filhos`(p_id INTEGER) RETURNS int(11)
RETURN (SELECT COUNT(id) > 0 FROM cms_paginas WHERE parent_id = p_id);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_banners`
--

CREATE TABLE IF NOT EXISTS `cms_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alt` text COLLATE utf8_unicode_ci,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `img_banner` text COLLATE utf8_unicode_ci NOT NULL,
  `img_thumb` text COLLATE utf8_unicode_ci NOT NULL,
  `path` text COLLATE utf8_unicode_ci,
  `link` text COLLATE utf8_unicode_ci,
  `target` text COLLATE utf8_unicode_ci,
  `categoria_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `dt_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_fim` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `ordem` int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Fazendo dump de dados para tabela `cms_banners`
--

INSERT INTO `cms_banners` (`id`, `alt`, `titulo`, `img_banner`, `img_thumb`, `path`, `link`, `target`, `categoria_id`, `usuario_id`, `dt_inicio`, `dt_fim`, `ativo`, `ordem`) VALUES
(1, NULL, '', '1/banner_imobiliaria_reichert.jpg', '1/thumb_banner_imobiliaria_reichert.jpg', NULL, '', '_self', 1, 1, '2015-09-29 18:53:05', NULL, 1, 10),
(2, NULL, '', '1/banner_minha_casa_minha_vida.jpg', '1/thumb_banner_minha_casa_minha_vida.jpg', NULL, '', '_self', 1, 1, '2015-09-29 18:54:15', NULL, 1, 20),
(3, NULL, '', '1/banner_imobiliaria_reichert.jpg', '1/thumb_banner_imobiliaria_reichert.jpg', NULL, '', '_self', 1, 1, '2015-09-29 18:54:32', NULL, 1, 30),
(4, NULL, '', '1/banner_casa_certa.jpg', '1/thumb_banner_casa_certa.jpg', NULL, '', '_self', 1, 1, '2015-09-29 18:54:52', NULL, 1, 40),
(5, NULL, '', '1/banner_sonho.jpg', '1/thumb_banner_sonho.jpg', NULL, '', '_self', 1, 1, '2015-09-29 18:55:03', NULL, 1, 50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_banners_categorias`
--

CREATE TABLE IF NOT EXISTS `cms_banners_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `largura` int(11) NOT NULL,
  `altura` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `posicao` text COLLATE utf8_unicode_ci NOT NULL,
  `largura_miniatura` int(11) DEFAULT NULL,
  `altura_miniatura` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `cms_banners_categorias`
--

INSERT INTO `cms_banners_categorias` (`id`, `titulo`, `largura`, `altura`, `site_id`, `posicao`, `largura_miniatura`, `altura_miniatura`) VALUES
(1, 'Banner Home', 676, 290, 2, 'banner_home', 100, 73);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_componentes`
--

CREATE TABLE IF NOT EXISTS `cms_componentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `posicao` text COLLATE utf8_unicode_ci NOT NULL,
  `site_id` int(11) NOT NULL,
  `criado_por` int(11) NOT NULL,
  `path_last` text COLLATE utf8_unicode_ci,
  `content_last` text COLLATE utf8_unicode_ci,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=29 ;

--
-- Fazendo dump de dados para tabela `cms_componentes`
--

INSERT INTO `cms_componentes` (`id`, `titulo`, `path`, `content`, `posicao`, `site_id`, `criado_por`, `path_last`, `content_last`, `ativo`) VALUES
(1, 'Banner - Home', 'banners/exibir', 'categoria_id:1', 'banner_home', 2, 1, NULL, NULL, 1),
(2, 'NotÃ­cias', 'noticias/exibir', '', 'noticias_exibir', 2, 1, NULL, NULL, 0),
(3, 'ImÃ³vel - Exibir', 'site/imoveis/exibir', '', 'imovel_exibir', 2, 1, NULL, NULL, 1),
(4, 'ImÃ³veis - Listar', 'site/imoveis/listar', '', 'imoveis_listar', 2, 1, NULL, NULL, 1),
(5, 'Buscar', 'site/busca/buscar', '', 'buscar', 2, 1, NULL, NULL, 1),
(6, 'Menu Topo', 'html', '<ul class="sf-menu">\n    <li>\n        <a href="{loadbase_url}">InÃ­cio</a>\n    </li>\n    <li>\n        <a href="{loadbase_url}sobre">A imobiliÃ¡ria</a>\n    </li>\n    <li>\n        <a href="{loadbase_url}imoveis/listar/1">ImÃ³veis p/ Venda</a>\n        {loadposition menu_topo_itens/negocio_id:1}\n    </li>\n    <li>\n        <a href="{loadbase_url}imoveis/listar/2">ImÃ³veis p/ LocaÃ§Ãµes</a>\n        {loadposition menu_topo_itens/negocio_id:2}\n    </li>\n    <li>\n        <a href="{loadbase_url}imoveis/listar/0/5">Loteamentos</a>\n    </li>\n    <li>\n        <a href="{loadbase_url}fale-conosco">Contato</a>\n    </li>\n</ul>', 'menu_topo', 2, 1, NULL, NULL, 1),
(7, 'Logo Topo', 'html', '<div id="logo">\n    <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="962" height="200">\n        <param name="movie" value="{loadbase_url}img/topo.swf">\n        <param name="quality" value="high">\n        <param name="wmode" value="transparent">\n        <param name="swfversion" value="9.0.45.0">\n        <!-- Esta tag param solicita que os usuÃ¡rios com o Flash Player 6.0 r65 e versÃµes posteriores baixem a versÃ£o mais recente do Flash Player. Exclua-o se vocÃª nÃ£o deseja que os usuÃ¡rios vejam o prompt. -->\n        <param name="expressinstall" value="../Scripts/expressInstall.swf">\n        <!-- A tag object a seguir aplica-se a navegadores que nÃ£o sejam o IE. Portanto, oculte-a do IE usando o IECC. -->\n        <!--[if !IE]>-->\n        <object type="application/x-shockwave-flash" data="{loadbase_url}img/topo.swf" width="962" height="200">\n            <!--<![endif]-->\n            <param name="quality" value="high">\n            <param name="wmode" value="transparent">\n            <param name="swfversion" value="9.0.45.0">\n            <param name="expressinstall" value="{loadbase_url}Scripts/expressInstall.swf">\n            <!-- O navegador exibe o seguinte conteÃºdo alternativo para usuÃ¡rios que tenham o Flash Player 6.0 e versÃµes anteriores. -->\n            <div>\n                <h4>O conteÃºdo desta pÃ¡gina requer uma versÃ£o mais recente do Adobe Flash Player.</h4>\n                <p>\n                    <a href="http://www.adobe.com/go/getflashplayer">\n                    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obter Adobe Flash player" width="112" height="33" />\n                    </a>\n                </p>\n            </div>\n            <!--[if !IE]>-->\n        </object>\n        <!--<![endif]-->\n    </object>\n</div>', 'logo_topo', 2, 1, NULL, NULL, 1),
(8, 'RodapÃ©', 'html', '<div id="footer">\n    <div id="inner-footer">\n        <ul>\n            <li>\n                <h3 class="widget-title">Nossos Corretores</h3>\n                {loadposition nossos_corretores}\n            </li>\n            <li>\n                <h3 class="widget-title"></h3>\n                <div class="widget-container last-tweets">\n                    <ul>\n                        <li>&nbsp;</li>\n                    </ul>\n                </div>\n            </li>\n            <li>\n                <h3 class="widget-title">Entre em Contato</h3>\n                <div class="widget-container contact-us">\n                    <form action="{loadbase_url}contato" method="post">\n                        <div class="row">\n                            <label>Nome</label>\n                            <input type="text" name="name" value="">\n                        </div>\n                        <div class="row">\n                            <label>Email</label>\n                            <input type="text" name="mail" value="">\n                        </div>\n                        <div class="row">\n                            <{loadtextarea} name="message">\n                            </{loadtextarea}>\n                        </div>\n                        <input type="submit" value="Enviar">\n                    </form>\n                </div>\n            </li>\n        </ul>\n    </div>\n    <div id="back-to-top">\n        <a href="{loadbase_url}#back-to-top">\n        <img src="{loadbase_url}img/back-to-top.png" alt="">\n        </a>\n    </div>\n    <div id="csdesign">\n        <a href="http://cssolucoes.com.br/design">Copyright Reichert ImÃ³veis {load ano} - Desenvolvimento CS design</a>\n    </div>\n</div>', 'rodape', 2, 1, NULL, NULL, 1),
(9, 'Menu Topo - Itens', 'site/menu', '', 'menu_topo_itens', 2, 1, NULL, NULL, 1),
(10, 'Nossos Corretores', 'html', '<div class="widget-container our-agents">\n     <a href="{loadbase_url}fale-conosco">\n          <img src="{loadbase_url}upload/agent-img-1.jpg" alt="">\n     </a>\n     <a href="{loadbase_url}fale-conosco">\n          <img src="{loadbase_url}upload/agent-img-2.jpg" alt="">\n     </a>\n     <a href="{loadbase_url}fale-conosco">\n          <img src="{loadbase_url}upload/agent-img-3.jpg" alt="">\n     </a>\n</div>', 'nossos_corretores', 2, 1, NULL, NULL, 1),
(11, 'Busca - Cidades', 'site/cidades/listar', '', 'busca_cidades', 2, 1, NULL, NULL, 1),
(12, 'Busca - Categorias', 'site/imoveis/listar_categorias', '', 'busca_categorias', 2, 1, NULL, NULL, 1),
(13, 'Busca - Quartos', 'html', '<option value="">Quartos</option>\n<option value="1">1</option>\n<option value="2">2</option>\n<option value="3">3</option>\n<option value="4">4</option>\n<option value="5">5+</option>', 'busca_quartos', 2, 1, NULL, NULL, 1),
(14, 'Busca - Garagem', 'html', '<option value="">Garagem</option>\n<option value="1">1</option>\n<option value="2">2</option>\n<option value="3">3+</option>', 'busca_garagem', 2, 1, NULL, NULL, 1),
(15, 'Busca - Abaixo de R$', 'html', '<option value="">Abaixo de</option>\n<option value="5000000">R$50.000</option>\n<option value="10000000">R$100.000</option>\n<option value="15000000">R$150.000</option>\n<option value="20000000">R$200.000</option>\n<option value="15000000">R$250.000</option>\n<option value="30000000">R$300.000</option>', 'busca_abaixo_de', 2, 1, NULL, NULL, 1),
(16, 'Busca - Acima de R$', 'html', '<option value="">Acima de</option>\n<option value="5000000">R$50.000</option>\n<option value="10000000">R$100.000</option>\n<option value="15000000">R$150.000</option>\n<option value="20000000">R$200.000</option>\n<option value="25000000">R$250.000</option>\n<option value="30000000">R$300.000</option>', 'busca_acima_de', 2, 1, NULL, NULL, 1),
(17, 'ImÃ³veis - Listar Destaques', 'site/imoveis/listar_destaques', '', 'imoveis_destaques', 2, 1, NULL, NULL, 1),
(18, 'ImÃ³veis - Listar p/ LocaÃ§Ã£o', 'site/imoveis/listar_locacao', '', 'imoveis_para_locacao', 2, 1, NULL, NULL, 1),
(19, 'ImÃ³veis - Listar p/ Venda', 'site/imoveis/listar_venda', '', 'imoveis_para_venda', 2, 1, NULL, NULL, 1),
(21, 'ImÃ³veis - Listar Ãšltimos Home', 'site/imoveis/listar_ultimos_home', '', 'listar_ultimos_home', 2, 1, NULL, NULL, 1),
(22, 'Busca rÃ¡pida - Home', 'html', '<div id="quick-search">\n    <form action="{loadbase_url}buscar" method="post">\n        <h4 class="head">O Que Deseja?</h4>\n        <div class="slideToggle">\n            <div class="switcher">\n                <input type="checkbox" class="custom-style" value="2" data-off="Comprar" data-on="Alugar" name="busca[negocio_id]"/>\n            </div>\n        </div>\n        <div>\n            <div class="row">\n                <input type="text" class="input normal selectbox-dropdown selectbox-label" name="busca[titulo]" placeholder="ImÃ³vel" value="" style="width:210px;padding-left:10px"/>\n            </div>\n            <div class="row">\n                <select class="select normal" name="busca[cidade_id]">\n                {loadposition busca_cidades}\n                </select>\n            </div>\n            <div class="row">\n                <input type="text" class="input normal selectbox-dropdown selectbox-label" name="busca[bairro]" placeholder="Bairro" value="" style="width:210px;padding-left:10px">\n            </div>\n        </div>\n        <div>\n            <div class="row">\n                <select class="select normal" name="busca[categoria_id]">\n                {loadposition busca_categorias}\n                </select>\n            </div>\n            <div class="row">\n                <select class="select left" name="busca[quartos]">\n                {loadposition busca_quartos}\n                </select>\n                <select class="select right" name="busca[garagens]">\n                {loadposition busca_garagem}\n                </select>\n            </div>\n            <div class="row">\n                <select class="select left" name="busca[acima_de]">\n                {loadposition busca_acima_de}\n                </select>\n                <select class="select right" name="busca[abaixo_de]">\n                {loadposition busca_abaixo_de}\n                </select>\n            </div>\n            <div class="row">\n                <input class="button-blue" type="submit" value="Procurar">\n            </div>\n        </div>\n    </form>\n</div>', 'quick_search_home', 2, 1, NULL, NULL, 1),
(23, 'Menu Lateral', 'html', '<div id="sidebar">\n    <ul>\n        <li>\n            <h3 class="widget-title">ImÃ³veis p/ Venda</h3>\n            <div class="widget-container testimonials">\n                <div class="testimonials-container">\n                    {loadposition imoveis_para_venda}\n                </div>\n            </div>\n        </li>\n        <li>\n            <h3 class="widget-title">ImÃ³veis p/ LocaÃ§Ã£o</h3>\n            <div class="widget-container testimonials">\n                <div class="testimonials-container">\n                    {loadposition imoveis_para_locacao}\n                </div>\n            </div>\n        </li>\n        <li>\n            <h3 class="widget-title">Destaques</h3>\n            <div class="widget-container testimonials">\n                <div class="testimonials-container">\n                    {loadposition imoveis_destaques}\n                </div>\n            </div>\n        </li>\n    </ul>\n</div>', 'menu_sidebar', 2, 1, NULL, NULL, 1),
(24, 'Busca rÃ¡pida', 'html', '<div id="quick-search">\n    <form action="{loadbase_url}buscar" method="post">\n        <h4 class="head">O QUE DESEJA?</h4>\n        <a href="#" class="show-hide">Show/Hide</a>\n        <div class="slideToggle" style="display:none;">\n            <div class="switcher">\n                <input type="checkbox" class="custom-style" value="2" data-off="Comprar" data-on="Alugar" name="busca[negocio_id]"/>\n            </div>\n            <div class="select-container clearfix">\n                <div class="column">\n                    <input type="text" class="input normal selectbox-dropdown selectbox-label" name="busca[titulo]" placeholder="ImÃ³vel" value="" style="width:210px;padding-left:10px"\\>\n                    <select class="select normal" name="busca[categoria_id]">\n                        {loadposition busca_categorias}\n                    </select>\n                </div>\n                <div class="column">\n                    <select class="select normal" name="busca[cidade_id]">\n                        {loadposition busca_cidades}\n                    </select>\n                    <select class="select small first" name="busca[quartos]">\n                        {loadposition busca_quartos}\n                    </select>\n                    <select class="select small" name="busca[garagens]">\n                        {loadposition busca_garagem}\n                    </select>\n                </div>\n                <div class="column">\n                    <input type="text" class="input normal selectbox-dropdown selectbox-label" name="busca[bairro]" placeholder="Bairro" value="" style="width:210px;padding-left:10px"\\>\n                    <select class="select small first" name="busca[acima_de]">\n                        {loadposition busca_acima_de}\n                    </select>\n                    <select class="select small" name="busca[abaixo_de]">\n                        {loadposition busca_abaixo_de}\n                    </select>\n                    <input class="button-blue" type="submit" value="Procurar">\n                </div>\n            </div>\n        </div>\n    </form>\n</div>', 'quick_search', 2, 1, NULL, NULL, 1),
(25, 'ImÃ³veis - Listar Venda Home', 'site/imoveis/listar_venda_home', '', 'listar_venda_home', 2, 1, NULL, NULL, 1),
(26, 'ImÃ³veis - Listar LocaÃ§Ã£o Home', 'site/imoveis/listar_locacao_home', '', 'listar_locacao_home', 2, 1, NULL, NULL, 1),
(27, 'ImÃ³veis - Listar Loteamento Home', 'site/imoveis/listar_loteamento_home', '', 'listar_loteamento_home', 2, 1, NULL, NULL, 1),
(28, 'FormulÃ¡rio de contato', 'site/contato/enviar', 'email:ArthurLehdermann@gmail.com', 'post_contato', 2, 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_componentes_paginas`
--

CREATE TABLE IF NOT EXISTS `cms_componentes_paginas` (
  `componente_id` int(11) NOT NULL,
  `pagina_id` int(11) NOT NULL,
  PRIMARY KEY (`componente_id`,`pagina_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `cms_componentes_paginas`
--

INSERT INTO `cms_componentes_paginas` (`componente_id`, `pagina_id`) VALUES
(1, 1),
(2, 7),
(3, 4),
(4, 33),
(5, 6),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0),
(11, 0),
(12, 0),
(13, 0),
(14, 0),
(15, 0),
(16, 0),
(17, 0),
(18, 0),
(19, 0),
(21, 1),
(22, 1),
(23, 0),
(24, 0),
(25, 1),
(26, 1),
(27, 1),
(28, 34);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_enquetes`
--

CREATE TABLE IF NOT EXISTS `cms_enquetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `multipla_resposta` tinyint(1) NOT NULL DEFAULT '0',
  `intervalo_entre_votos` int(11) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL,
  `dt_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dt_fim` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_enquetes_opcoes`
--

CREATE TABLE IF NOT EXISTS `cms_enquetes_opcoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquete_id` int(11) NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci NOT NULL,
  `cor` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_enquetes_votos`
--

CREATE TABLE IF NOT EXISTS `cms_enquetes_votos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquete_id` int(11) NOT NULL,
  `opcao_id` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `votante` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_logs`
--

CREATE TABLE IF NOT EXISTS `cms_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela` text COLLATE utf8_unicode_ci NOT NULL,
  `campo` text COLLATE utf8_unicode_ci NOT NULL,
  `valor` text COLLATE utf8_unicode_ci,
  `id_registro` int(11) NOT NULL,
  `data_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

--
-- Fazendo dump de dados para tabela `cms_logs`
--

INSERT INTO `cms_logs` (`id`, `tabela`, `campo`, `valor`, `id_registro`, `data_hora`, `usuario_id`) VALUES
(1, 'cms_paginas', 'content', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_ultimos_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_destaques_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 19:28:18', 1),
(2, 'cms_paginas', 'criado_por', '0', 1, '2015-11-03 19:28:18', 1),
(3, 'cms_paginas', 'content_last', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_ultimos_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_destaques_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 19:28:18', 1),
(4, 'cms_paginas', 'content', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_destaques_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 20:23:14', 1),
(5, 'cms_paginas', 'criado_por', '0', 1, '2015-11-03 20:23:14', 1),
(6, 'cms_paginas', 'content_last', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_ultimos_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_destaques_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 20:23:14', 1),
(7, 'cms_paginas', 'content', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:destaque}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:venda}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:locacao}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:loteamentos}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 20:25:34', 1),
(8, 'cms_paginas', 'criado_por', '0', 1, '2015-11-03 20:25:34', 1),
(9, 'cms_paginas', 'content_last', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_destaques_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 20:25:34', 1),
(10, 'cms_paginas', 'content', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:destaque}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:venda}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:locacao}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:loteamento}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 20:38:15', 1),
(11, 'cms_paginas', 'criado_por', '0', 1, '2015-11-03 20:38:15', 1),
(12, 'cms_paginas', 'content_last', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:destaque}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:venda}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:locacao}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:loteamentos}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-03 20:38:15', 1),
(13, 'cms_paginas', 'content', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_destaque_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_loteamento_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-04 00:18:09', 1),
(14, 'cms_paginas', 'criado_por', '0', 1, '2015-11-04 00:18:10', 1),
(15, 'cms_paginas', 'content_last', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:destaque}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:venda}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:locacao}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition imoveis_listar_home/tipo:loteamento}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', 1, '2015-11-04 00:18:10', 1),
(16, 'cms_paginas', 'content', '', 34, '2015-11-09 18:52:05', 1),
(17, 'cms_paginas', 'criado_por', '1', 34, '2015-11-09 18:52:06', 1),
(18, 'cms_paginas', 'content', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="nome" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="telefone" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="mail" value="">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="mensagem" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 18:52:43', 1),
(19, 'cms_paginas', 'criado_por', '0', 32, '2015-11-09 18:52:43', 1),
(20, 'cms_paginas', 'content_last', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}contato">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="nome" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="telefone" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="mail" value="">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="mensagem" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 18:52:43', 1),
(21, 'cms_paginas', 'content', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="nome" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="telefone" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="mail" value="">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="mensagem" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 19:02:09', 1),
(22, 'cms_paginas', 'criado_por', '0', 32, '2015-11-09 19:02:09', 1);
INSERT INTO `cms_logs` (`id`, `tabela`, `campo`, `valor`, `id_registro`, `data_hora`, `usuario_id`) VALUES
(23, 'cms_paginas', 'content_last', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="nome" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="telefone" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="mail" value="">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="mensagem" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 19:02:09', 1),
(24, 'cms_paginas', 'content', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="contato[nome]" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="contato[email]" value="">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="contato[telefone]" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="contato[mensagem]" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 19:30:24', 1),
(25, 'cms_paginas', 'criado_por', '0', 32, '2015-11-09 19:30:24', 1),
(26, 'cms_paginas', 'content_last', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="nome" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="telefone" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="mail" value="">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="mensagem" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 19:30:24', 1),
(27, 'cms_paginas', 'content', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="contato[nome]" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="contato[email]" value="">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="contato[telefone]" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="contato[mensagem]" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>\n\n<script type="text/javascript">\n$(function()\n{\n  if ( window.location.href.split(''/'').pop() == ''sucesso'' )\n  {\n    alert(''Sua mensagem foi enviada com sucesso. Responderemos o mais breve possÃ­vel, obrigado!'');\n  }\n  elseif ( window.location.href.split(''/'').pop() == ''falha'' )\n  {\n    alert(''Lamento, mas sua mensagem nÃ£o foi enviada. Tente novamente mais tarde.'');\n  }\n});\n</script>', 32, '2015-11-09 19:30:41', 1),
(28, 'cms_paginas', 'criado_por', '0', 32, '2015-11-09 19:30:42', 1),
(29, 'cms_paginas', 'content_last', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="contato[nome]" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="contato[email]" value="">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="contato[telefone]" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="contato[mensagem]" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>', 32, '2015-11-09 19:30:42', 1),
(30, 'cms_paginas', 'content', 'busca', 6, '2015-11-10 18:14:48', 1),
(31, 'cms_paginas', 'template_id', '4', 6, '2015-11-10 18:14:48', 1),
(32, 'cms_paginas', 'criado_por', '0', 6, '2015-11-10 18:14:48', 1),
(33, 'cms_paginas', 'content_last', '<style type="text/css">\nli a.current{\n  cursor:default !important;\n  font-weight: bolder !important;\n  color: #8d84e4 !important;\n}\n</style>\n\n<div id="main">\n	<div class="container">\n		<div class="row">\n			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">\n				<div class="sidebar">\n					<section class="widget widget_search clearfix">\n						<form role="search" action="{loadbase_url}buscar" method="post" id="searchform" class="searchform" action="#">\n							<input type="text" placeholder="Buscar por..." name="busca" id="s" value/>\n						</form>\n					</section>\n					<section class="widget widget_nav_menu clearfix">\n						{loadposition menu_sidebar}\n						<div class="clear"></div>\n					</section>\n					<div class="clearfix"></div>\n				</div><!--end:Sidebar-->\n			</div><!--end:colleft-->\n			{loadposition busca}\n		</div><!--end:row-->\n	</div><!--end:container-->\n</div><!--end:main-->\n\n<script type="text/javascript">\n$(function()\n{\n    var hash=window.location.hash.substring(1);\n    if(hash==''lista'')\n    {\n        $(''.style-switch .list'').click();\n    }\n});\n</script>', 6, '2015-11-10 18:14:48', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_menus`
--

CREATE TABLE IF NOT EXISTS `cms_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `cms_menus`
--

INSERT INTO `cms_menus` (`id`, `titulo`, `site_id`) VALUES
(1, 'Menu principal', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_modulos`
--

CREATE TABLE IF NOT EXISTS `cms_modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8_unicode_ci,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `padrao` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Fazendo dump de dados para tabela `cms_modulos`
--

INSERT INTO `cms_modulos` (`id`, `titulo`, `descricao`, `path`, `padrao`) VALUES
(1, 'Sites', 'Gerenciamento dos sites', 'sites', 0),
(2, 'MÃ³dulos', '<p>\n	Gereciamento dos m&oacute;dulos</p>\n', 'modulos', 0),
(3, 'UsuÃ¡rios', '<p>\n	Gerenciamento dos usu&aacute;rios do site</p>\n', 'usuarios', 0),
(5, 'Arquivos', 'Gerenciamento de arquivos', 'gerenciador_de_arquivos', 1),
(6, 'Banners', 'Gerenciamento dos banners do site', 'banners', 1),
(7, 'Componentes', 'Gerenciamento dos componentes do site', 'componentes', 1),
(8, 'Enquetes', 'Gerenciamento das enquetes do site', 'enquetes', 1),
(9, 'Menus', 'Gerenciamento dos menus do site', 'menus', 1),
(10, 'NotÃ­cias', '<p>\n	Gerenciamento das not&iacute;cias do site</p>\n', 'noticias', 1),
(11, 'PÃ¡ginas', '<p>\n	Gerenciamento das p&aacute;ginas do site</p>\n', 'paginas', 1),
(12, 'Templates', 'Gerenciamento dos templates do site', 'templates', 1),
(13, 'ImÃ³veis', '<p>\n	Im&oacute;veis</p>\n', 'site/imoveis', 1),
(14, 'Logs', '<p>\n	Logs</p>\n', 'logs', 0),
(15, 'Backup', '<p>\n	Backup da base de dados MySQL.</p>\n', 'backup', 0),
(16, 'Imagens p/ Newsletter', '<p>\n	Cadastro de imagens para usar nas newsletters.</p>\n', 'site/imagens', 1),
(18, 'Cidades', '<p>\n	Cidades</p>\n', 'site/imoveis/listar_cidades', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_noticias`
--

CREATE TABLE IF NOT EXISTS `cms_noticias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET latin1 NOT NULL,
  `alias` varchar(255) CHARACTER SET latin1 NOT NULL,
  `cartola` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `intro` text CHARACTER SET latin1 NOT NULL,
  `texto` text CHARACTER SET latin1,
  `site_id` int(11) NOT NULL,
  `criado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criado_por` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_noticias_categorias`
--

CREATE TABLE IF NOT EXISTS `cms_noticias_categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET latin1 NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `cms_noticias_categorias`
--

INSERT INTO `cms_noticias_categorias` (`id`, `parent_id`, `titulo`, `site_id`) VALUES
(1, NULL, 'Notícias', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_noticias_destaques`
--

CREATE TABLE IF NOT EXISTS `cms_noticias_destaques` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `noticia_id` int(11) NOT NULL,
  `ordem` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_noticias_imagens`
--

CREATE TABLE IF NOT EXISTS `cms_noticias_imagens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `noticia_id` int(11) NOT NULL,
  `dir` text CHARACTER SET latin1 NOT NULL,
  `arquivo` text CHARACTER SET latin1 NOT NULL,
  `arquivo_thumb` text CHARACTER SET latin1 NOT NULL,
  `credito` text CHARACTER SET latin1,
  `legenda` text CHARACTER SET latin1,
  `capa` tinyint(1) NOT NULL DEFAULT '0',
  `criado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criado_por` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_noticias_rel_categorias`
--

CREATE TABLE IF NOT EXISTS `cms_noticias_rel_categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `noticia_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_paginas`
--

CREATE TABLE IF NOT EXISTS `cms_paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `titulo_conteudo` text COLLATE utf8_unicode_ci,
  `note` text COLLATE utf8_unicode_ci,
  `tipo` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `content_last` text COLLATE utf8_unicode_ci,
  `menu_id` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '10',
  `criado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criado_por` int(11) NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT '0',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

--
-- Fazendo dump de dados para tabela `cms_paginas`
--

INSERT INTO `cms_paginas` (`id`, `parent_id`, `url`, `titulo`, `titulo_conteudo`, `note`, `tipo`, `content`, `content_last`, `menu_id`, `ordem`, `criado`, `criado_por`, `site_id`, `template_id`, `ativo`) VALUES
(1, NULL, '', 'Principal', 'PÃ¡gina Principal', '', 'html', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_ultimos_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_loteamento_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', '{loadposition banner_home}\n\n<!-- Quick Search -->\n{loadposition quick_search_home}\n<!-- End Quick Search -->\n\n<!-- Content -->\n<div id="content" class="no-bg">\n    <!-- LanÃ§amentos -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Ãšltimos ImÃ³veis</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_destaque_home}\n    </div>\n    <!-- End lanÃ§amentos -->\n\n    <!-- Vendas -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para Venda</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/1" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_venda_home}\n    </div>\n    <!-- End Vendas -->\n\n    <!-- Aluguel -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos ImÃ³veis para LocaÃ§Ã£o</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar/2" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_locacao_home}\n    </div>\n    <!-- End Aluguel -->\n\n    <!-- Loteamento -->\n    <div id="new-listings" class="clearfix">\n        <br>\n        <br>\n        <div id="content-title" class="clearfix">\n            <span>Confira Nossos Destaques</span>\n            <ul>\n                <li><a href="{loadbase_url}imoveis/listar" class="grid"> Todos</a></li>\n            </ul>\n        </div>\n        {loadposition listar_loteamento_home}\n    </div>\n    <!-- End loteamento -->\n\n    <br>\n    <br>\n    <br>\n    <br>\n    <div class="about clearfix">\n        <img alt="" src="{loadbase_url}upload/about-img.png">\n        <h3><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" target="new">Correspondente CAIXA</a></h3>\n        <p>Encaminhe toda a papelada com a Reichert ImÃ³veis, que irÃ¡ fazer todos os trÃ¢mites junto Ã  Caixa, possibilitando assim, um maior controle e agilidade do processo.<br>Isso Ã© a Reichert ImÃ³veis pensando cada vez mais em VOCÃŠ</p>\n    </div>\n    <div class="services clearfix">\n        <ul>\n            <li>\n                <img alt="" src="{loadbase_url}img/caixa.png">\n                <h3><a href="http://www1.caixa.gov.br/atendimento/canais_atendimento/correspondente_caixa_aqui.asp#_" target="new">Correspondente ImobiliÃ¡rio</a></h3>\n                <p>Somos uma empresa contratada pela CAIXA para efetuar serviÃ§os financeiros em nome dela</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/casa.png">\n                <h3><a href="http://www.caixa.gov.br/novo_habitacao/minha-casa-minha-vida/" target="new">Minha Casa Minha Vida</a></h3>\n                <p>O Minha Casa Minha Vida Ã© um programa de governo que tem transformado o sonho da casa prÃ³pria em realidade</p>\n            </li>\n            <li>\n                <img alt="" src="{loadbase_url}img/ideia.png">\n                <h3><a href="{loadbase_url}fale-conosco">Negociar o seu ImÃ³vel</a></h3>\n                <p>Querendo vender ou alugar seu imÃ³vel e nÃ£o sabe como, cadastre seu imÃ³vel com a Reichert ImÃ³veis.</p>\n            </li>\n        </ul>\n    </div>\n</div>\n<!-- End Content -->\n\n<!-- Sidebar -->\n{loadposition menu_sidebar}\n<!-- End Sidebar -->', NULL, 10, '2013-05-22 16:33:26', 0, 2, 5, 1),
(3, 1, 'imoveis', 'ImÃ³veis', 'ImÃ³veis', '', 'html', '{loadposition imoveis_listar}', '<style type="text/css">\nli a.current{\n  cursor:default !important;\n  font-weight: bolder !important;\n  color: #8d84e4 !important;\n}\n</style>\n\n<div id="main">\n	<div class="container">\n		<div class="row">\n			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">\n				<div class="sidebar">\n					<section class="widget widget_search clearfix">\n						<form role="search" action="{loadbase_url}buscar" method="post" id="searchform" class="searchform" action="#">\n							<input type="text" placeholder="Buscar por..." name="busca" id="s" value/>\n						</form>\n					</section>\n					<section class="widget widget_nav_menu clearfix">\n						{loadposition menu_sidebar}\n						<div class="clear"></div>\n					</section>\n					<div class="clearfix"></div>\n				</div><!--end:Sidebar-->\n			</div><!--end:colleft-->\n			{loadposition produtos_listar}\n		</div><!--end:row-->\n	</div><!--end:container-->\n</div><!--end:main-->\n\n<script type="text/javascript">\n$(function()\n{\n    var hash=window.location.hash.substring(1);\n    if(hash==''lista'')\n    {\n        $(''.style-switch .list'').click();\n    }\n});\n</script>', NULL, 10, '2015-05-07 00:51:21', 0, 2, 4, 1),
(4, 1, 'imovel', 'ImÃ³vel', 'ImÃ³vel', '', 'html', '{loadposition imovel_exibir}', '<style type="text/css">\n#box_orcamento{\n  display:none;\n  position: fixed;\n  width: 60%;\n  height: 350px;\n  top: 20%;\n  left: 20%;\n  background-color: rgba(255,255,255,0.96);\n  margin: 0;\n  padding: 20px;\n  box-shadow: 1px 1px 50px rgba(0,0,0,0.5);\n  z-index: 999999;\n  border-radius: 10px;\n  overflow: auto;\n}\n#box_orcamento #fechar_box_orcamento{\n  position: absolute;\n  top: 10px;\n  right: 15px;\n  font-weight: normal;\n  font-size: 2em;\n  cursor: pointer;\n}\n#box_orcamento form textarea{\n  width:100%;\n  height:100px;\n}\n#box_orcamento form button[type="submit"]{\n  margin:0px;\n  position: absolute;\n  bottom: 20px;\n  right: 20px;\n}\n\n.img_produto{\n  width: 100%;\n  padding-bottom: 100%;\n  background-repeat: no-repeat;\n  background-size: cover;\n  background-position: center center;\n}\n\n.sidebar ul.list li a\n{\n  font-weight: 400;\n  font-size: 14px;\n}\n.img_produto_aleatorio{\n  background-repeat: no-repeat;\n  background-size: cover;\n  background-position: center center;\n  width: 100%;\n  padding-bottom: 100%;\n  margin-bottom: 5px;\n  border: 1px solid #e4e4e4;\n  border-radius: 3px;\n  transition: all ease-in-out .3s;\n  -moz-transition: all ease-in-out .3s;\n  -ms-transition: all ease-in-out .3s;\n  -o-transition: all ease-in-out .3s;\n  -webkit-transition: all ease-in-out .3s;\n}\n.img_produto_aleatorio:hover{\n  border-color: #8D84E4;\n}\n</style>\n\n{loadposition produto_exibir}', NULL, 10, '2015-05-13 04:52:29', 0, 2, 6, 1),
(5, 4, 'imovel/contato', 'Contato', '', '', 'html', '{loadposition envia_orcamento}', '', NULL, 10, '2015-05-27 02:19:31', 0, 2, 2, 1),
(6, 1, 'buscar', 'Buscar', 'Buscar', '', 'html', '{loadposition buscar}', 'busca', NULL, 10, '2015-06-02 04:13:50', 0, 2, 6, 1),
(7, 1, 'noticias', 'NotÃ­cias', '', '', 'html', '{loadposition noticias}', NULL, NULL, 10, '2015-06-10 04:31:49', 0, 2, 4, 1),
(31, 1, 'sobre', 'Nossa HistÃ³ria', 'Nossa HistÃ³ria', '', 'html', '<div id="content">\n    <h4>Nossa HistÃ³ria</h4>\n    <br>\n    <p>Durante 32 anos fomos ImobiliÃ¡ria Canabarrense, e ao olharmos para trÃ¡s, vimos que ao longo destes anos, muita coisa aconteceu.</p>\n    <br>\n    <div id="map"><img src="{loadbase_url}img/business.jpg" width="687" height="318" alt=""></div>\n    <p>Parece muito tempo, mas ainda temos claro na memÃ³ria o dia em que comeÃ§amos a trilhar nosso caminho pelo mundo ImobiliÃ¡rio, numa longa e crescente caminhada comeÃ§amos com casas e terrenos, hoje jÃ¡ sÃ£o prÃ©dios e loteamentos.<br>\n        <br><br>\n        <img src="{loadbase_url}img/business2.jpg" width="687" height="318" alt="">\n        <br><br><br>\n        Fizemos de cada passo um trajeto e de cada momento uma HistÃ³ria.<br>\n        <br>\n    </p>\n    <div class="contact clearfix">\n        <div class="about2 clearfix">\n            <p>\n                E hoje vemos que nada Ã© capaz de tirar o Ã¢nimo de quem acredita que, com confianÃ§a e perseveranÃ§a, sempre se chega aonde se quer.<br><br>\n                Ã‰ a partir desta histÃ³ria que apresentamos a ImobiliÃ¡ria Reichert ImÃ³veis que Ã© a uniÃ£o de empresas sÃ³lidas e experientes no ramo imobiliÃ¡rio que acreditam no seu trabalho.<br>\n                <br>\n                Somos referÃªncia de qualidade consolidada ao longo destes 32 anos, e temos o compromisso com seus sonhos, que nos fazem ser cada dia melhor.<br>\n                <br>\n                Ã‰ aqui na ImobiliÃ¡ria Reichert ImÃ³veis que vocÃª encontra a chave do seu imÃ³vel.\n            </p>\n        </div>\n        <img src="{loadbase_url}img/iraci.jpg" width="320" height="539" alt="">\n    </div>\n</div>', '<h4>Nossa HistÃ³ria</h4>\n<br>\n<p>Durante 32 anos fomos ImobiliÃ¡ria Canabarrense, e ao olharmos para trÃ¡s, vimos que ao longo destes anos, muita coisa aconteceu.</p>\n<br>\n<div id="map"><img src="../img/business.jpg" width="687" height="318" alt=""></div>\n<p>Parece muito tempo, mas ainda temos claro na memÃ³ria o dia em que comeÃ§amos a trilhar nosso caminho pelo mundo ImobiliÃ¡rio, numa longa e crescente caminhada comeÃ§amos com casas e terrenos, hoje jÃ¡ sÃ£o prÃ©dios e loteamentos.<br>\n    <br><br>\n    <img src="../img/business2.jpg" width="687" height="318" alt="">\n    <br><br><br>\n    Fizemos de cada passo um trajeto e de cada momento uma HistÃ³ria.<br>\n    <br>\n</p>\n<div class="contact clearfix">\n    <div class="about2 clearfix">\n        <p>\n            E hoje vemos que nada Ã© capaz de tirar o Ã¢nimo de quem acredita que, com confianÃ§a e perseveranÃ§a, sempre se chega aonde se quer.<br><br>\n            Ã‰ a partir desta histÃ³ria que apresentamos a ImobiliÃ¡ria Reichert ImÃ³veis que Ã© a uniÃ£o de empresas sÃ³lidas e experientes no ramo imobiliÃ¡rio que acreditam no seu trabalho.<br>\n            <br>\n            Somos referÃªncia de qualidade consolidada ao longo destes 32 anos, e temos o compromisso com seus sonhos, que nos fazem ser cada dia melhor.<br>\n            <br>\n            Ã‰ aqui na ImobiliÃ¡ria Reichert ImÃ³veis que vocÃª encontra a chave do seu imÃ³vel.\n        </p>\n    </div>\n    <img src="../img/iraci.jpg" width="320" height="539" alt="">\n</div>', NULL, 10, '2015-09-29 17:05:13', 0, 2, 6, 1),
(32, 1, 'fale-conosco', 'Fale Conosco', 'Fale Conosco', '', 'html', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="contato[nome]" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="contato[email]" value="">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="contato[telefone]" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="contato[mensagem]" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>\n\n<script type="text/javascript">\n$(function()\n{\n  if ( window.location.href.split(''/'').pop() == ''sucesso'' )\n  {\n    alert(''Sua mensagem foi enviada com sucesso. Responderemos o mais breve possÃ­vel, obrigado!'');\n  }\n  else if ( window.location.href.split(''/'').pop() == ''falha'' )\n  {\n    alert(''Lamento, mas sua mensagem nÃ£o foi enviada. Tente novamente mais tarde.'');\n  }\n});\n</script>', '<div id="content">\n    <h4>Entre em Contato</h4>\n    <!-- contact -->\n    <blockquote>em Canabarro</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:imobiliariacanabarrense@hotmail.com" target="_blank">imobiliariacanabarrense@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137627108">(51) 3762-7108</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="map"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <blockquote>em Languiru</blockquote>\n    <div class="contact clearfix">\n        <div class="details">\n            <p>\n                <h7>e-mail</h7>\n                <br>\n                <h8><a href="mailto:reichert.imoveis@hotmail.com" target="_blank">reichert.imoveis@hotmail.com</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>ImobiliÃ¡ria</h7>\n                <br>\n                <h8><a href="tel:05137621359">(51) 3762-1359</a></h8>\n                <br><br>\n            </p>\n            <p>\n                <h7>Corretora Iraci</h7>\n                <br>\n                <h8><a href="tel:05199947358">(51) 9994-7358</a></h8>\n                <br><br>\n            </p>\n        </div>\n        <div class="description clearfix">\n            <div id="mapb"></div>\n        </div>\n        <div class="seperator clearfix"></div>\n    </div>\n    <!-- End Contact -->\n    <h4><br><br>Entre em Contato</h4>\n    <br><br>\n    <form action="{loadbase_url}fale-conosco/contato" method="post">\n        <div class="row first">\n            <label>Nome</label>\n            <input type="text" name="contato[nome]" value="" id="nome">\n        </div>\n        <div class="row">\n            <label>E-mail</label>\n            <input type="text" name="contato[email]" value="">\n        </div>\n        <div class="row">\n            <label>Telefone</label>\n            <input type="text" name="contato[telefone]" value="" id="telefone">\n        </div>\n        <div class="row">\n            <label>Mensagem</label>\n            <{loadtextarea} name="contato[mensagem]" id="mensagem"></{loadtextarea}>\n        </div>\n        <input class="button" type="submit" value="Enviar">\n    </form>\n</div>\n\n<script type="text/javascript">\n$(function()\n{\n  if ( window.location.href.split(''/'').pop() == ''sucesso'' )\n  {\n    alert(''Sua mensagem foi enviada com sucesso. Responderemos o mais breve possÃ­vel, obrigado!'');\n  }\n  elseif ( window.location.href.split(''/'').pop() == ''falha'' )\n  {\n    alert(''Lamento, mas sua mensagem nÃ£o foi enviada. Tente novamente mais tarde.'');\n  }\n});\n</script>', NULL, 10, '2015-10-16 18:24:48', 0, 2, 6, 1),
(33, 3, 'imoveis/listar', 'Listar', 'Listar', '', 'html', '{loadposition imoveis_listar}', NULL, NULL, 10, '2015-11-04 00:24:10', 1, 2, 6, 1),
(34, 32, 'fale-conosco/contato', 'Contato', 'Contato', '', 'html', '{loadposition post_contato}', '', NULL, 10, '2015-11-09 18:51:50', 0, 2, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_permissoes`
--

CREATE TABLE IF NOT EXISTS `cms_permissoes` (
  `usuario_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Fazendo dump de dados para tabela `cms_permissoes`
--

INSERT INTO `cms_permissoes` (`usuario_id`, `modulo_id`, `site_id`) VALUES
(1, 18, 2),
(1, 13, 2),
(1, 16, 2),
(1, 11, 2),
(1, 10, 2),
(1, 5, 2),
(1, 7, 2),
(1, 6, 2),
(1, 3, 1),
(1, 12, 1),
(1, 1, 1),
(1, 2, 1),
(1, 14, 1),
(1, 5, 1),
(1, 15, 1),
(1, 12, 2),
(2, 6, 2),
(2, 7, 2),
(2, 5, 2),
(2, 11, 2),
(2, 13, 2),
(2, 18, 2),
(2, 12, 2),
(2, 3, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_sites`
--

CREATE TABLE IF NOT EXISTS `cms_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `path` text COLLATE utf8_unicode_ci,
  `template_id` int(11) DEFAULT '0',
  `dir` text COLLATE utf8_unicode_ci,
  `icone` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Fazendo dump de dados para tabela `cms_sites`
--

INSERT INTO `cms_sites` (`id`, `titulo`, `url`, `path`, `template_id`, `dir`, `icone`) VALUES
(1, 'AdministraÃ§Ã£o', 'http://imobiliariareichert.com.br/cms/', '/home/wwwimobi/public_html/cms/', 1, 'cms', '1.png'),
(2, 'Reichert ImÃ³veis | ImobiliÃ¡ria em Canabarro e Languiru', 'http://imobiliariareichert.com.br/', '/home/wwwimobi/public_html/', 4, '', '2.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_templates`
--

CREATE TABLE IF NOT EXISTS `cms_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `site_id` int(11) DEFAULT NULL,
  `html` text COLLATE utf8_unicode_ci NOT NULL,
  `html_last` text COLLATE utf8_unicode_ci,
  `cabecalho_rodape_padrao` tinyint(1) DEFAULT '1',
  `html_header` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Fazendo dump de dados para tabela `cms_templates`
--

INSERT INTO `cms_templates` (`id`, `titulo`, `site_id`, `html`, `html_last`, `cabecalho_rodape_padrao`, `html_header`) VALUES
(1, 'Template limpo', 1, '<html>\n    <head>\n        <title>{loadtitle}</title>\n    </head>\n    <body>\n        {loadcontent}\n    </body>\n</html>', '', 0, ''),
(2, 'Em branco', NULL, '{loadcontent}', '', 0, ''),
(3, 'Em breve', 2, '<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=utf-8" />\n        <title>{loadtitle}</title>\n    </head>\n    <body>\nEm breve...\n    </body>\n</html>\n', '<!DOCTYPE html>\n<html>\n    <head>\n        <meta charset=utf-8" />\n        <title>{loadtitle}</title>\n    </head>\n    <body>\nEm breve...\n    </body>\n</html>\n', 0, ''),
(5, 'Reichert ImÃ³veis | Home', 2, '<!doctype html>\n<html lang="pt-br">\n    <head>\n        <meta charset="utf-8" />\n        <title>Reichert ImÃ³veis | ImobiliÃ¡ria em Canabarro e Languiru | {loadcontenttitle}</title>\n        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}css/style.css"/>\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}arquivos/css/template.css"/>\n        <script src="{loadbase_url}Scripts/swfobject_modified.js" type="text/javascript"></script>\n    </head>\n    <body>\n        <!-- Header -->\n        <div id="header">\n            <div id="inner-header">\n                <!-- Logo -->\n                {loadposition logo_topo}\n                <!-- End Logo -->\n\n                <!-- Menu -->\n                {loadposition menu_topo}\n                <!-- End Menu -->\n            </div>\n        </div>\n        <!-- End Header -->\n\n        <!-- Container -->\n        <div id="container">\n            {loadcontent}\n        </div>\n        <!-- End Container -->\n\n        <!-- Footer -->\n        {loadposition rodape}\n        <!-- End Footer -->\n\n        <!-- Scripts -->\n        <script type="text/javascript" src="{loadbase_url}js/jquery.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.flexslider.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.slides.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.selectbox.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/script.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/superfish.js"></script>\n        <script type="text/javascript">\n        // initialise pluginsjQuery(function(){jQuery(''ul.sf-menu'').superfish();});\n        swfobject.registerObject("FlashID");\n        </script>        \n        <script type="text/javascript">$(function(){$(''div.switcher-bg span.circle'').click(function(){if($(''div.switcher input.custom-style'').attr(''checked'')==''checked''){$(''div.switcher a.off'').click();}else{$(''div.switcher a.on'').click();}});});</script>\n        <script type="text/javascript" src="{loadbase_url}arquivos/js/scripts.js"></script>\n        <!-- End Scripts -->\n    </body>\n</html>', '<!doctype html>\n<html lang="pt-br">\n    <head>\n        <meta charset="utf-8" />\n        <title>Reichert ImÃ³veis | ImobiliÃ¡ria em Canabarro e Languiru | {loadcontenttitle}</title>\n        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}css/style.css"/>\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}arquivos/css/template.css"/>\n        <script src="{loadbase_url}Scripts/swfobject_modified.js" type="text/javascript"></script>\n    </head>\n    <body>\n        <!-- Header -->\n        <div id="header">\n            <div id="inner-header">\n                <!-- Logo -->\n                {loadposition logo_topo}\n                <!-- End Logo -->\n\n                <!-- Menu -->\n                {loadposition menu_topo}\n                <!-- End Menu -->\n            </div>\n        </div>\n        <!-- End Header -->\n\n        <!-- Container -->\n        <div id="container">\n            {loadcontent}\n        </div>\n        <!-- End Container -->\n\n        <!-- Footer -->\n        {loadposition rodape}\n        <!-- End Footer -->\n\n        <!-- Scripts -->\n        <script type="text/javascript" src="{loadbase_url}js/jquery.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.flexslider.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.slides.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.selectbox.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/script.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/superfish.js"></script>\n        <script type="text/javascript" src="{loadbase_url}arquivos/js/scripts.js"></script>\n        <script type="text/javascript">\n        // initialise pluginsjQuery(function(){jQuery(''ul.sf-menu'').superfish();});\n        swfobject.registerObject("FlashID");\n        </script>        \n        <script type="text/javascript">$(function(){$(''div.switcher-bg span.circle'').click(function(){if($(''div.switcher input.custom-style'').attr(''checked'')==''checked''){$(''div.switcher a.off'').click();}else{$(''div.switcher a.on'').click();}});});</script>\n        <!-- End Scripts -->\n    </body>\n</html>', 0, ''),
(6, 'Reichert ImÃ³veis | Internas', 2, '<!doctype html>\n<html lang="pt-br">\n    <head>\n        <meta charset="utf-8" />\n        <title>Reichert ImÃ³veis | ImobiliÃ¡ria em Canabarro e Languiru | {loadcontenttitle}</title>\n        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}css/style.css"/>\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}arquivos/css/template.css"/>\n        <link rel=stylesheet href="{loadbase_url}arquivos/libs/swiper/dist/css/swiper.min.css">\n        <script src="{loadbase_url}Scripts/swfobject_modified.js" type="text/javascript"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.min.js"></script>\n    </head>\n    <body>\n        <!-- Header -->\n        <div id="header">\n            <div id="inner-header">\n                <!-- Logo -->\n                {loadposition logo_topo}\n                <!-- End Logo -->\n\n                <!-- Menu -->\n                {loadposition menu_topo}\n                <!-- End Menu -->\n            </div>\n        </div>\n        <!-- End Header -->\n\n        <!-- Container -->\n        <div id="container">\n            <!-- Content -->\n            <div id="content" class="no-bg">\n                <!-- Quick Search -->\n                {loadposition quick_search}\n                <!-- End Quick Search -->\n\n                {loadcontent}\n            </div>\n            <!-- End Content -->\n\n            <!-- Sidebar -->\n            {loadposition menu_sidebar}\n            <!-- End Sidebar -->\n        </div>\n        <!-- End Container -->\n\n        <!-- Footer -->\n        {loadposition rodape}\n        <!-- End Footer -->\n\n        <!-- Scripts -->\n        <script type="text/javascript" src="{loadbase_url}js/jquery.flexslider.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.slides.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.selectbox.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/script.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/superfish.js"></script>\n        <script type="text/javascript">\n            // initialise pluginsjQuery(function(){jQuery(''ul.sf-menu'').superfish();});\n            swfobject.registerObject("FlashID");\n        </script>        \n        <script type="text/javascript">$(function(){$(''div.switcher-bg span.circle'').click(function(){if($(''div.switcher input.custom-style'').attr(''checked'')==''checked''){$(''div.switcher a.off'').click();}else{$(''div.switcher a.on'').click();}});});</script>\n        <script src="{loadbase_url}arquivos/libs/swiper/dist/js/swiper.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}arquivos/js/scripts.js"></script>\n        <!-- End Scripts -->\n    </body>\n</html>', '<!doctype html>\n<html lang="pt-br">\n    <head>\n        <meta charset="utf-8" />\n        <title>Reichert ImÃ³veis | ImobiliÃ¡ria em Canabarro e Languiru | {loadcontenttitle}</title>\n        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}css/style.css"/>\n        <link rel="stylesheet" type="text/css" href="{loadbase_url}arquivos/css/template.css"/>\n        <link rel=stylesheet href="{loadbase_url}arquivos/libs/swiper/dist/css/swiper.min.css">\n        <script src="{loadbase_url}Scripts/swfobject_modified.js" type="text/javascript"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.min.js"></script>\n    </head>\n    <body>\n        <!-- Header -->\n        <div id="header">\n            <div id="inner-header">\n                <!-- Logo -->\n                {loadposition logo_topo}\n                <!-- End Logo -->\n\n                <!-- Menu -->\n                {loadposition menu_topo}\n                <!-- End Menu -->\n            </div>\n        </div>\n        <!-- End Header -->\n\n        <!-- Container -->\n        <div id="container">\n            <!-- Content -->\n            <div id="content" class="no-bg">\n                <!-- Quick Search -->\n                {loadposition quick_search}\n                <!-- End Quick Search -->\n\n                {loadcontent}\n            </div>\n            <!-- End Content -->\n\n            <!-- Sidebar -->\n            {loadposition menu_sidebar}\n            <!-- End Sidebar -->\n        </div>\n        <!-- End Container -->\n\n        <!-- Footer -->\n        {loadposition rodape}\n        <!-- End Footer -->\n\n        <!-- Scripts -->\n        <script type="text/javascript" src="{loadbase_url}js/jquery.flexslider.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.slides.min.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/jquery.selectbox.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/script.js"></script>\n        <script type="text/javascript" src="{loadbase_url}js/superfish.js"></script>\n        <script type="text/javascript" src="{loadbase_url}arquivos/js/scripts.js"></script>\n        <script type="text/javascript">\n            // initialise pluginsjQuery(function(){jQuery(''ul.sf-menu'').superfish();});\n            swfobject.registerObject("FlashID");\n        </script>        \n        <script type="text/javascript">$(function(){$(''div.switcher-bg span.circle'').click(function(){if($(''div.switcher input.custom-style'').attr(''checked'')==''checked''){$(''div.switcher a.off'').click();}else{$(''div.switcher a.on'').click();}});});</script>\n        <script src="{loadbase_url}arquivos/libs/swiper/dist/js/swiper.min.js"></script>\n        <!-- End Scripts -->\n    </body>\n</html>', 0, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cms_usuarios`
--

CREATE TABLE IF NOT EXISTS `cms_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `usuario` text COLLATE utf8_unicode_ci NOT NULL,
  `senha` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Fazendo dump de dados para tabela `cms_usuarios`
--

INSERT INTO `cms_usuarios` (`id`, `nome`, `email`, `usuario`, `senha`) VALUES
(1, 'Arthur Lehdermann', 'ArthurLehdermann@gmail.com', 'ArthurLehdermann', 'aa95e0857f1219fc49ba12fab32f9795'),
(2, 'Samuel', 'samuel.gaucho@gmail.com', 'Samuel', '3e6f7568aac84d6a7dfe1b3641698697');

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_cidades`
--

CREATE TABLE IF NOT EXISTS `site_cidades` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `estado_id` int(11) unsigned NOT NULL,
  `cep` int(10) unsigned DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cep` (`cep`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Fazendo dump de dados para tabela `site_cidades`
--

INSERT INTO `site_cidades` (`id`, `estado_id`, `cep`, `nome`) VALUES
(1, 21, 95890000, 'Teutônia'),
(2, 21, NULL, 'Lajeado'),
(3, 21, NULL, 'Paverama'),
(4, 21, NULL, 'Westfália'),
(5, 21, NULL, 'Estrela'),
(6, 21, NULL, 'Fazenda Vila Nova'),
(7, 21, NULL, 'Boa Vista do Sul'),
(8, 21, NULL, 'Taquari'),
(9, 21, NULL, 'Bom Retiro Do Sul'),
(10, 21, NULL, 'Linha São Jacó'),
(11, 21, NULL, 'Linha Wink'),
(12, 21, NULL, 'Poço das Antas'),
(13, 21, NULL, 'Pontes Filho'),
(14, 21, NULL, 'Outras localidades');

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_estados`
--

CREATE TABLE IF NOT EXISTS `site_estados` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pais_id` int(11) unsigned NOT NULL,
  `sigla` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigla` (`sigla`),
  KEY `pais_id` (`pais_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Fazendo dump de dados para tabela `site_estados`
--

INSERT INTO `site_estados` (`id`, `pais_id`, `sigla`, `nome`) VALUES
(1, 36, 'AC', 'Acre'),
(2, 36, 'AL', 'Alagoas'),
(3, 36, 'AP', 'Amapa'),
(4, 36, 'AM', 'Amazonas'),
(5, 36, 'BA', 'Bahia'),
(6, 36, 'CE', 'Ceará'),
(7, 36, 'DF', 'Distrito Federal'),
(8, 36, 'ES', 'Espírito Santo'),
(9, 36, 'GO', 'Goiânia'),
(10, 36, 'MA', 'Maranhão'),
(11, 36, 'MT', 'Mato Grosso'),
(12, 36, 'MS', 'Mato Grosso do Sul'),
(13, 36, 'MG', 'Minas Gerais'),
(14, 36, 'PA', 'Pará'),
(15, 36, 'PB', 'Paraíba'),
(16, 36, 'PR', 'Paraná'),
(17, 36, 'PE', 'Pernambuco'),
(18, 36, 'PI', 'Piauí'),
(19, 36, 'RJ', 'Rio de Janeiro'),
(20, 36, 'RN', 'Rio Grande do Norte'),
(21, 36, 'RS', 'Rio Grande do Sul'),
(22, 36, 'RO', 'Rondônia'),
(23, 36, 'RR', 'Roraima'),
(24, 36, 'SC', 'Santa Catarina'),
(25, 36, 'SP', 'São Paulo'),
(26, 36, 'SE', 'Sergipe'),
(27, 36, 'TO', 'Tocantins');

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_imoveis`
--

CREATE TABLE IF NOT EXISTS `site_imoveis` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `referencia` text COLLATE utf8_unicode_ci NOT NULL,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome para o anúncio',
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `endereco` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Endereço do imóvel',
  `cidade_id` int(11) unsigned NOT NULL COMMENT 'Cidade onde se encontra o imóvel',
  `destaque` tinyint(1) NOT NULL COMMENT 'Flag informando se é destaque ou não',
  `area_terreno` text COLLATE utf8_unicode_ci COMMENT 'Área total do terreno',
  `area_construida` text COLLATE utf8_unicode_ci COMMENT 'Área total construída',
  `dormitorios` int(11) unsigned NOT NULL COMMENT 'Quantidade de dormitórios',
  `vagas_garagem` int(11) unsigned NOT NULL COMMENT 'Quantidade de vagas(garagem)',
  `valor` text COLLATE utf8_unicode_ci COMMENT 'Valor do imóvel',
  `obs` text COLLATE utf8_unicode_ci COMMENT 'Observações',
  `descricao` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição do anúncio',
  `categoria_id` int(11) unsigned DEFAULT NULL,
  `negocio_id` int(11) unsigned DEFAULT NULL,
  `foto_capa` text COLLATE utf8_unicode_ci NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT '10',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `tipo_negocio_id` (`negocio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1294 ;

--
-- Fazendo dump de dados para tabela `site_imoveis`
--

INSERT INTO `site_imoveis` (`id`, `referencia`, `titulo`, `link`, `endereco`, `cidade_id`, `destaque`, `area_terreno`, `area_construida`, `dormitorios`, `vagas_garagem`, `valor`, `obs`, `descricao`, `categoria_id`, `negocio_id`, `foto_capa`, `ordem`, `ativo`) VALUES
(35, 'Ca001', 'Ca001', 'ca001', 'Bairro Canabarro', 1, 1, '1.074', '120', 4, 1, 'R$ 260.000,00', '', 'Casa de alvenaria, 04 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem, cercado.\r\n', 2, 1, 'ca001_thumb.jpg', 10, 1),
(37, 'Ca003', 'Ca003', 'ca003', 'Bairro Canabarro', 1, 0, '436', '100', 2, 1, 'R$ 250.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem aberta, quiosque e piscina.', 2, 1, 'ca003_thumb.jpg', 10, 1),
(38, 'Ca004', 'Ca004', 'ca004', 'Bairro Canabarro', 1, 0, '1.420', '222', 3, 1, 'R$ 450.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, 01 suÃ­te, 02 salas, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem, pÃ¡tio todo cercado.', 2, 1, 'ca004_thumb.jpg', 10, 1),
(49, 'Ca016', 'Ca016', 'ca016', 'Bairro Canabarro', 1, 1, '363', '220', 4, 3, 'R$ 370.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 1, 'dsc01747_thumb.jpg', 10, 1),
(50, 'Ca017', 'Ca017', 'ca017', 'Bairro Canabarro', 1, 0, '726', '200', 3, 1, '', 'Valor Ã¡ negociar', 'Casa de alvenaria, 03 dormitÃ³rios, 02 salas de estar, 01 sala de jantar, 02 banheiros sociais, cozinha, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado, portÃ£o eletrÃ´nico, quiosque e piscina.', 2, 1, 'ca017_thumb.jpg', 10, 1),
(59, 'Ca027', 'Ca027', 'ca027', 'Bairro Canabarro', 1, 0, '624', '196', 3, 1, 'R$ 286.000,00', '', 'Casa de alvenaria, 02 pisos, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 1, 'ca027_thumb.jpg', 10, 1),
(62, 'Ca030', 'Ca030', 'ca030', 'Bairro Canabarro', 1, 0, '1.106,68mÂ²', '120', 1, 1, 'R$ 460.000,00', '', 'Casa de alvenaria, 01 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 1, 'ca030_thumb.jpg', 10, 1),
(63, 'Ca031', 'Ca031', 'ca031', 'Bairro Canabarro', 1, 1, '82,00', '', 2, 1, '', '', 'Sobrados de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 8, 1, 'dsc02000_thumb.jpg', 10, 1),
(65, 'Ca033', 'Ca033', 'ca033', 'Bairro Canabarro', 1, 0, '633', '80', 3, 1, 'R$ 88.000,00', '', 'Casa de madeira, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 1, 'ca033_thumb.jpg', 10, 1),
(68, 'Ca035', 'Ca035', 'ca035', 'Bairro Canabarro', 1, 1, '', '75', 2, 0, 'R$ 165.000,00', '', 'Sobrados de alvenaria, parte superior: 02 dormitÃ³rios, banheiro, parte inferior: sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 8, 1, 'aerea_thumb.jpg', 10, 1),
(75, 'Ca044', 'Ca044', 'ca044', 'Bairro Canabarro', 1, 0, '372', '127', 3, 1, 'R$ 159.000,00', '', 'Casa de alvenaria, semi - acabada, 03 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'ca044_thumb.jpg', 10, 1),
(84, 'Ca054', 'Ca054', 'ca054', 'Bairro Canabarro', 1, 0, '330', '151', 4, 2, 'R$ 198.000,00', '', 'Casa de alvenaria, 04 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 1, 'ca054_thumb.jpg', 10, 1),
(86, 'Ca056', 'Ca056', 'ca056', 'Bairro Canabarro', 1, 1, '1.227,00', '60', 2, 1, 'R$ 243.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'ca056_thumb.jpg', 10, 1),
(88, 'Ca058', 'Ca058', 'ca058', 'Bairro Canabarro', 1, 0, '4.025,78', '311,87', 4, 2, 'R$ 530.000,00', 'Toda feita de chapa.', 'Casa de alvenaria, 04 dormitÃ³rios, sala de estar, sala de jantar, cozinha, 03 banheiros, Ã¡rea de serviÃ§o, garagem, patio todo fechado com muro e portÃ£o.', 2, 1, 'ca058_thumb.jpg', 10, 1),
(89, 'Ca059', 'Ca059', 'ca059', 'Bairro Canabarro', 1, 1, '363', '150', 3, 1, 'R$ 270.000,00', 'SofÃ¡, ar condicionado, mesa com 6 cadeiras.', 'Casa de alvenaria, 03 dormitÃ³rios, 02 salas, 02 cozinhas, 02 banheiros, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado, portÃ£o eletrÃ´nico.', 2, 1, 'ca059_thumb.jpg', 10, 1),
(98, 'Ca070', 'Ca070', 'ca070', 'Bairro Canabarro', 1, 0, '363', '143', 3, 1, 'R$ 153.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 1, 'ca070_thumb.jpg', 10, 1),
(110, 'Ca083', 'Ca083', 'ca083', 'Bairro Canabarro', 1, 0, '364', '102', 4, 1, 'R$ 106.000,00', '', 'Casa mista, 04 dormitÃ³rios, 01 sala de estar, 01 sala de jantar, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercado.', 2, 1, 'ca083_thumb.jpg', 10, 1),
(120, 'Ca094', 'Ca094', 'ca094', 'Bairro Canabarro', 1, 1, '1.090', '315', 4, 2, 'R$ 840.000,00', 'Piscina.', 'Casa de alvenaria, 04 dormitÃ³rios, sala, 02 cozinhas, 03 banheiros, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado com portÃ£o eletrÃ´nico.', 2, 1, 'imagem_001_thumb.jpg', 10, 1),
(134, 'Alc012', 'Alc012', 'alc012', 'Bairro Canabarro', 1, 1, '', '', 0, 0, 'R$ 314,00', 'HÃ¡ vÃ¡rios disponÃ­veis, jÃ¡ incluso valor de Ã¡gua.', 'PeÃ§a inteira com banheiro.', 4, 2, 'maria_i_thumb.jpg', 10, 1),
(152, 'Ca061', 'Ca061', 'ca061', 'Morgen Land', 1, 0, '1.270', '63,17', 2, 0, 'R$ 150.000,00', 'Toda feita de chapa.', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 3, 1, 'dsc01340_thumb.jpg', 10, 1),
(155, 'All016', 'All016', 'all016', 'Bairro Centro Administrativo', 1, 1, '', '', 0, 0, '', 'Valores Ã¡ ver na ImobiliÃ¡ria.\r\nCondomÃ­nio R$ 145,00', 'Apartamentos de 02 e 03 dormitÃ³rios, sala, cozinha, banheiros, Ã¡rea de serviÃ§o, garagem.', 1, 2, 'sergio_veiculos_thumb.jpg', 10, 1),
(171, 'All015', 'All015', 'all015', 'Bairro Languiru', 1, 1, '', '', 2, 1, 'R$ 550,00', 'Valor de taxa de Ã¡gua R$ 29,00', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 1, 2, 'cristian_wessel_thumb.jpg', 10, 1),
(188, 'Alc030', 'Alc030', 'alc030', 'Bairro Canabarro', 1, 1, '', '', 0, 0, 'R$ 2.200,00', '', 'Sala comercial de 200mÂ², com banheiro.', 7, 2, 'dsc01469_thumb.jpg', 10, 1),
(192, 'Ca039', 'Ca039', 'ca039', 'Fazenda Vila Nova', 6, 1, '50.000', '120', 3, 1, 'R$ 420.000,00', 'HÃ¡ aÃ§udes e outras benfeitorias.', 'Casa de alvenaria de 120mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.\r\n', 3, 1, 'ca39_thumb.jpg', 10, 1),
(194, 'All027', 'All027', 'all027', 'Bairro Languiru', 1, 1, '', '33,45', 0, 0, 'R$ 500,00', '', 'Sala comercial de 33,45mÂ², com banheiro e mezanino.Sala interna', 7, 2, 'floresta_negra_thumb.jpg', 10, 1),
(202, 'Alt013', 'Alt013', 'alt013', 'Bairro TeutÃ´nia', 1, 1, '562,50', '', 0, 0, 'R$ 65.000,00', '', 'Terreno medindo 562,50mÂ², sendo 12,50m de frente por 45,00m de frente a fundos.', 5, 1, 't6_thumb.jpg', 10, 1),
(203, 'Alt005', 'Alt005', 'alt005', 'Bairro TeutÃ´nia', 1, 1, '420,80', '', 0, 0, 'R$ 53.000,00', '', 'Terreno medindo 420,82mÂ², sendo 16,00m de frente por 26,30m de frente a fundos.', 5, 1, 'dsc05546_thumb.jpg', 10, 1),
(210, 'La003', 'La003', 'la003', 'Bairro Languiru', 1, 0, '1.480,32', '', 5, 1, 'R$ 424.000,00', '', 'Casa de alvenaria, 05 dormitÃ³rios, sala, sala de jantar, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'la_003-1u00ba_thumb.jpg', 10, 1),
(212, 'Ca049', 'Ca049', 'ca049', 'PoÃ§o das Antas', 12, 1, '360', '119,30', 2, 1, 'R$ 165.000,00', '', 'Casa de alvenaria 119,30mÂ²,02 dormitÃ³rios,sala,\r\ncozinha,banheiro,Ã¡rea de serviÃ§o,garagem,cercado.', 2, 1, 'cesar_paulo1_thumb.jpg', 10, 1),
(213, 'Alt011', 'Alt011', 'alt011', 'Pontes Filho', 13, 1, '640', '', 0, 0, 'R$ 30.000,00', '', 'Terreno medindo 640,00mÂ², sendo 22,00m de frente por 30,00m de frente a fundos.', 5, 1, 'douglas_post_30_mil_thumb.jpg', 10, 1),
(214, 'Alt012', 'Alt012', 'alt012', 'Pontes Filho', 13, 1, '360', '', 0, 0, 'R$ 20.000,00', '', 'Terreno medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos.', 5, 1, 'marlene_20_mil_thumb.jpg', 10, 1),
(216, 'La005', 'La005', 'la005', 'Bairro Languiru', 1, 0, '383,23', '110', 3, 0, 'R$ 160.000,00', '', 'Casa de alvenaria 110,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 1, 'la_005_thumb.jpg', 10, 1),
(217, 'La006', 'La006', 'la006', 'Bairro Languiru', 1, 0, '352', '135', 2, 1, 'R$ 280.000,00', '', 'Casa mista 135mÂ², 02 dormitÃ³rios, sala, sala de jantar, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'la_006-1u00ba_thumb.jpg', 10, 1),
(220, 'La024', 'La024', 'la024', 'Bairro TeutÃ´nia', 1, 0, '', '195', 3, 1, 'R$ 420.000,00', '', 'Casa mista de 195,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, despensa, garagem.', 2, 1, 'la_024-1u00ba_thumb.jpg', 10, 1),
(221, 'La026', 'La026', 'la026', 'Bairro TeutÃ´nia', 1, 0, '317', '104', 3, 1, 'R$ 212.000,00', '', 'Casa de alvenaria de 104,00mÂ², 03 dormitÃ³rios, sala, sala de jantar, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'la_026_thumb.jpg', 10, 1),
(222, 'La028', 'La028', 'la028', 'Bairro TeutÃ´nia', 1, 0, '376,20', '172', 5, 1, 'R$ 265.000,00', '', 'Casa de alvenaria 172,00mÂ², 05 domitÃ³rios, 02 salas, sala de jantar, 02 cozinhas, 02 banheiros, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'la_028-10u00ba_thumb.jpg', 10, 1),
(226, 'La007', 'La007', 'la007', 'Bairro Languiru', 1, 0, '300', '70', 2, 0, 'R$ 90.000,00', '', 'Casa de madeira 70,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, porÃ£o.', 2, 1, 'la_033_thumb.jpg', 10, 1),
(228, 'La009', 'La009', 'la009', 'Bairro Languiru', 1, 0, '', '189', 3, 1, 'R$ 212.000,00', '', 'Casa de alvenaria de 189,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercado.\r\n', 2, 1, 'la_009_thumb.jpg', 10, 1),
(237, 'La027', 'La027', 'la027', 'Bairro TeutÃ´nia', 1, 0, '363', '70', 2, 0, 'R$ 106.000,00', '', 'Casa de alvenaria de 70,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada.', 2, 1, 'la_027_thumb.jpg', 10, 1),
(239, 'La031', 'La031', 'la031', 'Bairro TeutÃ´nia', 1, 0, '360', '108,28', 3, 1, 'R$ 160.000,00', '', 'Casa de alvenaria de 108,28mÂ², 03 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem, cercado.', 2, 1, 'la_031_thumb.jpg', 10, 1),
(240, 'La033', 'La033', 'la033', 'Bairro TeutÃ´nia', 1, 0, '1.393,20', '', 0, 0, 'R$ 200.000,00', '', 'Casa de alvenaria antiga.', 2, 1, 'imagem059_thumb.jpg', 10, 1),
(244, 'Alc029', 'Alc029', 'alc029', 'Bairro Canabarro', 1, 1, '', '205', 0, 0, 'R$ 1.540,00', '', 'Duas salas comerciais, sendo uma de 205mÂ² e a outra de 185mÂ², com banheiro.', 7, 2, 'dsc01532_thumb.jpg', 10, 1),
(254, 'Alt015', 'Alt015', 'alt015', 'Rua Erno Dahmer', 1, 1, '455,00', '', 0, 0, 'R$ 85.000,00', '', 'Terreno medindo 455,00mÂ², sendo 13,00m de frente por 35,00m de frente a fundos.', 5, 1, 'a_thumb.jpg', 10, 1),
(255, 'Alt016', 'Alt016', 'alt016', 'Bairro Alesgut', 1, 1, '363', '', 0, 0, 'R$ 75.000,00', '', 'Terreno medindo 363,00mÂ², sendo 11,00m de frente por 30,00m de frente a fundos.', 5, 1, 'a_thumb.jpg', 10, 1),
(259, 'Act002', 'Act002', 'act002', 'Bairro Canabarro', 1, 1, '396', '', 0, 0, 'R$ 68.000,00', '', 'Terreno medindo 396mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01542_thumb.jpg', 10, 1),
(261, 'Act004', 'Act004', 'act004', 'Bairro Canabarro', 1, 1, '459', '', 0, 0, 'R$ 50.000,00', '', 'Terreno de esquina medindo 459mÂ², sendo 17,00m de frente por 27,00m de frente a fundos.', 5, 1, 'dsc01544_thumb.jpg', 10, 1),
(262, 'Act005', 'Act005', 'act005', 'Bairro Canabarro', 1, 1, '432', '', 0, 0, 'R$ 50.000,00', '', 'Terreno medindo 432,00mÂ², sendo 16,00m de frente por 27,00m de frente a fundos.', 5, 1, 'dsc01544_thumb.jpg', 10, 1),
(265, 'Act008', 'Act008', 'act008', 'Bairro Canabarro', 1, 1, '366', '', 0, 0, 'R$ 70.000,00', 'Cada no valor de R$ 70.000,00.', 'TrÃªs terrenos medindo 366,00mÂ², sendo 12,00m de frente por 30,50m de frente a fundos.', 5, 1, 'dsc01474_thumb.jpg', 10, 1),
(267, 'Act010', 'Act010', 'act010', 'Bairro Canabarro', 1, 1, '440', '', 0, 0, 'R$ 68.000,00', '', 'Terreno medindo 44,00mÂ², sendo 11,00m de frente por 40,00m de frente a fundos.', 5, 1, 'dsc01480_thumb.jpg', 10, 1),
(268, 'Act011', 'Act011', 'act011', 'Bairro Canabarro', 1, 1, '319,01', '', 0, 0, 'R$ 98.000,00', '', 'Terreno de esquina medindo 319,01mÂ², sendo 13,75m de frente por 23,22m de frente a fundos.', 5, 1, 'dsc01486_thumb.jpg', 10, 1),
(269, 'Act012', 'Act012', 'act012', 'Bairro Canabarro', 1, 1, '318,65', '', 0, 0, 'R$ 95.000,00', '', 'Terreno medindo 318,65mÂ², sendo 23,05m de frente por 13,80m de frente a fundos.', 5, 1, 'dsc01487_thumb.jpg', 10, 1),
(271, 'Act013', 'Act013', 'act013', 'Bairro Canabarro', 1, 1, '318,96', '', 0, 0, 'R$ 95.000,00', '', 'Terreno medindo 318,96mÂ², sendo 23,05m de frente por 13,90m de frente a fundos.', 5, 1, 'dsc01487_thumb.jpg', 10, 1),
(273, 'Act015', 'Act015', 'act015', 'Bairro Canabarro', 1, 1, '684', '', 0, 0, 'R$ 80.000,00', '', 'Terreno medindo 684,00mÂ², sendo 12,00m de frente por 57,00m de frente a fundos.', 5, 1, 'dsc01547_thumb.jpg', 10, 1),
(274, 'Act016', 'Act016', 'act016', 'Bairro Canabarro', 1, 1, '720', '', 0, 0, 'R$ 148.400,00', '', 'Terreno de esquina medindo 720,00mÂ², sendo 24,00m de frente por 30,00m de frente a fundos.', 5, 1, 'dsc01488_thumb.jpg', 10, 1),
(276, 'Act018', 'Act018', 'act018', 'Bairro Canabarro', 1, 1, '468', '', 0, 0, 'R$ 233.500,00', '', 'Terreno medindo 468,00mÂ², sendo 13,00m de frente por 36,00m de frente a fundos.', 5, 1, 'dsc01556_thumb.jpg', 10, 1),
(277, 'Act019', 'Act019', 'act019', 'Bairro Canabarro', 1, 1, '363', '', 0, 0, 'R$ 127.500,00', '', 'Terreno medindo 363,00mÂ², sendo 11,00m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01553_thumb.jpg', 10, 1),
(279, 'Act021', 'Act021', 'act021', 'Bairro Canabarro', 1, 1, '396', '', 0, 0, 'R$ 68.000,00', '', 'Terreno medindo 396,00mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01550_thumb.jpg', 10, 1),
(281, 'Act023', 'Act023', 'act023', 'Bairro Canabarro', 1, 1, '444', '', 0, 0, 'R$ 106.000,00', '', 'Terreno de esquina medindo 444,00mÂ², sendo 12,00m de frente a 37,00m de frente a fundos.', 5, 1, 'dsc01554_thumb.jpg', 10, 1),
(282, 'Act024', 'Act024', 'act024', 'Bairro Canabarro', 1, 1, '363', '', 0, 0, 'R$ 70.000,00', 'Cada: R$ 35.000,00\r\nOs dois por R$ 70.000,00\r\nHÃ¡ negociaÃ§Ã£o.', 'Dois terrenos medindo 363,00mÂ², sendo 11,00m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01562_thumb.jpg', 10, 1),
(283, 'Act025', 'Act025', 'act025', 'Bairro Canabarro', 1, 1, '542,40', '', 0, 0, 'R$ 74.000,00', '', 'Terreno medindo 542,40mÂ², sendo 12,00m de frente por 45,20m de frente a fundos.', 5, 1, 'dsc01560_thumb.jpg', 10, 1),
(284, 'Act026', 'Act026', 'act026', 'Bairro Canabarro', 1, 1, '272,65', '', 0, 0, 'R$ 105.000,00', '', 'Terreno medindo 272,65mÂ², sendo 14,35m de frente por 19,00m de frente a fundos.', 5, 1, 'dsc01558_thumb.jpg', 10, 1),
(286, 'Act027', 'Act027', 'act027', 'Bairro Canabarro', 1, 1, '360', '', 0, 0, 'R$ 110.000,00', '', 'Terreno de 360,00mÂ².', 5, 1, 'dsc01495_thumb.jpg', 10, 1),
(287, 'Act028', 'Act028', 'act028', 'Bairro Canabarro', 1, 1, '448,50', '', 0, 0, 'R$ 160.000,00', '', 'Terreno medindo 448,50mÂ².', 5, 1, 'dsc01496_thumb.jpg', 10, 1),
(289, 'Act030', 'Act030', 'act030', 'Bairro Canabarro', 1, 1, '396', '', 0, 0, 'R$ 60.000,00', 'Cada: R$ 60.000,00', 'TrÃªs terrenos de 396,00mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01561_thumb.jpg', 10, 1),
(294, 'Alt019', 'Alt019', 'alt019', 'Boa Vista', 7, 1, '292,50', '', 0, 0, '', 'Ã partir de R$ 40.000,00.\r\nLoteamento Parque da Boa Vista.', 'Terrenos medindo 292,50mÂ², sendo 15,00m de frente por 19,50m de frente a fundos.', 5, 1, 'apartir_de_40_mil_thumb.jpg', 10, 1),
(319, 'Act049', 'Act049', 'act049', 'Bairro Canabarro', 1, 1, '562,50', '', 0, 0, 'R$ 127.000,00', '', 'Dois terrenos um de esquina e o outro ao lado de 562,50mÂ², cada um, sendo 15,00m de frente por 37,50m de frente a fundos.', 5, 1, 'dsc01588_thumb.jpg', 10, 1),
(321, 'Act051', 'Act051', 'act051', 'Bairro Canabarro', 1, 1, '2.018,25', '', 0, 0, 'R$ 265.000,00', '', 'Terreno medindo 2.018,25mÂ², sendo 33,00m de frente por 57,83m de frente a fundos.', 5, 1, 'dsc01592_thumb.jpg', 10, 1),
(327, 'La035', 'La035', 'la035', 'Bairro Canabarro', 1, 1, '330', '83,07', 2, 1, 'R$ 117.000,00', 'ContÃ©m salÃ£o de beleza.', 'Casa de alvenaria de 83,07mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'dsc01639_thumb.jpg', 10, 1),
(328, 'La036', 'La036', 'la036', 'Bairro Alesgut', 1, 1, '363', '112,32', 5, 1, 'R$ 190.000,00', '', 'Casa de alvenaria de 112,32mÂ², 02 pisos, sendo parte superior: 01 dormitÃ³rio, sala, cozinha, banheiro, garagem, parte inferior: 04 dormitÃ³rios, sala, cozinha, 02 banheiros, ', 2, 1, 'dsc01620_thumb.jpg', 10, 1),
(333, 'Alt024', 'Alt024', 'alt024', 'Bairro Centro Administrativo', 1, 1, '427', '', 0, 0, 'R$ 100.000,00', '', 'Terreno medindo 427,00mÂ², sendo 12,20m de frente por 35,00m de frente a fundos.', 5, 1, 'dsc01618_thumb.jpg', 10, 1),
(334, 'Alt025', 'Alt025', 'alt025', 'Bairro Centro Administrativo', 1, 1, '366', '', 0, 0, 'R$ 200.000,00', '', 'Terreno medindo 366,00mÂ², sendo 12,20m de frente por 30,00m de frente a fundos.', 5, 1, 'dsc01615_thumb.jpg', 10, 1),
(351, 'La038', 'La038', 'la038', 'Bairro Centro Administrativo', 1, 1, '1.489,71', '', 0, 0, 'R$ 450.000,00', '', 'Ãrea de terras de 1.489,71mÂ², contendo uma casa de alvenaria.', 2, 1, 'imagem_002_thumb.jpg', 10, 1),
(352, 'Act053', 'Act053', 'act053', 'Bairro Canabarro', 1, 1, '388,18', '', 0, 0, 'R$ 69.000,00', '', 'Terreno medindo 388,18mÂ², sendo 14,76m de frente por 26,30m de frente a fundos.', 5, 1, 'dsc01650_thumb.jpg', 10, 1),
(353, 'Act054', 'Act054', 'act054', 'Bairro Canabarro', 1, 1, '394,50', '', 0, 0, 'R$ 69.000,00', '', 'Terreno medindo 394,50mÂ², sendo 15,00m de frente por 26,30m de frente a fundos.', 5, 1, 'dsc01651_thumb.jpg', 10, 1),
(358, 'La043', 'La043', 'la043', 'Bairro Canabarro', 1, 1, '', '434', 0, 0, 'R$ 530.000,00', '', 'Casa residencial e comercial medindo 270,00mÂ² mais 164,00mÂ².', 2, 1, 'imagem039_thumb.jpg', 10, 1),
(361, 'La046', 'La046', 'la046', 'Bairro TeutÃ´nia', 1, 0, '', '180', 0, 0, 'R$ 420.000,00', '', 'PrÃ©dio comercial de 180,00mÂ², no segundo andar, contendo uma casa de 180,00mÂ², 02 dormitÃ³rios.', 7, 1, 'dsc03367_thumb.jpg', 10, 1),
(363, 'La048', 'La048', 'la048', 'Linha Geralda', 5, 0, '7.600,00', '40', 2, 0, 'R$ 85.000,00', '', 'Casa de madeira de 40,00mÂ², localizada em uma Ã¡rea de 7.600,00mÂ², contendo 02 dormitÃ³rios, sala, cozinha, banheiro.', 2, 1, 'fgts1010_thumb.jpg', 10, 1),
(364, 'La049', 'La049', 'la049', 'Linha Germano', 1, 1, '', '100', 0, 0, 'R$ 138.000,00', '', 'Casa de alvenaria de 100,00mÂ², localizada em uma Ã¡rea de aproximadamente 05 terrenos.', 2, 1, 'fotos25-10-08002_thumb.jpg', 10, 1),
(366, 'Alt026', 'Alt026', 'alt026', 'Bairro TeutÃ´nia', 1, 1, '408', '', 0, 0, 'R$ 296.000,00', '', 'Terreno medindo 408,00mÂ², sendo 13,60m de frente 30,00m de frente a fundos.', 5, 1, 'dsc01654_thumb.jpg', 10, 1),
(369, 'La050', 'La050', 'la050', 'Boa Vista do Sul', 7, 1, '', '', 0, 0, 'R$ 2.000.000,00', '', 'Ãrea de 09 hectares, com galpÃ£o, salÃ£o de festas, churrasqueira, 11 cabanas, aÃ§ude, quartos de pousada, casa de 03 dormitÃ³rios, sala, cozinha, banheiro, garagem, churrasqueira.', 3, 1, 'imagem_022_thumb.jpg', 10, 1),
(370, 'La051', 'La051', 'la051', 'Linha Capivara', 1, 1, '', '', 0, 0, 'R$ 210.000,00', '', 'Ãrea de terras de 4,5hectares, com duas casas de alvenaria, sendo 01 casa de 40,00mÂ² e a 2Âº casa de 100,00mÂ².', 3, 1, 'imagem079_thumb.jpg', 10, 1),
(371, 'La039', 'La039', 'la039', 'Linha Germano', 1, 1, '', '', 0, 0, 'R$ 530.000,00', '', 'Ãrea de terras medindo 10,20 hectares contendo uma casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, garagem e demais benfeitorias rurais.', 3, 1, 'cristiano1_thumb.jpg', 10, 1),
(373, 'La052', 'La052', 'la052', 'Bairro TeutÃ´nia', 1, 1, '', '', 0, 0, 'R$ 700.000,00', '', 'Sala comercial de alvenaria medindo 550,00mÂ², localizada em uma Ã¡rea de 1.024,00mÂ².', 7, 1, 'helton_driemeyer_thumb.jpg', 10, 1),
(383, 'Ca026', 'Ca026', 'ca026', 'Bairro Languiru', 1, 1, '', '282,46', 2, 2, 'R$ 470.000,00', '', 'Apartamento 03 dormitÃ³rios, living. EdifÃ­cio sendo 01 suÃ­te com closet e banheira de hidromassagem, 01 banheiro social, cozinha integrada com sala de jantar, Ã¡rea de serviÃ§o, andar superior: sala com espera para lareira, escritÃ³rio, lavabo, Ã¡rea de lazer com piscina, com cascata e hidromassagem e iluminaÃ§Ã£o, churrasqueira, cobertura de vidro, forro com gesso e iluminaÃ§Ã£o embutida.', 1, 1, 'ca048_thumb.jpg', 10, 1),
(393, 'All033', 'All033', 'all033', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 770,00', '', 'Sala comercial de com banheiro.', 7, 2, 'carmen_thumb.jpg', 10, 1),
(397, 'Act006', 'Act006', 'act006', 'Bairro Canabarro', 1, 1, '1.212,75', '', 0, 0, 'R$ 480.000,00', '', 'Terreno medindo 1.212,75mÂ², sendo 12,25m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01709_thumb.jpg', 10, 1),
(400, 'Act055', 'Act055', 'act055', 'Bairro Canabarro', 1, 1, '363,00', '', 0, 0, '', 'Dois terrenos R$ 160.000,00 ou R$ 80.000,00 cada.', 'Terreno medindo 363,00mÂ², sendo 11,00m de frente por 33,00m de frente a fundos.', 5, 1, 'posto_thumb.jpg', 10, 1),
(407, 'All020', 'All020', 'all020', 'Bairro TeutÃ´nia', 1, 1, '', '', 0, 0, 'R$ 660,00', '', 'Sala comercial de 71mÂ², com banheiro.', 7, 2, 'aluguel_002_thumb.jpg', 10, 1),
(409, 'All037', 'All037', 'all037', 'Bairro Languiru', 1, 1, '', '', 2, 1, 'R$ 772,00', '', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 1, 2, 'aluguel_003_thumb.jpg', 10, 1),
(411, 'La056', 'La056', 'la056', 'Bairro Languiru', 1, 1, '', '', 0, 0, '', 'LocalizaÃ§Ã£o privilegiada; Excelente iluminaÃ§Ã£o e ventilaÃ§Ã£o; Box incluso no apartamento; Elevador; Esquadrias de madeira; Espera para Split, Piso CerÃ¢mico "A"; Espera para Ã¡gua quente; Alvenaria tijolo maciÃ§o; Ãgua e luz individuais. Para mais informaÃ§Ãµes entre em contato.', 'Apartamento de 01, 02 dormitÃ³rios, 02 dormitÃ³rios sendo 01 suÃ­te, 03 dormitÃ³rios sendo 01 suÃ­te, metragens Ã¡ partir de 49,31mÂ² Ã¡ 101,75mÂ².', 1, 1, 'caderno-10_thumb.jpg', 10, 1),
(424, 'Alt007', 'Alt007', 'alt007', 'Bairro Languiru', 1, 1, '360', '', 0, 0, 'R$ 75.000,00', 'A partir de R$ 75.000,00', 'Terrenos de 360,00mÂ², frente para as ruas Major Bandeira e Rua 17. Loteamento Paladini.', 5, 1, 'imagem_464_thumb.jpg', 10, 1),
(427, 'La057', 'La057', 'la057', 'Bairro TeutÃ´nia', 1, 0, '660,00', '80,00', 2, 0, 'R$ 138.000,00', '', 'Casa de alvenaria de aproximadamente 80,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 1, 'alceu_thumb.jpg', 10, 1),
(428, 'La058', 'La058', 'la058', 'Bairro Alesgut', 1, 1, '335,00', '49,52', 2, 0, 'R$ 150.000,00', '', 'Casa mista de 49,52mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, pÃ¡tio cercado.', 2, 1, 'aluguel_003_thumb.jpg', 10, 1),
(434, 'All011', 'All011', 'all011', 'Bairro Centro Administrativo', 1, 1, '', '', 0, 0, 'R$ 250,00', 'Este valor inclui a Ã¡gua.', 'Kitnet com peÃ§a inteira e um banheiro.', 4, 2, 'jacyjung1_thumb.jpg', 10, 1),
(438, 'La059', 'La059', 'la059', 'Bairro Languiru', 1, 1, '1065', '69', 2, 1, 'R$ 180.000,00', '', 'Casa de alvenaria 69mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, puxado para Ã¡rea de serviÃ§o e garagem, cercado.', 2, 1, 'aluguel_005_thumb.jpg', 10, 1),
(450, 'La061', 'La061', 'la061', 'Arroio do Pau', 6, 1, '5.5hectares', '', 0, 0, 'R$ 270.000,00', '', 'Ãrea de terras medindo 5.5 hectares, cercada, plana, nÃ£o tem mato inativo, tem Ã¡gua e luz trifÃ¡sica, galpÃ£o de 120,00mÂ², 10 baias para vacas, vertente, estufa, aÃ§ude, uma casa mista de 90,00mÂ² e uma casa de madeira de 88,00mÂ².', 3, 1, 'imagem_510_thumb.jpg', 10, 1),
(478, 'Alc038', 'Alc038', 'alc038', 'Bairro Canabarro', 1, 1, '', '', 0, 0, 'R$ 275,00', 'Cond. R$ 27,00\r\nGaragem R$ 18,00(se tiver carro)\r\n', 'Kitnet com peÃ§a inteira e um banheiro.', 4, 2, 'eltonklein_thumb.jpg', 10, 1),
(480, 'Alt031', 'Alt031', 'alt031', 'Boa Vista', 14, 1, '224,00', '', 0, 0, 'R$ 48.000,00', '', 'Lote 05: Terreno medindo 224,00mÂ², sendo 14,00m de frente por 16,00m de frente a fundos.\r\nLote 13: Terreno medindo 224,00mÂ², sendo 14,00m de frente por 16,00m de frente a fundos.', 5, 1, 'imagem220_thumb.jpg', 10, 1),
(481, 'La037', 'La037', 'la037', 'Bairro Alesgut', 1, 1, '330,00', '104,00', 3, 1, 'R$ 255.000,00', '', 'Casa de alvenaria de 104,00mÂ², 03 dormitÃ³rios, sala, sala de jantar, cozinha, banheiro, churrasqueira, garagem, piscina, cercado.', 2, 1, 'imagem203_thumb.jpg', 10, 1),
(494, 'La064', 'La064', 'la064', 'Bairro Languiru', 1, 0, '', '103,8416', 3, 0, 'R$ 275.000,00', '', 'Apartamento medindo 103,8416mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, varanda.', 1, 1, 'imagem_001_thumb.jpg', 10, 1),
(534, 'La068', 'La068', 'la068', 'Bairro Languiru', 1, 1, '', '', 0, 0, '', '', 'Apartamento, segue fotos em anexo!', 1, 1, '', 10, 1),
(537, 'La069', 'La069', 'la069', 'Bairro TeutÃ´nia', 1, 0, '345', '54,25', 2, 0, 'R$ 120.000,00', '', 'Casa de alvenaria de 54,25mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 1, 'imagem_016_thumb.jpg', 10, 1),
(541, 'Act014', 'Act014', 'act014', 'Bairro Canabarro', 1, 0, '1.112,19mÂ²', '', 0, 0, 'R$ 90.000,00', '', 'Terreno medindo 1.112,19mÂ², equivalente a trÃªs terrenos.', 5, 1, 'dsc01918_thumb.jpg', 10, 1),
(545, 'All004', 'All004', 'all004', 'Bairro TeutÃ´nia', 1, 1, '', '', 0, 0, 'R$ 550,00', '', 'Sala comercial de 50mÂ² com 02 banheiros.', 7, 2, '100_4716_thumb.jpg', 10, 1),
(547, 'Act039', 'Act039', 'act039', 'Bairro Canabarro', 1, 1, '660', '', 0, 0, 'R$ 95.000,00', '', 'Terreno medindo 660,00mÂ², sendo 16,5m de frente por 40,00m de frente a fundos.', 5, 1, 'dsc01927_thumb.jpg', 10, 1),
(548, 'Ca072', 'Ca072', 'ca072', 'Bairro Canabarro', 1, 1, '585,00', '130,00', 3, 1, 'R$ 255.000,00', 'Cozinha projetada e balcÃ£o no banheiro, incluso no valor. Tem instalaÃ§Ã£o para Ã¡gua quente.', 'Casa de alvenaria de 105,00mÂ², 03 dormitÃ³rios, sala, sala de jantar, cozinha, banheiro, Ã¡rea de serviÃ§o, mais 25mÂ² de garagem, toda de chapa e gesso.', 2, 1, 'dsc01924_thumb.jpg', 10, 1),
(549, 'Alt033', 'Alt033', 'alt033', 'Bairro TeutÃ´nia', 1, 1, '3.000,00mÂ²', '', 0, 0, 'R$ 475.000,00', '', 'Ãrea de terras com a superfÃ­cie 3.000,00mÂ², sendo 52,03m de frente para a Via Lactea RST 128.', 5, 1, 'imagem_001_thumb.jpg', 10, 1),
(552, 'All019', 'All019', 'all019', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 827,00', '', 'Sala comercial com banheiro.', 7, 2, '100_4726_thumb.jpg', 10, 1),
(554, 'Alt017', 'Alt017', 'alt017', 'Bairro Canabarro', 1, 0, '399,00', '', 0, 0, 'R$ 79.000,00', '', 'Terreno medindo 399,00mÂ², sendo 14,00m de frente por 28,50m de frente a fundos.', 5, 1, 'imagem_008_thumb.jpg', 10, 1),
(556, 'Alt029', 'Alt029', 'alt029', 'Bairro TeutÃ´nia', 1, 0, '1.204,00', '', 0, 0, 'R$ 320.000,00', 'Pode ser desmembrado, trÃªs terrenos de 372,00mÂ² casa no valor de R$ 120.000,00', 'Ãrea de terras de 1.204,00mÂ².', 5, 1, 'imagem_003_thumb.jpg', 10, 1),
(562, 'Alt034', 'Alt034', 'alt034', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 65.000,00', '', 'Terreno medindo 418,00mÂ², sendo 12,5m de frente por 33,44m de frente a fundos.', 5, 1, 'imagem037_thumb.jpg', 10, 1),
(563, 'La015', 'La015', 'la015', 'WestfÃ¡lia', 4, 1, '915,85', '166,10', 3, 2, '', 'ImÃ³vel residencial e comercial medindo 145,27mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, e parte comercial(hoje ocupada pela Biblioteca Municipal de WestfÃ¡lia).', 'Terreno medindo 915,85mÂ², de esquina, contendo uma casa de alvenaria de 166,10mÂ², 03 dormitÃ³rios, 02 banheiros, 01 lavabo, sala, sala de jantar, escritÃ³rio, garagem para 02 veÃ­culos, Ã¡rea de serviÃ§o, churrasqueira.', 2, 1, 'imagem001_thumb.jpg', 10, 1),
(566, 'Alt035', 'Alt035', 'alt035', 'Bairro Centro Administrativo', 1, 1, '', '', 0, 0, 'R$ 140.000,00', '', 'Terreno medindo 370,66mÂ², sendo 12,00m de frente por 30,83m de um lado e 30,95m de outro lado.', 5, 1, 'imagem001_thumb.jpg', 10, 1),
(583, 'La071', 'La071', 'la071', 'Arroio do Pau', 6, 0, '4.5hectares', '', 3, 0, 'R$ 250.000,00', '', 'Ãrea de terras, medindo 3.0hectares, contendo uma casa de alvenaria de 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, com poÃ§o artesiano, vertente para aÃ§ude, cercada.', 3, 1, 'imagem006_thumb.jpg', 10, 1),
(584, 'La054', 'La054', 'la054', 'Linha Frank', 4, 0, '50.540,00', '', 0, 0, 'R$ 400.000,00', '', 'Ãrea de terras medindo 50.540,00mÂ², contendo duas casas, sendo uma casa mista de 02 dormitÃ³rios, sala, cozinha, banheiro, churrasqueira e garagem e a outra casa de alvenaria de 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada, plana, potreiro, Ã¡gua, luz trifÃ¡sica.', 3, 1, 'imagem001_thumb.jpg', 10, 1),
(590, 'La016', 'La016', 'la016', 'Bairro Centro Administrativo', 1, 1, '', '', 0, 3, 'R$ 1.200.000,00', '', 'Ãrea de terras de 2.568,82mÂ², contendo um pavilhÃ£o de 192,00mÂ², mais Ã¡rea fechada de 48,00mÂ², contendo uma rampa de lavagem e uma rampa de carregamento. E prÃ©dio de dois pavimentos sendo parte inferior comercial de 144,00mÂ², com escritÃ³rio recepÃ§Ã£o e 02 banheiros; parte superior residencial de 156,00mÂ², 03 dormitÃ³rios, sendo 01 suÃ­te com closet e banheira de hidromassagem, sala, cozinha, sala de jantar, banheiro social.', 2, 1, 'imagem055_thumb.jpg', 10, 1),
(591, 'Act009', 'Act009', 'act009', 'Bairro Canabarro', 1, 0, '369,20', '', 0, 0, 'R$ 79.000,00', '', 'Terreno medindo 369,20mÂ², sendo 14,20m de frente por 26,00m de frente a fundos.', 5, 1, 'dsc01935_thumb.jpg', 10, 1),
(592, 'Act036', 'Act036', 'act036', 'Bairro Canabarro', 1, 1, '', '', 0, 0, '', '', 'Segundo terreno, medindo 600,00mÂ², sendo 15,00m de frente por 40,00m de frente a fundos. R$ 140.000,00\r\nDe traz, medindo 452,65mÂ², sendo 33,73m de frente por 13,42m de frente a fundos. R$ 130.000,00', 5, 1, 'dsc01936_thumb.jpg', 10, 1),
(596, 'Act046', 'Act046', 'act046', 'Bairro Canabarro', 1, 0, '1.048,69', '', 0, 0, 'R$ 298.000,00', '', 'Terreno medindo 1.048,69mÂ².', 5, 1, 'dsc01948_thumb.jpg', 10, 1),
(597, 'Act047', 'Act047', 'act047', 'Bairro Canabarro', 1, 0, '474,00', '', 0, 0, 'R$ 80.000,00', '', 'Terreno medindo 15,80m de frente por 30,00m de frente a fundos.', 5, 1, 'dsc01942_thumb.jpg', 10, 1),
(600, 'Ca081', 'Ca081', 'ca081', 'Bairro Canabarro', 1, 0, '338,55', '70,00', 3, 1, 'R$ 120.000,00', '', 'Casa de alvenaria de aproximadamente 70,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'theobaldoschneider_thumb.jpg', 10, 1),
(602, 'La073', 'La073', 'la073', 'Praia de Mariluz', 14, 1, '', '', 3, 1, 'R$ 197.000,00', 'Casa na praia de Mariluz, Ã¡ trÃªs quadras do Mar.', 'Casa de alvenaria, 02 dormitÃ³rios, 01 suÃ­te, sala, cozinha, banheiro social, churrasqueira, contendo quiosque com garagem, banheiro, churrasqueira, pÃ¡tio cercado.', 2, 1, 'pic_0074_thumb.jpg', 10, 1),
(624, 'Alt040', 'Alt040', 'alt040', 'Bairro Centro Administrativo', 1, 0, '360,00', '', 0, 0, 'R$ 130.000,00', '', 'Terreno medindo 360,00mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'imagem001_thumb.jpg', 10, 1),
(626, 'Alt041', 'Alt041', 'alt041', 'Bairro TeutÃ´nia', 1, 0, '396,00', '', 0, 0, 'R$ 80.000,00', '', 'Terreno medindo 396,00mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'imagem275_thumb.jpg', 10, 1),
(627, 'Alt043', 'Alt043', 'alt043', 'Boa Vista', 14, 0, '312,40', '', 0, 0, 'R$ 53.000,00', '', 'Terreno medindo 312,40mÂ², sendo 12,50m de frente  por 25,01m de frente a fundos.', 5, 1, 'imagem273_thumb.jpg', 10, 1),
(628, 'La012', 'La012', 'la012', 'Bairro Languiru', 1, 0, '361,35', '70,00', 3, 1, 'R$ 450.000,00', '', 'Casa de alvenaria medindo 70,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, garagem.', 2, 1, 'imagem005_thumb.jpg', 10, 1),
(629, 'La018', 'La018', 'la018', 'Bairro Alesgut', 1, 1, '390,00', '74,73', 2, 1, 'R$ 200.000,00', '', 'Casa de alvenaria medindo 44,73mÂ², 02 dormitÃ³rios, sala, cozinha e banheiro, mais uma ampliaÃ§Ã£o de aproximadamente 30,00mÂ², sendo uma Ã¡rea, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'imagem277_thumb.jpg', 10, 1),
(631, 'Act035', 'Act035', 'act035', 'Bairro Canabarro', 1, 0, '463,87', '', 0, 0, 'R$ 65.000,00', '', 'Terreno medindo 463,87mÂ², sendo 12,08m de frente por 38,40m de frente a fundos.', 5, 1, 'dsc01960_thumb.jpg', 10, 1),
(637, 'La030', 'La030', 'la030', 'Linha Clara', 1, 0, '', '', 0, 0, 'R$ 600.000,00', '', 'Ãrea de terras medindo 14hectares, plana, com casa, banheiros, Ã¡rea para camping, faz divisa com o arroio Boa Vista.', 3, 1, 'imagem005_thumb.jpg', 10, 1),
(638, 'La045', 'La045', 'la045', 'Linha Catarina', 1, 0, '', '', 0, 0, 'R$ 244.000,00', '', 'Ãrea de terras medindo 10 hectares, sem benfeitorias, semi plana, 82,168m de frente para a Estrada Geral de Linha Catarina.', 3, 1, 'imagem001_thumb.jpg', 10, 1),
(657, ' Alt001', ' Alt001', '_alt001', 'Bairro Centro Administrativo', 1, 0, '360,00', '', 0, 0, 'R$ 115.000,00', 'Cada terreno no valor de R$ 115.000,00.', 'Dois terrenos medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos.', 5, 1, '1_thumb.jpg', 10, 1),
(660, 'Alt010', 'Alt010', 'alt010', 'Bairro Canabarro', 1, 0, '393,60', '', 0, 0, 'R$ 106.000,00', '', 'Terreno medindo 393,60mÂ², sendo 12,00m de frente por 32,80m de frente a fundos.', 5, 1, 'imagem013_thumb.jpg', 10, 1),
(661, 'La075', 'La075', 'la075', 'Bairro TeutÃ´nia', 1, 1, '398,11', '208,75', 4, 1, 'R$ 398.000,00', 'Com gesso, porcelanato. PÃ¡tio cercado nas laterais e nos fundos, asfalto na frente.', 'Terreno medindo 398,11mÂ², sendo 11,65m de frente por 33,40m de frente a fundos, contendo uma casa de alvenaria de 208,75mÂ², 02 andares, 04 dormitÃ³rios, sendo 01 suÃ­te, living, sala, cozinha, 02 banheiros, garagem, Ã¡rea de serviÃ§o.', 2, 1, 'imagem015_thumb.jpg', 10, 1),
(670, 'La077', 'La077', 'la077', 'Bairro Navegantes', 14, 0, '528', '112', 3, 1, 'R$ 116.000,00', '', 'Cada de alvenaria, 03 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, '', 10, 1),
(672, 'Alt045', 'Alt045', 'alt045', 'Bairro Languiru', 1, 0, '', '', 0, 0, 'R$ 58.000,00', '', 'Terreno medindo 303,77mÂ², sendo 24,50m de frente por 12,40m de frente a fundos.', 5, 1, 'foto_thumb.jpg', 10, 1),
(684, 'Ca008', 'Ca008', 'ca008', 'Bairro Canabarro', 1, 1, '363,00', '170', 3, 1, 'R$ 285.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem, portÃ£o eletrÃ´nico, cercada.', 2, 1, 'dsc01998_thumb.jpg', 10, 1),
(695, 'Alc011', 'Alc011', 'alc011', 'Bairro Canabarro', 1, 0, '', '', 2, 0, 'R$ 385,00', '', 'Casa de madeira, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 2, 'fbiomattes_thumb.jpg', 10, 1),
(704, 'La025', 'La025', 'la025', 'Bairro Canabarro', 1, 0, '183,70', '139,87', 3, 1, 'R$ 170.000,00', 'Quiosque medindo 47,50mÂ², Ã¡rea de serviÃ§o, garagem e banheiro.', 'Sobrado de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, lavabo, Ã¡rea de serviÃ§o, garagem.', 8, 1, '10_thumb.jpg', 10, 1),
(705, 'La060', 'La060', 'la060', 'Bairro Languiru', 1, 0, '', '', 2, 1, 'R$ 220.000,00', '', 'Apartamento de frente, medindo 117,00mÂ², 02 dormitÃ³rios, sala, sala de jantar, cozinha, lavabo, Ã¡rea de serviÃ§o, sacada, box. EdifÃ­cio com elevador.', 1, 1, '1_thumb.jpg', 10, 1),
(710, 'Act037', 'Act037', 'act037', 'Bairro Canabarro', 1, 0, '416', '', 0, 0, 'R$ 275.000,00', '', 'Terreno de esquina medindo 416,00mÂ², sendo 26,00m de frente por 13,00m de frente a fundos.', 5, 1, 'dsc02006_thumb.jpg', 10, 1),
(711, 'Act040', 'Act040', 'act040', 'Bairro Canabarro', 1, 0, '399,45', '', 0, 0, 'R$ 58.000,00', '', 'Terreno medindo 399,45mÂ², sendo 12,96m de frente por 30,68m de frente a fundos.', 5, 1, 'dsc02004_thumb.jpg', 10, 1),
(713, 'Act057', 'Act057', 'act057', 'Bairro Canabarro', 1, 0, '360', '', 0, 0, 'R$ 155.000,00', '', 'Terreno de esquina medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos.', 5, 1, 'dsc02005_thumb.jpg', 10, 1),
(714, 'Act058', 'Act058', 'act058', 'Bairro Canabarro', 1, 0, '1.683,00', '', 0, 0, 'R$ 190.000,00', '', 'Ãrea de terras medindo 1.683,00mÂ², sendo 33,00m de frente por 51,00m de frente a fundos.', 5, 1, 'dsc02000_thumb.jpg', 10, 1),
(715, 'Act059', 'Act059', 'act059', 'Bairro Canabarro', 1, 0, '552', '', 0, 0, 'R$ 90.000,00', '', 'Terreno medindo 552,00mÂ², sendo 11,50m de frente por 48,00m de frente a fundos.', 5, 1, 'adelmoosterkampav1lestecotovelo_thumb.jpg', 10, 1),
(716, 'Act060', 'Act060', 'act060', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 60.000,00', '', 'Dois terrenos medindo   sendo 14,10m de frente por frente a fundos.', 5, 1, 'dsc02032_thumb.jpg', 10, 1),
(717, 'Act061', 'Act061', 'act061', 'Bairro Canabarro', 1, 0, '484', '', 0, 0, 'R$ 115.000,00', '', 'Terreno medindo 484,00mÂ², sendo 11,00m de frnete por 44,00m de frente a fundos.', 5, 1, 'ladisetedesetembro_thumb.jpg', 10, 1),
(719, 'Ca014', 'Ca014', 'ca014', 'Bairro Canabarro', 1, 1, '420', '130', 3, 1, 'R$ 350.000,00', '', 'Casa de alvenaria de 130mÂ², 03 dormitÃ³rios, sendo 01 suÃ­te, sala, cozinha, banheiro social, lavabo, Ã¡rea de serviÃ§o, porÃ£o com garagem, piscina, quiosque, cercada.', 2, 1, 'dsc02002_thumb.jpg', 10, 1),
(722, 'Act062', 'Act062', 'act062', 'Bairro Canabarro', 1, 0, '', '', 0, 0, '', 'Ã partir de R$ 130.000,00', 'Diversos terrenos, conforme mapa, mais detalhes com a corretora de imÃ³veis.', 5, 1, 'dsc02052_thumb.jpg', 10, 1),
(723, 'Act063', 'Act063', 'act063', 'Bairro Canabarro', 1, 0, '', '', 0, 0, '', 'Ã partir de R$ 38.000,00', 'Diversos terrenos conforme mapa, mais detalhes com as corretoras.', 5, 1, 'dsc02022_thumb.jpg', 10, 1),
(724, 'Ca060', 'Ca060', 'ca060', 'Bairro Canabarro', 1, 0, '229', '50', 3, 1, 'R$ 50.000,00', '', 'Terreno medindo 229,00mÂ², contendo uma casa de madeira aproximadamente de 50mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, puxado p/ garagem.', 2, 1, 'dsc02057_thumb.jpg', 10, 1),
(726, 'La080', 'La080', 'la080', 'Linha Capivara', 1, 0, '', '', 0, 0, 'R$ 190.000,00', '', 'Ãrea de terras de 15 hectares, contendo casa mista antiga, Ã¡gua, luz, vertente natural.', 2, 1, '1_thumb.jpg', 10, 1),
(729, 'All005', 'All005', 'all005', 'Bairro Languiru', 1, 0, '', '', 0, 0, 'R$ 550,00', 'Cond R$ 30,00', 'Sala comercial de 90mÂ² com banheiro.', 7, 2, 'novaimagem_thumb.jpg', 10, 1),
(730, 'Alc017', 'Alc017', 'alc017', 'Bairro Canabarro', 1, 0, '', '', 1, 0, 'R$ 352,00', '', 'Casa mista, 01 dormitÃ³rio, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o.', 2, 2, 'dsc02039_thumb.jpg', 10, 1),
(749, 'Act064', 'Act064', 'act064', 'Bairro Canabarro', 1, 0, '4742,43', '', 0, 0, 'R$ 700.000,00', '', 'Ãrea de terras medindo 4.742,43mÂ², sendo 39,60m de frente.', 5, 1, 'dsc02180_thumb.jpg', 10, 1),
(750, 'Act065', 'Act065', 'act065', 'Bairro Canabarro', 1, 0, '968', '', 0, 0, 'R$ 160.000,00', '', 'Terreno medindo 968mÂ², sendo 22,00m de frente por 44,00m de frente a fundos.', 5, 1, 'dsc02178_thumb.jpg', 10, 1),
(751, 'Alc041', 'Alc041', 'alc041', 'Bairro Canabarro', 1, 0, '', '', 3, 0, 'R$ 352,00', '', 'Casa mista, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 2, 'glacyhunsche_thumb.jpg', 10, 1),
(754, 'Act066', 'Act066', 'act066', 'Bairro Canabarro', 1, 0, '363,23', '', 0, 0, 'R$ 85.000,00', '', 'Terreno medindo 363,23mÂ², sendo 12,33m de frente por 29,45m de frente a fundos.', 5, 1, 'dsc02195_thumb.jpg', 10, 1),
(755, 'Act067', 'Act067', 'act067', 'Bairro Canabarro', 1, 0, '362,71', '', 0, 0, 'R$ 85.000,00', '', 'Terreno medindo 362,71mÂ², sendo 12,32m de frente por 29,45m de frente a fundos.', 5, 1, 'dsc02196_thumb.jpg', 10, 1),
(756, 'Act022', 'Act022', 'act022', 'Bairro Canabarro', 1, 0, '465', '', 0, 0, 'R$ 70.000,00', '', 'Terreno medindo 465mÂ², sendo de esquina.', 5, 1, 'dsc02198_thumb.jpg', 10, 1),
(757, 'Act068', 'Act068', 'act068', 'Bairro Canabarro', 1, 0, '467,05', '', 0, 0, 'R$ 88.000,00', '', 'Terreno de esquina de 467,05mÂ².Loteamento Residencial Morada do Sol.', 5, 1, 'dsc02200_thumb.jpg', 10, 1),
(758, 'La082', 'La082', 'la082', 'Bairro TeutÃ´nia', 1, 0, '367,50', '72,00', 2, 0, 'R$ 105.000,00', '', 'Terreno medindo 367,50mÂ², sendo 15,00m de frente por 24,50m, contendo uma de alvenaria de 72,00m, sendo 02 dormitÃ³rios, sala, cozinha, 02 banheiros.', 2, 1, '1_thumb.jpg', 10, 1),
(759, 'La083', 'La083', 'la083', 'Bairro Canabarro', 1, 0, '', '', 3, 1, 'R$ 180.000,00', '', 'Sobrado de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, lavabo, Ã¡rea de serviÃ§o, garagem.', 8, 1, '10_thumb.jpg', 10, 1),
(760, 'La085', 'La085', 'la085', 'Bairro Canabarro', 1, 0, '360,00', '', 3, 1, 'R$ 180.000,00', '', 'Terreno medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos, contendo uma casa de alvenaria de 120,00mÂ², 03 dormitÃ³rios, sala, sala de jantar, 02 cozinhas, 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, '1_thumb.jpg', 10, 1),
(761, 'La084', 'La084', 'la084', 'Bairro Canabarro', 1, 0, '370,70', '69,98', 2, 1, 'R$ 185.000,00', '', 'Terreno medindo 370,70mÂ², sendo 11,00m de frente por 33,00m de frente, contendo uma casa de alvenaria de 69,98mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, piscina.', 2, 1, '10_thumb.jpg', 10, 1),
(764, 'Ca085', 'Ca085', 'ca085', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 220.000,00', '', 'Ãrea de terras de 1.5 hectares, terra plana, com benfeitorias, contendo uma casa de alvenaria.', 2, 1, 'dsc02201_thumb.jpg', 10, 1),
(771, 'Act031', 'Act031', 'act031', 'Bairro Canabarro', 1, 0, '336,60', '', 0, 0, 'R$ 55.000,00', '', 'Terreno medindo 336,60mÂ², sendo 16,50m de frente por 20,40m de frente a fundos.', 5, 1, 'dsc02262_thumb.jpg', 10, 1),
(772, 'Ca023', 'Ca023', 'ca023', 'Bairro Canabarro', 1, 0, '700mÂ²', '150mÂ²', 3, 1, 'R$ 270.000,00', '', 'Terreno medindo 700mÂ², contendo uma mista de aproximadamente 150mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, piscina, cercada, portÃ£o.', 2, 1, 'dsc02257_thumb.jpg', 10, 1),
(773, 'Ca025', 'Ca025', 'ca025', 'Bairro Canabarro', 1, 0, '300mÂ²', '80mÂ²', 2, 0, 'R$ 275.000,00', '', 'Casa de alvenaria de aproximadamente 80mÂ², contendo 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada.', 2, 1, 'dsc02254_thumb.jpg', 10, 1),
(774, 'Ca047', 'Ca047', 'ca047', 'Bairro Canabarro', 1, 1, '363mÂ²', '120mÂ²', 3, 1, 'R$ 170.000,00', '', 'Casa de alvenaria de 120mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'dsc02260_thumb.jpg', 10, 1),
(777, 'Ca093', 'Ca093', 'ca093', 'Bairro Canabarro', 1, 0, '250', '81', 2, 0, 'R$ 100.000,00', '', 'Terreno medindo 250mÂ², contendo uma casa de madeira de 81mÂ².', 2, 1, 'dsc02266_thumb.jpg', 10, 1),
(791, 'Alc045', 'Alc045', 'alc045', 'Bairro Canabarro', 1, 0, '', '220mÂ²', 0, 0, 'R$ 1.320,00', '', 'Sala comercial de 220mÂ², com 02 banheiros.', 7, 2, 'dsc02349_thumb.jpg', 10, 1),
(792, 'Alc022', 'Alc022', 'alc022', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 2.000,00', '', 'PrÃ©dio comercial de 330mÂ², com 02 banheiros.', 6, 2, 'dsc02335_thumb.jpg', 10, 1),
(798, 'Act070', 'Act070', 'act070', 'Bairro Canabarro', 1, 0, '360,00', '', 0, 0, 'R$ 65.000,00', '', 'Terreno medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos.', 5, 1, 'dsc02322_thumb.jpg', 10, 1),
(799, 'Act041', 'Act041', 'act041', 'Bairro Canabarro', 1, 0, '644,26', '', 0, 0, 'R$ 75.000,00', '', 'Terreno medindo 644,26mÂ², sendo 12,95m de frente por 49,75m de frente a fundos.', 5, 1, 'dsc02344_thumb.jpg', 10, 1),
(800, 'Act069', 'Act069', 'act069', 'Bairro Canabarro', 1, 0, '1.5hectare', '', 0, 0, 'R$ 220.000,00', '', 'Ãrea de terras de 1.5hectare com benfeitorias e Ã¡gua.', 5, 1, 'dsc02263_thumb.jpg', 10, 1),
(802, 'Ca078', 'Ca078', 'ca078', 'Bairro Canabarro', 1, 0, '345,00mÂ²', '80,00mÂ²', 2, 1, 'R$ 160.000,00', '', 'Casa de alvenaria de aproximadamente 80,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'dsc02342_thumb.jpg', 10, 1),
(803, 'Ca006', 'Ca006', 'ca006', 'Bairro Canabarro', 1, 0, '405,00', '140', 3, 1, 'R$ 212.000,00', '', 'Casa de alvenaria de 140,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'dsc02289_thumb.jpg', 10, 1),
(813, 'Ca050', 'Ca050', 'ca050', 'Bairro Canabarro', 1, 0, '', '166,00', 3, 1, 'R$ 370.000,00', 'Ãgua quente, laminado.', 'Casa de alvenaria de 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'dsc02358_thumb.jpg', 10, 1),
(816, 'Act044', 'Act044', 'act044', 'Bairro Canabarro', 1, 0, '396', '', 0, 0, 'R$ 150.000,00', '', 'Terreno medindo 396,00mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'luisbroenstrup1_thumb.jpg', 10, 1),
(817, 'Act052', 'Act052', 'act052', 'Bairro Canabarro', 1, 0, '363,00', '', 0, 0, 'R$ 60.000,00', '', 'Terreno medindo 363,00mÂ², sendo 11,00m de frente por 33,00m de frente a fundos.', 5, 1, 'jorgepintor_thumb.jpg', 10, 1),
(818, 'Act071', 'Act071', 'act071', 'Bairro Canabarro', 1, 0, '351,45', '', 0, 0, 'R$ 140.000,00', '', 'Terreno de 351,45m, sendo 10,65m de frente por 33,00m de frente a fundos.', 5, 1, 'martimeggers2_thumb.jpg', 10, 1),
(819, 'Ca091', 'Ca091', 'ca091', 'Bairro Canabarro', 1, 1, '', '', 0, 0, '', 'Mais detalhes com corretoras.', 'Sobrado de alvenaria de 92,37mÂ², 03 dormitÃ³rios, sala, cozinha, lavabo, banheiro, Ã¡rea de serviÃ§o, garagem, com chapa, quiosque de 47,50mÂ². R$ 170.000,00\r\nSobrado de alvenaria de 90,75mÂ², 03 dormitÃ³rios, sala, cozinha, lavabo, banheiro, Ã¡rea de serviÃ§o, garagem. R$ 180.000,00\r\nSobrado de alvenaria de 76,68mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, lavabo, Ã¡rea de serviÃ§o, garagem.', 8, 1, 'eracidossantos_thumb.jpg', 10, 1),
(829, 'Alt009', 'Alt009', 'alt009', 'Bairro TeutÃ´nia', 1, 0, '360,00', '', 0, 0, 'R$ 64.000,00', '', 'Terreno medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos.', 5, 1, 'marivani001_thumb.jpg', 10, 1),
(830, 'Ca010', 'Ca010', 'ca010', 'Bairro Canabarro', 1, 0, '468', '', 1, 0, 'R$ 233.500,00', '', 'Terreno de 468mÂ², contendo uma casa de madeira, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 1, 'jairton_thumb.jpg', 10, 1),
(840, 'Ca024', 'Ca024', 'ca024', 'Bairro Canabarro', 1, 0, '', '', 2, 1, 'R$ 212.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'aliviomarkus_thumb.jpg', 10, 1),
(846, 'La087', 'La087', 'la087', 'Rua Veleda Schaeffer, Vila EsperanÃ§a', 1, 1, '325,00 mÂ²', '70,00 mÂ²', 2, 1, 'R$ 128.000,00', '', 'Casa de alvenaria com 70,00 mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'imoveislangloc0011_thumb.jpg', 10, 1),
(853, 'Alc031', 'Alc031', 'alc031', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 2.225,00', '', 'PavilhÃ£o com 560mÂ², com banheiro.', 6, 2, 'dsc01492_thumb.jpg', 10, 1),
(854, 'Act001', 'Act001', 'act001', 'Bairro Canabarro', 1, 0, '474', '', 0, 0, 'R$ 88.000,00', '', 'Terreno medindo 474,00mÂ², sendo 12,00m de frente por 38,50m de frente a fundos.', 5, 1, 'dsc02449_thumb.jpg', 10, 1),
(861, 'Ca067', 'Ca067', 'ca067', 'Bairro Canabarro', 1, 0, '484,00', '140,00', 3, 1, 'R$ 160.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada com portÃ£o eletrÃ´nico.', 2, 1, 'valeriobauer1_thumb.jpg', 10, 1),
(876, 'Ca092', 'Ca092', 'ca092', 'Rua CapitÃ£o Schneider, Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 300.000,00', '', 'Sala comercial de 266.92mÂ² com banheiro, terreno de 372mÂ² ', 7, 1, 'imagem_thumb.jpg', 10, 1),
(884, 'Ca013', 'Ca013', 'ca013', 'Rua Duque de Caxias, Bairro Canabarro', 1, 0, '', '', 3, 1, 'R$ 210.000,00', '', 'Casa de alvenaria, 155,80mÂ², 03 dormitÃ³rios, 02 banheiros, garagem.', 2, 1, 'dsc04196_thumb.jpg', 10, 1),
(901, 'Alc062', 'Alc062', 'alc062', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 890,00', '', 'Sala comercial de 110mÂ² com banheiros', 7, 2, 'dsc04260_thumb.jpg', 10, 1),
(902, 'Alc063', 'Alc063', 'alc063', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 678,00', '', 'Sala comercial de 66mÂ² com banheiro.', 7, 2, 'dsc04261_thumb.jpg', 10, 1),
(904, 'La029', 'La029', 'la029', 'Bairro TeutÃ´nia', 1, 0, '420,00mÂ²', '130,00mÂ²', 3, 1, 'R$ 190.000,00', '', 'Casa da alvenaria, 03 dormitÃ³rios, sala, cozinha, 02 banheiros, dispensa, Ã¡rea de serviÃ§o, garagem, cercada. ', 2, 1, '1020_thumb.jpg', 10, 1),
(905, 'Ca040', 'Ca040', 'ca040', 'Bairro Canabarro', 1, 0, '363mÂ²', '70mÂ²', 2, 0, 'R$ 106.000,00', '', 'Um terreno de 363mÂ² contendo uma casa mista de 70mÂ² com 02 quartos, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 1, 'jairo1_thumb.jpg', 10, 1),
(906, 'Ca034', 'Ca034', 'ca034', 'Bairro Alto do Parque', 2, 0, '360mÂ²', '200mÂ²', 3, 1, 'R$ 320.000,00', '', 'um terreno de 360mÂ² contendo uma casa de alvenaria, 03 dormitÃ³rios, 03 salas, 02 banheiros, cozinha, garagem.', 2, 1, 'marianeprefeitura12_thumb.jpg', 10, 1);
INSERT INTO `site_imoveis` (`id`, `referencia`, `titulo`, `link`, `endereco`, `cidade_id`, `destaque`, `area_terreno`, `area_construida`, `dormitorios`, `vagas_garagem`, `valor`, `obs`, `descricao`, `categoria_id`, `negocio_id`, `foto_capa`, `ordem`, `ativo`) VALUES
(908, 'Act003', 'Act003', 'act003', 'Bairro Canabarro', 1, 0, '360,00', '', 0, 0, 'R$ 67.000,00', '', 'Terreno medindo 360,00mÂ², sendo 12,00m de frente por 30,00m de frente a fundos.', 5, 1, 'dsc04175_thumb.jpg', 10, 1),
(912, 'Ca048', 'Ca048', 'ca048', 'Estrela', 5, 1, '', '', 3, 1, 'R$ 355.000,00', '', 'Apartamento de 140,00mÂ², 03 dormitÃ³rios sendo 01 suÃ­te, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 1, 1, 'dsc06114_thumb.jpg', 10, 1),
(913, 'Act042', 'Act042', 'act042', 'Bairro Canabarro', 1, 0, '', '', 0, 0, 'R$ 160.000,00', '', 'Ãrea de terras de 6.600mÂ², sendo 44,00m de frente por 150,00m de frente a fundos.', 5, 1, 'dsc04304_thumb.jpg', 10, 1),
(914, 'Act034', 'Act034', 'act034', 'Bairro Canabarro', 1, 1, '276,00', '', 0, 0, 'R$ 85.000,00', '', 'Terreno medindo 276,00mÂ², sendo 12,00m de frente por 23,00m de frente a fundos.', 5, 1, 'dsc04303_thumb.jpg', 10, 1),
(915, 'La091', 'La091', 'la091', 'Bairro Alesgut', 1, 0, '360,00', '90,00', 3, 1, 'R$ 250.000,00', '', 'Terreno medindo 360,00mÂ², contendo uma casa de alvenaria de 90,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercado.', 2, 1, 'itbi008_thumb.jpg', 10, 1),
(916, 'La092', 'La092', 'la092', 'Bairro Alesgut', 1, 0, '243,75', '149,50', 0, 0, 'R$ 285.000,00', '', 'Terreno medindo 243,75mÂ², contendo um prÃ©dio comercial de 149,50mÂ².', 6, 1, 'itbi013_thumb.jpg', 10, 1),
(919, 'Act056', 'Act056', 'act056', 'Bairro Canabarro', 1, 0, '396,00', '', 0, 0, 'R$ 80.000,00', '', 'Terreno medindo 396,00mÂ², sendo 12,00m de frente por 33,00m de frente a fundos.', 5, 1, 'dsc01752_thumb.jpg', 10, 1),
(920, 'Ca009', 'Ca009', 'ca009', 'Bairro Canabarro', 1, 0, '900,00mÂ²', '', 0, 0, 'R$ 250.000,00', '', 'Contem 02 casas, uma de alvenaria de 80mÂ² e uma mista de 130mÂ²', 2, 1, 'dsc01878_thumb.jpg', 10, 1),
(922, 'Ca032', 'Ca032', 'ca032', 'Bairro Canabarro', 1, 0, '', '76,85mÂ²', 2, 1, 'R$ 150.000,00', '', 'Apartamento de 02 dormitÃ³rios, sala, cozinha, Ã¡rea de serviÃ§o, sacada fechada de fundos, garagem.', 1, 1, 'elainehepp_thumb.jpg', 10, 1),
(923, 'Ca065', 'Ca065', 'ca065', 'Linha Schmidt Fundos', 14, 0, '', '', 0, 0, 'R$ 265.000,00', '', 'Ãrea de terras, contendo uma casa de alvenaria de 121mÂ², mecanizÃ¡vel, contendo chiqueirÃ£o, aviÃ¡rio e galpÃ£o. ', 5, 1, 'dsc02435_thumb.jpg', 10, 1),
(927, 'Alc007', 'Alc007', 'alc007', 'Bairro Canabarro', 1, 1, '', '', 0, 0, 'R$ 750,00', '', 'Ãrea comercial, cercada, com luz trifÃ¡sica.', 6, 2, 'antenor1_thumb.jpg', 10, 1),
(934, 'Alt028', 'Alt028', 'alt028', 'Bairro Alesgut', 1, 0, '829,40mÂ²', '', 0, 0, 'R$ 265.000,00', '', 'Terreno medindo 829,40mÂ²', 5, 1, '1019_thumb.jpg', 10, 1),
(936, 'Ca042', 'Ca042', 'ca042', 'Bairro Canabarro', 1, 0, '', '73', 2, 1, 'R$ 148.000,00', '', 'Sobrado de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 8, 1, 'dsc04361_thumb.jpg', 10, 1),
(940, 'La002', 'La002', 'la002', 'GlÃ³ria', 5, 1, '1.200,00', '200,00', 3, 2, 'R$ 280.000,00', 'Localizada prÃ³ximo Ã  BR386', 'Casa de alvenaria, medindo aproximadamente 200,00mÂ², 03 dormitÃ³rios, sala, sala de jantar, cozinha, banheiro, garagem, lavanderia, churrasqueira. \r\nQuiosque com piscina.\r\nPÃ¡tio cercado.', 2, 1, 'imagem018_thumb.jpg', 10, 1),
(941, 'La040', 'La040', 'la040', 'Languiru', 1, 0, '285,00', '69,90', 2, 1, 'R$ 170.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala cozinha, banheiro, Ã¡rea de serviÃ§os e garagem.\r\nPÃ¡tio cercado.', 2, 1, 'imagem013_thumb.jpg', 10, 1),
(942, 'La044', 'La044', 'la044', 'Bairro Canabarro', 1, 0, '', '90,75', 3, 1, 'R$ 160.000,00', '', 'Sobrado 03 dormitÃ³rios, sala, cozinha, banheiro, lavabo, lavanderia, churrasqueira, garagem.', 8, 1, 'imagem025_thumb.jpg', 10, 1),
(944, 'La053', 'La053', 'la053', 'Bairro Canabarro', 1, 1, '', '76,68', 2, 1, 'R$ 150.000,00', '', 'Sobrado 02 dormitÃ³rios, sala, cozinha, banheiro, lavabo, lavanderia, churrasqueira e garagem.', 8, 1, 'imagem031_thumb.jpg', 10, 1),
(945, 'La004', 'La004', 'la004', 'Bairro Centro Administrativo', 1, 0, '440,20', '', 0, 0, 'R$ 180.000,00', '', 'Terreno de esquina medindo 440,20mÂ², sendo 14,06m com a Rua 3 de Outubro e 40,34m com a Rua 2 Norte.', 5, 1, 'imagem019_thumb.jpg', 10, 1),
(946, 'Ca046', 'Ca046', 'ca046', 'EdifÃ­cio Bela Vista, Bairro Canabarro', 1, 0, '', '', 0, 1, 'R$ 110.000,00', '', 'Apartamento 01 dormitÃ³rio, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, churrasqueira, garagem, sacada de fundos por R$ 110.000,00.\r\n\r\nApartamento 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, churrasqueira, garagem, sacada de frente por R$ 150.000,00.', 1, 1, 'mariaveleda_thumb.jpg', 10, 1),
(947, 'la008', 'la008', 'la008', 'Bairro TeutÃ´nia', 1, 1, '480,00', '', 0, 0, 'R$ 69.000,00', '', 'Terreno medindo 15,00m de frente por 32,00m de frente a fundos.', 5, 1, 'imagem021_thumb.jpg', 10, 1),
(949, 'La090', 'La090', 'la090', 'Bairro TeutÃ´nia', 1, 1, '406,40', '59,97', 0, 0, 'R$ 138.000,00', '', 'Terreno de esquina, medindo 16,00m de frente.\r\nCasa mista, 02 dormitÃ³rios, sala, cozinha, banheiro e garagem.', 2, 1, 'imagem017_thumb.jpg', 10, 1),
(951, 'La067', 'La067', 'la067', 'Languiru', 1, 0, '330,00', '', 0, 0, 'R$ 268.000,00', 'Terreno comercial, Ã³tima localizaÃ§Ã£o.', 'Terreno medindo 10,00m x 33,00m', 5, 1, 'dsc00603_thumb.jpg', 10, 1),
(966, 'Act072', 'Act072', 'act072', 'Bairro Canabarro', 1, 0, '404,03 mÂ²', '', 0, 0, 'R$ 680.000,00', '', 'Terreno medindo 404,03 mÂ²sendo 13,95m de frente e 28,92 de frente a fundos.', 5, 1, 'luiz_thumb.jpg', 10, 1),
(967, 'Act073', 'Act073', 'act073', 'Bairro Canabarro', 1, 0, '332,96 mÂ²', '', 0, 0, 'R$ 58.000,00', '', 'Terreno medindo 332,96 mÂ² sendo 16 de frente e 20,8 de frente a fundos.', 5, 1, 'erimarcosdacosta_thumb.jpg', 10, 1),
(968, 'Act074', 'Act074', 'act074', 'Bairro Canabarro', 1, 0, '372,10 mÂ²', '', 0, 0, 'R$ 85.000,00', '', 'Terreno medindo 372,10 mÂ² sendo 12,20m de frente e 30,50m de frente  fundos.', 5, 1, 'josantonio_thumb.jpg', 10, 1),
(969, 'Act075', 'Act075', 'act075', 'Bairro Canabarro', 1, 0, '495,00 mÂ²', '', 0, 0, 'R$ 75.000,00', '', 'Terreno de esquina medindo 495,00 mÂ² sendo 15,00m de frente e 33,00m de frente a fundos.', 5, 1, 'isoldirollofrua17dejunhoesquina_thumb.jpg', 10, 1),
(972, 'Act076', 'Act076', 'act076', 'Bairro Canabarro', 1, 0, '440,22 mÂ²', '', 0, 0, 'R$ 58.000,00', '', 'Terreno de 440,22mÂ² sendo 11,00m de frente e 34,00m de frente a fundos.', 5, 1, 'andre2_thumb.jpg', 10, 1),
(973, 'Act077', 'Act077', 'act077', 'Bairro Canabarro', 1, 0, '546,71mÂ²', '', 0, 0, 'R$ 80.000,00', '', 'Terreno de 546,71mÂ² sendo 13,60m de frente por 40,20m de frente a fundos', 5, 1, 'wanderlei_thumb.jpg', 10, 1),
(974, 'La086', 'La086', 'la086', 'Boa Vista do Sul', 7, 0, '02 hectares', '', 0, 0, 'R$ 150.000,00', 'PrÃ³ximo a Rota do Sol', 'Ãrea de terras medindo 2 hectares, com aÃ§ude e uma casa de madeira.', 3, 1, 'imagem017_thumb.jpg', 10, 1),
(975, 'Ca068', 'Ca068', 'ca068', 'Bairro Canabarro', 1, 1, '224,00 mÂ²', '190,00 mÂ²', 4, 2, 'R$ 265.000,00', '', 'Casa de alvenaria com 04 quartos, sala de estar e jantar, cozinha, banheiro, Ã¡rea de serviÃ§o toda cercada e garagem para 02 carros.', 2, 1, '20140122_143532_thumb.jpg', 10, 1),
(978, 'Ca082', 'Ca082', 'ca082', 'Bairro Canabarro', 7, 1, '720,00 mÂ²', '240,00 mÂ²', 0, 0, 'R$ 600.000,00', '', 'Terreno medindo 720,00 mÂ² com um prÃ©dio comercial medindo  240,00 mÂ²', 6, 1, '20140123_103107_thumb.jpg', 10, 1),
(980, 'La062', 'La062', 'la062', 'Bairro Centro Administrativo', 1, 1, '360', '155,46', 2, 1, 'R$ 350.000,00', '', 'Linda casa de alvenaria! Ã“timo estado e localizaÃ§Ã£o! \r\nPÃ¡tio cercado, quiosque, canil.', 2, 1, 'imagem029_thumb.jpg', 10, 1),
(981, 'La072', 'La072', 'la072', 'Bairro Centro Administrativo', 1, 0, '455,00', '70,00', 2, 0, 'R$ 265.000,00', '', 'Terreno de esquina, Ã“tima localizaÃ§Ã£o!!!', 2, 1, 'imagem021_thumb.jpg', 10, 1),
(983, 'Ca002', 'Ca002', 'ca002', 'Bairro Canabarro', 1, 0, '741,00 mÂ²', '90,00mÂ²', 3, 1, 'R$ 318.000,00', '', 'Dois terrenos sendo cada um de 370,50 mÂ² contendo uma casa mista com 03 dormitÃ³rios, sala de estar, cozinha Ã¡rea de serviÃ§o e garagem.', 5, 1, 'airo_thumb.jpg', 10, 1),
(986, 'Ca087', 'Ca087', 'ca087', 'Bairro Canabarro', 1, 1, '420,00mÂ²', '140,00mÂ²', 3, 1, 'R$ 370.000,00', 'Troca-se por apartamento em Porto Alegre ou CapÃ£o de Canoa.', 'Uma casa de alvenaria,com 3 dormitÃ³rios,sendo um suite,sala de estar,cozinha,banheiro,areÃ¡ de serviÃ§o,garagem e cercada.', 2, 1, 'dsc04198_thumb.jpg', 10, 1),
(989, 'Ca089', 'Ca089', 'ca089', 'Bairro Canabarro', 1, 1, '363,00mÂ²', '170,00mÂ²', 3, 1, 'R$ 275.000,00', '', 'Uma casa de alvenaria com 03 dormitÃ³rios, sala, cozinha,02 banheiros,Ã¡rea de serviÃ§o,garagem e cercada.', 2, 1, 'docjessica5002_thumb.jpg', 10, 1),
(990, 'La020', 'La020', 'la020', 'Bairro TeutÃ´nia', 1, 1, '330,00', '100,00', 2, 1, 'R$ 120.000,00', '', 'Casa mista, 02 dormitÃ³rios, sala, cozinha, banheiro, garagem, lavanderia.', 2, 1, 'imagem002_thumb.jpg', 10, 1),
(991, 'Ca052', 'Ca052', 'ca052', 'Bairro Canabarro', 1, 1, '430,86mÂ²', '60,00mÂ²', 3, 1, 'R$ 106.000,00', '', 'Uma casa de madeira com 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o,garagem e cercada.', 2, 1, 'fotocasaclaudia_thumb.jpg', 10, 1),
(993, 'Ca045', 'Ca045', 'ca045', 'Bairro Canabarro', 1, 1, '330,00mÂ²', '100,00mÂ²', 2, 1, 'R$ 265.000,00', '', 'Uma casa com 02 dormitÃ³rios, sala, cozinha, banheiro,garagem,Ã¡rea de serviÃ§o,alarme,toda imobiliada,com piscina e quiosque de 35mÂ² c/banheiro, alarme e cercada.', 2, 1, '20140217_095118_thumb.jpg', 10, 1),
(1000, 'La021', 'La021', 'la021', 'Rua MaurÃ­cio Cardoso', 1, 1, '546,80', '227,35', 0, 0, 'R$ 280.000,00', 'Ã“tima LocalizaÃ§Ã£o.', 'PrÃ©dio comercial de alvenaria.', 7, 1, 'imagem001_thumb.jpg', 10, 1),
(1003, 'La041', 'La041', 'la041', 'Bairro Centro Administrativo', 1, 1, '363,00', '89,85', 2, 1, 'R$ 120.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, garagem, lavanderia.\r\nPÃ¡tio cercado.', 2, 1, 'casasdealuguel005_thumb.jpg', 10, 1),
(1005, 'Ca022', 'Ca022', 'ca022', 'Bairro Canabarro', 1, 1, '363,00mÂ²', '170,00mÂ²', 3, 1, 'R$ 370.000,00', '', 'Casa de alvenaria com 03 dormitÃ³rios, sala de estar, cozinha, 02 banheiros, garagem, lavanderia, cercada, portÃ£o eletrÃ´nico.', 2, 1, '20140407_103337_thumb.jpg', 10, 1),
(1006, 'Ca037', 'Ca037', 'ca037', 'Bairro Canabarro', 1, 1, '369,00mÂ²', '70,00mÂ²', 2, 1, 'R$ 148.000,00', '', 'Casa de alvenaria com 02 dormitÃ³rios, sala de estar, cozinha, banheiro, Ã¡rea de serviÃ§o e garagem. ', 2, 1, '20140407_153753_ruaevaldoschaefer_thumb.jpg', 10, 1),
(1008, 'Alt047', 'Alt047', 'alt047', 'Rua GetÃºlio Vargas', 1, 1, '408,00', '', 0, 0, 'R$ 170.000,00', '', 'Terreno medindo 408,00mÂ², sendo 13,00m de frente', 5, 1, 'imagem001_thumb.jpg', 10, 1),
(1009, 'La095', 'La095', 'la095', 'Languiru', 1, 1, '', '', 0, 1, '', '', 'Sobrado nÂº 02, 02 dormitÃ³rios, sala, cozinha, banheiro, lavabo, lavanderia e garagem com churrasqueira. R$ 154.000,00. \r\n\r\nSobrado nÂº 03, 03 dormitÃ³rios, sala, cozinha, banheiro, lavabo, lavanderia e garagem com churrasqueira. R$ 165.000,00', 8, 1, 'imagem001_thumb.jpg', 10, 1),
(1021, 'All024', 'All024', 'all024', 'Languiru', 1, 1, '', '', 0, 0, 'R$ 550,00', '', '04 Salas Comerciais com banheiro.', 7, 2, 'dsc04972_thumb.jpg', 10, 1),
(1026, 'Alt004', 'Alt004', 'alt004', 'Rua Jorge Schneider', 1, 1, '431,28mÂ²', '', 0, 0, 'R$ 64.000,00', '', 'Terreno medindo 12,00m x 35,94m', 5, 1, 'imagem016_thumb.jpg', 10, 1),
(1027, 'La013', 'La013', 'la013', 'Bairro TeutÃ´nia', 1, 1, '', '', 3, 1, '', 'Sobrado 9 - R$ 210.000,00\r\nSobrado 8 e 7 - R$ 190.000,00\r\nSobrado 05 - R$ 150.000,00', 'Lindos sobrados, medindo aproximadamente 125,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, lavabo, lavanderia, garagem, churrasqueira, pÃ¡tio cercado, Ã³tima localizaÃ§Ã£o e acabamento.', 8, 1, 'imagem001_thumb.jpg', 10, 1),
(1028, 'La017', 'La017', 'la017', 'Rua Frederico Pott, bairro TeutÃ´nia', 1, 1, '330,00', '100,00', 2, 1, 'R$ 112.000,00', '', 'Casa mista, medindo 100,00mÂ², 02 dormitÃ³rios, sala, sala de jantar, cozinha, banheiro, garagem, churrasqueira, pÃ¡tio cercado. ', 2, 1, 'imagem002_thumb.jpg', 10, 1),
(1029, 'Alc085', 'Alc085', 'alc085', 'Bairro Canabarro', 1, 1, '', '37mÂ²', 0, 0, '', '', 'Sala comercial com banheiro.', 7, 2, 'dsc04959_thumb.jpg', 10, 1),
(1038, 'La042', 'La042', 'la042', 'Rua Frederico Pott, bairro TeutÃ´nia', 1, 1, '342,00', '94,43', 3, 1, 'R$ 165.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, sala de jantar, cozinha, 02 banheiros, lavanderia, garagem e churrasqueira.', 2, 1, 'imagem019_thumb.jpg', 10, 1),
(1039, 'La081', 'La081', 'la081', 'Rua Frederico Gerhardt, bairro Canabarro', 1, 1, '365,44', '70,00', 2, 1, '', '', 'Terreno em Ã³tima localizaÃ§Ã£o!', 2, 1, 'imagem016_thumb.jpg', 10, 1),
(1040, 'Alt042', 'Alt042', 'alt042', 'Loteamento SilvÃ©rio - bairro TeutÃ´nia', 1, 1, '', '', 1, 0, '', 'Lindos terrenos, planos em Ã³tima localizaÃ§Ã£o!', 'Terrenos de vÃ¡rios tamanhos, a partir de R$ 45.000,00', 5, 1, 'imagem002_thumb.jpg', 10, 1),
(1041, 'Alt002', 'Alt002', 'alt002', 'Rua LourenÃ§o Griebeler', 1, 1, '348,00', '', 0, 0, 'R$ 65.000,00', '', 'Terreno de esquina, sendo 12,00m x 29,00m', 5, 1, 'imagem014_thumb.jpg', 10, 1),
(1042, 'Alt003', 'Alt003', 'alt003', 'RS 419, km 1,8', 1, 1, '369,35', '', 0, 0, 'R$ 75.000,00', '', 'Terreno medindo 12,03m x 31,00m', 5, 1, 'imagem022_thumb.jpg', 10, 1),
(1043, 'La019', 'La019', 'la019', 'Rua Adolfo Hunsche, 292, bairro TeutÃ´nia', 1, 1, '363,00', '133,51', 3, 0, 'R$ 243.000,00', 'Terreno medindo 11,00m x 33,00m, pÃ¡tio cercado.', 'Casa de alvenaria, 03 dormitÃ³rios, sendo 02 suÃ­tes, sala, sala de jantar, cozinha, banheiro, lavanderia, churrasqueira, garagem para 02 carros', 2, 1, 'imagem001_thumb.jpg', 10, 1),
(1044, 'LA034', 'LA034', 'la034', 'Rua Arthur Reinaldo Graef, 39, bairro Languiru', 1, 1, '363,00', '96,00', 3, 1, 'R$ 180.000,00', '', 'Terreno medindo 11,00m x 33,00m', 2, 1, 'imagem018_thumb.jpg', 10, 1),
(1046, 'La079', 'La079', 'la079', 'Rua 7 de Norte', 1, 1, '', '', 1, 1, 'R$ 122.000,00', 'Apartamento Mobiliado!', 'Apartamento de 01 dormitÃ³rio, sala, cozinha, banheiro, lavanderia e box.', 1, 1, '100_4886_thumb.jpg', 10, 1),
(1047, 'Ca100', 'Ca100', 'ca100', 'Bairro Canabarro', 1, 1, '369,86 mÂ²', '70 mÂ²', 2, 1, 'R$ 148.000,00', '', 'Casa de alvenaria, 2 dormitÃ³rios, sala, cozinha, banheiro, garagem.', 2, 1, 'evertonluizdossantos_thumb.jpg', 10, 1),
(1051, 'Ca103', 'Ca103', 'ca103', 'Bairro Canabarro', 1, 1, '', '69 mÂ²', 2, 1, 'R$ 180.000,00', '', 'Casa de alvenaria, 2 dormitÃ³rios, sala, cozinha, banheiro, garagem, churrasqueira.', 2, 1, 'marciorosadossantos_thumb.jpg', 10, 1),
(1052, 'Ca104', 'Ca104', 'ca104', 'Bairro Canabarro', 1, 1, '330  mÂ²', '100  mÂ²', 3, 1, 'R$ 165.000,00', '', 'Casa  de alvenaria, 3 dormitÃ³rios, sala, cozinha, banheiro, garagem, cercada.', 2, 1, 'idaelsidakrommenauer_thumb.jpg', 10, 1),
(1056, 'Ca106', 'Ca106', 'ca106', 'Bairro Canabarro', 1, 1, '152', '363', 3, 0, 'R$ 212.000,00', '', 'Casa de alvenaria de 152 mÂ², 3 dormitÃ³rios, 2 banheiros, sala,sala de festas,cozinha, garagem.', 2, 1, 'eliasborgelt_thumb.jpg', 10, 1),
(1057, 'La096', 'La096', 'la096', 'Rua Edmundo Hauenstein, bairro Canabarro', 1, 0, '396,00', '60,00', 2, 0, 'R$ 128.000,00', 'Terreno de esquina.', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro.\r\nPÃ¡tio cercado', 2, 1, 'dsc05189_thumb.jpg', 10, 1),
(1058, 'Alt063', 'Alt063', 'alt063', 'Rua JosÃ© de Anchieta, bairro Canabarro', 1, 0, '363,00', '', 0, 0, 'R$ 58.000,00', '', 'Ã“tima localizaÃ§Ã£o!', 5, 1, 'dsc05191_thumb.jpg', 10, 1),
(1060, 'Terrenos', 'Terrenos', 'terrenos', 'Bairro Canabarro', 1, 1, '', '', 0, 0, '', '', 'Entrada de 30% e saldo em salÃ¡rios mÃ­nimos', 5, 1, 'lotsandra1_thumb.jpg', 10, 1),
(1061, 'Ca107', 'Ca107', 'ca107', 'CabriÃºva ', 3, 1, '2.376', '80', 0, 0, 'R$ 87.500,00', '', 'Ãrea de terras de 2.376mÂ², 6 terrenos, contendo uma casa de madeira de 80mÂ² e dois pavilhÃµes para caminhÃ£o.\r\n', 3, 1, '86_thumb.jpg', 10, 1),
(1062, 'La097', 'La097', 'la097', 'Languiru', 1, 1, '', '', 0, 0, '', 'Garagem coberta.', 'Sobrados de 02 dormitÃ³rios medindo 82,00mÂ², valor R$ 155.000,00\r\n\r\nSobrados de 03 dormitÃ³rios, medindo 95,00mÂ², valor R$ 176.000,00', 8, 1, 'dsc05847_thumb.jpg', 10, 1),
(1066, 'All008', 'All008', 'all008', 'Rua Major Bandeira ', 1, 1, '', '', 3, 1, 'R$ 500,00', '', 'Casa de 3 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, amplo pÃ¡tio nos fundos.', 2, 2, 'alug_thumb.jpg', 10, 1),
(1069, 'Alt037', 'Alt037', 'alt037', 'Rua Senhor dos Passos', 1, 1, '360,00', '', 0, 0, 'R$ 160.000,00', 'Ã“tima LocalizaÃ§Ã£o!', 'Terreno medindo 12,00 x 30,00', 5, 1, 'dsc05394_thumb.jpg', 10, 1),
(1070, 'La093', 'La093', 'la093', 'Rua Guilherme Brust', 1, 0, '174,15', '60,90', 2, 1, 'R$ 128.000,00', 'Garagem em construÃ§Ã£o, pÃ¡tio parcialmente fechado.', 'Terreno medindo 12,90m x 13,50m.\r\nDois dormitÃ³rios, sala, cozinha, banheiro e garagem.', 2, 1, 'dsc05399_thumb.jpg', 10, 1),
(1072, 'CA021', 'CA021', 'ca021', 'Bairro Canabarro', 1, 1, '838mÂ²', '426mÂ²', 4, 5, 'R$ 850.000,00', 'Aceita-se negociaÃ§Ãµes para trocas, carro, terrenos, apartamento ou casa.', 'A casa possui 02 suÃ­tes, salÃ£o de festas, escritÃ³rio, kiosque, piscina, portÃ£o eletrÃ´nico, cercada.', 2, 1, 'dsc05402_thumb.jpg', 10, 1),
(1073, 'CA079', 'CA079', 'ca079', 'Bairro Canabarro', 1, 1, '461,5mÂ²', '125mÂ²', 3, 1, 'R$ 310.000,00', 'Aceita carro de atÃ© R$ 40.000,00', 'Casa Possui uma suÃ­te, cercada, toda com chapa e gesso, churrasqueira giratÃ³ria fica no imÃ³vel.', 2, 1, 'dsc05388_thumb.jpg', 10, 1),
(1075, 'CA007', 'CA007', 'ca007', 'Bairro Canabarro', 1, 1, '', '100mÂ²', 2, 1, 'R$ 210.000,00', '', 'Apartamento,  2 dormitÃ³rios sendo 1 suÃ­te,sacada fundos, garagem.', 1, 1, 'astorkilpp_thumb.jpg', 10, 1),
(1076, 'CA109', 'CA109', 'ca109', 'Bairro Canabarro', 1, 1, '', '', 1, 0, 'R$ 140.000,00', '', 'Apartamentos de 02 e 03 dormitÃ³rios.\r\n2 Apartamentos de 70mÂ² R$ 140.000,00 \r\n2 Apartamentos de 100mÂ² R$ 190.000,00\r\n2 Apartamentos de 140mÂ² R$ 320.000,00', 1, 1, 'dsc05456_thumb.jpg', 10, 1),
(1079, 'CA108', 'CA108', 'ca108', 'Bairro Canabarro', 1, 1, '', '200mÂ²', 3, 2, 'R$ 339.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sendo 01 suÃ­te, garagem para 02 carros, 02 salas de estar, 01 sala de jantar, garagem, segundo piso em construÃ§Ã£o.', 2, 1, 'dsc05452_thumb.jpg', 10, 1),
(1080, 'Ca110', 'Ca110', 'ca110', 'Bairro Canabarro', 1, 1, '363mÂ²', '120mÂ²', 1, 0, 'R$ 265.000,00', 'Aceita troca por casa ou terreno.', 'Um terreno 11x33, contendo sala comercial na parte inferior de 60mÂ² e na parte superior apartamento de 60mÂ² em fase de construÃ§Ã£o.', 7, 1, 'dsc05455_thumb.jpg', 10, 1),
(1084, 'Ca018', 'Ca018', 'ca018', 'Bairro Canabarro', 1, 1, '363mÂ²', '224mÂ²', 3, 3, 'R$ 230.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, banheiro, 02 salas, cozinha, Ã¡rea de serviÃ§o, fogÃ£o campeiro, cercada.', 2, 1, 'palcavende_thumb.jpg', 10, 1),
(1085, 'Act078', 'Act078', 'act078', 'Rua Dom Pedro II, Bairro Canabarro', 1, 1, '13,5 x 60=810mÂ²', '', 0, 0, 'R$ 135.000,00', '', 'sÃ£o 03 terrenos com as mesmas medidas.', 5, 1, 'dsc05461_thumb.jpg', 10, 1),
(1086, 'Ca112', 'Ca112', 'ca112', 'Bairro Canabarro', 1, 1, '', '69,98mÂ²', 0, 0, 'R$ 170.000,00', '', 'CASA DE ALVENARIA, 02 DORMITÃ“RIOS, SALA, COZINHA, 02 BANHEIROS,ÃREA DE SERVIÃ‡O, GARAGEM, CERCADA, PORTÃƒO ELETRÃ”NICO.', 2, 1, 'dsc05462_thumb.jpg', 10, 1),
(1088, 'La099', 'La099', 'la099', 'Linha Frank', 4, 1, '462,00', '60,00', 2, 2, 'R$ 120.000,00', '', 'Casa de alvenaria, 60,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro. Garagem de madeira para 02 carros , lavanderia, churrasqueira.', 2, 1, 'dsc05473_thumb.jpg', 10, 1),
(1090, 'Alt044', 'Alt044', 'alt044', 'Bairro TeutÃ´nia', 1, 1, '364,00', '', 0, 0, 'R$ 58.000,00', '', 'Terreno medindo 364,00mÂ², sendo 13,00m x 28,00m', 5, 1, 'dsc05459_thumb.jpg', 10, 1),
(1091, 'Alt046', 'Alt046', 'alt046', 'Rua MaurÃ­cio Cardoso', 1, 1, '324,00', '', 0, 0, 'R$ 64.000,00', '', 'Terreno medindo 324,00mÂ², sendo 13,00m de frente por 27,00m de frente a fundos.', 5, 1, '20140815_161447_thumb.jpg', 10, 1),
(1093, 'All034', 'All034', 'all034', 'Via LÃ¡ctea', 1, 1, '', '300mÂ²', 0, 0, 'R$ 2.640,00', '', 'PrÃ©dio Comercial DE 300mÂº.', 6, 2, 'clipboard02_thumb.jpg', 10, 1),
(1094, 'Ca029', 'Ca029', 'ca029', 'Bairro Canabarro', 1, 1, '100,00', '413,00', 2, 2, 'R$ 190.000,00', 'Nos fundos tem uma casa de madeira de 40mÂ².', 'Linda casa de alvenaria medindo 100,00mÂ², 02 dormitÃ³rios, closet, banheiro, sala, sala de jantar, cozinha, Ã¡rea de serviÃ§o, garagem para 02 carros, pÃ¡tio fechado.', 2, 1, 'dscn2339_thumb.jpg', 10, 1),
(1095, 'Ca051', 'Ca051', 'ca051', 'Morro Harmonia', 1, 1, '400mÂ²', '112mÂ²', 3, 1, 'R$ 127.200,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, despensa, banheiro, 02 Ã¡reas, garagem e pÃ¡tio cercado.\r\n', 2, 1, 'silvialagemann_thumb.jpg', 10, 1),
(1096, 'Ca102', 'Ca102', 'ca102', 'Linha Harmonia', 1, 1, '5.000mÂ²', '', 0, 0, 'R$ 40.000,00', '', 'Ãrea de terras na Linha Harmonia, 1/2 hectare.', 5, 1, 'palcavende_thumb.jpg', 10, 1),
(1097, 'La100', 'La100', 'la100', 'Rua Pedro Schneider, Languiru', 1, 1, '443,30', '118,83', 0, 0, 'R$ 400.000,00', '', 'Ã“tima localizaÃ§Ã£o! Terreno medindo 15,70m de frente.', 2, 1, 'dsc05510_thumb.jpg', 10, 1),
(1098, 'Lote 02', 'Lote 02', 'lote_02', 'Linha Ribeiro', 1, 1, '396,00mÂ²', '', 0, 0, 'R$ 70.000,00', '', 'Terreno localizado em loteamento na Linha Ribeira, em fase de conclusÃ£o.', 5, 1, 'lotsandra1_thumb.jpg', 10, 1),
(1099, 'La101', 'La101', 'la101', 'Rua Guilherme Brust, Bairro Languiru', 1, 1, '', '63,00', 2, 1, 'R$ 128.000,00', 'Ã“tima LocalizaÃ§Ã£o e Acabamento!', 'Lindos Sobrados, 02 dormitÃ³rios, sala, cozinha, banheiro, lavanderia.', 8, 1, 'folderresidencialsahana_frente_thumb.jpg', 10, 1),
(1100, 'La001', 'La001', 'la001', 'Rua Fridoldo Altevogt, Bairro Alesgut', 1, 1, '36,00', '98,00', 3, 0, 'R$ 160.000,00', 'Terreno de esquina, todo cercado.', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, garagem com churrasqueira.', 2, 1, 'dsc05513_thumb.jpg', 10, 1),
(1102, 'Alt008', 'Alt008', 'alt008', 'Rua 3 de Outubro, bairro Languiru', 1, 1, '300,00', '', 0, 0, 'R$ 180.000,00', '', 'Terreno em Ã³tima localizado!', 5, 1, 'dsc05519_thumb.jpg', 10, 1),
(1103, 'Alt014', 'Alt014', 'alt014', 'Rua Osvaldo Geib, Alesgut', 1, 1, '382,80', '', 0, 0, 'R$ 95.000,00', '', 'Lindos Terrenos medindo 11,00m de frente por 34,80m de frente a fundos.', 5, 1, 'dsc05522_thumb.jpg', 10, 1),
(1104, 'Ca053', 'Ca053', 'ca053', 'Rua Walter Sippel', 1, 1, '360', '294', 2, 1, 'R$ 370.000,00', '', 'Casa de alvenaria, 02 pisos mais quiosque.', 2, 1, 'dsc05527_thumb.jpg', 10, 1),
(1105, 'Ca073', 'Ca073', 'ca073', 'Rua 16 de Abril, Canabarro', 1, 0, '300', '206', 2, 1, 'R$ 220.000,00', '', 'casa de 02 pisos, parte inferior comercial e superior residencial.', 2, 1, 'dsc05529_thumb.jpg', 10, 1),
(1106, 'Act079', 'Act079', 'act079', 'Avenida 01 Leste', 1, 1, '957', '', 0, 0, 'R$ 235.000,00', '', 'Bairro Canabarro', 5, 1, 'dsc05525_thumb.jpg', 10, 1),
(1108, 'Ca115', 'Ca115', 'ca115', 'BR 386 KM 245 a 80mts do Trevo de TeutÃ´nia', 6, 1, '1418', '172', 2, 1, 'R$ 275.600,00', '', 'Casa de Alvenaria, 02 suÃ­tes, banheiro social, lavanderia, 02 churrasqueiras, garagem, sala, cozinha, terraÃ§o.', 2, 1, '20131218_185028_thumb.jpg', 10, 1),
(1109, 'Ca114', 'Ca114', 'ca114', 'Rua Bruno Driemeier, nÂº 62', 1, 1, '342', '110', 2, 1, 'R$ 189.000,00', 'Aceita troca, terreno de atÃ© R$ 100.000,00', 'Casa de alvenaria, 02 quartos sendo 1 suÃ­te, sala, cozinha, garagem,lavanderia, cercada com grade.', 2, 1, 'edsonventura_thumb.jpg', 10, 1),
(1110, 'Ca116', 'Ca116', 'ca116', 'Linha Geralda', 1, 1, '1440', '100', 0, 0, 'R$ 100.000,00', '', 'Aceita troca, casa em Imigrante.', 2, 1, 'arnildozagave_thumb.jpg', 10, 1),
(1111, 'La102', 'La102', 'la102', 'Bairro TeutÃ´nia', 1, 1, '120,99', '', 2, 0, 'R$ 85.000,00', '', 'Sobrado de 02 dormitÃ³rios', 8, 1, 'dsc05537_thumb.jpg', 10, 1),
(1112, 'La014', 'La014', 'la014', 'Bairro Canabarro', 1, 1, '', '76,68', 2, 1, 'R$ 220.000,00', 'Ã“tima localizaÃ§Ã£o.\r\nMÃ³veis do dormitÃ³rio de casal sob medida.', 'Lindo sobrado, 02 dormitÃ³rios, banheiro, sala, cozinha, lavabo, lavanderia, churrasqueira e garagem.', 8, 1, 'dsc05549_thumb.jpg', 10, 1),
(1114, 'Alt018', 'Alt018', 'alt018', 'Bairro Canabarro', 1, 1, '4.000,00', '', 0, 0, 'R$ 260.000,00', '', 'Ãrea de Terras medindo aproximadamente 4.000,00mÂ²', 5, 1, 'dsc05564_thumb.jpg', 10, 1),
(1115, 'La047', 'La047', 'la047', 'Bairro Languiru', 1, 1, '925,00', '120,00', 3, 1, 'R$ 690.000,00', 'Ã“tima LocalizaÃ§Ã£o! PrÃ³ximo ao hospital Ouro Branco.', 'Terreno medindo 925,00mÂ², sendo 23,30m de frente por 39,70m de frente a fundos.', 2, 1, 'dsc05533_thumb.jpg', 10, 1),
(1116, 'La063', 'La063', 'la063', 'Bairro Languiru', 1, 1, '363,00', '260,00', 4, 2, 'R$ 650.000,00', 'Ã“tima LocalizaÃ§Ã£o! Centro do bairro Languiru!\r\nSala comercial e casa de alvenaria.', 'Sala comercial medindo 93,00mÂ².\r\nCasa de alvenaria medindo aproximadamente 260,00mÂ², 04 dormitÃ³rios, 03 banheiros, sala, sala de jantar, 02 cozinhas, pÃ¡tio cercado.', 2, 1, 'dsc05631_thumb.jpg', 10, 1),
(1117, 'La088', 'La088', 'la088', 'Bairro TeutÃ´nia', 1, 1, '225,00', '96,00', 2, 2, 'R$ 155.000,00', 'Aberturas de madeira, piso e telhas de cerÃ¢mica.', 'Casa de alvenaria medindo 96,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, garagem para 02 carros, lavanderia,churrasqueira.', 2, 1, '1_thumb.jpg', 10, 1),
(1118, 'La066', 'La066', 'la066', 'Bairro Languiru', 1, 1, '330,00', '93,00', 2, 2, 'R$ 197.000,00', 'Aberturas de madeira Baiana, piso porcelanato.', 'Casa de alvenaria, medindo 93,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, garagem para 02 carros.', 2, 1, 'wp_20141022_001_thumb.jpg', 10, 1),
(1119, 'La103', 'La103', 'la103', 'Bairro Languiru', 1, 1, '560,00', '60,00', 2, 1, 'R$ 180.000,00', 'Ã“tima localizaÃ§Ã£o no bairro Languiru!', 'Terreno medindo 16,00 x 35,00 = 560,00mÂ²\r\nCasa mista medindo 60,00mÂ²', 2, 1, 'dsc05519_thumb.jpg', 10, 1),
(1120, 'La104', 'La104', 'la104', 'Bairro Languiru', 1, 1, '137,95', '', 3, 2, 'R$ 350.000,00', 'Porcelanato nasala, cozinha, garagem e banheiros.\r\nDormitÃ³rios e escada com laminado.\r\nTelha de cerÃ¢mica.\r\nEsquadrias em madeira.\r\nAcabamento classe A', 'Lindos Sobrados 03 dormitÃ³rios (01 suÃ­te com sacada), garagem coberta para 02 carros, localizaÃ§Ã£o privilegiada.\r\n', 8, 1, 'placavillajardim1_thumb.jpg', 10, 1),
(1121, 'La105', 'La105', 'la105', 'Bairro Languiru', 1, 1, '368,50', '100,00', 0, 0, 'R$ 600.000,00', 'Terreno de esquina medindo 368,50mÂ², sendo 22,00m de frente com a Rua 3 de Outubro.', 'Sala comercial medindo 100,00mÂ², 04 banheiros, e uma casa de madeira nos fundos do terreno.\r\nTerreno de esquina medindo 368,50mÂ², sendo 22,00m de frente com a Rua 3 de Outubro.', 7, 1, 'dsc05640_thumb.jpg', 10, 1),
(1122, 'La106', 'La106', 'la106', 'Bairro Languiru', 1, 1, '312,00', '110,02', 3, 0, 'R$ 255.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala cozinha, banheiro, garagem, lavanderia, churrasqueira.\r\nPÃ¡tio cercado, interfone, alarme, portÃ£o eletrÃ´nico.', 2, 1, 'dsc05650_thumb.jpg', 10, 1),
(1124, 'La105', 'La105', 'la105', 'Bairro Canabarro', 1, 1, '363,00', '74,00', 3, 1, 'R$ 65.000,00', '', 'Terreno de esquina, com casa de alvenaria semi acabada, 03 dormitÃ³rios, sala, cozinha, banheiro, garagem, churrasqueira.', 2, 1, '1_thumb.jpg', 10, 1),
(1126, 'Ca118', 'Ca118', 'ca118', 'Rua Frederico Leopoldo Gerhardt 514, Bairro Canabarro', 1, 1, '363', '140', 2, 1, 'R$ 222.600,00', '', 'Casa de 02 pisos sendo de 70mÂ² em cada um,  2 quartos, sala, cozinha, embaixo cozinha, garagem, lavanderia, pÃ¡tio cercado.', 2, 1, 'casamagnus_thumb.jpg', 10, 1),
(1127, 'Ca119     ', 'Ca119     ', 'ca119_____', 'Rua 07 de setembro, nÂº 1060, Bairro Canabarro', 1, 1, '368,50', '70', 3, 1, 'R$ 135.000,00', '', 'Casa de 70mÂ², 3 quartos, sala, cozinha, banheiro e garagem.', 2, 1, 'dsc05658_thumb.jpg', 10, 1),
(1128, 'Ca120', 'Ca120', 'ca120', 'Rua 16 de Abril, nÂº 290, bairro Canabarro', 1, 1, '', '130', 3, 1, 'R$ 212.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala de jantar, cozinha, banheiro, lavanderia, garagem, cercada, portÃµes eletrÃ´nicos, canil e depÃ³sito.', 2, 1, 'andreiabottezzini_thumb.jpg', 10, 1),
(1129, 'Ca122', 'Ca122', 'ca122', 'Rua 1 A1, bairro Canabarro', 1, 1, '357,61', '', 0, 0, 'R$ 80.000,00', '', 'Terreno 12 x 29,8\r\nNa 2Âª rua atrÃ¡s da Ioga.', 5, 1, 'karinadevargas_thumb.jpg', 10, 1),
(1130, 'Ca123', 'Ca123', 'ca123', 'Rua Guilherme Schneider Sobrinho, bairro Canabarro', 1, 1, '404,03', '', 0, 0, 'R$ 69.000,00', '', 'um terreno 13,95 x 28,92', 5, 1, 'valdecirroyer_thumb.jpg', 10, 1),
(1131, 'Ca124', 'Ca124', 'ca124', 'Rua Dom Pedro II, Bairro Canabarro', 1, 1, '2700,', '', 0, 0, 'R$ 1.272.000,00', '', 'Ao lado do Fritz AutomÃ³veis', 5, 1, 'dsc05664_thumb.jpg', 10, 1),
(1132, 'Ca125', 'Ca125', 'ca125', 'Rua Edvino Horst, Bairro Canabarro', 1, 1, '357', '', 0, 0, 'R$ 74.000,00', '', 'Entra da Avenida na rua ao lado na MecÃ¢nica Jantsch, 3Âº terreno a direita', 5, 1, 'dsc05662_thumb.jpg', 10, 1),
(1136, 'All022', 'All022', 'all022', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 800,00', '', 'Sala comercial de 80mÂº com banheiro.', 7, 2, 'all022_thumb.jpg', 10, 1),
(1137, 'All032', 'All032', 'all032', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 1.000,00', '', 'Sala comercial medindo 40mÂº com banheiro.', 7, 2, 'all032_thumb.jpg', 10, 1),
(1138, 'Ca126', 'Ca126', 'ca126', 'Bairro Canabarro', 1, 1, '', '', 3, 0, 'R$ 95.400,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada.', 2, 1, 'dsc05700_thumb.jpg', 10, 1),
(1140, 'Alt064', 'Alt064', 'alt064', 'Bairro TeutÃ´nia', 1, 1, '360,00', '', 0, 0, 'R$ 53.000,00', '', 'Terreno plano, medindo 12,00m de frente por 30,00 de frente a fundos.', 5, 1, 'dsc05724_thumb.jpg', 10, 1),
(1144, 'All042', 'All042', 'all042', 'Bairro TeutÃ´nia', 1, 1, '', '', 2, 1, 'R$ 780,00', '', 'Sobrado, 02 dormitÃ³rios, sala, cozinha, 01 banheiro, dispensa, churrasqueira, garagem.\r\n', 8, 2, '20141210_110019_thumb.jpg', 10, 1),
(1146, 'All039', 'All039', 'all039', 'Languiru', 1, 1, '', '', 2, 1, '', '', 'Apartamento, 130mÂ², sala com sacada, cozinha, lavanderia, churrasqueira, 02 dormitÃ³rios com sacada em cada um, banheiro, garagem.\r\n', 1, 2, 'apart_thumb.jpg', 10, 1),
(1147, 'All031', 'All031', 'all031', 'Bairro Alesgut', 1, 1, '', '', 2, 0, 'R$ 445,00', 'R$70,00 Cond.', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.\r\n', 1, 2, '100_3034_thumb.jpg', 10, 1),
(1148, 'All034', 'All034', 'all034', 'Bairro TeutÃ´nia', 1, 1, '', '', 0, 0, '', '', 'Ampla sala comercial de 300mÂ² com banheiro.\r\n', 7, 2, 'cam00869_thumb.jpg', 10, 1),
(1149, 'All038', 'All038', 'all038', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 450,00', '', 'Sala comercial de 16,50mÂ² com banheiro.', 7, 2, 'sala_thumb.jpg', 10, 1),
(1150, 'All019', 'All019', 'all019', 'Bairro Languiru', 1, 1, '', '', 0, 0, 'R$ 827,00', '', 'Sala comercial com banheiro.\r\n', 7, 2, '100_4726_thumb.jpg', 10, 1),
(1151, 'La108', 'La108', 'la108', 'PoÃ§o das Antas', 12, 1, '1 hectare', '350,00mÂ²', 4, 2, '', '', 'Linda Ã¡rea de terras medindo 01 hectare, com uma casa de alvenaria, medindo aproximadamente 350,00mÂ², 04 dormitÃ³rios, sendo 01 suÃ­te, 02 salas, 02 cozinhas, sala de jogos, 03 banheiros, garagem, lavanderia e churrasqueira.\r\nDivisa com arroio.', 3, 1, 'dsc05735_thumb.jpg', 10, 1),
(1152, 'Ca076', 'Ca076', 'ca076', 'Bairro Languiru', 1, 1, '', '', 2, 2, 'R$ 450.000,00', 'Aceita carro.', 'Cobertura duplex, com 02 dormitÃ³rios, 03 banheiros, 02 cozinhas, 01 sala de jantar e social, 01 sala de estar, lavanderia, Ã¡rea aberta e piscina.\r\nPrÃ©dio/Ã¡rea condominial: SalÃ£o de festas e Ã¡rea aberta de 500mÂ² com piscina, 02 elevadores-social e serviÃ§o, portaria 24 hrs e circuito de cÃ¢mera interno e externo c/ visualizaÃ§Ã£o individual em cada apartamento.', 1, 1, 'cpiadeca076_thumb.jpg', 10, 1),
(1153, 'Ca127', 'Ca127', 'ca127', 'Bairro Canabarro', 1, 1, '', '', 4, 1, 'R$ 212.000,00', '', 'Casa de alvenaria, 04 dormitÃ³rios, sala, 02 cozinha, 02 banheiros, garagem.\r\n', 2, 1, 'dsc05753_thumb.jpg', 10, 1),
(1155, 'Ca128', 'Ca128', 'ca128', 'Bairro Canabarro', 1, 1, '', '330,00mÂ²', 4, 2, 'R$ 494.400,00', '', 'Cobertura de 330,00mÂ², 01 suÃ­te, 03 quartos, escritÃ³rio, sala para 02 ambientes, cozinha, Ã¡rea de serviÃ§o, banheiro, quiosque com salÃ£o de festas, ', 2, 1, 'dsc05771_thumb.jpg', 10, 1),
(1156, 'Ca129', 'Ca129', 'ca129', 'Paverama', 1, 1, '2.045', '170', 0, 0, 'R$ 424.000,00', '', 'Ãrea de 2.045mÂ², contendo uma casa de 170mÂ² e um galpÃ£o.', 3, 1, 'dsc05727_thumb.jpg', 10, 1),
(1157, 'Ca129', 'Ca129', 'ca129', 'Paverama', 3, 1, '2045', '170', 0, 0, 'R$ 424.000,00', '', 'Ãrea de 2.045mÂ², contendo uma casa de 170mÂ² e um galpÃ£o.', 3, 1, 'dsc05727_thumb.jpg', 10, 1),
(1158, 'La022', 'La022', 'la022', 'Bairro TeutÃ´nia', 1, 1, '', '', 2, 0, '', '', 'Casa de alvenaria medindo 50,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, lavanderia.\r\n\r\nCasa de esquina R$ 122.000,00\r\nAs demais casas R$ 115.000,00', 8, 1, '2015-01-0914_thumb.jpg', 10, 1),
(1159, 'La032', 'La032', 'la032', 'Bairro Alesgut', 1, 1, '360,00', '100,00', 3, 2, 'R$ 138.000,00', '', 'Casa de alvenaria medindo 100,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, lavanderia.', 2, 1, 'dsc05844_thumb.jpg', 10, 1),
(1160, 'La055', 'La055', 'la055', 'Linha Germano', 1, 1, '100,00', '15.000,00', 3, 0, 'R$ 240.000,00', '', 'Linda Ã¡rea de terras medindo 1,5 hectares, com mato de acÃ¡cia e eucalipto, Ã¡rvores frutÃ­feras, casa de alvenaria medindo 100,00mÂ², 03 dormitÃ³rios, sala, sala de jantar, cozinha, banheiro, Ã¡rea de serviÃ§os.\r\nÃ“tima LocalizaÃ§Ã£o!', 3, 1, 'dsc05855_thumb.jpg', 10, 1),
(1161, 'Alt020', 'Alt020', 'alt020', 'Bairro Canabarro', 1, 1, '300,00', '', 0, 0, 'R$ 64.000,00', '', 'Terreno medindo 12,00m de frente por 25,00m de frente a fundos.\r\nÃ“tima localizaÃ§Ã£o!', 5, 1, '20150129_170641_thumb.jpg', 10, 1),
(1162, 'La065', 'La065', 'la065', 'Bairro Alesgut', 1, 1, '363,00', '168,00', 3, 2, 'R$ 220.000,00', '', 'Casa de alvenaria medindo 112,00mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, garagem. churrasqueira, lavanderia.\r\nEspaÃ§o para lancheria, com 02 banheiros, churrasqueira.\r\nPÃ¡tio cercado, terreno de esquina.', 2, 1, '20150130_153934_thumb.jpg', 10, 1),
(1163, 'Ca130', 'Ca130', 'ca130', 'Bairro Canabarro', 1, 1, '', '', 0, 0, 'R$ 135.000,00', 'PrÃ³ximo a Beira Rio', 'Terreno medindo 11x33 contendo uma casa de 80mÂ² mista.', 2, 1, 'dsc05732_thumb.jpg', 10, 1),
(1165, 'Ca132', 'Ca132', 'ca132', 'Bairro Canabarro', 1, 1, '', '', 4, 0, 'R$ 150.000,00', '', 'Terreno medindo 11x33, contendo uma casa mista, 04 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada.', 2, 1, 'dsc05882_thumb.jpg', 10, 1),
(1166, 'Alt021', 'Alt021', 'alt021', 'Bairro Centro Administrativo', 1, 1, '346,80', '', 0, 0, 'R$ 78.000,00', '', 'Terreno medindo 12,00n x 28,90m.', 5, 1, 'dsc06174_thumb.jpg', 10, 1),
(1167, 'Alt022', 'Alt022', 'alt022', 'Bairro Boa Vista', 1, 1, '438,23', '', 0, 0, 'R$ 55.000,00', '', 'Lote 05 medindo 428,32mÂ².\r\nLote 06 medindo 438,23mÂ².\r\n', 2, 1, 'dsc06172_thumb.jpg', 10, 1),
(1168, 'Alt022', 'Alt022', 'alt022', 'Bairro Boa Vista', 1, 1, '438,23', '', 0, 0, 'R$ 55.000,00', '', 'Lote 05 medindo 428,32mÂ².\r\nLote 06 medindo 438,28mÂ².', 5, 1, 'dsc06171_thumb.jpg', 10, 1),
(1169, 'Alt027', 'Alt027', 'alt027', 'Languiru', 1, 1, '1.140,00', '', 0, 0, 'R$ 75.000,00', '', 'Terreno medindo 30,00m x 38,00m', 5, 1, 'dsc06183_thumb.jpg', 10, 1),
(1171, 'La109', 'La109', 'la109', 'Bairro Languiru', 1, 1, '', '156,00', 2, 0, 'R$ 320.000,00', '', 'Apartamento medindo 156,00mÂ², 03 dormitÃ³rios, sendo 01 suÃ­te, sala, cozinha, banheiro, churrasqueira, sacada, 02 box. ', 1, 1, 'dsc06177_thumb.jpg', 10, 1),
(1174, 'All047', 'All047', 'all047', 'Evaldo Hilgemann,esq.25 de Julho ED.MAESTRO', 1, 1, '', '', 2, 1, 'R$ 650,00', 'Mais R$100,00 Condominio', 'Apartamento com 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, sacada c/churrasqueira, box garagem', 1, 2, 'fotomaestro_thumb.jpg', 10, 1),
(1175, 'Alc129', 'Alc129', 'alc129', 'Fazenda SÃ£o Jose', 1, 1, '', '', 2, 1, 'R$ 550,00', '', 'Sobrado com 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 8, 2, 'alc129_thumb.jpg', 10, 1),
(1178, 'All044', 'All044', 'all044', 'Rua Evaldo Hilgemann. nÂº522', 1, 1, '', '', 3, 1, '', '', 'Apartamentos com 02 e 03 dormitÃ³rios, sacada, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 1, 2, '20698_1565821327028046_7360057274918529039_n_thumb.jpg', 10, 1),
(1181, 'Alt032', 'Alt032', 'alt032', 'Bairro Languiru', 1, 1, '303,77', '', 0, 0, 'R$ 57.000,00', '', 'Terreno medindo 24,50m x 12,40m ', 5, 1, 'dsc06613_thumb.jpg', 10, 1),
(1184, 'La112', 'La112', 'la112', 'Bairro Languiru', 1, 1, '140,00', '273,00', 2, 3, '', '', 'Casa de alvenaria, medindo 132,73mÂ², sala, sala de jantar, cozinha, banheiro, lavanderia, garagem para 03 carros, churrasqueira.\r\nPÃ¡tio cercado, portÃ£o eletrÃ´nico.', 2, 1, 'dsc06616_thumb.jpg', 10, 1),
(1185, 'Alt030', 'Alt030', 'alt030', 'Bairro Canabarro', 1, 1, '363,48mÂ²', '', 0, 0, 'R$ 80.000,00', '', 'Terreno medindo 12,00m de frente por 30,29m de frente a fundos.\r\nÃ“tima localizaÃ§Ã£o!', 5, 1, 'dsc06393_thumb.jpg', 10, 1),
(1191, 'La107', 'La107', 'la107', 'Bairro TeutÃ´nia', 1, 1, '252,00', '388,75', 3, 2, '', '', 'Linda casa de alvenaria, medindo 252,00mÂ², 03 dormitÃ³rios sendo 01 suÃ­te com banheira, banheiro social, lavabo, sala de estar, sala de jantar, cozinha, espaÃ§o gourmet, garagem para 02 carros, pÃ¡tio cercado, piso porcelanato e laminado, gesso no teto, aquecimento a gÃ¡s, mÃ³veis sob medida.\r\nÃ“tima localizaÃ§Ã£o!', 2, 1, '10_thumb.jpg', 10, 1),
(1192, 'La113', 'La113', 'la113', 'Bairro Canabarro', 1, 1, '87,00', '', 2, 1, 'R$ 212.000,00', '', 'Apartamento medindo 87,00mÂ², 02 dormitÃ³rios, sala de estar, sala de jantar, cozinha, banheiro, lavanderia, sacada com churrasqueira e garagem. ', 1, 1, 'dsc06688_thumb.jpg', 10, 1),
(1193, 'Ca113', 'Ca113', 'ca113', 'Bairro Canabarro', 1, 1, '300mÂ²', '145mÂ²', 4, 1, 'R$ 265.000,00', '', 'Terreno 300mÂ², com casa de alvenaria, 04 dormitÃ³rios, sala, cozinha , 02 banheiros, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'dsc06657_thumb.jpg', 10, 1),
(1194, 'Ca140', 'Ca140', 'ca140', 'Bairro Canabarro', 1, 1, '', '80mÂ²', 2, 1, 'R$ 138.000,00', '', 'Casa mista de 80mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, garagem, cercada, portÃ£o eletrÃ´nico.', 2, 1, 'dsc06658_thumb.jpg', 10, 1),
(1195, 'Ca139', 'Ca139', 'ca139', 'Bairro Canabarro', 1, 1, '', '57,34mÂ²', 2, 0, 'R$ 75.000,00', '', 'Casa geminada, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada.', 2, 1, 'dsc06399_thumb.jpg', 10, 1),
(1196, 'La114', 'La114', 'la114', 'Bairro Canabarro', 1, 1, '90,75', '', 3, 1, 'R$ 159.000,00', '', 'Sobrado medindo 90,75mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, lavabo, Ã¡rea de serviÃ§os, churrasqueira e garagem.\r\nMÃ³veis da cozinha sob medida.', 1, 1, '1_thumb.jpg', 10, 1),
(1197, 'La114', 'La114', 'la114', 'Bairro Canabarro', 1, 1, '90,75', '', 3, 1, 'R$ 159.000,00', '', 'Sobrado medindo 90,75mÂ², 03 dormitÃ³rios, sala, cozinha, banheiro, lavabo, Ã¡rea de serviÃ§os, churrasqueira e garagem.\r\nMÃ³veis da cozinha sob medida.', 8, 1, '11_thumb.jpg', 10, 1),
(1198, 'Alt038', 'Alt038', 'alt038', 'Bairro Canabarro', 1, 1, '', '396,00', 0, 0, 'R$ 148.000,00', '', 'Terreno medindo 12,00m de frente, por 33,00m de frente a fundos. Ã“tima LocalizaÃ§Ã£o, 01 quadra da Rua Carlos Arnt.', 5, 1, '1_thumb.jpg', 10, 1),
(1202, 'La115', 'La115', 'la115', 'Bairro TeutÃ´nia', 1, 1, '56,00', '405,00', 2, 0, 'R$ 132.000,00', '', 'Casa de alvenaria, medindo 56,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro. PÃ¡tio parcialmente cercado.', 2, 1, '10_thumb.jpg', 10, 1),
(1204, 'La116', 'La116', 'la116', 'Bairro Canabarro', 1, 1, '42,00', '210,00', 1, 0, 'R$ 100.000,00', '', 'Casa de alvenaria, medindo 42,00mÂ², 01 dormitÃ³rio, sala, cozinha, banheiro.', 2, 1, '01_thumb.jpg', 10, 1),
(1205, 'La117', 'La117', 'la117', 'Bairro Alesgut', 1, 1, '90,00', '390,00', 3, 1, 'R$ 160.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, lavanderia, quiosque, garagem e churrasqueira.\r\nPÃ¡tio cercado.', 2, 1, '01_thumb.jpg', 10, 1),
(1209, 'Ca05', 'Ca05', 'ca05', 'Bairro Canabarro', 1, 1, '', '80 MÂ²', 3, 1, 'R$ 180.000,00', '', 'Casa em alvenaria, 3 quartos, sala, cozinha, banheiro, garagem, cercada.', 2, 1, 'nair_thumb.jpg', 10, 1),
(1210, 'Ca025', 'Ca025', 'ca025', 'Bairro Canabarro', 1, 1, '11x33', '60mÂ²', 1, 2, 'R$ 250.000,00', '', 'casa em alvenaria, 1 dormitÃ³rio, sala, cozinha, banheiro, garagem para dois carros.', 2, 1, 'nair2001_thumb.jpg', 10, 1),
(1211, 'Ca114', 'Ca114', 'ca114', 'Bairro Canabarro', 1, 1, '', '125mÂ²', 3, 1, 'R$ 116.000,00', '', 'Casa de madeira com 3 dormitÃ³rios, sala, cozinha, 1 banheiro, Ã¡rea de serviÃ§o, garagem aberta, pÃ¡tio fechado', 2, 1, 'nair3_thumb.jpg', 10, 1),
(1212, 'Ca114', 'Ca114', 'ca114', 'Bairro Canabarro', 1, 1, '', '125mÂ²', 3, 1, 'R$ 116.000,00', '', 'Casa em alvenaria com 3 dormitÃ³rios, sala, cozinha, 1 banheiro, Ã¡rea de serviÃ§o, garagem aberta, pÃ¡tio fechado', 2, 1, 'nair3_thumb.jpg', 10, 1),
(1213, 'Ca061', 'Ca061', 'ca061', 'Bairro Canabarro', 1, 1, '702mÂ²', '98mÂ²', 3, 1, 'R$ 360.000,00', '', 'Casa em alvenaria, com 3 dormitÃ³rios, sala, cozinha, banheiro, lavabo, toda com chapa.', 2, 1, 'nair4_thumb.jpg', 10, 1),
(1214, 'Ca090', 'Ca090', 'ca090', 'Bairro Canabarro', 1, 1, '20x24', '80mÂ²', 3, 0, 'R$ 138.000,00', '', 'Casa mista com 3 dormitÃ³rios, sala, cozinha, banheiro.', 2, 1, 'nair5_thumb.jpg', 10, 1),
(1216, 'All 072', 'All 072', 'all_072', 'Bairro Languiru', 1, 1, '', '', 2, 1, 'R$ 600,00', '', 'Apartamento para locaÃ§Ã£o, aproximadamente 70 mÂ², dois quartos, sala, cozinha, banheiro e Ã¡rea de serviÃ§o.\r\nMais 29,00 taxa de Ã¡gua', 1, 2, 'lw1_thumb.jpg', 10, 1),
(1220, 'La 118', 'La 118', 'la_118', 'Boa Vista', 1, 1, '', '270,00mÂ²', 0, 1, 'R$ 530.000,00', '', 'PrÃ©dio medindo 136,00mÂ², parte de baixo comercial, 3 banheiros, sala, confeitaria e cozinha.\r\nParte superior, 3 dormitÃ³rios, sendo 1 suÃ­te, banheiro, sala, cozinha, varanda, lavanderia, churrasqueira(rotativa)sacada.Medindo 130,00mÂ²', 6, 1, 'elschadai1_thumb.jpg', 10, 1),
(1221, 'La 120', 'La 120', 'la_120', 'Bairro Languiru', 1, 1, '13,00x45,00=585mÂ²', '', 1, 1, 'R$ 235.000,00', '', 'Casa mista, 1 dormitÃ³rio, sala, cozinha, banheiro, garagem com churrasqueira. Casa de madeira com garagem de alvenaria', 2, 1, '20150601_104804_thumb.jpg', 10, 1),
(1222, 'La 119', 'La 119', 'la_119', 'Bairro Alesgut', 1, 1, '12x30=360,00mÂ²', '54,23', 1, 0, 'R$ 150.000,00', 'Casa em alvenaria, com 1 dormitÃ³rio, sala, cozinha, banheiro. Tem estrutura para 3 pisos.', 'Casa em alvenaria, com 1 dormitÃ³rio, sala, cozinha, banheiro. Tem estrutura para 3 pisos.', 2, 1, '20150615_153625_thumb.jpg', 10, 1),
(1223, 'Alc001', 'Alc001', 'alc001', 'Fazenda SÃ£o JosÃ©', 3, 0, '', '', 2, 0, 'R$ 385,00', '', 'Casa de madeira, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, cercada.', 2, 2, 'clriaherder_thumb.jpg', 10, 1),
(1225, 'Alc144', 'Alc144', 'alc144', 'Canabarro', 1, 1, '', '', 3, 2, 'R$ 600,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, pÃ¡tio cercado.', 2, 2, 'valriojantsch_thumb.jpg', 10, 1),
(1226, 'Ca033', 'Ca033', 'ca033', 'Bairro Canabarro', 7, 1, '210,00', '800,00', 4, 2, 'R$ 250.000,00', '', 'Casa de alvenaria, 04 dormitÃ³rios, 03 salas, cozinha, 02 banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'dsc05057_thumb.jpg', 10, 1),
(1231, 'All083', 'All083', 'all083', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 445,00', '', 'Casa em alvenaria, com 2 dormitÃ³rios, sala,cozinha, banheiro, garagem aberta.', 2, 2, 'dsc08029_thumb.jpg', 10, 1),
(1232, 'La111', 'La111', 'la111', 'Canabarro', 1, 1, '49,22', '80,25', 2, 0, 'R$ 100.000,00', '', 'Casa geminada, medindo 50,00mÂ², 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§os.', 2, 1, 'dsc08081_thumb.jpg', 10, 1),
(1233, 'La120', 'La120', 'la120', 'Bairro Centro Administrativo', 1, 1, '', '', 2, 1, 'R$ 145.000,00', '', 'Casas geminadas, 02 dormitÃ³rios, sala, cozinha, banheiro, lavanderia, garagem e churrasqueira.\r\nCasa 01 - 57,42mÂ² R$ 150.000,00\r\nCasa 02 e 03 - 64,64mÂ² R$ 145.000,00\r\nCasa 04 - 65,69mÂ² R$ 150.000,00', 2, 1, 'a_thumb.jpg', 10, 1),
(1234, 'Alc167', 'Alc167', 'alc167', 'Bairro Canabarro', 1, 1, '', '', 1, 0, 'R$ 370,00', '', 'Casa de madeira, 01 domritÃ³rio, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 2, 'dsc01822_thumb.jpg', 10, 1),
(1235, 'All 088', 'All 088', 'all_088', 'Bairro Languiru', 1, 1, '', '', 3, 2, 'R$ 2.000,00', '', 'Casa em alvenaria, trÃªs dormitÃ³rios, sala, cozinha, lavanderia, 2 banheiros, garagem, cercada.', 2, 2, 'dsc08235_thumb.jpg', 10, 1),
(1236, 'All 089', 'All 089', 'all_089', 'Bairro TeutÃ´nia', 1, 1, '', '', 3, 3, 'R$ 770,00', '', 'Casa em alvenaria, 3 dormitÃ³rios, sala, cozinha, 2 banheiros, lavanderia, cercada.', 2, 2, 'dsc07760_thumb.jpg', 10, 1),
(1237, 'All 085', 'All 085', 'all_085', 'Bairro Languiru', 1, 1, '', '', 3, 1, 'R$ 620,00', '', 'Casa em alvenaria, trÃªs dormitÃ³rios, sala, cozinha, lavanderia, garagem, cercada.', 2, 2, 'dsc08145_thumb.jpg', 10, 1),
(1242, 'Ca015', 'Ca015', 'ca015', 'Bairro Canabarro', 1, 1, '300mÂ²', '', 2, 1, 'R$ 780.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 3, 1, 'dsc08244_thumb.jpg', 10, 1),
(1243, 'Ca038', 'Ca038', 'ca038', 'Bairro Canabarro', 1, 1, '280,17mÂ²', '', 2, 4, 'R$ 140.000,00', 'Duas casas de alvenaria.', 'Casa da frente: 02 dormitÃ³rios, sala, cozinha, 02 banheiros, Ã¡rea de serviÃ§o, garagem para 04 carros.\r\nCasa fundos: 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, varanda.', 2, 1, 'dsc08144_thumb.jpg', 10, 1),
(1244, 'Ca070', 'Ca070', 'ca070', 'Bairro Canabarro', 1, 1, '656,50mÂ²', '42mÂ²', 2, 1, 'R$ 117.000,00', '', 'Casa de madeira, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'vernoilcanabarro_thumb.jpg', 10, 1),
(1246, 'Alt028', 'Alt028', 'alt028', 'Westfalia', 1, 1, '', '369,60', 0, 0, 'R$ 48.000,00', '', 'Terreno medindo 369,60mÂ², sendo 12,00m de frente por 30,80m de frente a fundos.', 5, 1, 'dsc08541_thumb.jpg', 10, 1),
(1247, 'La121', 'La121', 'la121', 'Bairro Centro Administrativo', 1, 1, '50,00', '', 2, 0, 'R$ 105.000,00', '', 'Casa geminada, de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§os.', 2, 1, '1_thumb.jpg', 10, 1),
(1248, 'La122', 'La122', 'la122', 'Bairro Canabarro', 1, 1, '184,39', '646,00', 0, 0, 'R$ 318.000,00', '', 'Terreno de esquina medindo 646,00mÂ², Ã³tima localizaÃ§Ã£o! Com casa de alvenaria, semi acabada medindo 184,39mÂ², quiosque, piscina, pÃ¡tio cercado.', 2, 1, '1_thumb.jpg', 10, 1),
(1249, 'Alt039', 'Alt039', 'alt039', 'Bairro Canabarro', 1, 1, '', '260,00', 0, 0, 'R$ 63.000,00', '', 'Lindos terrenos, a partir de R$ 63.000,00.\r\nCondiÃ§Ãµes a negociar!', 5, 1, '1_thumb.jpg', 10, 1),
(1250, 'Ca030', 'Ca030', 'ca030', 'Bairro Canabarro', 1, 1, '57mÂ²', '350mÂ²', 2, 1, 'R$ 160.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'geraldovaliatti_thumb.jpg', 10, 1),
(1251, 'Ca034', 'Ca034', 'ca034', 'Fazenda SÃ£o JosÃ©', 3, 1, '60,29mÂ²', '330,00mÂ²', 3, 1, 'R$ 127.000,00', 'Garagem aberta.', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 1, 'cleusarodriguesdesouza_thumb.jpg', 10, 1),
(1252, 'Ca081', 'Ca081', 'ca081', 'Bairro Canabarro', 1, 1, '441,22mÂ²', '924,00mÂ²', 1, 0, 'R$ 480.000,00', '', 'PrÃ©dio medindo 441,22mÂ², sendo o 1Âº piso comercial medindo 200mÂ², e o 2Âº piso residencial medindo 241,22mÂ².', 6, 1, 'ceciliawoiciechoski_thumb.jpg', 10, 1),
(1253, 'Ca095', 'Ca095', 'ca095', 'Bairro Canabarro', 1, 1, '140mÂ²', '', 4, 1, 'R$ 180.000,00', '', 'Casa de alvenaria, 04 dormitÃ³rios, 02 salas, cozinha, 02 banheiros, garagem, cercada, portÃ£o eletrÃ´nico.', 2, 1, 'wilsonlarsen_thumb.jpg', 10, 1),
(1254, 'La123', 'La123', 'la123', 'Bairro Centro Administrativo', 1, 1, '159,00', '250,00', 3, 2, 'R$ 265.000,00', '', 'Linda Casa de alvenaria, medindo 159,00mÂ², 03 dormitÃ³rios sendo 01 suÃ­te, sala, sala de jantar, cozinha, churrasqueira, lavanderia, garagem para 02 carros, pÃ¡tio cercado.', 2, 1, '10_thumb.jpg', 10, 1);
INSERT INTO `site_imoveis` (`id`, `referencia`, `titulo`, `link`, `endereco`, `cidade_id`, `destaque`, `area_terreno`, `area_construida`, `dormitorios`, `vagas_garagem`, `valor`, `obs`, `descricao`, `categoria_id`, `negocio_id`, `foto_capa`, `ordem`, `ativo`) VALUES
(1255, 'Ca008', 'Ca008', 'ca008', 'Bairro Alesgut', 1, 1, '140mÂ²', '396mÂ²', 3, 1, 'R$ 180.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'iaraosterkamp_thumb.jpg', 10, 1),
(1256, 'Ca121', 'Ca121', 'ca121', 'Fazenda SÃ£o JosÃ©', 3, 1, '150mÂ²', '363mÂ²', 3, 1, 'R$ 190.000,00', '', 'Casa de alvenaria, 02 pisos, 03 dormitÃ³rios, sala, 02 cozinhas, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'renildomarquesdacosta_thumb.jpg', 10, 1),
(1257, 'La124', 'La124', 'la124', 'Bairro TeutÃ´nia', 1, 1, '212,73', '1732,64', 8, 0, 'R$ 350.000,00', '', 'PrÃ©dio comercial medindo 212,73mÂ². ', 6, 1, '1_thumb.jpg', 10, 1),
(1258, 'Ca010', 'Ca010', 'ca010', 'Bairro Canabarro', 1, 1, '85mÂ²', '363mÂ²', 2, 1, 'R$ 148.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'alexvalecross_thumb.jpg', 10, 1),
(1260, 'Alc024', 'Alc024', 'alc024', 'Bairro Canabarro', 7, 1, '', '', 2, 1, 'R$ 550,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 2, 'dsc08654_thumb.jpg', 10, 1),
(1261, 'Alc056', 'Alc056', 'alc056', 'Bairro Canabarro', 1, 1, '', '', 1, 1, 'R$ 450,00', '', 'Casa de alvenaria, 01 dormitÃ³rio, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem aberta.', 2, 2, 'ademir_thumb.jpg', 10, 1),
(1262, 'Alc006', 'Alc006', 'alc006', 'Bairro Canabarro', 1, 1, '', '', 1, 1, 'R$ 390,00', 'CondomÃ­nio e internet incluso.', 'JK. 01 dormitÃ³rio, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 4, 2, 'dsc08775_thumb.jpg', 10, 1),
(1264, 'Alc008', 'Alc008', 'alc008', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 750,00', 'CondomÃ­nio interno.', 'Apartamento, 02 dormitÃ³rios, senso 01 suÃ­te, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, sacada, garagem.', 1, 2, 'astorkilpp_thumb.jpg', 10, 1),
(1265, 'Alt065', 'Alt065', 'alt065', 'Bairro Languiru', 1, 1, '', '363,00', 0, 0, 'R$ 122.000,00', '', 'Lindo terreno medindo 11,00m de frente por 33,00m de frente a fundos.\r\nÃ“tima localizaÃ§Ã£o!', 5, 1, 'imagem001_thumb.jpg', 10, 1),
(1267, 'La076', 'La076', 'la076', 'Bairro Canabarro', 1, 1, '76,68', '', 2, 1, 'R$ 160.000,00', '', 'Lindo sobrado, 02 dormitÃ³rios, sala, cozinha, banheiro, lavabo, lavanderia, churrasqueira, garagem.\r\nPÃ¡tio parcialmente cercado.\r\nMÃ³veis sob medida.', 8, 1, '1_thumb.jpg', 10, 1),
(1268, 'Ca021', 'Ca021', 'ca021', 'Bairro Canabarro', 1, 1, '', '96mÂ²', 0, 0, 'R$ 990,00', '', 'Sala comercial com 01 banheiro.', 7, 2, 'img-20150527-wa0035_thumb.jpg', 10, 1),
(1269, 'All099', 'All099', 'all099', 'Bairro Languiru', 1, 1, '', '', 2, 1, '', '', 'Apartamento 2 dormitÃ³rios, sala, cozinha, banheiro, lavanderia, sacada.', 1, 2, 'dsc08986_thumb.jpg', 10, 1),
(1270, 'All100', 'All100', 'all100', 'Bairro Languiru', 1, 1, '', '', 1, 0, '', '', 'Casa um dormitÃ³rio, sala, cozinha, banheiro, Ã¡rea de serviÃ§o.', 2, 2, 'site2_thumb.jpg', 10, 1),
(1271, 'All101', 'All101', 'all101', 'Bairro Alesgut', 1, 1, '', '', 2, 1, 'R$ 550,00', '', 'casa de dois dormitÃ³rios, sala, cozinha, Ã¡rea de serviÃ§o, lavanderia, pÃ¡tio cercado.', 2, 2, 'site3_thumb.jpg', 10, 1),
(1272, 'All 102', 'All 102', 'all_102', 'Bairro TeutÃ´nia', 1, 1, '', '', 3, 1, '', '', 'Casa mista, trÃªs dormitÃ³rios, sala, cozinha, lavanderia, banheiro, garagem.', 2, 2, 'sitee_thumb.jpg', 10, 1),
(1273, 'All098', 'All098', 'all098', 'Centro Administrativo', 1, 1, '', '', 2, 1, '', '', 'Apartamento, 2 dormitÃ³rios, sala, cozinha, lavanderia, sacada e suÃ­te.', 1, 2, 'dsc08981_thumb.jpg', 10, 1),
(1274, 'All103', 'All103', 'all103', 'Languiru', 1, 1, '', '', 2, 1, '', '', 'Sobrado, dois dormitÃ³rios, sala, cozinha, lavanderia, banheiro.', 8, 2, 'dsc08143_thumb.jpg', 10, 1),
(1275, 'Ca011', 'Ca011', 'ca011', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 612,00', 'Incluso Ã¡gua e luz.', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro,  Ã¡rea de serviÃ§o, garagem.', 1, 2, 'dsc01821_thumb.jpg', 10, 1),
(1276, 'Ca014', 'Ca014', 'ca014', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 500,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 2, 'anapaula015_thumb.jpg', 10, 1),
(1277, 'Ca120', 'Ca120', 'ca120', 'Bairro Canabarro', 1, 1, '940,50mÂ²', '', 3, 1, 'R$ 159.000,00', '', 'Casa mista, 03 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'jorgemoersbachen_thumb.jpg', 10, 1),
(1278, 'Ca121', 'Ca121', 'ca121', 'Fazenda SÃ£o JosÃ©', 3, 1, '363mÂ²', '150mÂ²', 3, 1, 'R$ 190.000,00', '', 'Casa de de dois pisos de alvenaria, 03 dormitÃ³rios, 02 cozinhas, sala, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 1, 'renildomarquesdacosta_thumb.jpg', 10, 1),
(1279, 'Alc005', 'Alc005', 'alc005', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 583,00', 'CondomÃ­nio R$50,00\r\n', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, sacada, garagem.', 1, 2, 'ccf17082015_00008_thumb.jpg', 10, 1),
(1281, 'Ca074', 'Ca074', 'ca074', 'Bairro Centro administrativo', 1, 1, '345mÂ²', '90mÂ²', 0, 1, 'R$ 158.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sendo um suÃ­te, 02 salas, banheiro, Ã¡rea de serviÃ§o, garagem, cercado.', 2, 1, 'diogocardosoeclaudiasippel_thumb.jpg', 10, 1),
(1282, 'La125', 'La125', 'la125', 'Bairro Canabarro', 1, 1, '430,00', '826,00', 3, 6, 'R$ 450.000,00', '', 'Linda casa de alvenaria medindo 332,80mÂ², com quiosque medindo 60,00mÂ², garagem lateral medindo 22,40mÂ², piscina, quintal com pomar/horta. PÃ¡tio cercado, portÃ£o eletrÃ´nico. Ã“tima localizaÃ§Ã£o!', 2, 1, '1_thumb.jpg', 10, 1),
(1283, 'La126', 'La126', 'la126', 'Bairro Canabarro', 1, 1, '189,14', '911,39', 3, 2, 'R$ 450.000,00', '', 'Casa de alvenaria, 03 dormitÃ³rios, sala, sala de jantar, cozinha, lavanderia, garagem, churrasqueira. PÃ¡tio cercado, portÃ£o eletrÃ´nico. Terreno de esquina.', 2, 1, '1_thumb.jpg', 10, 1),
(1284, 'La127', 'La127', 'la127', 'Fazenda SÃ£o JosÃ©', 3, 1, '76,50', '282,75', 2, 1, 'R$ 79.000,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, garagem, churrasqueira, pÃ¡tio cercado.', 2, 1, '1_thumb.jpg', 10, 1),
(1285, 'All105', 'All105', 'all105', 'Centro', 4, 1, '', '', 2, 1, 'R$ 450,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem aberta.', 2, 2, 'dsc09358_thumb.jpg', 10, 1),
(1286, 'Alc119', 'Alc119', 'alc119', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 425,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 2, 2, 'clementinariva_thumb.jpg', 10, 1),
(1287, 'Alc004', 'Alc004', 'alc004', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 600,00', 'CondomÃ­nio incluso.', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem.', 1, 2, 'rudifritscher_thumb.jpg', 10, 1),
(1288, 'Alc013', 'Alc013', 'alc013', 'Bairro Canabarro', 1, 1, '', '', 2, 1, 'R$ 495,00', '', 'Casa de alvenaria, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, garagem, cercada.', 2, 2, 'rupaulofeyh_thumb.jpg', 10, 1),
(1289, 'Alc009', 'Alc009', 'alc009', 'Bairro Canabarro', 7, 1, '', '', 2, 1, 'R$ 700,00', 'CondomÃ­nio incluso.', 'Apartamento, 02 dormitÃ³rios, sala, cozinha, banheiro, Ã¡rea de serviÃ§o, sacada de frente, garagem.', 1, 2, 'rudifritscher1_thumb.jpg', 10, 1),
(1290, 'La 127', 'La 127', 'la_127', ' Bairro TeutÃ´nia', 1, 1, '112.00 mÂ²', '369, 00mÂ²', 3, 1, 'R$ 212.000,00', '', 'Casa com 3 dormitÃ³rios, sala, cozinha, 2 banheiros, lavanderia, despensa, garagem.', 2, 1, 'img_20151026_150226037_hdr_thumb.jpg', 10, 1),
(1291, 'La 128', 'La 128', 'la_128', 'Bairro Languiru', 1, 1, '118,23mÂ²', '544,50mÂ²', 0, 0, 'R$ 800.000,00', '', 'Terreno medindo 544,50 mÂ². Com duas frentes, fundos Rua EsperanÃ§a, ao lado Banco do Brasil', 2, 1, 'img_20151028_154633753_hdr_thumb.jpg', 10, 1),
(1292, 'Alt066', 'Alt066', 'alt066', 'Bairro TeutÃ´nia', 1, 1, '', '386,10', 0, 0, 'R$ 139.000,00', '', 'Lindo terreno medindo 11,70m x 33,00m = 386,10mÂ².\r\nPrÃ³ximo ao ColÃ©gio TeutÃ´nia.', 5, 1, '1_thumb.jpg', 10, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_imoveis_categorias`
--

CREATE TABLE IF NOT EXISTS `site_imoveis_categorias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Código do tipo de imóvel',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `titulo` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição do tipo de imóvel',
  `arquivo` text COLLATE utf8_unicode_ci,
  `icone` text COLLATE utf8_unicode_ci,
  `ordem` int(11) NOT NULL DEFAULT '10',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tabela de tipos de imóveis' AUTO_INCREMENT=9 ;

--
-- Fazendo dump de dados para tabela `site_imoveis_categorias`
--

INSERT INTO `site_imoveis_categorias` (`id`, `parent_id`, `titulo`, `arquivo`, `icone`, `ordem`, `ativo`) VALUES
(1, 0, 'Apartamento', '', '', 10, 1),
(2, 0, 'Casa', '', '', 10, 1),
(3, 0, 'Chácara', '', '', 10, 1),
(4, 0, 'Kitnet', '', '', 10, 1),
(5, 0, 'Terreno', '', '', 10, 1),
(6, 0, 'Prédio Comercial', '', '', 10, 1),
(7, 0, 'Sala Comercial', '', '', 10, 1),
(8, 0, 'Sobrado', '', '', 10, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_imoveis_negocios`
--

CREATE TABLE IF NOT EXISTS `site_imoveis_negocios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Código do tipo de negócio',
  `titulo` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição do tipo de negócio',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tipos de negócio' AUTO_INCREMENT=3 ;

--
-- Fazendo dump de dados para tabela `site_imoveis_negocios`
--

INSERT INTO `site_imoveis_negocios` (`id`, `titulo`) VALUES
(1, 'Venda'),
(2, 'Locação');

-- --------------------------------------------------------

--
-- Estrutura para tabela `site_paises`
--

CREATE TABLE IF NOT EXISTS `site_paises` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sigla` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sigla` (`sigla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=257 ;

--
-- Fazendo dump de dados para tabela `site_paises`
--

INSERT INTO `site_paises` (`id`, `sigla`, `nome`) VALUES
(1, '', 'AfeganistÃ£o'),
(2, NULL, 'Ãfrica do Sul'),
(3, NULL, 'Akrotiri'),
(4, NULL, 'AlbÃ¢nia'),
(5, NULL, 'Alemanha'),
(6, NULL, 'Andorra'),
(7, NULL, 'Angola'),
(8, NULL, 'Anguila'),
(9, NULL, 'AntÃ¡rctida'),
(10, NULL, 'AntÃ­gua e Barbuda'),
(11, NULL, 'Antilhas Neerlandesas'),
(12, NULL, 'ArÃ¡bia Saudita'),
(13, NULL, 'Arctic Ocean'),
(14, NULL, 'ArgÃ©lia'),
(15, NULL, 'Argentina'),
(16, NULL, 'ArmÃªnia'),
(17, NULL, 'Aruba'),
(18, NULL, 'Ashmore and Cartier Islands'),
(19, NULL, 'Atlantic Ocean'),
(20, NULL, 'AustrÃ¡lia'),
(21, NULL, 'Ãustria'),
(22, NULL, 'AzerbaijÃ£o'),
(23, NULL, 'Baamas'),
(24, NULL, 'Bangladeche'),
(25, NULL, 'Barbados'),
(26, NULL, 'BarÃ©m'),
(27, NULL, 'BÃ©lgica'),
(28, NULL, 'Belize'),
(29, NULL, 'Benim'),
(30, NULL, 'Bermudas'),
(31, NULL, 'BielorrÃºssia'),
(32, NULL, 'BirmÃ¢nia'),
(33, NULL, 'BolÃ­via'),
(34, NULL, 'BÃ³snia e Herzegovina'),
(35, NULL, 'Botsuana'),
(36, NULL, 'Brasil'),
(37, NULL, 'Brunei'),
(38, NULL, 'BulgÃ¡ria'),
(39, NULL, 'Burquina Faso'),
(40, NULL, 'BurÃºndi'),
(41, NULL, 'ButÃ£o'),
(42, NULL, 'Cabo Verde'),
(43, NULL, 'CamarÃµes'),
(44, NULL, 'Camboja'),
(45, NULL, 'CanadÃ¡'),
(46, NULL, 'Catar'),
(47, NULL, 'CazaquistÃ£o'),
(48, NULL, 'Chade'),
(49, NULL, 'Chile'),
(50, NULL, 'China'),
(51, NULL, 'Chipre'),
(52, NULL, 'Clipperton Island'),
(53, NULL, 'ColÃ´mbia'),
(54, NULL, 'Comores'),
(55, NULL, 'Congo-Brazzaville'),
(56, NULL, 'Congo-Kinshasa'),
(57, NULL, 'Coral Sea Islands'),
(58, NULL, 'Coreia do Norte'),
(59, NULL, 'Coreia do Sul'),
(60, NULL, 'Costa do Marfim'),
(61, NULL, 'Costa Rica'),
(62, NULL, 'CroÃ¡cia'),
(63, NULL, 'Cuba'),
(64, NULL, 'Dhekelia'),
(65, NULL, 'Dinamarca'),
(66, NULL, 'DomÃ­nica'),
(67, NULL, 'Egipto'),
(68, NULL, 'Emiratos Ãrabes Unidos'),
(69, NULL, 'Equador'),
(70, NULL, 'Eritreia'),
(71, NULL, 'EslovÃ¡quia'),
(72, NULL, 'EslovÃªnia'),
(73, NULL, 'Espanha'),
(74, NULL, 'Estados Unidos'),
(75, NULL, 'EstÃ´nia'),
(76, NULL, 'EtiÃ³pia'),
(77, NULL, 'FaroÃ©'),
(78, NULL, 'Fiji'),
(79, NULL, 'Filipinas'),
(80, NULL, 'FinlÃ¢ndia'),
(81, NULL, 'FranÃ§a'),
(82, NULL, 'GabÃ£o'),
(83, NULL, 'GÃ¢mbia'),
(84, NULL, 'Gana'),
(85, NULL, 'Gaza Strip'),
(86, NULL, 'GeÃ³rgia'),
(87, NULL, 'GeÃ³rgia do Sul e Sandwich do Sul'),
(88, NULL, 'Gibraltar'),
(89, NULL, 'Granada'),
(90, NULL, 'GrÃ©cia'),
(91, NULL, 'GronelÃ¢ndia'),
(92, NULL, 'Guame'),
(93, NULL, 'Guatemala'),
(94, NULL, 'Guernsey'),
(95, NULL, 'Guiana'),
(96, NULL, 'GuinÃ©'),
(97, NULL, 'GuinÃ© Equatorial'),
(98, NULL, 'GuinÃ©-Bissau'),
(99, NULL, 'Haiti'),
(100, NULL, 'Honduras'),
(101, NULL, 'Hong Kong'),
(102, NULL, 'Hungria'),
(103, NULL, 'IÃªmen'),
(104, NULL, 'Ilha Bouvet'),
(105, NULL, 'Ilha do Natal'),
(106, NULL, 'Ilha Norfolk'),
(107, NULL, 'Ilhas CaimÃ£o'),
(108, NULL, 'Ilhas Cook'),
(109, NULL, 'Ilhas dos Cocos'),
(110, NULL, 'Ilhas Falkland'),
(111, NULL, 'Ilhas Heard e McDonald'),
(112, NULL, 'Ilhas Marshall'),
(113, NULL, 'Ilhas SalomÃ£o'),
(114, NULL, 'Ilhas Turcas e Caicos'),
(115, NULL, 'Ilhas Virgens Americanas'),
(116, NULL, 'Ilhas Virgens BritÃ¢nicas'),
(117, NULL, 'Ãndia'),
(118, NULL, 'Indian Ocean'),
(119, NULL, 'IndonÃ©sia'),
(120, NULL, 'IrÃ£o'),
(121, NULL, 'Iraque'),
(122, NULL, 'Irlanda'),
(123, NULL, 'IslÃ¢ndia'),
(124, NULL, 'Israel'),
(125, NULL, 'ItÃ¡lia'),
(126, NULL, 'Jamaica'),
(127, NULL, 'Jan Mayen'),
(128, NULL, 'JapÃ£o'),
(129, NULL, 'Jersey'),
(130, NULL, 'Jibuti'),
(131, NULL, 'JordÃ¢nia'),
(132, NULL, 'Kuwait'),
(133, NULL, 'Laos'),
(134, NULL, 'Lesoto'),
(135, NULL, 'LetÃ´nia'),
(136, NULL, 'LÃ­bano'),
(137, NULL, 'LibÃ©ria'),
(138, NULL, 'LÃ­bia'),
(139, NULL, 'Listenstaine'),
(140, NULL, 'LituÃ¢nia'),
(141, NULL, 'Luxemburgo'),
(142, NULL, 'Macau'),
(143, NULL, 'MacedÃ´nia'),
(144, NULL, 'MadagÃ¡scar'),
(145, NULL, 'MalÃ¡sia'),
(146, NULL, 'MalÃ¡ui'),
(147, NULL, 'Maldivas'),
(148, NULL, 'Mali'),
(149, NULL, 'Malta'),
(150, NULL, 'Man, Isle of'),
(151, NULL, 'Marianas do Norte'),
(152, NULL, 'Marrocos'),
(153, NULL, 'MaurÃ­cia'),
(154, NULL, 'MauritÃ¢nia'),
(155, NULL, 'Mayotte'),
(156, NULL, 'MÃ©xico'),
(157, NULL, 'MicronÃ©sia'),
(158, NULL, 'MoÃ§ambique'),
(159, NULL, 'MoldÃ­via'),
(160, NULL, 'MÃ´naco'),
(161, NULL, 'MongÃ³lia'),
(162, NULL, 'Monserrate'),
(163, NULL, 'Montenegro'),
(164, NULL, 'Mundo'),
(165, NULL, 'NamÃ­bia'),
(166, NULL, 'Nauru'),
(167, NULL, 'Navassa Island'),
(168, NULL, 'Nepal'),
(169, NULL, 'NicarÃ¡gua'),
(170, NULL, 'NÃ­ger'),
(171, NULL, 'NigÃ©ria'),
(172, NULL, 'Niue'),
(173, NULL, 'Noruega'),
(174, NULL, 'Nova CaledÃ³nia'),
(175, NULL, 'Nova ZelÃ¢ndia'),
(176, NULL, 'OmÃ£'),
(177, NULL, 'Pacific Ocean'),
(178, NULL, 'PaÃ­ses Baixos'),
(179, NULL, 'Palau'),
(180, NULL, 'PanamÃ¡'),
(181, NULL, 'Papua-Nova GuinÃ©'),
(182, NULL, 'PaquistÃ£o'),
(183, NULL, 'Paracel Islands'),
(184, NULL, 'Paraguai'),
(185, NULL, 'Peru'),
(186, NULL, 'Pitcairn'),
(187, NULL, 'PolinÃ©sia Francesa'),
(188, NULL, 'PolÃ´nia'),
(189, NULL, 'Porto Rico'),
(190, NULL, 'Portugal'),
(191, NULL, 'QuÃ©nia'),
(192, NULL, 'QuirguizistÃ£o'),
(193, NULL, 'QuiribÃ¡ti'),
(194, NULL, 'Reino Unido'),
(195, NULL, 'RepÃºblica Centro-Africana'),
(196, NULL, 'RepÃºblica Checa'),
(197, NULL, 'RepÃºblica Dominicana'),
(198, NULL, 'RomÃªnia'),
(199, NULL, 'Ruanda'),
(200, NULL, 'RÃºssia'),
(201, NULL, 'Salvador'),
(202, NULL, 'Samoa'),
(203, NULL, 'Samoa Americana'),
(204, NULL, 'Santa Helena'),
(205, NULL, 'Santa LÃºcia'),
(206, NULL, 'SÃ£o CristÃ³vÃ£o e Neves'),
(207, NULL, 'SÃ£o Marinho'),
(208, NULL, 'SÃ£o Pedro e Miquelon'),
(209, NULL, 'SÃ£o TomÃ© e PrÃ­ncipe'),
(210, NULL, 'SÃ£o Vicente e Granadinas'),
(211, NULL, 'Sara Ocidental'),
(212, NULL, 'Seicheles'),
(213, NULL, 'Senegal'),
(214, NULL, 'Serra Leoa'),
(215, NULL, 'SÃ©rvia'),
(216, NULL, 'Singapura'),
(217, NULL, 'SÃ­ria'),
(218, NULL, 'SomÃ¡lia'),
(219, NULL, 'Southern Ocean'),
(220, NULL, 'Spratly Islands'),
(221, NULL, 'Sri Lanca'),
(222, NULL, 'SuazilÃ¢ndia'),
(223, NULL, 'SudÃ£o'),
(224, NULL, 'SuÃ©cia'),
(225, NULL, 'SuÃ­Ã§a'),
(226, NULL, 'Suriname'),
(227, NULL, 'Svalbard e Jan Mayen'),
(228, NULL, 'TailÃ¢ndia'),
(229, NULL, 'Taiwan'),
(230, NULL, 'TajiquistÃ£o'),
(231, NULL, 'TanzÃ¢nia'),
(232, NULL, 'TerritÃ³rio BritÃ¢nico do Oceano Ãndico'),
(233, NULL, 'TerritÃ³rios Austrais Franceses'),
(234, NULL, 'Timor Leste'),
(235, NULL, 'Togo'),
(236, NULL, 'Tokelau'),
(237, NULL, 'Tonga'),
(238, NULL, 'Trindade e Tobago'),
(239, NULL, 'TunÃ­sia'),
(240, NULL, 'TurquemenistÃ£o'),
(241, NULL, 'Turquia'),
(242, NULL, 'Tuvalu'),
(243, NULL, 'UcrÃ¢nia'),
(244, NULL, 'Uganda'),
(245, NULL, 'UniÃ£o Europeia'),
(246, NULL, 'Uruguai'),
(247, NULL, 'UsbequistÃ£o'),
(248, NULL, 'Vanuatu'),
(249, NULL, 'Vaticano'),
(250, NULL, 'Venezuela'),
(251, NULL, 'Vietname'),
(252, NULL, 'Wake Island'),
(253, NULL, 'Wallis e Futuna'),
(254, NULL, 'West Bank'),
(255, NULL, 'ZÃ¢mbia'),
(256, NULL, 'Zimbabwe');

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `site_imoveis`
--
ALTER TABLE `site_imoveis`
  ADD CONSTRAINT `imoveis_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `site_imoveis_categorias` (`id`),
  ADD CONSTRAINT `imoveis_ibfk_2` FOREIGN KEY (`negocio_id`) REFERENCES `site_imoveis_negocios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
