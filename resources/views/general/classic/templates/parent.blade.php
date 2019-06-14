<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="gdevilbat">
    <title>@yield('title', env('APP_NAME')) - {{!empty($settings->where('name','global')->flatten()[0]->value['meta_title']) ? $settings->where('name','global')->flatten()[0]->value['meta_title'] : 'gdevilbat'}}</title>
    @section('meta_tag')
      <meta name="description" content="{{!empty($settings->where('name','global')->flatten()[0]->value['meta_description']) ? $settings->where('name','global')->flatten()[0]->value['meta_description'] : 'The Best Place To Find The Best Pampering Place'}}" />
      <meta name="keywords" content="{{!empty($settings->where('name','global')->flatten()[0]->value['meta_keyword']) ? $settings->where('name','global')->flatten()[0]->value['meta_keyword'] : env('APP_NAME')}}" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="{{!empty($settings->where('name','global')->flatten()[0]->value['fb_share_title']) ? $settings->where('name','global')->flatten()[0]->value['fb_share_title'] : env('APP_NAME')}}" />
      <meta property="og:description" content="{{!empty($settings->where('name','global')->flatten()[0]->value['fb_share_description']) ? $settings->where('name','global')->flatten()[0]->value['fb_share_description'] : 'Simple And Elegant CMS Laravel'}}" />
      <meta property="og:image" content="{{!empty($settings->where('name','global')->flatten()[0]->value['fb_share_image']) ? asset($settings->where('name','global')->flatten()[0]->value['fb_share_image']) : asset('public/img/LOGO_1024X1024.jpg')}}"/>
      <meta property="og:image:alt" content="{{!empty($settings->where('name','global')->flatten()[0]->value['fb_share_title']) ? $settings->where('name','global')->flatten()[0]->value['fb_share_title'] : env('APP_NAME')}}" />
      <meta property="og:url"           content="{{url()->full()}}" />
      <meta property="og:image:width" content="1024" />
      <meta property="og:image:height" content="1024" />
    @show
    <link rel="icon" type="image/png" sizes="1024x1024" href="{{asset(!empty($settings->where('name','global')->flatten()->first()->value['favicon']) ? $settings->where('name','global')->flatten()->first()->value['favicon'] : config('app.name'))}}">
    <meta property="webcrawlers" content="all" />
    <meta property="spiders" content="all" />
    <meta property="robots" content="all" />
    <meta name="google-site-verification" content="{{!empty($settings->where('name','global')->flatten()->first()->value['google_site_verification']) ? $settings->where('name','global')->flatten()->first()->value['google_site_verification'] : ''}}" />

    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
    <!-- Plugin-CSS -->
    {{Html::style(module_asset_url('blog:assets/css/app.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/owl.carousel.min.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/icofont.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/magnific-popup.css'))}}
    {{Html::style(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/css/animate.css'))}}
    {{Html::style(module_asset_url('blog:assets/fontawesome/css/all.min.css'))}}
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

    {!!!empty($settings->where('name','global')->flatten()->first()->value['meta_script']) ? $settings->where('name','global')->flatten()->first()->value['meta_script'] : ''!!}

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
     </script>
</head>

<body class="d-flex flex-column">
    <!--Preloader-->
    <div class="preloader">
        <div class="spinner"></div>
    </div>

    <!-- Mainmenu-Area -->
    <nav class="navbar navbar-expand-lg navbar-fixed-top mainmenu-area">
      <div class="container">
        <div class="w-100">
            <div class="col-12">
                <div id="search-box" class="collapse">
                    <form action="#">
                        <input type="search" class="form-control" placeholder="What do you want to know?">
                    </form>
                </div>
            </div>
            <div class="col-12">
              <div class="d-flex float-lg-left">
                <img src="{{empty($settings->where('name','global')->flatten()->first()->value['logo']) ? module_asset_url('core:assets/images/Spartan.png') : url($settings->where('name','global')->flatten()->first()->value['logo'])}}" alt="logo">
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fas fa-bars"></i>
                </button>
              </div>
              <div class="collapse navbar-collapse px-3 px-lg-0" id="navbarSupportedContent">
                <ul class="nav navbar-nav ml-auto">
                  @foreach ($navbars as $navbar)
                      @if(isset($navbar->children))
                        <li class="dropdown">
                          <a href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}" class="dropdown-toggle nav-link" aria-haspopup="true" aria-expanded="false">
                            {{$navbar->text}}
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            @include('blog::general.'.$theme_public->value.'.partials.navbar_child', ['navbars' => $navbar->children])
                          </ul>
                        </li>
                      @else
                        <li>
                          <a class="nav-link" href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}">{{$navbar->text}}</a>
                        </li>
                      @endif
                    @endforeach
                    <li><a class="nav-link" href="#search-box" data-toggle="collapse"><i class="icofont icofont-search-alt-2"></i></a></li>
                </ul>
              </div>
          </div>
        </div>
      </div>
    </nav>
    <!-- Mainmenu-Area-/ -->


    @yield('content')

    <!-- Footer-Area -->
    <footer class="footer-area mt-auto">
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <div class="copyright d-flex justify-content-center">
                          <nav class="navbar-expand-lg">
                            <ul class="nav ml-auto">
                              <li class="border-right">
                                <span class="pr-1">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved <i class="icofont icofont-heart-alt" aria-hidden="true"></i></span>
                              </li>
                              @foreach ($post_navbars as $navbar)
                                  <li>
                                    <a class="nav-link py-0 px-1" href="{{isset($navbar->slug) ? url($navbar->slug) : 'javascript:void(0)'}}" target="{{$navbar->target}}" title="{{$navbar->title}}">{{$navbar->text}}</a>
                                  </li>
                              @endforeach
                            </ul>
                          </nav>
                        </div>
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
    {{Html::script(module_asset_url('blog:assets/js/app.js'))}}
    {{Html::script(module_asset_url('blog:assets/fontawesome/js/all.min.js'))}}
    <!--Plugin-JS-->

    @yield('page_level_js')
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/owl.carousel.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/appear.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/bars.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/counterup.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/easypiechart.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/mixitup.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/contact-form.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/scrollUp.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/magnific-popup.min.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/wow.min.js'))}}
    <!--Main-active-JS-->
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/main.js'))}}
    {{Html::script(module_asset_url('blog:resources/views/general/'.$theme_public->value.'/js/classic.js?v='.filemtime(module_asset_path('blog:resources/views/general/'.$theme_public->value.'/js/classic.js'))))}}

    @yield('page_script_js')
</body>

</html>