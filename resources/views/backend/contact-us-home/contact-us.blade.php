@extends('backend.admin-master')
@section('site-title')
    {{__('Contact Us Home')}}
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            padding: 0 !important;
        }
        div.dataTables_wrapper div.dataTables_length select {
            width: 60px;
            display: inline-block;
        }
    </style>
@endsection
@section('content')
<style>
    .text-center .pagination {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important;
    }
</style>
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
                        <h4 class="header-title">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    {{__('Contact Us Form Input')}}
                                </div>
                                <div class="col-12 offset-md-1 col-md-2 d-flex">
                                    <input type="text" name="search" class="form-control" placeholder="search" id="search" style="width: calc(100% - 50px);">
                                    <button class="btn btn-primary" onclick="search()"><i class="ti-search"></i></button>
                                </div>
                                <div class="col-12 col-md-1">
                                    <select name="pagination" class="form-control" id="pagination" style="width:70px">
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-1">
                                    <div class="dropdown">
                                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#dateFilter">
                                            <i class="fas fa-calendar-alt"></i> Date Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-1">
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Export
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="min-width:6rem">
                                            <a class="dropdown-item" href="#" onclick="donlod('excel')">Excel</a>
                                            <a class="dropdown-item" href="#" onclick="donlod('csv')">CSV</a>
                                            <a class="dropdown-item" href="#" onclick="donlod('json')">JSON</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h4>
                        <div class="bulk-delete-wrapper d-none">
                            <div class="select-box-wrap">
                                <select name="bulk_option" id="bulk_option">
                                    <option value="">{{{__('Bulk Action')}}}</option>
                                    <option value="delete">{{{__('Delete')}}}</option>
                                </select>
                                <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{__('Apply')}}</button>
                            </div>
                        </div>
                        <div class="position-relative">
                            <div id="overlay" class="position-absolute w-100 h-100" style="background-color:white;z-index:10;opacity:50%;display:none;">
    
                            </div>
                            <table class="table table-default" id="all_blog_table">
                                <thead>
                                <th class="no-sort d-none">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Phone')}}</th>
                                <th>{{__('Message')}}</th>
                                <th>{{__('IP')}}</th>
                                <th class="d-none">{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_contact as $data)
                                    <tr>
                                        <td class="d-none">
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                            </div>
                                        </td>
                                        <td>{{date('Y-m-d h:i',strtotime($data->created_at))}}</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->email}}</td>
                                        <td>{{$data->phone}}</td>
                                        <td>{{$data->message}}</td>
                                        <td>{{$data->ip_client}}</td>
                                        <td class="d-none">
                                            {{-- <x-delete-popover :url="route('admin.contact-us-home.delete',$data->id)"/> --}}
                                            {{-- <a href="#"
                                               data-toggle="modal"
                                               data-target="#testimonial_item_edit_modal"
                                               class="btn btn-xs btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                               data-id="{{$data->id}}"
                                               data-title="{{$data->title}}"
                                               data-embedcode="{{$data->embed_code}}"
                                               data-status="{{$data->status}}"
                                            >
                                                <i class="ti-pencil"></i> 
                                            </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
    
                            <div class="text-center" id="page-list">
                                {{$all_contact->links()}}
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="dateFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title"> Date Range Filter <span></span></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="container" id="error">
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.contact-us.pagination')}}" method="POST">
                        @csrf
                        <div class="form-group row" tyle="display:none">
                        <div class="col-sm-5">
                            <input class="form-control" type="date" name="from" id="from">
                        </div>
                        <div class="col-sm-2 text-center">
                            -
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" type="date" name="to" id="to">
                        </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                <button type="button" id="submit" onclick="submit('dateFilter')" class="btn btn-primary">Submit</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
    </div>

@endsection
@section('script')

    <script>
        $(document).ready(function () {

            $(document).on('click','#bulk_delete_btn',function (e) {
                e.preventDefault();

                var bulkOption = $('#bulk_option').val();
                var allCheckbox =  $('.bulk-checkbox:checked');
                var allIds = [];
                allCheckbox.each(function(index,value){
                    allIds.push($(this).val());
                });
                if(allIds != '' && bulkOption == 'delete'){
                    $(this).text('{{__('Deleting...')}}');
                    $.ajax({
                        'type' : "POST",
                        'url' : "{{route('admin.contact-us-home.bulk.action')}}",
                        'data' : {
                            _token: "{{csrf_token()}}",
                            ids: allIds
                        },
                        success:function (data) {
                            location.reload();
                        }
                    });
                }

            });

            $('#pagination').on('change',function (e) {
                let search = $("#search").val();
                $(document).find('#overlay').show()
                $.ajax({
                    'type' : "POST",
                    'url' : "{{route('admin.contact-us-home.pagination')}}"+(search?"/"+search:''),
                    'data' : {
                        _token: "{{csrf_token()}}",
                        pagination: $(this).val()
                    },
                    success:function (response) {
                        updateTable(response.data)
                        updatePagination(response.pagination)
                        $(document).find('#overlay').hide()
                    }
                });
            });

            $('.all-checkbox').on('change',function (e) {
                e.preventDefault();
                var value = $('.all-checkbox').is(':checked');
                var allChek = $(this).parent().parent().parent().parent().parent().find('.bulk-checkbox');
                //have write code here fr
                if( value == true){
                    allChek.prop('checked',true);
                }else{
                    allChek.prop('checked',false);
                }
            });

            $(document).on('click','.testimonial_edit_btn',function(){
                var el = $(this);
                var id = el.data('id');

                var form = $('#testimonial_edit_modal_form');
                form.find('#gallery_id').val(id);
                form.find('input[name="title"]').val(el.data('title'));
                form.find('textarea[name="embed_code"]').val(el.data('embedcode'));
                form.find('select[name="status"] option[value="'+el.data('status')+'"]').attr('selected',true);
            });
        });

        $(document).on('click', '#page-list nav a', function(event){
            event.preventDefault(); 
            let page = $(this).attr('href').split('page=')[1];
            let pagination = $("#pagination").val();
            let search = $("#search").val();
            $(document).find('#overlay').show()

            $.ajax({
                url:"{{route('admin.contact-us-home.pagination')}}"+(search?"/"+search:''),
                type: "POST",
                data : {
                        _token: "{{csrf_token()}}",
                        pagination: pagination,
                        page: page
                },
                success: function(response) {
                    updateTable(response.data)
                    updatePagination(response.pagination)
                    $(document).find('#overlay').hide()
                }
            })
        });

        function search() {    
            let search = $("#search").val();
            let pagination = $("#pagination").val();
            $(document).find('#overlay').show()

            $.ajax({
                url:"{{route('admin.contact-us-home.pagination')}}"+(search?"/"+search:''),
                type: "POST",
                data : {
                        _token: "{{csrf_token()}}",
                        pagination: pagination,
                },
                success: function(response) {
                    updateTable(response.data)
                    updatePagination(response.pagination)
                    $(document).find('#overlay').hide()
                }
            })
        }

        function updatePagination(data) {
            let container = $("#page-list");
            container.html(data);
        }
        
        function updateTable(data) {
            let container = $("#all_blog_table tbody");
            container.html('');
            data.forEach(element => {
                let isi = `
                <tr>
                    <td class="d-none">
                    </td>
                    <td>${element.date}</td>
                    <td>${element.name}</td>
                    <td>${element.email}</td>
                    <td>${element.phone}</td>
                    <td>${element.message}</td>
                    <td>${element.ip_client}</td>
                    <td class="d-none">
                    </td>
                </tr>`;
                container.append(isi);
            });
        }

        function donlod(tipe) {
            let search = $("#search").val();

            window.location = "{{route('admin.contact-us-home.export')}}/"+tipe+(search?"/"+search:'');
        }

        function submit(id) {
            let form = $("#"+id).find("form");
            let search = $("#search").val();
            let pagination = $("#pagination").val();
            $.ajax({
                url:"{{route('admin.contact-us-home.pagination')}}"+(search?"/"+search:''),
                type: "POST",
                data : {
                        _token: "{{csrf_token()}}",
                        pagination: pagination,
                        from: form.find("#from").val(),
                        to: form.find("#to").val(),
                },
                success: function(response) {
                    updateTable(response.data)
                    updatePagination(response.pagination)
                    $(document).find('#overlay').hide()
                    $("#"+id).modal("hide")
                }
            })
        }
    </script>
    <!-- Start datatable js -->
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="//cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table-wrap > table').DataTable( {
                "order": [[ 1, "desc" ]],
                'columnDefs' : [{
                    'targets' : 'no-sort',
                    'orderable' : false
                }]
            } );
        } );
    </script>
@endsection
