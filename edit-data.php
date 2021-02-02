<?php
    include_once('dbconfig.php');
    include_once('header.php');
    $edit_id = $_GET['edit_id'];
    $edit_infor = $crud->getID($edit_id);
    // print_r($edit_infor); exit;
    $basetag = $edit_infor['basetag'];
    $basetag = explode(",", $basetag);
    array_pop($basetag); 
    $updatetag = $edit_infor['updatetag'];
    $updatetag = explode(",", $updatetag);
    array_pop($updatetag);
    $willEditCountry = $edit_infor['defaultcountry'];
    $jobLocationType = $edit_infor['joblocationtype'];
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
                    <a href="addurl.php" class="nav-link"><i class="fas fa-plus-circle"></i> Add feeds to map</a>
                </li>
                <li class="nav-item">
                    <a href="filefeed.php" class="nav-link"><i class="fas fa-plus-circle"></i> Add file feed</a>
                </li>
                <li class="nav-item">
                    <a href="managefeeds.php" class="nav-link active"><i class="fas fa-pen"></i> Manage Feeds</a>
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
                                                <input type="text" class="form-control form-control-lg" id="feedName" placeholder="Feed name" value="<?php echo $edit_infor['name']; ?>">
                                            </div>
                                            <div class="col-lg-5 px-sm-1 col-sm-9 mt-2 mb-2">
                                                <div class="input-group mb-0">
                                                  <input type="text" class="form-control form-control-lg" id="xmlurl" placeholder="Add the url to map" value="<?php echo $edit_infor['url']; ?>" aria-label="Add url to map" aria-describedby="button-addon2" readonly>
                                                  <!-- <div class="input-group-append">
                                                    <button class="btn btn-default btn-lg" type="button"  id="parseXML" type="button"> <i class="fas fa-plus-circle mr-1"></i>
                                                    </button>
                                                  </div> -->
                                                </div>
                                            </div>

                                            <div class="col-lg-2 offset-lg-1  col-sm-12 mt-2 mb-2">
                                                <button class="btn btn-primary btn-lg btn-block "  id="updateDetail" type="button">
                                                <i class="fas fa-save mr-1"></i>
                                                Update
                                                  <i class="fas fa-check ml-1"></i></button>
                                            </div>
                                            <?php
                                                if(!empty($willEditCountry)) {
                                            ?>
                                                <div class="col-lg-6 px-sm-1 col-sm-12 mt-2 mb-2 check-label">
                                                    <label style="width: 350px; margin-left: 25px;" class="form-check-label" for="willCountryCheck">Will add addressCountry tag?</label>
                                                    <input style="height: 30px;" type="checkbox" checked class="form-control form-control-lg"  id="willCountryCheck" name="willCountryCheck"> 
                                                </div>
                                                <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                    <input type="text" class="form-control form-control-lg" id="willEditCountry" name="willEditCountry" placeholder="Add Country Value" value="<?php echo $willEditCountry?>">
                                                </div>
                                            <?php
                                                }
                                                else {
                                            ?>
                                                <div class="col-lg-6 px-sm-1 col-sm-12 mt-2 mb-2 check-label">
                                                    <label style="width: 350px; margin-left: 25px;" class="form-check-label" for="willCountryCheck">Will add addressCountry tag?</label>
                                                    <input style="height: 30px;" type="checkbox" class="form-control form-control-lg"  id="willCountryCheck" name="willCountryCheck"> 
                                                </div>
                                                <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                    <input type="text" class="form-control form-control-lg" style="display: none;" id="willEditCountry" name="willEditCountry" placeholder="Add Country Value" value="">
                                                </div>
                                            <?php
                                                }
                                            ?>

                                            <?php
                                                if(!empty($jobLocationType)) {
                                            ?>
                                                <div class="col-lg-6 px-sm-1 col-sm-12 mt-2 mb-2 check-label">
                                                    <label style="width: 350px; margin-left: 25px;" class="form-check-label" for="willLocationCheck">Will add jobLocationType tag?</label>
                                                    <input style="height: 30px;" type="checkbox" class="form-control form-control-lg" checked  id="willLocationCheck" name="willLocationCheck"> 
                                                </div>
                                                <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                    <input type="text" class="form-control form-control-lg" id="jobLocationType" name="jobLocationType" value="TELECOMMUTE" readonly>
                                                </div>
                                            <?php
                                                }
                                                else {
                                            ?>
                                                <div class="col-lg-6 px-sm-1 col-sm-12 mt-2 mb-2 check-label">
                                                    <label style="width: 350px; margin-left: 25px;" class="form-check-label" for="willLocationCheck">Will add jobLocationType tag?</label>
                                                    <input style="height: 30px;" type="checkbox" class="form-control form-control-lg"  id="willLocationCheck" name="willLocationCheck"> 
                                                </div>
                                                <div class="col-lg-4 px-sm-1 col-sm-12 mt-2 mb-2">
                                                    <input type="text" style="display: none;" class="form-control form-control-lg" id="jobLocationType" name="jobLocationType" value="TELECOMMUTE" readonly>
                                                </div>
                                            <?php
                                                }
                                            ?>
                                            
                                        </div>
                                    </form>
                                </div>


                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center mt-2 ml-4 mb-2 mr-4">
                                        <p class="mb-0" id="tagNumber"></p>
                                    </div>
                                    <div>
                                        <table class="table table-striped align-middle">
                                            <tbody id="parsing">
                                                <input type="hidden" id="xmlid" name="xmlid" value="<?php echo $edit_id; ?>">
                                                <?php foreach($basetag as $key => $basevalue) {?>
                                                    <tr>
                                                        <td class="align-middle"><strong> &lt;<?php echo $basevalue; ?>&gt;</strong></td>
                                                        <!-- <td class="align-middle text" data-container="body" data-toggle="popover" data-placement="top" data-content="rices."><span><i class="fas fa-eye"></i>${baseValue[i]}</span></td> -->
                                                        <td align="right" class="" style="width: 15%;">
                                                            <div class="dropdown">
                                                                <button class="btn btn-default  dropdown-toggle btn-block" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <?php echo $updatetag[$key]; ?>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-status-tag dropdown-menu-right p-4" aria-labelledby="dropdownMenuButton">
                                                                    <div class="row mx-sm-n1">
                                                                        <div class="col-md">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_1" value="Default" <?php echo ($updatetag[$key]=='Default')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_1">
                                                                                &lt;Default&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_2" value="id" <?php echo ($updatetag[$key]=='id')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_2">
                                                                                &lt;id&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_3" value="title" <?php echo ($updatetag[$key]=='title')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_3">
                                                                                &lt;title&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_4" value="company" <?php echo ($updatetag[$key]=='company')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_4">
                                                                                &lt;company&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_5" value="addressCountry" <?php echo ($updatetag[$key]=='addressCountry')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_5">
                                                                                &lt;addressCountry&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_6" value="city" <?php echo ($updatetag[$key]=='city')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_6">
                                                                                &lt;city&gt;
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md">                                                                        
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_26" value="city" <?php echo ($updatetag[$key]=='addressRegion')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_26">
                                                                                &lt;addressRegion&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_7" value="geonameId" <?php echo ($updatetag[$key]=='geonameId')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_7">
                                                                                &lt;geonameId&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_8" value="geonameLocality" <?php echo ($updatetag[$key]=='geonameLocality')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_8">
                                                                                &lt;geonameLocality&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_9" value="geonameLongitude" <?php echo ($updatetag[$key]=='geonameLongitude')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_9">
                                                                                &lt;geonameLongitude&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_10" value="geonameLatitude" <?php echo ($updatetag[$key]=='geonameLatitude')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_10">
                                                                                &lt;geonameLatitude&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_11"  value="content" <?php echo ($updatetag[$key]=='content')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_11">
                                                                                &lt;content&gt;
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_14" value="url" <?php echo ($updatetag[$key]=='url')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_14">
                                                                                &lt;url&gt;
                                                                                </label>
                                                                            </div>
                                                                            
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_12" value="datePosted" <?php echo ($updatetag[$key]=='datePosted')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_12">
                                                                                &lt;datePosted&gt;
                                                                                </label>
                                                                            </div>
                                                                            <!-- <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_15" value="contractType" <?php echo ($updatetag[$key]=='contractType')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_15">
                                                                                &lt;contractType&gt;
                                                                                </label>
                                                                            </div> -->
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_16" value="remotePolicy" <?php echo ($updatetag[$key]=='remotePolicy')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_16">
                                                                                &lt;remotePolicy&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_17" value="employmentType" <?php echo ($updatetag[$key]=='employmentType')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_17">
                                                                                &lt;employmentType&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_18" value="salaryCurrency" <?php echo ($updatetag[$key]=='salaryCurrency')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_18">
                                                                                &lt;salaryCurrency&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_19" value="industry" <?php echo ($updatetag[$key]=='industry')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_19">
                                                                                &lt;industry&gt;
                                                                                </label>
                                                                            </div>                                            
                                                                        </div>
                                                                        <div class="col-md">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_20" value="estimatedSalary" <?php echo ($updatetag[$key]=='estimatedSalary')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_20">
                                                                                &lt;estimatedSalary&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_21" value="validThrough" <?php echo ($updatetag[$key]=='validThrough')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_21">
                                                                                &lt;validThrough&gt;
                                                                                </label>
                                                                            </div>                                                
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_22" value="hiringOrganization" <?php echo ($updatetag[$key]=='hiringOrganization')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_22">
                                                                                &lt;hiringOrganization&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_23" value="occupationalCategory" <?php echo ($updatetag[$key]=='occupationalCategory')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_23">
                                                                                &lt;occupationalCategory&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_24" value="logoUrl" <?php echo ($updatetag[$key]=='logourl')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_24">
                                                                                &lt;logoUrl&gt;
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="tagRadio_<?php echo $key; ?>" id="labelRadio_<?php echo $key; ?>_25" value="discard" <?php echo ($updatetag[$key]=='discard')?'checked':'' ?> >
                                                                                <label class="form-check-label" for="labelRadio_<?php echo $key; ?>_25">
                                                                                Discard
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                
                                            </tbody>
                                        </table>
                                        <!-- /.table -->
                                        <div class="p-5 text-center">
                                        <!-- <p class="lead"> <br>Please Check xml url and update</p> -->
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

