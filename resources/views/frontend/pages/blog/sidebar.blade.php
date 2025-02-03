<div class="widget-area">
    @if(!in_array(get_static_option('home_page_variant'),['22','23']))
        {!! App\WidgetsBuilder\WidgetBuilderSetup::render_frontend_sidebar('blog',['column' => false]) !!}
    @else
        @include('frontend.pages.blog.sidebar-22')
    @endif
</div>
