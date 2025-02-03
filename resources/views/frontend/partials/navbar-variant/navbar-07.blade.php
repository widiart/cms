@php
$bahasa = request()->segments()[0] ?? '';
$searchUrl = '';

if($bahasa == 'id') {
    $page = request()->segments()[1] ?? '';
    $bahasa = 'id';
    if($page == 'thenove') {
        $searchUrl = '/thenove';
        $url = "$page/".request()->segments()[2];
    }
} else {
    $bahasa = '';
    $page = request()->segments()[0] ?? '';
    if($page == 'thenove') {
        $searchUrl = '/thenove';
        $url = "$page/".request()->segments()[1];
    }
}

if(empty($url)) {
    $url = "$page";
}
@endphp
<div id="nuvasabay">
    <div class="site-mobile-menu site-navbar-target bg-primary">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->
</div>

<div id="nuvasabay">
    <header class="main-header">
        <div class="site-navbar site-navbar-target js-sticky-header bg-white border-bottom">
            <div class="container">
                <div class="row align-items-center" style="height: 84px">
                    <div class="col-4 d-block d-lg-none">
                        <div class="nav-item login-new-two">
                            <a class="nav-link login-btn text-center" href="/login">LOGIN</a>
                        </div>
                    </div>
                    <div class="col-4 col-lg-2">
                        <h1 class="my-0 site-logo text-center text-lg-start">
                            <a href="{{url('/')}}" class="logo navbar-brand">
                                @if(!empty(filter_static_option_value('site_logo',$global_static_field_data)))
                                    {!! render_image_markup_by_attachment_id(filter_static_option_value('site_logo',$global_static_field_data)) !!}
                                @else
                                    <h2 class="site-title">{{filter_static_option_value('site_'.$user_select_lang_slug.'_title',$global_static_field_data)}}</h2>
                                @endif
                            </a>
                        </h1>
                    </div>
                    <div class="col-4 col-lg-10">
                        <nav class="site-navigation text-right" role="navigation">
                            <div class="container">
                            <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3"><p class="site-menu-toggle js-menu-toggle text-white my-0"><span class="icon-menu h3"></span></p></div>

                            <ul class="site-menu main-menu js-clone-nav d-none d-xl-block">
                                <li>
                                    <div class="input-group search_form_two">
                                        <form action="{{$searchUrl}}/blog-search" class="d-block position-relative mx-3">
                                            <div class="form-outline">
                                                <input id="search-input" id="form1" class="form-control text-white" name="search" placeholder="Search" autocomplete="off"/>
                                            </div>
                                            <button id="search-button" type="submit" class="btn position-absolute end-0 top-0">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                                <li class="nav-item search-logic" style="width: 500px; display: none;">
                                    <form action="{{$searchUrl}}/blog-search" method="get" class="d-flex search-logic-child w-100"
                                        role="search">
                                        <input class="form-control me-2 flex-fill" placeholder="Search" aria-label="Search" name="search">
                                        <button type="submit" class="login-btn"><i class="fas fa-search"></i></button>
                                    </form>
                                </li>
                                {!! render_frontend_menu($primary_menu) !!}
                                <li class="nav-item d-none d-lg-inline-block">
                                    <a class="nav-link" href="#" onclick="searchClick();">
                                        <img class="openclose-search" src="/assets/nuvasabay/images/home/Icon Search.png" width="22" height="22"
                                            alt="Logo.png" />
                                        <i class="fa-solid fa-xmark openclose-search text-primary" style="display: none; font-size: 22px;"></i>
                                    </a>
                                </li>
                                <li class="nav-item d-none d-lg-inline-block">
                                    <a class="nav-link login-btn mx-3" href="/login">LOGIN</a>
                                </li>
                                <li class="nav-item">
                                    <ul class="change_language nav-link justify-content-left">
                                        <li><a href="/id/{{ $url }}" class="Id">ID</a></li>
                                        <li><a class="Id">|</a></li>
                                        <li><a href="/{{ $url }}" class="Id">EN</a></li>
                                    </ul>
                                </li>
                            </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>