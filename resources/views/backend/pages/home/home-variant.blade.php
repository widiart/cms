@extends('backend.admin-master')
@section('site-title')
    {{__('Home Variant Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <!-- basic form start -->
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-flash-msg/>
                <x-error-msg/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Home Variant')}}</h4>
                        <form action="{{route('admin.home.variant')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" class="form-control"  id="home_page_variant" value="{{get_static_option('home_page_variant')}}" name="home_page_variant">
                            </div>
                            @php
                                $homepages = [
                                //    '01' => 'home-01.jpg',
                                //    '02' => 'home-02.jpg',
                                //    '03' => 'home-03.jpg',
                                //    '04' => 'home-04.jpg',
                                //    '05' => 'home-05.jpg',
                                //    '06' => 'home-06.jpg',
                                //    '07' => 'home-07.jpg',
                                //    '08' => 'home-08.jpg',
                                //    '09' => 'home-09.jpg',
                                //    '10' => 'home-10.png',
                                //    '11' => 'home-11.png',
                                //    '12' => 'home-12.png',
                                //    '13' => 'home-13.png',
                                //    '14' => 'home-14.png',
                                //    '15' => 'home-15.png',
                                //    '16' => 'home-16.png',
                                //    '17' => 'home-17.png',
                                //    '18' => 'home-18.png',
                                //    '19' => 'home-19.jpg',
                                //    '20' => 'home-20.jpg',
                                //    '21' => 'home-21.jpg',
                                   '22' => 'home-22.png',
                                   '23' => 'home-23.png',
                                ];
                            @endphp
                            <div class="row">
                            @foreach($homepages as $home_number => $image)
                                <div class="col-lg-3 col-md-6">
                                    <div class="img-select selected">
                                        <div class="img-wrap">
                                            <img src="{{asset('assets/frontend/home-variant/'.$image)}}" data-home_id="{{$home_number}}" alt="">
                                        </div>
                                    </div>
                                </div>
                             @endforeach

                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Home Variant')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($){
            "use strict";

            $(document).ready(function () {

                var imgSelect = $('.img-select');
                var id = $('#home_page_variant').val();
                imgSelect.removeClass('selected');
                $('img[data-home_id="'+id+'"]').parent().parent().addClass('selected');
                $(document).on('click','.img-select img',function (e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#home_page_variant').val($(this).data('home_id'));
                })
            })

        })(jQuery);
    </script>
@endsection