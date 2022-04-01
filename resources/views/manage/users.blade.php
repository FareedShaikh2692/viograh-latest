@extends('adminlte::page')
@section('title',$title)

@section('content_header')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6 col-6">
            <h3>{{ $title }}</h3>
            <ol class="breadcrumb">
                <li><a href="{{ url('/manage') }}">Home</a></li>
                <li class="active">{{ $title }}</li>
            </ol>
        </div>
        @php /*<div class="col-md-6 col-sm-6 col-xs-6 col-6">
            <a href="{{ url('/manage/users/add/') }}" data-toggle="tooltip" title="Add New" class="a_link"><i class="fa fa-fw fa-plus-circle"></i>Add New</a>
        </div>*/ @endphp
    </div>
@stop
@section('content')
    @include('manage.include.notification')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="" class="dataTables_filter custom-filter">
                                <form action="{{url('manage/users')}}" method="get">
                                    <div class="justify-content-end row" style="margin-right:2px;">
                                      
                                        <!-- SEARCH BY DATE -->
                                        <div class=" users_start_date">
                                            <span class="user_date_span">
                                                 <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                            </span>  
                                            <input type="text" name="search_date" readonly='true' id="startdate" class="form-control searchdate " placeholder="Start Date" value="{{@$search_date}}"   autocomplete="off"  style="font-size:14px;"/>  
                                        </div>
                                        <div class=" users_end_date">
                                            <span class="user_date_span">
                                                <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                            </span>  
                                             <input type="text" name="search_end_date" readonly='true' id="enddate" class=" form-control searchdate " placeholder="End Date" value="{{@$search_end_date}}"   autocomplete="off" style="font-size:14px;"/>        
                                        </div>
                                                        
                                        <!-- END SEARCH BY DATE -->
                                        <input type="text" class="form-control input-crtl col-sm-3 col-8" name="search" value="{{@$search}}" placeholder="Search by name or email" style="font-size:14px;">
                                        <button class="btn btn-default ml-1" type="submit"><i class="fa fa-search"></i></button>
                                        @if(@$search!='' || @$search_date!='' || @$search_end_date!='')
                                        <div class="ml-1">
                                            <a href="{{url('manage/users')}}" class="btn btn-dark btn-md btn-block">ALL</a>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th width=1%>#</th> 
                                    <th class="roundimg-col"></th>  
                                    <th>User Info</th>                                                                     
                                    <th>Gender</th>
                                    <th width="5%">Login Platform</th>  
                                    <th>Created On</th>                                  
                                    <th width="10%" class="text-center"><a>Status<i class="ml-1"></i></a></th>                                   
                                    <th width=""class="text-center"><a>Action<i class="ml-1"></i></a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($results->total()==0)
                                    <tr class="data">
                                        <td colspan="12" align="center">No Records Found</td>
                                    </tr>
                                @else
                                    @php
                                        $i = $results->firstItem() - 1;
                                    @endphp
                                    @foreach($results as $key=>$row)
                                        <tr class="data module-list" id="data-{{ $row->id }}">
                                            <td>{{ ++$i }}</td>
                                            <td class="no-sort roundimg-col">
                                                <div class="bg-image">
                                                    <a href="{{@$row->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),$row->profile_image) : url('images/userdefault-1.svg')}}" data-fancybox = "gallery" >
                                                    <img src="{{@$row->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),$row->profile_image) : url('images/userdefault-1.svg')}}" class="list-image-prof"></a>
                                                    <!-- {{ Common_function::get_s3_file(config('custom_config.s3_user_thumb'),$row->profile_photo) }} -->
                                                </div>
                                            </td>
                                            <td>
                                                <p class="cls-p"><b>Name :</b>
                                                {{ $row->first_name}}</p>
                                                <p class="cls-p"><b>Email :</b>
                                                <a class="permlink" href="mailto:{{ $row->email }}">{{ $row->email }}</a></p>
                                                <p class="cls-p"><b>Contact No :</b>
                                               {{$row->dial_code}} {{ $row->phone_number ?  $row->phone_number : '-' }}</p>
                                            </td>
                                            <td class="">{{ $row->gender ? ucfirst($row->gender) : '-'}}</td>                                            
                                            <td class="">{{ ucfirst($row->login_platform) }}</td>
                                            <td class="">{{ Common_function::show_date_time($row->created_at) }}</td>
                                            <td class="text-center stusBtn-td">                                                
                                                <div class="switch {{$row->status == 'enable' ? 'on' : ''}}">
                                                    <div class="knob"></div>
                                                </div>
                                            </td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{ url('manage/users/information/'.$row->id) }}" class="" data-toggle="tooltip" title="View Details"><i class="icon fas fa-eye fa-lg"></i></a>
                                               
                                               <!--  <a href="{{ url('manage/admins/edit/'.$row->id) }}" class="" data-toggle="tooltip" title="Edit"><i class="icon fas fa-edit fa-lg"></i></a> -->
                                                @if($row->admin_type != "super_admin")
                                                <a href="javascript:;" class="delete" data-toggle="tooltip" data-module="{{@$main_module}}"  title="Delete"><i class="icon fas fa-trash fa-lg"></i></a>
                                                @endif 
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($results->total()>0)
                    <div class="row">
                        <div class="col-sm-5">
                            <div  class="pagination-txt">Showing <span>{{$results->firstItem()}} - {{$results->lastItem()}} </span> of <span>{{$results->total()}}</span> entries</div>
                        </div>
                        <div class="col-sm-7">
                            <div class="float-right search-pagination">
                                {{$results->render()}}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
    <input type="hidden" id="hdn" value="{{$tbl}}">
@stop
