@php
    $home_page_variant = $home_page ?? get_static_option('home_page_variant');
    $navbar_variant = filter_static_option_value('navbar_variant',$global_static_field_data);
    $home_page19_color_con = $home_page_variant == '19' ? '' :  'footer-top';
    if(in_array($home_page_variant,['22','23'])) {
        $padding_top = 'pt-4';
        $padding_bottom = 'pb-4';
    } else {
        $padding_top = 'padding-top-90';
        $padding_bottom = 'padding-bottom-65';
    }
    
    $footer = 'footer';
    if(check_microsite())
        $footer .= "_".check_microsite()->slug;
@endphp
@if(!in_array(Route::currentRouteName(),['frontend.course.lesson','frontend.course.lesson.start']))
<footer class="footer-area home-variant-{{$home_page_variant}}
    @if((request()->routeIs('homepage')  || request()->routeIs('frontend.homepage.demo') ) && $home_page_variant == '17' &&
    filter_static_option_value('home_page_call_to_action_section_status',$static_field_data))
    has-top-padding
    @endif
    @if($home_page_variant === '21')
    home-21 home-21-section-bg footer-color-five
    @elseif($home_page_variant == '19')
    footer-bg footer-color-three
    @endif
    "
    @if(in_array($home_page_variant,['22','23']) || $navbar_variant == '07')
    id="nuvasabay"
    @endif
    >
    @if(App\WidgetsBuilder\WidgetBuilderSetup::render_frontend_sidebar($footer,['column' => true]))
    <div class="{{$home_page19_color_con}} {{$padding_top}} {{$padding_bottom}}">
        <div class="container">
            <div class="row">
                {!! App\WidgetsBuilder\WidgetBuilderSetup::render_frontend_sidebar($footer,['column' => true]) !!}
            </div>
        </div>
    </div>
    @endif
    <div class="copyright-area copyright-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-item">
                        <div class="copyright-area-inner">
                            {!! get_footer_copyright_text() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@if($home_page_variant == 22 || $navbar_variant == '07')
<img src="/assets/nuvasabay/images/home/footer_img_2.png" alt="product-footer" class="product-footer-img-two d-none d-md-block position-relative">
<img src="/assets/nuvasabay/images/home/footer_img_3.png" alt="product-footer" class="product-footer-img-three d-block d-md-none position-relative">
@endif
@if(preg_match('/(xgenious)/',url('/')))
<div class="buy-now-wrap">
<ul class="buy-list">
    <li><a target="_blank"href="https://xgenious.com/laravel/nexelit/doc/" data-container="body" data-toggle="popover" data-placement="left" data-content="{{__('Documentation')}}"><i class="far fa-file-alt"></i></a></li>
    <li><a target="_blank"href="https://1.envato.market/OXNPP"><i class="fas fa-shopping-cart"></i></a></li>
    <li><a target="_blank"href="https://xgenious51.freshdesk.com/"><i class="fas fa-headset"></i></a></li>
</ul>
</div>
@endif
@if(!in_array($home_page_variant,['22','23']))
<div class="back-to-top">
    <span class="back-top">
        <i class="fas fa-angle-up"></i>
    </span>
</div>
@endif
@include('frontend.partials.popup-structure')
@endif
<!-- load all script -->
<script src="{{asset('assets/frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/dynamic-script.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.magnific-popup.js')}}"></script>
<script src="{{asset('assets/frontend/js/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.waypoints.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/wow.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/jQuery.rProgressbar.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.mb.YTPlayer.js')}}"></script>
<script src="{{asset('assets/frontend/js/jquery.nicescroll.min.js')}}"></script>
<script src="{{asset('assets/frontend/js/slick.js')}}"></script>
<script src="{{asset('assets/frontend/js/main.js')}}"></script>

@if(in_array($home_page_variant,['22','23']) || $navbar_variant == '07')
    <script src="{{asset('/assets/nuvasabay/js/jquery.min.js')}}"></script>
    <script src="{{asset('/assets/nuvasabay/js/popper.min.js')}}"></script>
    <script src="{{asset('/assets/nuvasabay/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('/assets/nuvasabay/js/jquery.mousewheel.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{asset('/assets/nuvasabay/js/jquery.sticky.js')}}"></script>
    <script src="{{asset('/assets/nuvasabay/js/menu.js')}}"></script>
    <script src="{{asset('/assets/frontend/js/wheel-zoom.js')}}" type="text/javascript"></script>
    <script src="{{asset('/assets/nuvasabay/js/script.js')}}"></script>
    <script>
        $(document).ready(function() {
            let height = $("#myViewport").find("img").height()
            $("#image-container").css("height", height + "px")
        })
        window.addEventListener('resize', function () {
            let height = $("#myViewport").find("img").height()
            $("#image-container").css("height", height + "px")
        });
    </script>
@endif
<x-frontend.others.advertisement-script/>
@if(request()->routeIs('homepage') || request()->routeIs('frontend.homepage.demo'))
@include('frontend.partials.popup-jspart')
@include('frontend.partials.gdpr-cookie')
@endif

@include('frontend.partials.twakto')
@include('frontend.partials.google-captcha')
@include('frontend.partials.inline-script')
@include('frontend.partials.product-ajax-js')
@yield('scripts')



</body>
</html>
