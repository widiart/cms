@extends('frontend.frontend-page-master')
@section('page-meta-data')
@php
    $post_img = null;
    $page_image = get_attachment_image_by_id($page_post->image,"full",false);
    $post_img = !empty($page_image) ? $page_image['img_url'] : '';
    $meta_description = $page_post->meta_description ?? get_static_option('site_meta_'.(App\Helpers\LanguageHelper::user_lang_slug()).'_description'); 
    $meta_tags = $page_post->meta_tags ?? get_static_option('site_meta_'.(App\Helpers\LanguageHelper::user_lang_slug()).'_tags'); 
@endphp
<meta name="description" content="{{$meta_description}}">
<meta name="tags" content="{{$meta_tags}}">

<meta property="og:type" content="article">
<meta property="og:url" content="{{request()->fullUrl()}}">
<meta property="og:title" content="{{$page_post->title}}">
<meta property="og:description" content="{{$meta_description}}">
<?=render_og_meta_image_by_attachment_id($page_post->meta_image)?>
@endsection
@section('site-title')
    {{$page_post->title}}
@endsection
@section('page-title')
    {{$page_post->title}}
@endsection
@section('content')
    @if($page_post->visibility === 'user' && !auth()->guard('web')->check())
       <section class="padding-top-100 padding-bottom-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-warning"><strong><a href="{{route('user.login')}}">{{__('login')}}</a></strong> {{__('to see page content')}}</div>
                    </div>
                </div>
            </div>
       </section>
    @else
        @if(!empty($page_post->page_builder_status))
            {!! \App\PageBuilder\PageBuilderSetup::render_frontend_pagebuilder_content_for_dynamic_page('dynamic_page',$page_post->id) !!}
        @else
            @include('frontend.partials.dynamic-page-content')
        @endif
    @endif
  
@endsection
