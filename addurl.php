<?php
    include_once('dbconfig.php');
    include_once('header.php');
?>
    <body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
        <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light sticky-top">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a href="addurl.php" class="nav-link active"><i class="fas fa-plus-circle"></i> Add feeds to map</a>
                </li>
                <li class="nav-item">
                    <a href="managefeeds.php" class="nav-link"><i class="fas fa-pen"></i> Manage Feeds</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
            <img src="dist/img/cf.svg" alt="l4c Logo" class="brand-image"
                style="opacity: .8">
            <span class="brand-text font-weight-light"><strong>Cleanfeed</strong></span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <span class="d-block mb-0"><?php echo $_SESSION['username']?></span>
                    </div>
                </div>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="nav-icon fas fa-power-off"></i>
                                <p>
                                    Disconnect
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mt-3">


                                <div class="card-header st-head">
                                    <form>
                                        <div class="row mx-sm-n1">
                                            <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                <input type="text" class="form-control form-control-lg" id="feedName" placeholder="Feed name">
                                            </div>
                                            <div class="col-lg-5 px-sm-1 col-sm-9 mt-2 mb-2">
                                                <div class="input-group mb-0">
                                                  <input type="text" class="form-control form-control-lg" id="xmlurl" placeholder="Add the url to map" aria-label="Add url to map" aria-describedby="button-addon2">
                                                  <div class="input-group-append">
                                                    <button class="btn btn-default btn-lg" type="button"  id="parseXML" type="button"> <i class="fas fa-plus-circle mr-1"></i>
                                                    </button>
                                                  </div>
                                                </div>                                     
                                            </div>

                                            <div class="col-lg-2 offset-lg-1  col-sm-12 mt-2 mb-2">
                                                <button class="btn btn-primary btn-lg btn-block "  id="saveDetail" type="button">
                                                <i class="fas fa-save mr-1"></i>
                                                Saved
                                                  <i class="fas fa-check ml-1"></i></button>
                                            </div>
                                            <div class="col-lg-6 px-sm-1 col-sm-12 mt-2 mb-2 check-label">
                                                <label style="width: 350px; margin-left: 25px;" class="form-check-label" for="willCountryCheck">Will add addressCountry tag?</label>
                                                <input style="height: 30px;" type="checkbox" class="form-control form-control-lg"  id="willCountryCheck" name="willCountryCheck"> 
                                            </div>
                                            <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                <input type="text" class="form-control form-control-lg" id="willAddCountry" name="willAddCountry" placeholder="Add Country Value">
                                            </div>
                                            <div class="col-lg-6 px-sm-1 col-sm-12 mt-2 mb-2 check-label">
                                                <label style="width: 350px; margin-left: 25px;" class="form-check-label" for="willLocationCheck">Will add jobLocationType tag?</label>
                                                <input style="height: 30px;" type="checkbox" class="form-control form-control-lg"  id="willLocationCheck" name="willLocationCheck"> 
                                            </div>
                                            <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                <input type="text" style="display: none;" class="form-control form-control-lg" id="jobLocationType" name="jobLocationType" value="TELECOMMUTE" readonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center mt-2 ml-4 mb-2 mr-4">
                                        <p class="mb-0" id="tagNumber"></p>
                                    </div>
                                    <div>
                                        <form>
                                            
                                        </form>
                                        <table class="table table-striped align-middle">
                                            <input type="hidden" id="xmlurlHidden" name="xmlurlHidden">
                                            <input type="hidden" id="cdataTag" name="cdataTag">
                                            <input type="hidden" id="baseTagValue" name="baseTagValue">
                                            <tbody id="parsing">
                                                
                                            </tbody>
                                        </table>
                                        <!-- /.table -->
                                        <div class="p-5 text-center">
                                        <p class="lead"><i class="nav-icon fas fa-link"></i> <br>add a url to see the results</p>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <!-- Main Footer -->
<?php
include_once('footer.php');
?>

