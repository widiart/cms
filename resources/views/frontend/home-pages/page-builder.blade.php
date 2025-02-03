@include('frontend.partials.navbar-variant.navbar-'.get_static_option('navbar_variant'))
@php
    $home_page_variant = $home_page ?? filter_static_option_value('home_page_variant',$global_static_field_data);
@endphp
@if(in_array($home_page_variant,['22','23']))
    <div id="nuvasabay" class="section-start">
        {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_by_location('homepage') !!}
    </div>
@else
    {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_by_location('homepage') !!}
@endif