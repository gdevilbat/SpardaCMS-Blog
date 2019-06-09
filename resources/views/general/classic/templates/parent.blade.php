<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta name="author" content="John Doe">
    <meta name="description" content="">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Classic One Page Template</title>
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
    <!-- Plugin-CSS -->
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/bootstrap.min.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/owl.carousel.min.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/icofont.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/magnific-popup.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/animate.css'))}}
    <!-- Main-Stylesheets -->
    @yield('page_level_css')
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/normalize.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/style.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/responsive.css'))}}

    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/classic.css?v='.filemtime(module_asset_path('blog:resources/views/general/'.$theme_public->value.'/css/classic.css'))))}}
    @yield('page_style_css')


    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/vendor/modernizr-2.8.3.min.js'))}}

    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body data-spy="scroll" data-target=".mainmenu-area">
    <!--Preloader-->
    <div class="preloader">
        <div class="spinner"></div>
    </div>

    <!-- Mainmenu-Area -->
    <nav class="navbar mainmenu-area" data-spy="affix" data-offset-top="197">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div id="search-box" class="collapse">
                        <form action="#">
                            <input type="search" class="form-control" placeholder="What do you want to know?">
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="navbar-header smoth">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainmenu">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span> 
                        </button>
                        <a class="navbar-brand" href="#home-area"><img src="images/logo.png" alt=""></a>
                    </div>
                    <div class="collapse navbar-collapse navbar-right" id="mainmenu">
                        <ul class="nav navbar-nav navbar-right help-menu">
                            <li><a href="#search-box" data-toggle="collapse"><i class="icofont icofont-search-alt-2"></i></a></li>
                        </ul>
                        <ul class="nav navbar-nav primary-menu">
                            @foreach ($navbars as $navbar)
                              @if(isset($navbar->children))
                                <li class="dropdown">
                                  <a href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}" class="dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                                    {{$navbar->text}}
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    @include('blog::general.'.$theme_public->value.'.partials.navbar_child', ['navbars' => $navbar->children])
                                  </ul>
                                </li>
                              @else
                                <li>
                                  <a href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}">{{$navbar->text}}</a>
                                </li>
                              @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Mainmenu-Area-/ -->


    <!--Header-Area-->
    <header class="header-area overlay" id="home-area">
        <div class="vcenter">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-md-8">
                        <div class="header-text">
                            <h2 class="header-title wow fadeInUp">We Are Provide Creative Business <span class="dot"></span></h2>
                            <div class="wow fadeInUp" data-wow-delay="0.5s"><q>We Mak Sure Best Business Solution For Our Client</q></div>
                            <div class="wow fadeInUp" data-wow-delay="0.7s">
                                <a href="#" class="bttn bttn-lg bttn-primary">Contact Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--Header-Area-/-->




    <!-- Footer-Area -->
    <footer class="footer-area">
        <div class="footer-top section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <div class="footer-text">
                            <h4 class="upper">Classic</h4>
                            <p>If you are going to use a passage of Lorem Ipsum, you need to be sure</p>
                            <div class="social-menu">
                                <a href="#"><i class="icofont icofont-social-facebook"></i></a>
                                <a href="#"><i class="icofont icofont-social-twitter"></i></a>
                                <a href="#"><i class="icofont icofont-social-google-plus"></i></a>
                                <a href="#"><i class="icofont icofont-social-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2 col-md-offset-1">
                        <div class="footer-single">
                            <h4 class="upper">News</h4>
                            <ul>
                                <li><a href="#">Subsciption</a></li>
                                <li><a href="#">New Apps</a></li>
                                <li><a href="#">Download now</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <div class="footer-single">
                            <h4 class="upper">Company</h4>
                            <ul>
                                <li><a href="#">Screenshot</a></li>
                                <li><a href="#">Fetures</a></li>
                                <li><a href="#">Price</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <div class="footer-single">
                            <h4 class="upper">Resources</h4>
                            <ul>
                                <li><a href="#">Support</a></li>
                                <li><a href="#">Contact</a></li>
                                <li><a href="#">Privacy &amp; Term</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-2">
                        <div class="footer-single">
                            <h4 class="upper">Solutions</h4>
                            <ul>
                                <li><a href="#">Bug Fixing</a></li>
                                <li><a href="#">Upgrade</a></li>
                                <li><a href="#">Malware Protect</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p class="copyright">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <i class="icofont icofont-heart-alt" aria-hidden="true"></i></p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer-Area / -->

     {{-- Javascript Core --}}
    <script type="text/javascript">
      var base = <?= "'".url('/')."'" ?>;
    </script>


    <!--Vendor-JS-->
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/vendor/jquery-1.12.4.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/vendor/bootstrap.min.js'))}}
    <!--Plugin-JS-->

    @yield('page_level_js')
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/owl.carousel.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/appear.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/bars.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/waypoints.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/counterup.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/easypiechart.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/mixitup.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/contact-form.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/scrollUp.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/magnific-popup.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/wow.min.js'))}}
    <!--Main-active-JS-->
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/main.js'))}}
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXZ3vJtdK6aKAEWBovZFe4YKj1SGo9V20&callback=initMap"></script>
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/maps.js'))}}

    @yield('page_script_js')
</body>

</html>