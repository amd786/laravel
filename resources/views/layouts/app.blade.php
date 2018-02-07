<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{{ url('/img/small-logo.png') }}}">
    <title>Power Seal</title>

    <!-- BEGIN GLOBAL MANDATORY STYLES. -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    {{ Html::style('css/font-awesome.min.css') }}
    {{ Html::style('css/bootstrap.css') }}
    {{-- Html::style('css/bootstrap-formhelpers.min.css') --}}
    {{-- Html::style('css/bootstrap-formhelpers.min.css') --}}
    {{ Html::style('css/build.css') }}

    <!-- layout styles -->
    {{ Html::style('css/layout.min.css') }}
    {{ Html::style('css/components.min.css') }}
    {{ Html::style('css/simple-line-icons.min.css') }}
    {{ Html::style('css/darkblue.min.css') }}
    {{ Html::style('css/bootstrap-editable.css') }}
    {{ Html::style('css/jquery.growl.css') }}
    {{ Html::style('css/select2.min.css') }}
    {{ Html::style('css/bootstrap-datepicker3.min.css') }}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    {{ Html::style('css/daterangepicker.css') }}
    {{ Html::style('css/style.css') }}
    <!-- autocomplete-->
    <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
    <!-- autocomplete-->
    <!-- END THEME LAYOUT STYLES -->
    {{ Html::script('js/jquery.min.js') }}
    <!--autocomplete-->
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
    <!--autocomplete-->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.13/pagination/input.js"></script>
    {{ Html::script('js/bootstrap.min.js') }}
    {{-- Html::script('js/bootstrap-formhelpers.min.js') --}}
    {{-- Html::script('js/bootstrap-switch.min.js') --}}
    {{ Html::script('js/bootstrap-editable.js') }}
    {{ Html::script('js/jquery.slimscroll.min.js') }}
    {{ Html::script('js/jquery.growl.js') }}
    {{ Html::script('js/select2.min.js') }}
    {{ Html::script('js/app.min.js') }}
    {{ Html::script('js/layout.min.js') }}
    {{ Html::script('js/quick-sidebar.min.js') }}
    {{ Html::script('js/bootstrap-datepicker.min.js') }}
    {{ Html::script('js/moment.min.js') }}
    {{ Html::script('js/daterangepicker.js') }}
    {{ Html::script('js/jquery.maskedinput.min.js') }}
    {{ Html::script('js/custom.js') }}
    <!-- star rating-->
    {{ Html::style('css/star-rating.min.css') }}
    {{ Html::script('js/star-rating.js') }}
    <!-- star rating-->

    <!-- full calendar-->
    {{ Html::script('js/fullcalendar.min.js') }}
    {{ Html::style('css/fullcalendar.min.css') }}



<link rel="shortcut icon" href="favicon.ico" />
    <script type="text/javascript">
        var baseURL = "{!!url('/')!!}";
    </script>
</head>
<?php $sidebar_toggle = App\Library\Functions::getClientToggleSettings(); ?>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white {{(isset($sidebar_toggle) && $sidebar_toggle=='compact') ? 'page-sidebar-closed' : ''}}">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <span class="username username-hide-on-mobile"> {{ Auth::User()->fullName() }} </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a href="{{ (Auth::user()->is_client==1) ? action('ClientController@EditProfile',['id'=>Auth::User()->id]) : action('UserController@EditProfile')}}">
                                            <i class="icon-user"></i> My Profile </a>
                                    </li>
                                    <li class="divider"> </li>
                                    <li>
                                        <a href="{{ url('/logout') }}">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <!-- <li class="dropdown dropdown-quick-sidebar-toggler">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <i class="icon-logout"></i>
                                </a>
                            </li> -->
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- BEGIN SIDEBAR MENU -->
                        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                        <ul class="page-sidebar-menu page-header-fixed white-sidebar page-sidebar-menu-closed" style="padding-top: 20px" data-slide-speed="200" data-auto-scroll="true" data-keep-expanded="false">
                            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <!-- <li class="sidebar-toggler-wrapper hide">
                                <div class="sidebar-toggler">
                                    <span></span>
                                </div>
                            </li> -->
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                            <div class="clearfix"></div>
                              <div class="page-logo-img {{(isset($sidebar_toggle) && $sidebar_toggle=='compact') ? '' : 'hide'}}">
                                  <a href="#">
                                      <img src="{{ url('/img/small-logo.png') }}" alt="PS" class="logo-default">
                                  </a>
                              </div>
                              <div class="page-small-logo {{(isset($sidebar_toggle) && $sidebar_toggle=='compact') ? 'hide' : ''}}">
                                  <a href="#">
                                      <img src="{{ url('/img/White-Logo.png') }}" alt="PS" class="logo-default">
                                  </a>
                              </div><br>
                            <!-- for client  -->
                            @if((App\Library\Functions::check(46,'R') || App\Library\Functions::check(47,'R') || App\Library\Functions::check(48,'R') || App\Library\Functions::check(49,'R')) && Auth::user()->is_client==1)
                            <li class="nav-item start {{ App\Library\Functions::set_active(['client/*']) }} {{ App\Library\Functions::set_active(['/']) }} ">
                                <a href="{{ action('ClientController@home') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-home"></i>
                                    <span class="title">Home</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>

                            <li class="nav-item start {{ App\Library\Functions::set_active(['client-settings']) }} ">
                                <a href="{{ action('ClientController@ClientSettings') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-cog"></i>
                                    <span class="title">Settings</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif
                            <!-- end here for client  -->
                            @if(App\Library\Functions::check(45,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['dashboard*']) }} {{ App\Library\Functions::set_active(['/']) }} ">
                                <a href="{{ action('UserController@dashboard') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-tachometer"></i>
                                    <span class="title">Dashboard</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif
                            @if(App\Library\Functions::check(3,'R') || App\Library\Functions::check(1) || App\Library\Functions::check(2,'R'))
                              <li class="nav-item start {{ App\Library\Functions::set_active(['users*']) }} ">
                                  <a href="{{ action('UserController@index') }}" class="nav-link nav-toggle">
                                      <i class="icon-user"></i>
                                      <span class="title">Users</span>
                                      <span class="selected"></span>
                                      <span class=" open"></span>
                                  </a>
                              </li>
                            @endif

                            @if(App\Library\Functions::check(4) || App\Library\Functions::check(5) || App\Library\Functions::check(6,'R') || App\Library\Functions::check(7,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['crm*']) }}">
                                <a href="{{ action('CrmController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-list-alt"></i>
                                    <span class="title">CRM</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif

                            @if(App\Library\Functions::check(8,'R') || App\Library\Functions::check(9,'R') || App\Library\Functions::check(10,'R') || App\Library\Functions::check(11,'R') || App\Library\Functions::check(12,'R') || App\Library\Functions::check(13,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['products*']) }}">
                                <a href="{{ action('ProductController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-cube"></i>
                                    <span class="title">Product Management</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif

                            @if(App\Library\Functions::check(14,'R') || App\Library\Functions::check(15,'R') || App\Library\Functions::check(16,'R') || App\Library\Functions::check(17,'R') || App\Library\Functions::check(18,'R') || App\Library\Functions::check(19,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['sales*']) }}">
                                <a href="{{ action('SaleController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="title">Sales</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif

                            @if(App\Library\Functions::check(21,'R') || App\Library\Functions::check(22,'R') || App\Library\Functions::check(23,'R') || App\Library\Functions::check(24,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['engineering*']) }}">
                                <a href="{{ action('EngineeringController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-cogs"></i>
                                    <span class="title">Engineering</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif

                            @if(App\Library\Functions::check(25,'R') || App\Library\Functions::check(26,'R') || App\Library\Functions::check(27,'R') || App\Library\Functions::check(28,'R') || App\Library\Functions::check(29,'R') || App\Library\Functions::check(30,'R') || App\Library\Functions::check(31,'R') || App\Library\Functions::check(32,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['inventory*']) }}">
                                <a href="{{ action('InventoryController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-archive"></i>
                                    <span class="title">Inventory</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif
                            @if(App\Library\Functions::check(34,'R') || App\Library\Functions::check(35,'R') || App\Library\Functions::check(36,'R') || App\Library\Functions::check(37,'R') || App\Library\Functions::check(38,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['purchase*']) }}">
                                <a href="{{ action('PurchaseController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-cart-plus"></i>
                                    <span class="title">Purchase</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif
                            @if(App\Library\Functions::check(50,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['orders*']) }}">
                                <a href="{{ action('OrdersController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-cart-plus"></i>
                                    <span class="title">Orders</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif
                            @if(App\Library\Functions::check(39,'R') || App\Library\Functions::check(40,'R') || App\Library\Functions::check(41,'R') || App\Library\Functions::check(42,'R') || App\Library\Functions::check(43,'R') || App\Library\Functions::check(44,'R'))
                            <li class="nav-item start {{ App\Library\Functions::set_active(['accounting*']) }}">
                                <a href="{{ action('AccountingController@index') }}" class="nav-link nav-toggle">
                                    <i class="fa fa-usd"></i>
                                    <span class="title">Accounting</span>
                                    <span class="selected"></span>
                                    <span class=" open"></span>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <!-- END SIDEBAR MENU -->
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                      <!--<div class="container">-->
                        @yield('content')
                      <!--</div>-->
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                <!-- BEGIN QUICK SIDEBAR -->

                <!-- END QUICK SIDEBAR -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2017 &copy; Power Seal
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <script type="text/javascript">
        <?php if(Session::has('success')){ ?>
            $.growl.notice({title:"Success", message: "<?php echo Session::get('success'); ?>",size:'large',duration:5000});
        <?php } ?>

        <?php if(Session::has('error')){ ?>
            $.growl.error({title:"Error", message: "<?php echo Session::get('error'); ?>",size:'large',duration:6000});
        <?php } ?>

        <?php if(Session::has('warning')){ ?>
            $.growl.warning({title:"Warning", message: "<?php echo Session::get('warning'); ?>",size:'large',duration:6000});
        <?php } ?>
        </script>
    <div class="ajax_loading"><p>Please do not refresh or close the browser</p></div>
    </body>
</html>
