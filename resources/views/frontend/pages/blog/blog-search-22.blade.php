<style>
    a {
        text-decoration: none !important;
        font-weight: 400
    }

    .header-style-01.navbar-variant-01 .navbar-area.nav-style-01.nav-fixed {
        background-color: white;
    }
    
    #nuvasabay .news-sec {
        background-image: url(/assets/nuvasabay/images/no-other-place/banner-1.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        width: 100%;
        height: calc(100vh - 86px);
        margin: 0;
        position: relative;
        margin-top: 86px;
    }
    
    .blog_image img {
        width: 100%;
        height: 200px;
    }
    
    .blog_desc p {
        color: black;
    }
    
    /* @media (max-width: 991px) { */
        #nuvasabay .news-sec {
            padding: 0;
            height: 233px;
            filter: blur(3px);
            -webkit-filter: blur(3px);
        }   
    /* } */
</style>
<section class="blog-content-area padding-bottom-40" id="nuvasabay">
    <div id="news">
        <div class="news-sec mb-4">
        </div>
        <div class="container-fluid px-0 padding-bottom-20 d-none">
            <div class="row g-0">
                <div class="col-md-7 col-12 bg-primary text-end">
                    <div class="py-3 px-5" style="background-color: #107AAF">
                        <h2 class="m-0 text-white text-center text-lg-end news-events-title" style="font-size: 28px; font-weight: 700">
                            News & Events
                        </h2>
                    </div>
                </div>
                <div class="col-md-5 col-12"></div>
            </div>
        </div>
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row m-0">
                        @php $i = 0 @endphp
                        @foreach ($all_blogs as $data)
                            @if (!empty($data->category_blogs->name) && in_array($data->category_blogs->name,["News","Events"]) )
                                <div class="mb-3 {{ $data->category_blogs->name }}" data-aos-duration="1500"
                                    data-aos="fade-up">
                                    <a href="{{ route('frontend.blog.single', $data->slug) }}">
                                        <div class="blog_container">
                                            <div class="blog_date mb-2">
                                                <p>
                                                    {{$data->category_blogs->name}}
                                                </p>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="blog_image overflow-hidden">
                                                        {!! render_image_markup_by_attachment_id($data->image) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="blog_name d-flex flex-column justify-content-center" style="height: 50px">
                                                        <h5>{{ $data->title }}</h5>
                                                    </div>
                                                    <div class="blog_date">
                                                        <p>{{ Carbon\Carbon::parse($data->created_at)->format('D, d M Y') }}
                                                        </p>
                                                    </div>
                                                    <div class="blog_desc">
                                                        <p>{{ $data->excerpt }}</p>
                                                    </div>
                                                    <div class="blog_link">
                                                        <p class="blog_btn">Read More</p>
                                                        {{-- <a class="blog_btn" href="{{ route('frontend.blog.single', $data->slug) }}">Read More</a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @php $i++ @endphp
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 d-none">
                    <nav aria-label="Page navigation " class="pagination-wrapper">
                        {{ $all_blogs->links() }}
                    </nav>
                </div>
            </div>
        </div>
        <div id="events" class="container-fluid px-0 padding-bottom-20 padding-top-40 d-none">
            <div class="row g-0">
                <div class="col-md-5 col-12"></div>
                <div class="col-md-7 col-12 bg-primary text-start">
                    <div class="py-3 px-5" style="background-color: #107AAF">
                        <h2 class="m-0 text-white text-center text-lg-start" style="font-size: 28px; font-weight: 700">
                            Product Pages
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row m-0">
                        @foreach ($all_pages as $data)
                            <div class="mb-3 {{ $data->slug }}" data-aos-duration="1500"
                                data-aos="fade-up">
                                @php
                                if(empty($data->microsite)) {
                                    $route = request()->getSchemeAndHttpHost().'/'.$data->slug;
                                } else {
                                    $route = request()->getSchemeAndHttpHost().'/'.$data->microsite.'/'.$data->slug;
                                }
                                @endphp
                                <a href="{{ $route }}">
                                    <div class="blog_container">
                                        <div class="blog_date mb-2">
                                            <p>
                                                Product Pages
                                            </p>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="blog_image overflow-hidden">
                                                    @php
                                                        $settings = unserialize($data->settings);
                                                        $lang = !empty(session()->get('lang')) ? session()->get('lang') : $default_lang->slug;
                                                        foreach ($settings as $key => $value) {
                                                            if(is_array($value)){
                                                                foreach ($value as $index => $isi) {
                                                                    $repeater[$index] = $isi[0];
                                                                }
                                                            }
                                                        }
                                                        $image_id = $settings['image'] ?? $settings['image_'.$lang] ?? $repeater['image_'.$lang] ?? 473;
                                                        $description = $settings['description'] ?? $settings['description_'.$lang] ?? $repeater['description_'.$lang] ??
                                                            $settings['desc'] ?? $settings['desc_'.$lang] ?? $repeater['desc_'.$lang] ?? '';
                                                    @endphp
                                                    {!! render_image_markup_by_attachment_id($image_id) !!}
                                                </div>
                                            </div>
                                            <div class="col-lg-8">
                                                <div class="blog_name d-flex flex-column justify-content-center" style="height: 50px">
                                                    <h5>{{ $data->title }}</h5>
                                                </div>
                                                <div class="blog_date">
                                                    <p>{{ Carbon\Carbon::parse($data->created_at)->format('D, d M Y') }}
                                                    </p>
                                                </div>
                                                <div class="blog_desc">
                                                    <p>{{ App\Helpers\SanitizeInput::esc_html($description) }}</p>
                                                </div>
                                                <div class="blog_link">
                                                    <p class="blog_btn">Read More</p>
                                                    {{-- <a class="blog_btn" href="{{ route('frontend.blog.single', $data->slug) }}">Read More</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @php $i++ @endphp
                        @endforeach
                        @if(empty($i))
                        <div class="mb-3">
                            <div class="blog_container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="text-center blog_name d-flex flex-column justify-content-center" style="height: 50px">
                                            <h5>SEARCH</h5>
                                        </div>
                                        <div class="text-center blog_desc">
                                            <p>RESULT NOT FOUND</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-12">
                    <nav aria-label="Page navigation " class="pagination-wrapper">
                        @if($all_blogs->total() > $all_pages->total())
                            {{ $all_blogs->links() }}
                        @else
                            {{ $all_pages->links() }}
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="nuvasabay">
    <footer class="product-footer">
        <div class="footer-img">
            <a href="https://www.google.com/maps/place/Nongsa,+Batam+City,+Riau+Islands,+Indonesia/@1.091018,104.092438,11z/data=!4m5!3m4!1s0x31d985dcce261ca7:0xfb4e44587d08a42!8m2!3d1.1499311!4d104.1320533?hl=en" target="_blank">
                <img src="/assets/uploads/media-uploader/sinarmasland-nuvasa-bay-p1-ranc-emaps-nuvasa-bay-1440-x-500-px-notes-1-053-15-februari-2023-011677211893.jpg" alt="product-footer" class="product-footer-img">
            </a>
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