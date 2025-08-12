<!DOCTYPE html>
<html>
<? include('head.php'); ?>
<body class="skin-blue">
    <!-- header logo: style can be found in header.less -->
    <? include('header.php'); ?>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <? include('left_side.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?=$content_header;?>
                    <small><?=$small_content_header;?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="home"><i class="fa fa-dashboard"></i> Home</a></li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content <?php echo isset($optional_class) ? $optional_class: '';?>" id="content">
                <?=$content;?>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
        <div id="loading_layer" style="display:none;  text-align: center; z-index: 999; position: fixed;top: 50%;left: 50%;">
            <img src="<?=base_url();?>assets/img/ajax-loader.gif" alt="" />
        </div>
    </div><!-- ./wrapper -->
</body>
</html>