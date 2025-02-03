@php
    $rtl_condition = get_user_lang_direction() == 'rtl' ? 'true' : 'false';
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
                            <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><p class="site-menu-toggle js-menu-toggle text-white my-0"><span class="icon-menu h3"></span></p></div>

                            <ul class="site-menu main-menu js-clone-nav d-none d-lg-block">
                                <li>
                                    <div class="input-group search_form_two">
                                        <form action="#" class="d-block position-relative mx-3">
                                            <div class="form-outline">
                                                <input id="search-input" type="search" id="form1" class="form-control" placeholder="Search" autocomplete="off"/>
                                            </div>
                                            <button id="search-button" type="button" class="btn position-absolute end-0 top-0">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                                <li class="nav-item search-logic" style="width: 500px; display: none;">
                                    <form action="/news-search" method="get" class="d-flex search-logic-child w-100"
                                        role="search">
                                        <input class="form-control me-2 flex-fill" type="search" placeholder="Search" aria-label="Search" name="search">
                                    </form>
                                </li>
                                {!! render_frontend_menu($primary_menu) !!}
                                {{-- <li class="active"><a href="#home-section" class="nav-link">Home</a></li>
                                <li><a href="#classes-section" class="nav-link">Classes</a></li>
                                <li class="has-children">
                                <a href="#" class="nav-link">Pages</a>
                                <ul class="dropdown arrow-top">
                                    <li><a href="#" class="nav-link">Team</a></li>
                                    <li><a href="#" class="nav-link">Pricing</a></li>
                                    <li><a href="#" class="nav-link">FAQ</a></li>
                                    <li class="has-children">
                                    <a href="#">More Links</a>
                                    <ul class="dropdown">
                                        <li><a href="#">Menu One</a></li>
                                        <li><a href="#">Menu Two</a></li>
                                        <li><a href="#">Menu Three</a></li>
                                    </ul>
                                    </li>
                                </ul>
                                </li>
                                <li><a href="#about-section" class="nav-link">About</a></li>
                                <li><a href="#events-section" class="nav-link">Events</a></li>
                                <li><a href="#gallery-section" class="nav-link">Gallery</a></li>
                                <li><a href="#contact-section" class="nav-link">Contact</a></li> --}}
                                <li class="nav-item">
                                    <a class="nav-link" href="#" onclick="searchClick();">
                                        <img class="openclose-search" src="/assets/nuvasabay/images/home/Icon Search.png" width="22" height="22"
                                            alt="Logo.png" />
                                        <i class="fa-solid fa-xmark openclose-search text-primary" style="display: none; font-size: 22px;"></i>
                                    </a>
                                </li>
                                <li class="nav-item d-block d-md-none">
                                    <a class="nav-link login-btn mx-3" href="/login">LOGIN</a>
                                </li>
                                <li class="nav-item">
                                    <ul class="change_language nav-link justify-content-center">
                                        <li><a href="/ID/{{ '$url' }}" class="Id">ID</a></li>
                                        <li><a class="Id">|</a></li>
                                        <li><a href="/{{ '$url' }}" class="Id">EN</a></li>
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

<div id="nuvasabay" class="section-start">
    <!-- banner section -->
    <section id="banner_section" class="p-0">
        <div class="container-fluid p-0">
            <div class="banner position-relative">
                <div class="banner_area">
                    <video muted="" loop="" id="banner_video" controls="" autoplay="" style="object-fit: fill;" class="banner_video_index" width="100%" height="100%">
                        <source src="/assets/nuvasabay/images/home/nuv2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <!-- <div class="">
                    <h1 class="new-face-title">THE NEW FACE OF BATAM</h1>
                </div> -->
                <!-- <div class="banner_play">
                    <a href="javascript:void(0)" class="btn_play">
                        <i class="fa-solid fa-play"></i>
                    </a>
                </div> -->
            </div>
        </div>
    </section>
    <!-- banner section end-->

    <!-- evaluated section -->
    <section id="evalute_section">
        <div class="container p-0">
            <div class="heading text-center">
                <h2 class="mb-2 elevated-mobile">ELEVATED PARADISE ISLAND</h2>
                <h4 class="elevated-mobile-2" style="font-size: 22px"> MOST EXCLUSIVE RESORT LIVING</h4>
            </div>
            <div class="carousel">
                <div id="elevated_carousel" class="elevated_carousel owl-carousel owl-theme">
                    <div class="item px-3">
                        <div class="island_container">
                            <div class="row p-0 m-0 g-0">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <img src="/assets/nuvasabay/images/home/slider_1.jpg" alt="slider 1">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="elevated-slide-content">
                                        <h3 class="slide-title">Change the game</h3>
                                        <p class="slide-desc">Nuvasa Bay is here to change the game. As the name
                                            implies, 'Nu'-vasa Bay exists to herald in the "new" face of Batam:
                                            revamping and reshaping Batam's residential and mixed-use property
                                            landscape and taking it to the new heights.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item px-3">
                        <div class="island_container">
                            <div class="row p-0 m-0 g-0">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <img src="/assets/nuvasabay/images/home/slider_2.jpg" alt="slider 1">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="elevated-slide-content">
                                        <h3 class="slide-title">Spectacular Getaway Retreat</h3>
                                        <p class="slide-desc">It will be Batam's first integrated luxury
                                            residential and mixed-use development; A Heaven for the elite of
                                            Batam to reside in and enjoy the scenic natural beauty of the
                                            island. In addition, it will have the conveniences of first class
                                            living including medical facilities, education, leisure,
                                            entertainment, retail and food and beverages facilities.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item px-3">
                        <div class="island_container">
                            <div class="row p-0 m-0 g-0">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <img src="/assets/nuvasabay/images/home/slider_3.jpg" alt="slider 1">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="elevated-slide-content">
                                        <h3 class="slide-title">A Heaven for The Elite</h3>
                                        <p class="slide-desc">Nuvasa Bay lies on the spectacular beachfront of
                                            Nongsa, Batam, the largest city in the Riau Islands Province of
                                            Indonesia and across the Straits of Singapore. In addition, it
                                            boasts an award winning 18-hole international golf course and 1.2 km
                                            of pristine beachfront, positioning it as an ideal getaway retreat
                                            for regional tourists.</p>
                                        <p class="slide-desc">Nuvasa Bay's master plan was developed by the
                                            world famous destination designers WATG. Be part of something
                                            special, discover Nuvasa Bay.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel">
                <div id="elevated_carousel2" class="elevated_carousel2 owl-carousel owl-theme">
                    <div class="item px-3">
                        <div class="island_container">
                            <div class="row p-0 m-0 g-0">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <img src="/assets/nuvasabay/images/smart-move.jpg" alt="slider 1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item px-3">
                        <div class="island_container">
                            <div class="row p-0 m-0 g-0">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <img src="/assets/nuvasabay/images/smart-move.jpg" alt="slider 2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item px-3">
                        <div class="island_container">
                            <div class="row p-0 m-0 g-0">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <img src="/assets/nuvasabay/images/smart-move.jpg" alt="slider 3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- evaluated section end -->

    <section id="luxury_living_sec" class="">
        <div class="container-fluid p-0">
            <div class="row p-0 position-relative bg-primary-before">
                <div class="col-lg-8 col-md-8 col-12 bg-primary text-center py-3 heading">
                    <h2 class="text-white text-center text-md-end p-0 pe-lg-4 pe-md-5">PRODUCT
                    </h2>
                </div>
                <div class="col-lg-4 col-md-2 col-12"></div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-3 residential_col">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="overflow-hidden my-3 position-relative">
                        <i class="fa-solid fa-house-chimney-window text-white position-absolute bottom-0 start-0 m-3" style="z-index: 999;"></i>
                        <div class="position-relative">
                            <img src="/assets/nuvasabay/images/product/luxury_living.jpg" alt="residential bg" class="residential_bg_img">
                            <div class="logo">
                                <img src="/assets/nuvasabay/images/product/product-logo.jpg" alt="Product Logo" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center mb-2">The Nove</h3>
                    <div class="desc mb-3">
                        <p>Enjoy a marvelous view of Palm Springs golf course greens, lakes and scenic view of
                            1,2 km long beachfront. The Nove is perched on a serene location, with a 20 m
                            elevation above sea-level.</p>
                        <p>Your very own place to call home and kingdom, away from the hustle and bustle of
                            Batam Center or the buzzing metropolis of Singapore.</p>
                        <p>Yet, The Nove reminds you everyday of just how proximate you are to the lion city
                            with it’s scenic view of the Singapore skyline the seas’ horizon.</p>
                    </div>
                    <div class="text-center">
                        <a href="/thenove/our-uniqueness" class="btn bg-primary">View More</a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="overflow-hidden my-3 position-relative">
                        <i class="fa-solid fa-house-chimney-window text-white position-absolute bottom-0 start-0 m-3" style="z-index: 999;"></i>
                        <div class="position-relative">
                            <img src="/assets/nuvasabay/images/product/palm_spring.jpg" alt="residential bg" class="residential_bg_img">
                            <div class="logo">
                                <img src="/assets/nuvasabay/images/product/palm_logo.jpg" alt="Product Logo" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center mb-2">PALM SPRINGS GOLF <br class="d-lg-none d-block"> &amp; COUNTRY CLUB</h3>
                    <div class="desc mb-3">
                        <p>A premiere golf Resort in Batam, then fully completed on 1995 into 27 holes with the
                            name of Palm Springs Golf &amp; Country Club. On 2002 Palm Springs reconstructs and
                            redesigns Palm Course and make it one of the most beautiful and challenging course
                            in South East Asia.</p>
                        <p>Situated in spectacular view of beautiful beach of Nongsa with Singapore skyscrapers
                            in the background. This 27 Challenging holes golf course was designed by world known
                            Larry Nelson and IMG ( International Management Group ).</p>
                        <p>Integrating slopes and breathtaking view become a hallmark of this golf course, well
                            known as a resort course with spectacular sea view and undulating terrain, the 230
                            hectares location overs both beginners and professionals alight three ideal course (
                            Island course, Resort course and Palm course ) to swing into action. </p>
                        <p>You can choose your own combination of 18 holes, or even 27 to go with our three
                            courses combination.</p>
                    </div>
                    <div class="text-center">
                        <a href="#" class="btn bg-primary">View More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="nootherplace_section">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-md-4 col-12"></div>
                <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                    <h2 class="text-white text-nowrap">NO OTHER PLACE BUT HERE</h2>
                    <h5 class="text-white d-none d-md-block"></h5>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row p-0 mt-4">
                <div class="col-12 p-0">
                    <div id="place_carousel" class="place_carousel owl-carousel owl-theme">
                        <div class="item" data-aos="fade-up" data-aos-duration="1500">
                            <div class="row p-0 g-3">
                                <div class=" col-lg-4 col-md-6 col-12 place-col">
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/1-1.jpg" alt="place 1" style="height: 592px;">
                                        <div class="place_text bg-place-secondary">
                                            <h4>Luxury Residential</h4>
                                            <p>The only luxury residential and mixed-use development in Batam that
                                                exists harmoniously with natural</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 place-col">
                                    <div class="place_container mb-3">
                                        <img src="/assets/nuvasabay/images/no-other-place/1-2.jpg" alt="place 1">
                                        <div class="place_text bg-place-primary">
                                            <h4>30 Minutes to Singapore</h4>
                                            <p>Close proximity from Tanah Merah Ferry Terminal Singapore</p>
                                        </div>
                                    </div>
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/1-3.jpg" alt="place 1">
                                        <div class="place_text bg-place-primary ">
                                            <h4> 110 Minutes</h4>
                                            <p>From Jakarta</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-12 place-col">
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/1-4.jpg" alt="place 1" style="height: 592px;">
                                        <div class="place_text bg-place-secondary">
                                            <h4>1.2 Km of Long Curvy Beaches</h4>
                                            <p>We are the only integrated luxury residential and mixed-use complex
                                                with
                                                the longest beach front in Batam</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row g-3 p-0">
                                <div class="col-lg-4 col-md-6 col-12 place-col">
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/2-1.jpg" alt="place 1" style="height: 592px;">
                                        <div class="place_text bg-place-secondary">
                                            <h4>Bless by Nature</h4>
                                            <p>surrounded by beautiful beachfront, has amazing natural contours and
                                                natural mangrove views</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 place-col">
                                    <div class="place_container mb-3">
                                        <img src="/assets/nuvasabay/images/no-other-place/2-2.jpg" alt="place 1">
                                        <div class="place_text bg-place-primary">
                                            <h4>Healthy Environment</h4>
                                            <p>Surrounded by natural mangrove that combine with a range of premium
                                                facilities designed to give you the best healhy environment</p>
                                        </div>
                                    </div>
                                    <div class="place_container golf">
                                        <img src="/assets/nuvasabay/images/about/place_2.jpg" alt="place 1">
                                        <div class="place_text bg-place-primary ">
                                            <h4>360° Golf Course View</h4>
                                            <p>We are the only integrated luxury residential and mixed-use complex
                                                with the longest beach front in Batam.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-12 place-col">
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/2-4.jpg" alt="place 1" style="height: 592px;">
                                        <div class="place_text bg-place-secondary">
                                            <h4>A High Quality Lifestyle</h4>
                                            <p>for it’s exclusive residents.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row g-3 p-0">
                                <div class="col-lg-4 col-md-6 col-12 place-col">
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/3-1.jpg" alt="place 1" style="height: 592px;">
                                        <div class="place_text bg-place-secondary">
                                            <h4>Promising Future Development in the Area</h4>
                                            <!-- <p>The only luxury residential and mixed-use development in Batam that
                                                exists harmoniously with natural</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-12 place-col">
                                    <div class="place_container mb-3">
                                        <img src="/assets/nuvasabay/images/no-other-place/3-2.jpg" alt="place 1">
                                        <div class="place_text bg-place-primary">
                                            <h4>Infrastructure, Utilities and Facilities</h4>
                                            <!-- <p>Close proximity from Tanah Merah Ferry Terminal Singapore</p> -->
                                        </div>
                                    </div>
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/3-3.jpg" alt="place 1">
                                        <div class="place_text bg-place-primary ">
                                            <h4>A Premium Experience</h4>
                                            <p>For tourists who want to spend time with their family</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-12 place-col">
                                    <div class="place_container">
                                        <img src="/assets/nuvasabay/images/no-other-place/3-4.jpg" alt="place 1" style="height: 592px;">
                                        <div class="place_text bg-place-secondary">
                                            <h4>Good Return and Easy to Invest</h4>
                                            <p>Presents an opportunity to invest in a development with high
                                                potential returns as it is targeting the premium segments of both
                                                Batam’s local market and the foreign market from Singapore.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Awards -->
    <section id="award_section">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-md-4 col-12"></div>
                <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                    <h2 class="text-white text-nowrap">AWARDS</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row p-0 mt-4 awards_container">
                <div id="" class="award-carousel owl-carousel">
                    <div>
                        <div class="award_container">
                            <div class="award_img">
                                <img src="/assets/nuvasabay/images/home/award1.png" alt="" class="img-fluid">
                            </div>
                            <div class="award_name">
                                <p class="blacking">WINNER OF ASIA PACIFIC PROPERTY AWARDS DEVELOPMENT 2017-2018</p>
                            </div>
                            <div class="award_desc">
                                <p class="blacking">Award Winner Development Marketing Indonesia</p>
                            </div>
                            <div class="award_by">
                                <p class="blacking">The Nove Apartment
                                    Nuvasa Bay by Sinar Mas Land
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class=" award_container">
                            <div class="award_img">
                                <img src="/assets/nuvasabay/images/home/award2.png" alt="" class="img-fluid">
                            </div>
                            <div class="award_name">
                                <p class="blacking">WINNER OF ASIA PACIFIC PROPERTY AWARDS DEVELOPMENT 2017-2018</p>
                            </div>
                            <div class="award_desc">
                                <p class="blacking">Award Winner Development Marketing Indonesia</p>
                            </div>
                            <div class="award_by">
                                <p class="blacking">The Nove Apartment
                                    Nuvasa Bay by Sinar Mas Land
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="award_container">
                            <div class="award_img">
                                <img src="/assets/nuvasabay/images/home/award3.png" alt="" class="img-fluid">
                            </div>
                            <div class="award_name">
                                <p class="blacking">WINNER OF ASIA PACIFIC PROPERTY AWARDS DEVELOPMENT 2017-2018</p>
                            </div>
                            <div class="award_desc">
                                <p class="blacking">Award Winner Development Marketing Indonesia</p>
                            </div>
                            <div class="award_by">
                                <p class="blacking">The Nove Apartment
                                    Nuvasa Bay by Sinar Mas Land
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="award_container">
                            <div class="award_img">
                                <img src="/assets/nuvasabay/images/home/award4.png" alt="" class="img-fluid">
                            </div>
                            <div class="award_name">
                                <p class="blacking">WINNER OF ASIA PACIFIC PROPERTY AWARDS DEVELOPMENT 2017-2018</p>
                            </div>
                            <div class="award_desc">
                                <p class="blacking">Award Winner Development Marketing Indonesia</p>
                            </div>
                            <div class="award_by">
                                <p class="blacking">The Nove Apartment
                                    Nuvasa Bay by Sinar Mas Land
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="award_container">
                            <div class="award_img">
                                <img src="/assets/nuvasabay/images/home/award4.png" alt="" class="img-fluid">
                            </div>
                            <div class="award_name">
                                <p class="blacking">WINNER OF ASIA PACIFIC PROPERTY AWARDS DEVELOPMENT 2017-2018</p>
                            </div>
                            <div class="award_desc">
                                <p class="blacking">Award Winner Development Marketing Indonesia</p>
                            </div>
                            <div class="award_by">
                                <p class="blacking">The Nove Apartment
                                    Nuvasa Bay by Sinar Mas Land
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="text-center" data-aos="fade-up" data-aos-duration="1500">
                        <a href="/developer#award_section" class="btn load_more_btn">DEVELOPER AWARDS</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Awards end-->

    <section class="consultants-section-new">
        <div class="container text-center desktop">
            <h2 class="mb-5 text-white position-relative z-2">CONSULTANTS</h2>
            <div class="row g-4">
                <div class="col-3 mt-n4 position-relative z-1">
                    <img src="/assets/nuvasabay/images/home/logo1.png" class="img-fluid" style="width: 100%;">
                </div>
                <div class="col-3 mt-n4 position-relative z-1">
                    <img src="/assets/nuvasabay/images/home/logo2.png" class="img-fluid" style="width: 100%;">
                </div>
                <div class="col-3 mt-n4 position-relative z-1">
                    <img src="/assets/nuvasabay/images/home/logo3.png" class="img-fluid" style="width: 100%;">
                </div>
                <div class="col-3 mt-n4 position-relative z-1">
                    <img src="/assets/nuvasabay/images/home/logo4.png" class="img-fluid" style="width: 100%;">
                </div>
                <div class="col-4 mt-n5 mb-n4">
                    <img src="/assets/nuvasabay/images/home/logo5.png" class="img-fluid" style="width: 100%;">
                </div>
                <div class="col-4 mt-n5 mb-n4">
                    <img src="/assets/nuvasabay/images/home/logo6.png" class="img-fluid" style="width: 100%;">
                </div>
                <div class="col-4 mt-n5 mb-n4">
                    <img src="/assets/nuvasabay/images/home/logo7.png" class="img-fluid" style="width: 100%;">
                </div>
            </div>
        </div>
        <div class="container text-center mobile">
            <h2 class="mb-5">CONSULTANTS</h2>
            <div id="" class="consultants-carousel owl-carousel">
                <div>
                    <img src="/assets/nuvasabay/images/home/logo1.png" class="img-fluid" style="width: 100%;">
                </div>
                <div>
                    <img src="/assets/nuvasabay/images/home/logo2.png" class="img-fluid" style="width: 100%;">
                </div>
                <div>
                    <img src="/assets/nuvasabay/images/home/logo3.png" class="img-fluid" style="width: 100%;">
                </div>
                <div>
                    <img src="/assets/nuvasabay/images/home/logo4.png" class="img-fluid" style="width: 100%;">
                </div>
                <div>
                    <img src="/assets/nuvasabay/images/home/logo5.png" class="img-fluid" style="width: 100%;">
                </div>
                <div>
                    <img src="/assets/nuvasabay/images/home/logo6.png" class="img-fluid" style="width: 100%;">
                </div>
                <div>
                    <img src="/assets/nuvasabay/images/home/logo7.png" class="img-fluid" style="width: 100%;">
                </div>
            </div>
        </div>
    </section>
    <!-- CONSULTANTS end-->

    <!-- why batam -->
    <section id="why_batam_section">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-md-4 col-12"></div>
                <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                    <h2 class="text-white text-nowrap">WHY BATAM ?</h2>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row p-0 mt-4">
                <div class="col-lg-5 col-md-5 col-sm-12 pb-4">
                    <h1 class="text-secondary" style="font-size: 28px;">SEIZE ALL THE POTENTIAL</h1>
                    <p class="text-justify" style="font-size: 12px;">We are the only residential and mixed use integrated township in Batam
                        that is surrounded by
                        beautiful beachfront, golf course, has amazing natural contour and natural mangroves views.
                        We
                        are designing Nuvasa Bay as a place for you to live your daily life at, but surrounded by a
                        resort like environment. We are the largest township development in Batam with total area of
                        228
                        hectares.</p>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12 col-12 mb-1" data-aos="fade-up" data-aos-duration="1500">
                            <div class="icon_container">
                                <div class="icon">
                                    <img src="/assets/nuvasabay/images/home/area_icon.png" alt="area icon" class="img-fluid">
                                </div>
                                <div class="description">
                                    <p>Potential Area to Develop</p>
                                    <h5 class="count d-inline-block">228</h5><span class="d-inline-block ps-1">ha</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12 mb-1" data-aos="fade-up" data-aos-duration="1500">
                            <div class="icon_container">
                                <div class="icon">
                                    <img src="/assets/nuvasabay/images/home/population_icon.png" alt="area icon" class="img-fluid">
                                </div>
                                <div class="description">
                                    <p>Population Growth </p>
                                    <p class="small-text">Proportional to Housing Needs </p>
                                    <h5 class="count d-inline-block">1200000</h5><span class="d-inline-block ps-1">(2015)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12 col-12 mb-1" data-aos="fade-up" data-aos-duration="1500">
                            <div class="icon_container">
                                <div class="icon">
                                    <img src="/assets/nuvasabay/images/home/stay_icon.png" alt="area icon" class="img-fluid">
                                </div>
                                <div class="description">
                                    <p>Short Stay Potential</p>
                                    <h5 class="count d-inline-block">38000 </h5><span class="d-inline-block ps-1">People/Mo.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12 mb-1" data-aos="fade-up" data-aos-duration="1500">
                            <div class="icon_container">
                                <div class="icon">
                                    <img src="/assets/nuvasabay/images/home/market_icon.png" alt="area icon" class="img-fluid">
                                </div>
                                <div class="description">
                                    <p>Residential housing market growth</p>
                                    <h5 class="count d-inline-block">413</h5><span class="d-inline-block ps-1">%/
                                        Year</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-12 col-12 mb-1" data-aos="fade-up" data-aos-duration="1500">
                            <div class="icon_container">
                                <div class="icon">
                                    <img src="/assets/nuvasabay/images/home/long_stay_icon.png" alt="area icon" class="img-fluid">
                                </div>
                                <div class="description">
                                    <p>Long Stay Potential </p>
                                    <h5 class="count d-inline-block">6000</h5><span class="d-inline-block ps-1">People</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-12 mb-1" data-aos="fade-up" data-aos-duration="1500">
                            <div class="icon_container">
                                <div class="icon">
                                    <img src="/assets/nuvasabay/images/home/growth_icon.png" alt="area icon" class="img-fluid">
                                </div>
                                <div class="description">
                                    <p>Investment Growth</p>
                                    <p class="small-text">Proportional to Housing Needs </p>
                                    <h5 class="count d-inline-block">14</h5><span class="d-inline-block ps-1">%(2016)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row mt-2">
                <div class="col-12">
                    <marquee class="seize_h1">
                        <h1>SEIZE ALL THE POTENTIAL</h1>
                    </marquee>
                </div>
            </div> -->
        </div>
    </section>
    <!-- why batam -->

    <section class="blog_section">
        <div class="container-fluid p-0">
            <div class="row p-0 position-relative bg-primary-before">
                <div class="col-lg-8 col-md-8 col-12 bg-primary text-end py-3 heading">
                    <h2 class="text-white text-uppercase text-center text-md-end p-0 pe-lg-4 pe-md-5">{{get_static_option('home_22_blog_section_'.$user_select_lang_slug.'_subtitle','Latest News & Events')}}</h2>
                </div>
                <div class="col-lg-4 col-md-12 col-12"></div>
            </div>
        </div>
        <div class="container">
            <div class="row p-0 mt-4 blog_part">
                @php $i=0
                @endphp
                @foreach($all_blog as $blog)
                    <?php
                        if($i++ == 3) {
                            break;
                        }
                    ?>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="blog_container">
                            <a href="{{route('frontend.blog.single', $blog->slug)}}">
                                <div class="blog_name d-flex flex-column justify-content-center mb-3">
                                    <h5>{{$blog->title}}</h5>
                                </div>
                                <div class="blog_date">
                                    <p>
                                        {{date_format(date_create($blog->created_at),'D, d M Y')}}
                                        <br>
                                    </p>
                                </div>
                                <div class="blog_image overflow-hidden">
                                    {!! render_image_markup_by_attachment_id($blog->image,'','grid') !!}
                                </div>
                                <div class="blog_desc" style="text-overflow: ellipsis; overflow:hidden">
                                    <p>{{$blog->excerpt}}</p>
                                </div>
                            </a>
                            <div class="blog_link">
                                <a class="blog_btn" href="{{route('frontend.blog.single', $blog->slug)}}">Read
                                    More <i class="fas fa-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <div id="nuvasabay">
        <footer class="product-footer">
            <div class="footer-img">
                <a href="https://www.google.co.in/maps" target="_blank"><img src="/assets/uploads/media-uploader/footer-img11675693719.jpg" alt="product-footer" class="product-footer-img"></a>
            </div>
            <div class="social-share">
                <div class="d-none">
                    <a href="https://wa.me/08117008238"><i class="fa-brands fa-whatsapp"></i></a>
                    <button class="border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#exampleModal"><a><i class="fa-solid fa-mobile"></i></a></button>
                    <a href="mailto:nuvasa@nuvasabay.com"><i class="fa-regular fa-envelope"></i></a>
                </div>
                <div>
                    <p href="#" onClick="socialButton()"><i class="fas fa-chevron-up"></i></p>
                </div>
            </div>
        </footer>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-center fs-6" id="exampleModalLabel">Contact Us</h1>
                        <button type="button" class="text-dark fs-6 border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <form class="modal-body" action="https://nuvasabay.maxyprime.com/contact-message" method="post" style="font-size:12px;">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput1" name="your-name" placeholder="Name">
                            <label for="floatingInput1">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingInput2" name="your-email" placeholder="Email">
                            <label for="floatingInput2">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput3" name="your-phone" placeholder="Phone">
                            <label for="floatingInput3">Phone</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="your-message"></textarea>
                            <label for="floatingTextarea">Leave a Message</label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary d-block w-100 text-center">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .header-style-01.navbar-variant-01 .navbar-area.nav-style-01.nav-fixed {
        background-color: white;
    }

    .owl-carousel button {
        border: 0;
        background-color: transparent;
    }

    .dynamic-page-content-area {
        padding-top: 86px !important;
    }

    .blacking {
        color: #111;
    }

    .golf img {
        height: 286px;
    }
</style>

@section('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '#get_in_touch_submit_btn', function (e) {
                e.preventDefault();
                var myForm = document.getElementById('get_in_touch_form');
                var formData = new FormData(myForm);

                $.ajax({
                    type: "POST",
                    url: "{{route('frontend.get.touch')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(){
                        $('#get_in_touch_submit_btn').parent().find('.ajax-loading-wrap').removeClass('hide').addClass('show');
                    },
                    success: function (data) {
                        var errMsgContainer = $('#get_in_touch_form').find('.error-message');
                        $('#get_in_touch_submit_btn').parent().find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                        errMsgContainer.html('');

                        if(data.status == '400'){
                            errMsgContainer.append('<span class="text-danger">'+data.msg+'</span>');
                        }else{
                            errMsgContainer.append('<span class="text-success">'+data.msg+'</span>');
                        }
                    },
                    error: function (data) {
                        var error = data.responseJSON;
                        var errMsgContainer = $('#get_in_touch_form').find('.error-message');
                        errMsgContainer.html('');
                        $.each(error.errors,function (index,value) {
                            errMsgContainer.append('<span class="text-danger">'+value+'</span>');
                        });
                        $('#get_in_touch_submit_btn').parent().find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                    }
                });
            });

            $('#elevated_carousel').owlCarousel({
                loop: false,
                nav: true,
                dots: true,
                autoHeight: true,
                // animateOut: 'fadeOut',
                smartSpeed: 1000,
                mouseDrag: false,
                margin:10,
                items: 1,
            });

        });
    </script>
@endsection