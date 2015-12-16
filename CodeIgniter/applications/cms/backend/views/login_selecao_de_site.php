<div class="wrapper">
<script>
$('body').addClass('layout-top-nav');
$('body').addClass('skin-blue');
</script>

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
        <a href="<?php echo base_url(); ?>" class="navbar-brand"><b>DivaSul</b> - Área restrita</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
<!--
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Seleção de site <span class="sr-only">(atual)</span></a></li>
            <li><a href="<?php echo base_url(); ?>login/deslogar">Logout</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Item</a></li>
                <li><a href="#">Item</a></li>
                <li><a href="#">Item</a></li>
                <li class="divider"></li>
                <li><a href="#">Item</a></li>
                <li class="divider"></li>
                <li><a href="#">Item</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Buscar...">
            </div>
          </form>
-->
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu" style="display:none;">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-envelope-o"></i>
                <span class="label label-success">1</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Você possui1 mensagm:</li>
                <li>
                  <!-- inner menu: contains the messages -->
                  <ul class="menu">
                    <li><!-- start message -->
                      <a href="<?php echo base_url(); ?>mensagens/1">
                        <div class="pull-left">
                          <!-- User Image -->
                          <img src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                          Equipde de suporte
                          <small><i class="fa fa-clock-o"></i> 2min atrás</small>
                        </h4>
                        <!-- The message -->
                        <p>Seja bem vindo!</p>
                      </a>
                    </li>
                    <!-- end message -->
                  </ul>
                  <!-- /.menu -->
                </li>
                <li class="footer"><a href="<?php echo base_url(); ?>mensagens">Veja todas as mensagens</a></li>
              </ul>
            </li>
            <!-- /.messages-menu -->

            <!-- Notifications Menu -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">1</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Você possui 1 notificação:</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  <ul class="menu">
                    <li><!-- start notification -->
                      <a href="<?php echo base_url(); ?>agenda/1">
                        <i class="fa fa-calendar"></i> Visitar <b class="text-aqua">Arthur Lehdermann</b>.
                        <small><i class="fa fa-clock-o"></i> 17h</small>
                      </a>
                    </li>
                    <!-- end notification -->
                  </ul>
                </li>
                <li class="footer"><a href="#">Ver todas</a></li>
              </ul>
            </li>
            <!-- Tasks Menu -->
            <li class="dropdown tasks-menu" style="display:none">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-flag-o"></i>
                <span class="label label-danger">1</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Você possui 1 agend:a</li>
                <li>
                  <!-- Inner menu: contains the tasks -->
                  <ul class="menu">
                    <li><!-- Task item -->
                      <a href="#">
                        <!-- Task title and progress text -->
                        <h3>
                          Andamento do projeto.
                          <small class="pull-right">20%</small>
                        </h3>
                        <!-- The progress bar -->
                        <div class="progress xs">
                          <!-- Change the css width attribute to simulate progress -->
                          <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">20% Concluído</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- end task item -->
                  </ul>
                </li>
                <li class="footer">
                  <a href="#">Ver todas agendas</a>
                </li>
              </ul>
            </li>
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <!--img src="../../dist/img/
                <!--img src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"-->
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?php echo $usuario['nome']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="<?php echo base_url(); ?>login/deslogar" class="btn btn-danger btn-flat">Sair</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>

  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Selecionar site
          <small>clique no site que deseja administrar</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url(); ?>login"><i class="fa fa-lock"></i> Login</a></li>
          <li class="active">Selecionar site</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
<style type="text/css">
.site_sel
{
    padding:1px 7px 4px 24px;
    border:1px solid #CCC;
    border-radius:3px;
    -moz-border-radius:3px;
    margin:5px;
    overflow:hidden;
    font-size:14px;
    background-image:url(<?php echo base_url('arquivos/css/icons/edit.png'); ?>);
    background-repeat:no-repeat;
    background-position:2px 4px;
    float:left;
}

.site_sel:hover
{
    cursor:pointer;
}
</style>

<?php echo form_open('', array('id' => 'form_login_site', 'method'=>'POST')); ?>
<input id="site_id_selected" type="hidden" name="site_id" value="" />
<?php
foreach ( $sites as $site )
{
    // Verifica se tem favicon, se tem usa-o, se não usa o ícone de editar
    $background = '';
    if ( (strlen($site['icone']) > 3 ) && is_file(SERVERPATH.'arquivos/icones_dos_sites/'.$site['icone']) )
    {
        $background = 'style="background-image:url('.base_url('../arquivos/icones_dos_sites/'.$site['icone']).');"';
    }

    // Botão de acesso ao site
    echo '<div class="site_sel" '.$background.' name="'.$site['id'].'" title="'.$site['titulo'].'" /> '.$site['titulo'].'</div>';
}
?>
<?php echo form_close(); ?>
<script type="text/javascript">
$('form .site_sel').click(function()
{
    $('#site_id_selected').val($(this).attr('name'));
    $('form').submit();
});
</script>

      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
