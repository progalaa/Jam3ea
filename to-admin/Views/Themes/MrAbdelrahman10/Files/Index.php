<!DOCTYPE html>
<html lang="<?php echo $_Lang ?>" dir="<?php echo $_Direction ?>">
    <head>
        <base href="<?php echo SITE_URL; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>
            <?php echo $_ControlPanel ?>
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="MrAbdelrahman10.com" />
        <!-- Fav and touch icons -->
        <link rel="shortcut icon" href="<?php echo APP_PUBLIC; ?>ico/favicon.ico" />
    </head>
    <body>

        <nav class="navbar navbar-inner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand text-white" href="<?php echo ADM_BASE; ?>">
                        <i class="fa fa-dashboard"></i>
                        <?php echo $_ControlPanel; ?>
                    </a>
                </div>
            </div><!-- /.container -->
        </nav><!-- /.navbar -->

        <div class="container-fluid">

            <div class="row row-offcanvas row-offcanvas-right">

                <?php
                require_once ADM_TEMPLATE_SHARED . 'Head.php';
                ?>
                <?php // include $this->PageContents; ?>
                <?php
                require_once $this->SharedScript;
                ?>

                <div class="col-xs-12 col-sm-2 sidebar-offcanvas" id="sidebar">
                    <div class="list-group">
                        <a href="<?php echo SITE_URL; ?>" class="list-group-item list-group-item-success" target="_blank">
                            <i class="fa fa-globe"></i>
                            <?php echo $_BrowseSite; ?>
                        </a>
                        <a href="<?php echo ADM_BASE; ?>" class="list-group-item active">
                            <i class="fa fa-dashboard"></i>
                            <?php echo $_ControlPanel; ?>
                        </a>
                        <?php
                        foreach ($dAdminPages as $i) {
                            echo Anchor(ADM_BASE . $i['ID'], '<i class="fa fa-' . $i['Icon'] . '"></i> ' . $i['Name'], 'class="list-group-item"');
                        }
                        ?>
                        <a href="<?php echo SITE_URL; ?>piwik" class="list-group-item" target="_blank">
                            <i class="fa fa-columns"></i>
                            <?php echo $_Piwik; ?>
                        </a>
                        <a href="<?php echo ADM_BASE; ?>Home/SignOut" class="list-group-item">
                            <i class="fa fa-sign-out"></i>
                            <?php echo $_SignOut; ?>
                        </a>
                    </div>
                </div><!--/.sidebar-offcanvas-->

                <div class="col-xs-12 col-sm-10">
                    <div class="breadcrumb col-md-12" id="Buttons">
                        <div class="pull-right">
                            <ul class="list-inline">
                                <li>
                                    <?php echo Anchor('javascript:void(0)', '<i class="fa fa-plus"></i> ' . $_Add, 'class="btn btn-primary" id="btnAdd"') ?>
                                </li>
                                <li>
                                    <?php echo Anchor('javascript:void(0)', '<i class="fa fa-edit"></i> ' . $_Edit, 'class="btn  btn-primary" id="btnEdit"') ?>
                                    <?php echo InputHidden('eID') ?>
                                </li>
                                <li>
                                    <?php echo Anchor('javascript:void(0)', '<i class="fa fa-ok"></i> ' . $_SelectAll, 'class="btn btn-success" id="btnSelectAll"', false) ?>
                                </li>
                                <li>
                                    <?php echo Anchor('#collapseSearch', '<i class="fa fa-search"></i> ' . $_Search, 'class="btn btn-primary" id="btnSearch" data-toggle="collapse" aria-expanded="false" aria-controls="collapseSearch"', false) ?>
                                </li>
                                <li>
                                    <?php echo Anchor('#DeleteOperation', '<i class="fa fa-trash"></i> ' . $_Delete, 'class="btn btn-danger" id="btnDelete" data-toggle="modal"', false) ?>
                                </li>
                            </ul>
                        </div>
                        <div class="pull-left">
                            <?php
                            $__dPagination = Session::Get('Admin.rowsNum')? : 50;
                            $__Pagination = '';
                            $__Ps = array(10, 20, 50, 100, 200, 500, 1000);
                            foreach ($__Ps as $v) {
                                $__Pagination .= '<option value="' . $v . '">' . $v . '</option>';
                            }
                            echo DropDown('cbPagination', $__Pagination, $__dPagination);
                            ?>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div id="PageContents">
                        <?php
                        require_once $this->PageContents;
                        require_once $this->SharedScript;
                        ?>
                    </div>
                </div><!--/.col-xs-12.col-sm-9-->

            </div><!--/row-->

            <div id="Modals">
                <!-- Modal -->
                <div class="modal fade" id="DetailOperation">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><?php echo $_Details ?></h4>
                            </div>
                            <div class="modal-body">
                                <p id="ItemDetails"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $_Cancel ?></button>
                                <?php echo Anchor('#', $_Edit, 'class="btn btn-primary" id="lnkEdit" onclick="$(\'#DetailOperation\').modal(\'hide\');"') ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <div class="modal fade" id="ChooseData">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><?php echo $_Choose ?></h4>
                            </div>
                            <div class="modal-body">
                                <p id="DataDetails"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $_Cancel ?></button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <div class="modal fade" id="DeleteOperation">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><?php echo $_Delete ?></h4>
                            </div>
                            <div class="modal-body">
                                <p id="DeleteDetails"><?php echo $_ConfirmDelete ?></p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" id="Deleting">
                                    <div class="col-md-4">
                                        <input type="submit" value="<?php echo $_Delete; ?>" class="btn btn-danger btn-block" />
                                    </div>
                                    <div class="col-md-4">
                                        <?php echo Anchor('javascript:void(0)', $_Cancel, 'class="btn btn-default btn-block" data-dismiss="modal"', false) ?>
                                    </div>
                                    <?php echo InputHidden('dIDs') ?>
                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <div class="modal fade" id="FileManager">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><?php echo $_FileManager ?></h4>
                            </div>
                            <div class="modal-body">
                                <p id="FileManagerDetails"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $_Cancel ?></button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <div id="Alerts" class="navbar-fixed-bottom">
                    <div id="SuccessMessage" class="msg alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×
                        </button><strong>
                            <?php echo $_Message ?></strong>
                        <?php echo $_SavedDone ?>
                    </div>
                    <div class="alert alert-error msg message">
                        <button type="button" class="close" data-dismiss="alert">×
                        </button><strong>
                            <?php echo $_Error ?></strong>
                        <?php echo $_Unexpected_Error ?>
                    </div>
                    <div id="WarningInner" class="msg alert alert-warning">
                        <strong>
                            <?php echo $_Warning ?></strong>
                        <div id="WarningMessage">
                            <?php echo $_Warning_Message ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <footer>
                <p>
                    <?php echo $_Copyright; ?>
                </p>
            </footer>

        </div><!--/.container-->

    </body>
</html>