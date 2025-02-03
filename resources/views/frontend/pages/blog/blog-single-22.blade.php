<style>
    a i {
        padding: 0;
    }

    .share-single .facebook {
        width: 30px;
        height: 30px;
        display: flex;;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        border-radius: 50%;
        background-color: #4C6CB5;
        border-color: #4C6CB5 !important;
    }
    .share-single .twitter {
        width: 30px;
        height: 30px;
        display: flex;;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        border-radius: 50%;
        background-color: #1CAEEA;
        border-color: #1CAEEA !important;
    }
    .share-single .linkedin {
        width: 30px;
        height: 30px;
        display: flex;;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        border-radius: 50%;
        background-color: #0A66C2;
        border-color: #0A66C2 !important;
    }
    .share-single .pinterest {
        width: 30px;
        height: 30px;
        display: flex;;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: white;
        border-radius: 50%;
        background-color: #B7081B;
        border-color: #B7081B !important;
        display: none;
    }
    .share-single .share {
        width: 30px;
        height: 30px;
        display: flex;;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: black;
        border-radius: 50%;
        background-color: white;
        border-color: black !important;
    }
    
    .blog_image img {
        width: 100%;
        height: 200px;
    }
    
    .blog_desc p {
        color: black;
    }
    
    .tanggal {
        font-size: 14px;
    }
    
    .content-area {
        font-size: 14px;
    }
    
    .content-area p {
        margin-bottom: 0px;
    }
    
    .thumb {
        margin-bottom: 8px !important;
    }
    
    .thumb img {
        width: 100%;
    }
    
    @media (max-width: 991px) {
            .tanggal {
                font-size: 12px;
            }
    
        .content-area {
            font-size: 12px;
        }
    }
    
    .widget {
        background-color: #ffffff;
    }
    
    .slug-breadcrumb {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
@php
    $host = request()->getSchemeAndHttpHost();
@endphp
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Nuvasa Bay",
        "item": "{{ $host }}"
        },{
        "@type": "ListItem",
        "position": 2,
        "name": "Berita",
        "item": "{{ $host }}/news"
        },{
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $blog_post->title }}",
        "item": "{{ $blog_post->slug }}"
        }]
    }
</script>
<section id="nuvasabay" class="blog-details-content-area padding-top-100 padding-bottom-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <h2 class="mb-3 mb-md-1" style="font-weight: 700; color: black !important;">{{ $blog_post->title }}</h2>
                <div class="d-flex gap-1 text-capitalize mb-1" style="font-size: 12px;">
                    @php
                        $segments = Request::segments();
                    @endphp
                    <a class="text-muted" href="/home">Home</a>
                    <span>/</span>
                    <a class="text-muted" href="/{{ $segments[0] }}">{{ $segments[0] }}</a>
                    <span>/</span>
                    <a class="text-muted slug-breadcrumb" href="#">{{ $blog_post->title }}</a>
                </div>
                <div class="d-flex justify-content-between mb-4 align-items-center">
                    <span class="text-muted tanggal">{{ date_format($blog_post->created_at, 'd M Y, h:i') }} WIB</span>
                    <div class="d-lg-flex align-items-center">
                        <span class="me-2 d-lg-inline d-none text-center" style="font-size: 12px;">Share<br><sm class="sharenumber">{{(int)$blog_post->share}}</sm></span>
                        <ul class="d-flex align-items-center gap-2 share-single" style="list-style: none;">
                            <li>
                                <a class="share border" href="#" onclick="myFunction()"><i class="fa-solid fa-share-nodes"></i></a>
                            </li>
                            {!! single_post_share(route('frontend.blog.single',$blog_post->slug),$blog_post->title,$post_img) !!}
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-modal-img d-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Launch demo modal
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                        <img class="w-100" src="{{ $blog_image['img_url'] }}" alt="{{ __($blog_post->title) }}">
                        </div>
                    </div>
                    </div>
                </div>
                <div class="blog-details-item">
                    <div class="thumb" onclick="$('.btn-modal-img').click();">
                        @php
                            
                        @endphp
                        @if (!empty($blog_image))
                            <img src="{{ $blog_image['img_url'] }}" alt="{{ __($blog_post->title) }}">
                        @endif
                    </div>
                    <div class="entry-content">
                        <div class="img-desc mb-4">
                            <p style="font-size: 12px;">
                                {{ $blog_post->img_desc }}
                            </p>
                        </div>
                        <div class="content-area">
                            {!! $blog_post->content !!}
                        </div>
                    </div>
                </div>
                @if (count($all_related_blog) > 1)
                    <div class="related-post-area margin-top-40">
                        <div class="section-title ">
                            <h4 class="title mb-3" style="color: black !important;">{{ get_static_option('blog_single_page_' . $user_select_lang_slug . '_related_post_title') }}</h4>
                            <div class="row margin-top-30">
                                @foreach ($all_related_blog as $data)
                                    @if ($data->id === $blog_post->id)
                                        @continue
                                    @endif
                                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3" data-aos-duration="1500" data-aos="fade-up">
                                        <a href="{{ route('frontend.blog.single', $data->slug) }}">
                                            <div class="blog_container">
                                                <div class="blog_name d-flex flex-column justify-content-center" style="height: 50px">
                                                    <h5>{{ $data->title }}</h5>
                                                </div>
                                                <div class="blog_date">
                                                    <p>{{ Carbon\Carbon::parse($data->created_at)->format('D, d M Y') }}
                                                    </p>
                                                </div>
                                                <div class="blog_image overflow-hidden">
                                                    {!! render_image_markup_by_attachment_id($data->image, null, 'grid') !!}
                                                </div>
                                                <div class="blog_desc">
                                                    <p>{{ $data->excerpt }}</p>
                                                </div>
                                                <div class="blog_link">
                                                    <a class="blog_btn"
                                                        href="{{ route('frontend.blog.single', $data->slug) }}">Read
                                                        More</a>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="padding-top-20" data-aos-duration="1500" data-aos="fade-up">
                    @if ($blog_post->blog_categories_id == 18)
                        <a href="/news#news" class="d-block w-100 border-0 bg-primary rounded py-2 text-center">News</a>
                    @endif
                    @if ($blog_post->blog_categories_id == 19)
                        <a href="/news#events" class="d-block w-100 border-0 bg-primary rounded py-2 text-center">Events</a>
                    @endif
                </div>
                <div class="disqus-comment-area margin-top-40">
                    <div id="disqus_thread"></div>
                </div>
            </div>
            <div class="col-lg-3 d-lg-block d-none">
                @include('frontend.pages.blog.sidebar')
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
                <a href="https://wa.me/08117008238"><i class="fa-brands fa-whatsapp"></i></a>
                <button class="border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#exampleModal"><a><i class="fa-solid fa-mobile"></i></a></button>
                <a href="mailto:nuvasa@nuvasabay.com"><i class="fa-regular fa-envelope"></i></a>
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