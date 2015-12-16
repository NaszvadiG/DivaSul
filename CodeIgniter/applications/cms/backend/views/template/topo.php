<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
      <a href="<?php echo base_url(); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>S</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Diva</b>Sul</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Menu</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu" style="display:none">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">1</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Você possui 1 mensagem:</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Equipe de suporte
                        <small><i class="fa fa-clock-o"></i> 5 min</small>
                      </h4>
                      <p>Teste.</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">Ver todas as mensagens</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">1</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Você possui 1 notificação:</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="<?php echo base_url(); ?>agenda/1">
                      <i class="fa fa-calendar text-aqua"></i> Visitar <b>Arthur Lehdermann</b>.
                      <i class="fa fa-clock-o"></i> 17h
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="<?php echo base_url(); ?>/agenda">Ver todas</a></li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><?php echo $usuario['nome']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('usuarios/editar/'.$usuario['id']); ?>" class="btn btn-primary btn-flat">Meus dados</a>

<?php
if ( $sites > 1 )
{
?>
                  <a href="<?php echo base_url('login/selecionar_site'); ?>" class="btn btn-primary btn-flat">Trocar site</a>
<?php
}
?>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url();?>login/deslogar" class="btn btn-danger btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url(); ?>../arquivos/icones_dos_sites/<?php echo $site['icone']; ?>" class="img-circle" alt="Icone do site">
        </div>
        <div class="pull-left info">
          <p><?php echo $site['titulo']; ?></p>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Buscar...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
<?php
    if ( $menu_topo )
    {
        echo $menu_topo;
    }
?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>login"><i class="fa fa-lock"></i>Login</a></li>
        <li class="active"><?php echo $modulo['titulo']; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
