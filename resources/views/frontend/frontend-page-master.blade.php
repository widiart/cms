@include('frontend.partials.header')
@include('frontend.partials.navbar-variant.navbar-'.get_static_option('navbar_variant'))
@if(!in_array(get_static_option('home_page_variant'),['22','23']))
    @include('frontend.partials.breadcrumb')
@endif
@yield('content')
@include('frontend.partials.footer')
