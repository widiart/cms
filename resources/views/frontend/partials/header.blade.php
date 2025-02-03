@php
    use App\Helpers\LanguageHelper;
    $home_page_variant = $home_page ?? filter_static_option_value('home_page_variant',$global_static_field_data);
    $navbar_variant = filter_static_option_value('navbar_variant',$global_static_field_data);
    $user_select_lang_slug = LanguageHelper::default_slug();
@endphp
        <!DOCTYPE html>
<html lang="{{$user_select_lang_slug}}"  dir="{{get_user_lang_direction()}}">

<head>
@if(!empty(filter_static_option_value('site_google_analytics',$global_static_field_data)))
    {!! get_static_option('site_google_analytics') !!}
@endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {!! render_favicon_by_id(filter_static_option_value('site_favicon',$global_static_field_data)) !!}
    {!! load_google_fonts() !!}
    <link rel="canonical" href="{{url()->current()}}">
    <link rel=preload href="{{asset('assets/frontend/css/fontawesome.min.css')}}" as="style">
    <link rel=preload href="{{asset('assets/frontend/css/flaticon.css')}}" as="style">
    <link rel=preload href="{{asset('assets/frontend/css/nexicon.css')}}" as="style">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/nexicon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/animate.css')}}">

    <link rel="stylesheet" href="{{asset('assets/frontend/css/magnific-popup.css')}}">
    @if(in_array($home_page_variant,['22','23']) || $navbar_variant == '07')
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style-22.css')}}">
    @else
        <link rel="stylesheet" href="{{asset('assets/frontend/css/style.css')}}">
    @endif
    <link rel="stylesheet" href="{{asset('assets/frontend/css/style-two.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/helpers.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/jquery.ihavecookies.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/toastr.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/slick.css')}}">
    <link href="{{asset('assets/frontend/css/jquery.mb.YTPlayer.min.css')}}" media="all" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    @if(!empty(get_static_option('google_adsense_publisher_id')))
        <script rel="preload" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{get_static_option('google_adsense_publisher_id')}}" crossorigin="anonymous"></script>
    @endif


    @if(in_array($home_page_variant,['22','23']) || $navbar_variant == '07')
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/fonts.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/global.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/fonts/icomoon/style.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/menu.css')}}">
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/style.css')}}">
        @if(in_array($home_page_variant,['23']))
            <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/style-23.css')}}">
        @endif
        <link rel="stylesheet" href="{{asset('/assets/nuvasabay/css/responsive.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
            integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous"
            referrerpolicy="no-referrer">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css"
            integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous"
            referrerpolicy="no-referrer">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @endif

@if(file_exists('assets/frontend/css/home-'.$home_page_variant.'.css') && empty(get_static_option('home_page_page_builder_status')))
        <link rel="stylesheet" href="{{asset('assets/frontend/css/home-'.$home_page_variant.'.css')}}">
    @endif
    @include('frontend.partials.css-variable')
    @include('frontend.partials.navbar-css')
    @yield('style')
    @if(!empty(filter_static_option_value('site_rtl_enabled',$global_static_field_data)) || get_user_lang_direction() == 'rtl')
        <link rel="stylesheet" href="{{asset('assets/frontend/css/rtl.css')}}">
        <link rel="stylesheet" href="{{asset('assets/frontend/css/new_rtl.css')}}">
    @endif
    @include('frontend.partials.og-meta')
    <script src="{{asset('assets/frontend/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('assets/frontend/js/jquery-migrate-3.1.0.min.js')}}"></script>

    <script>var siteurl = "{{url('/')}}"</script>

    {!! filter_static_option_value('site_third_party_tracking_code',$global_static_field_data) !!}

</head>

<body class="{{request()->path()}} home_variant_{{$home_page_variant}} nexelit_version_{{getenv('XGENIOUS_NEXELIT_VERSION')}} {{filter_static_option_value('item_license_status',$global_static_field_data)}} apps_key_{{filter_static_option_value('site_script_unique_key',$global_static_field_data)}} ">
@include('frontend.partials.preloader')
@include('frontend.partials.search-popup')


@if(!empty(get_static_option('navbar_variant')) && !in_array(get_static_option('navbar_variant'),['03','05']))
@include('frontend.partials.supportbar',['home_page_variant' => $home_page_variant])
@endif