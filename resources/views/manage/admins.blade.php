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
        <div class="col-md-6 col-sm-6 col-xs-6 col-6">
            <a href="{{ url('/manage/admins/add/') }}" data-toggle="tooltip" title="Add New" class="a_link"><i class="fa fa-fw fa-plus-circle"></i>Add New</a>
        </div>
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
                                <form action="{{url('manage/admins')}}" method="get">
                                <div class="justify-content-end row" style="margin-right:2px;">
                                        <input type="text" class="form-control input-crtl col-sm-3 col-8" name="search" value="{{@$search}}" placeholder="Search by name or email" style="font-size:14px;">
                                        <button class="btn btn-default ml-1" type="submit"><i class="fa fa-search"></i></button>
                                        @if(@$search!='')
                                        <div class="ml-1">
                                            <a href="{{url('manage/admins')}}" class="btn btn-dark btn-md btn-block">ALL</a>
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    <th>Admin Type</th>
                                    @if(Auth::guard('manage')->user()->admin_type == "super_admin" )
                                        <th width="10%" class="text-center"><a>Status<i class="ml-1"></i></a></th>
                                    @endif
                                    <th width="15%"class="text-center"><a>Action<i class="ml-1"></i></a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($results->total()==0)
                                    <tr class="data">
                                        <td colspan="8" align="center">No Records Found</td>
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
                                                    <a href="{{@$row->profile_photo ? Common_function::get_s3_file(config('custom_config.s3_admin_thumb'),$row->profile_photo) : url('images/userdefault-1.svg')}}" data-fancybox = "gallery" >
                                                    <img src="{{@$row->profile_photo ? Common_function::get_s3_file(config('custom_config.s3_admin_thumb'),$row->profile_photo) : url('images/userdefault-1.svg')}}" class="list-image-prof"></a>
                                                </div>
                                            </td>
                                            <td class="img-td">{{ $row->first_name.' '.$row->last_name }}</td>
                                            <td class="img-td"><a class="permlink" href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                                            <td class="img-td">{{ $row->contact_number ?  $row->contact_number : '-' }}</td>
                                            <td class="img-td">
                                                @if($row->admin_type == "super_admin") Super Admin @elseif($row->admin_type == "sub_admin") Sub Admin @endif
                                            </td>
                                            <td class="text-center stusBtn-td">
                                                @if(Auth::guard('manage')->user()->admin_type == "super_admin" && $row->admin_type == "sub_admin")
                                                <div class="switch {{$row->status == 'enable' ? 'on' : ''}}">
                                                    <div class="knob"></div>
                                                </div>
                                                @else
                                                <span class="no-result">-</span>
                                                @endif
                                                
                                            </td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{ url('manage/admins/information/'.$row->id) }}" class="" data-toggle="tooltip" title="View Details"><i class="icon fas fa-eye fa-lg"></i></a>
                                                @if($row->admin_type != "super_admin")
                                                <a href="{{ url('manage/admins/edit/'.$row->id) }}" class="" data-toggle="tooltip" title="Edit"><i class="icon fas fa-edit fa-lg"></i></a>
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