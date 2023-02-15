    <div id="wrapper" class="wrapper bg-ash">
       <!-- Header Menu Area Start Here -->
        <div class="navbar navbar-expand-md header-menu-one bg-light">
            <div class="nav-bar-header-one">
                <div class="header-logo">
                    <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>">
                        <img style="max-height: 50px;" src="<?php echo(ADMIN.'/img/logo.png'); ?>" alt="logo"> 
                    </a>
                </div>
                 <div class="toggle-button sidebar-toggle">
                    <button type="button" class="item-link">
                        <span class="btn-icon-wrap">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="d-md-none mobile-nav-bar">
               <button class="navbar-toggler pulse-animation" type="button" data-toggle="collapse" data-target="#mobile-navbar" aria-expanded="false">
                    <i class="far fa-arrow-alt-circle-down"></i>
                </button>
                <button type="button" class="navbar-toggler sidebar-toggle-mobile">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="header-main-menu collapse navbar-collapse" id="mobile-navbar">
                <ul class="navbar-nav">
                    <li class="navbar-item header-search-bar">
                        
                    </li>
                </ul>
                <?php $userArr = $this->common->getadminProfile($this->session->userdata('admin_id')); ?>
                <ul class="navbar-nav">
                    <li class="navbar-item dropdown header-admin">
                        <a class="navbar-nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                            aria-expanded="false">
                            <div class="admin-title">
                                <h5 class="item-title"><?php echo $userArr['fullname']; ?></h5>
                                <span>Admin</span>
                            </div>
                            <div class="admin-img">
                                <img style="max-width: 50px;" src="<?php echo $userArr['profile_pic']; ?>" alt="Admin">
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="item-header">
                                <h6 class="item-title"><?php echo $userArr['fullname']; ?></h6>
                            </div>
                            <div class="item-content">
                                <ul class="settings-list">
                                    <li><a href="<?php echo(ADMINURL.'Admin/editProfile'); ?>"><i class="flaticon-gear-loading"></i>Account Settings</a></li>
                                    <li><a href="<?php echo(ADMINURL.'Admin/do_logout'); ?>"><i class="flaticon-turn-off"></i>Log Out</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Header Menu Area End Here -->
        <!-- Page Area Start Here -->
        <div class="dashboard-page-one">
            <!-- Sidebar Area Start Here -->
            <div class="sidebar-main sidebar-menu-one sidebar-expand-md sidebar-color">
               <div class="mobile-sidebar-header d-md-none">
                    <div class="header-logo">
                        <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>"><img src="<?php echo(ADMIN.'/img/logo.png'); ?>" alt="logo"></a>
                    </div>
               </div>
                <div class="sidebar-menu-content">
                    <ul class="nav nav-sidebar-menu sidebar-toggle-view">
                        <?php if($this->uri->segment(3)=='dashboard'){ $show='menu-active';  }else{ $show='';  } ?>
                        <li class="nav-item">
                            <a href="<?php echo(ADMINURL.'Admin/dashboard/'); ?>" class="nav-link <?php echo $show; ?>"><i
                                    class="flaticon-dashboard"></i><span>Dashboard</span></a>
                        </li>


                        <?php if($this->uri->segment(2)=='User'){ $show='menu-active'; $submenu='active'; $openmenu = 'menu-open'; $openstyle='display:block'; }else{ $show=''; $submenu=''; $openmenu = ''; $openstyle='display:none'; } ?>
                        <li class="nav-item sidebar-nav-item <?php echo $submenu; ?>">
                            <a href="#" class="nav-link"><i class="flaticon-menu-1"></i><span>User</span></a>
                            <ul class="nav sub-group-menu <?php echo $openmenu; ?>" style="<?php echo $openstyle; ?>">
                                <li class="nav-item">
                                    <a href="<?php echo(ADMINURL.'User/listing/'); ?>" class="nav-link"><i class="fas fa-angle-right"></i>User Listing</a>
                                </li>

                            </ul>
                        </li>

                        <?php if($this->uri->segment(2)=='Captain'){ $show='menu-active'; $submenu='active'; $openmenu = 'menu-open'; $openstyle='display:block'; }else{ $show=''; $submenu=''; $openmenu = ''; $openstyle='display:none'; } ?>
                        <li class="nav-item sidebar-nav-item <?php echo $submenu; ?>">
                            <a href="#" class="nav-link"><i class="flaticon-menu-1"></i><span>Captain</span></a>
                            <ul class="nav sub-group-menu <?php echo $openmenu; ?>" style="<?php echo $openstyle; ?>">
                                <li class="nav-item">
                                    <a href="<?php echo(ADMINURL.'Captain/listing/'); ?>" class="nav-link"><i class="fas fa-angle-right"></i>Captain Listing</a>
                                </li>

                            </ul>
                        </li>

                       
                        
                        <?php if($this->uri->segment(3)=='editProfile'){ $show='menu-active';}else{ $show=''; } ?>
                        <li class="nav-item">
                            <a href="<?php echo(ADMINURL.'Admin/editProfile'); ?>" class="nav-link <?php echo $show; ?>"><i
                                    class="flaticon-settings"></i><span>Account</span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Sidebar Area End Here -->