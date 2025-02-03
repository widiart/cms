@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/media-uploader.css')}}">
@endsection
@section('site-title')
     {{__('Edit Microsite')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <h4 class="header-title">{{__('Edit Microsite')}}</h4>
                            <a href="{{route('admin.microsite')}}" class="btn btn-primary">{{__('All Microsites')}}</a>
                        </div>
                        <form action="{{route('admin.microsite.update',$microsite_post->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="title">{{__('Microsite Name')}}</label>
                                        <input type="text" class="form-control"  id="title" name="name" value="{{$microsite_post->name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">{{__('Slug')}}</label>
                                        <input type="text" class="form-control"  id="slug" name="slug" value="{{$microsite_post->slug}}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Status')}}</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="publish">{{__('Publish')}}</option>
                                            <option value="draft">{{__('Draft')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Home Variant')}}</label>
                                        <input type="hidden" class="form-control"  id="home_page_variant" value="{{$microsite_post->home_page_variant}}" name="home_page_variant">
                                    </div>
                                    @php
                                        $homepages = [
                                        //    '17' => 'home-17.png',
                                        //    '21' => 'home-21.jpg',
                                           '22' => 'home-22.png',
                                           '23' => 'home-23.png',
                                        ];
                                    @endphp
                                    <div class="row">
                                    @foreach($homepages as $home_number => $image)
                                        <div class="col-lg-3 col-md-6">
                                            <div class="img-select selected home_page_variant">
                                                <div class="img-wrap">
                                                    <img src="{{asset('assets/frontend/home-variant/'.$image)}}" data-home_id="{{$home_number}}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                     @endforeach
        
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{__('Navbar Variant')}}</label>
                                        <input type="hidden" class="form-control"  id="navbar_variant" value="{{$microsite_post->navbar_variant}}" name="navbar_variant">
                                    </div>
                                    @php
                                        $navbar = [
                                        //    '06' => 'navbar-06.png',
                                           '07' => 'navbar-07.png',
                                        ];
                                    @endphp
                                    <div class="row">
                                    @foreach($navbar as $home_number => $image)
                                        <div class="img-select selected navbar_variant">
                                            <div class="img-wrap">
                                                <img src="{{asset('assets/frontend/navbar-variant/'.$image)}}" data-navid="{{$home_number}}" alt="">
                                            </div>
                                        </div>
                                     @endforeach
        
                                    </div>
                                    <x-media-upload :name="'site_logo_'.$microsite_post->slug" :id="$microsite_post->site_logo" :dimentions="'160x50'" :title="__('Site Logo')"/>
                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Microsite')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
    <x-backend.auto-slug-js :url="route('admin.microsite.slug.check')" :type="'update'"/>
    <script>
        $(document).ready(function () {

            let microsite_builder = '{{$microsite_post->microsite_builder_status}}';
            let breadcrumb = '{{$microsite_post->breadcrumb_status}}';

            if(microsite_builder == 'on'){
                $('.breadcrumb_status').removeClass('d-none');
            }

            $(document).on('change','input[name="microsite_builder_status"]',function(){
                if($(this).is(':checked')){
                    $('.breadcrumb_status').removeClass('d-none');
                    $('.classic-editor-wrapper').addClass('d-none');
                    $('.microsite-builder-btn-wrapper').removeClass('d-none');
                }else {
                    $('.breadcrumb_status').addClass('d-none');
                    $('.classic-editor-wrapper').removeClass('d-none');
                    $('.microsite-builder-btn-wrapper').addClass('d-none');
                }
            });

            $('.summernote').summernote({
                height: 400,   //set editable area's height
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                callbacks: {
                    onChange: function(contents, $editable) {
                        $(this).prev('input').val(contents);
                    }
                }
            });
            if($('.summernote').length > 0){
                $('.summernote').each(function(index,value){
                    $(this).summernote('code', $(this).data('content'));
                });
            }

            var imgSelect = $('.home_page_variant.img-select');
            var id = $('#home_page_variant').val();
            imgSelect.removeClass('selected');
            $('img[data-home_id="'+id+'"]').parent().parent().addClass('selected');
            $(document).on('click','.home_page_variant.img-select img',function (e) {
                e.preventDefault();
                imgSelect.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#home_page_variant').val($(this).data('home_id'));
            })
            
            var imgSelect2 = $('.navbar_variant.img-select');
            var id2 = $('#navbar_variant').val();
            imgSelect2.removeClass('selected');
            $('img[data-navid="'+id2+'"]').parent().parent().addClass('selected');

            $(document).on('click','.navbar_variant.img-select img',function (e) {
                e.preventDefault();
                imgSelect2.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#navbar_variant').val($(this).data('navid'));
            })

        });
    </script>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    @include('backend.partials.media-upload.media-js')
@endsection
