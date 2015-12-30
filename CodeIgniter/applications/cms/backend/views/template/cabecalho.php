<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <title>Área Restrita</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo base_url() . '../arquivos/icones_dos_sites/' . ($site_id ? $site_id : 1) . '.png'; ?>" />

        <!-- jQuery -->
        <script type="text/javascript" src="<?php echo base_url() . '../arquivos/libs/jquery-1.11.3.min.js'; ?>"></script>

        <!-- jQuery UI -->
        <script type="text/javascript" src="<?php echo base_url() . '../arquivos/libs/jquery-ui/jquery-ui.min.js'; ?>"></script>
        <link rel="stylesheet" href="<?php echo base_url() . '../arquivos/libs/jquery-ui/jquery-ui.min.css'; ?>" type="text/css" media="screen" charset="utf-8" />

        <!-- Adiciona o editor like a Word nos campos textarea (com class="ckeditor") -->
        <script type="text/javascript" src="<?php echo base_url() . '../arquivos/libs/ckeditor/ckeditor.js'; ?>"></script>

        <!-- LIGHTBOX -->
        <link rel="stylesheet" href="<?php echo base_url() . '../arquivos/libs/prettyPhoto/css/prettyPhoto.min.css'; ?>" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
        <script src="<?php echo base_url() . '../arquivos/libs/prettyPhoto/js/jquery.prettyPhoto.min.js'; ?>" type="text/javascript" charset="utf-8"></script>

        <!-- Uploadify -->
        <link href="<?php echo base_url() . '../arquivos/libs/uploadify/uploadify.css'; ?>" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo base_url() . '../arquivos/libs/uploadify/jquery.uploadify.min.js'; ?>"></script>

        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/iCheck/square/blue.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/daterangepicker/daterangepicker-bs3.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/iCheck/all.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css">
        <!-- Bootstrap time Picker -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/select2/select2.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/dist/css/skins/_all-skins.min.css">
        <!-- SlimScroll -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/fastclick/fastclick.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/dist/js/app.min.js"></script>
        <!-- Select2 -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/select2/select2.full.min.js"></script>
        <!-- InputMask -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
        <!-- date-range-picker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap color picker -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
        <!-- bootstrap time picker -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.js"></script>
        <!-- SlimScroll 1.3.0 -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- iCheck 1.0.1 -->
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/iCheck/icheck.min.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="<?php echo base_url(); ?>../arquivos/js/html5shiv.min.js"></script>
        <script src="<?php echo base_url(); ?>../arquivos/js/respond.min.js"></script>
        <![endif]-->

        <!-- bootstrap table filter -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/libs/bootstrap-table-filter/src/bootstrap-table-filter.css">
        <script src="<?php echo base_url(); ?>../arquivos/libs/bootstrap-table-filter/src/bootstrap-table-filter.js"></script>

        <link rel="stylesheet" href="<?php echo base_url() . 'arquivos/css/backend.css'; ?>">
        <script src="<?php echo base_url() . 'arquivos/js/backend.js'; ?>"></script>

        <!-- fullCalendar 2.2.5-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/fullcalendar/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/fullcalendar/fullcalendar.print.css" media="print">

        <!-- fullCalendar 2.2.5 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>../arquivos/template/AdminLTE/plugins/fullcalendar/fullcalendar.min.js"></script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
