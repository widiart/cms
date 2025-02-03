<style>
    a {
        text-decoration: none !important;
        font-weight: 400
    }

    .header-style-01.navbar-variant-01 .navbar-area.nav-style-01.nav-fixed {
        background-color: white;
    }
</style>
<section id="nuvasabay" class="blog-content-area padding-bottom-40">
    <div class="news-carousel owl-carousel owl-theme">
        <div class="item">
            <img src="/assets/nuvasabay/images/developer/banner.jpg" alt="" style="height: calc(100vh - 26.5px)">
        </div>
        <div class="item">
            <img src="/assets/nuvasabay/images/about/different.jpg" alt="" style="height: calc(100vh - 26.5px)">
        </div>
    </div>
    <div class="container-fluid px-0 padding-bottom-40">
        <div class="row g-0">
            <div class="col-md-8">
                <div class="py-3 px-5 d-flex justify-content-end" style="background-color: #107AAF">
                    <h2 class="m-0 text-white" style="font-size: 28px; font-weight: 700">News & Events</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row m-0">
                    @foreach ($all_blogs as $data)
                        @if ($page_partial == 'home-17')
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3" data-aos="fade-up" data-aos-duration="1500">
                                <div class="blog_container">
                                    <div class="blog_name">
                                        <h5>{{ $data->title }}</h5>
                                    </div>
                                    <div class="blog_date">
                                        <p>{{ Carbon\Carbon::parse($data->created_at)->format('D, d M Y') }}</p>
                                    </div>
                                    <div class="blog_image overflow-hidden">
                                        {!! render_image_markup_by_attachment_id($data->image) !!}
                                    </div>
                                    <div class="blog_category">
                                        {!! get_blog_category_by_id($data->blog_categories_id, 'link') !!}
                                    </div>
                                    <div class="blog_desc">
                                        <p>{{ $data->excerpt }}</p>
                                    </div>
                                    <div class="blog_link">
                                        <a href="{{ route('frontend.blog.single', $data->slug) }}" class="blog_btn">Read More</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-frontend.blog.grid :blog="$data" :margin="true" />
                        @endif
                    @endforeach
                </div>
                <nav class="pagination-wrapper" aria-label="Page navigation ">
                    {{ $all_blogs->links() }}
                </nav>
            </div>
            {{-- <div class="col-lg-4">
                @include('frontend.pages.blog.sidebar')
            </div> --}}
        </div>
    </div>
</section>
