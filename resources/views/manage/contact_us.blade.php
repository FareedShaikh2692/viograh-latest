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
            <a href="{{ url('/manage/contact-us/add/') }}" data-toggle="tooltip" title="Add New" class="a_link"><i class="fa fa-fw fa-plus-circle"></i>Add New</a>
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
                                <form action="{{url('manage/contact-us')}}" method="get">
                                    <div class="justify-content-end row" style="margin-right:2px;">
                                        <!-- SEARCH BY DATE -->
                                            <input type="text" name="search_date" id="datefilter" class="date-range-filter form-control col-2 pr-2 mr-1" placeholder="Date Range" value="{{@$search_date}}"  autocomplete="off" onkeydown="return false;" style="font-size:14px;"/>                                      
                                        <!-- END SEARCH BY DATE -->
                                        <input type="text" class="form-control input-crtl col-sm-3 col-8" name="search" value="{{@$search}}" placeholder="Search by name or email" style="font-size:14px;">
                                        <button class="btn btn-default ml-1" type="submit"><i class="fa fa-search"></i></button>
                                        
                                        @if(@$search!='' || @$search_date!='')
                                        <div class="ml-1">
                                            <a href="{{url('manage/contact-us')}}" class="btn btn-dark btn-md btn-block">ALL</a>
                                        </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th width=1%>#</th>                                    
                                    <th width="10%">Name</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>                                    
                                    <th>Subject</th>  
                                    <th>Created On</th>                                                     
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
                                        @if($row->is_read == 'unread' )                               
                                            <tr class="data module-list change_row_color" id="data-{{ $row->id }}">
                                        @else
                                            <tr class="data module-list" id="data-{{ $row->id }}">
                                        @endif
                                            <td>{{ ++$i }}</td>
                                            <td class="text-break">{{ $row->name }}</td>
                                            <td class=""><a class="permlink" href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                                            <td class="">{{ $row->contact_number ?  $row->contact_number : '-' }}</td>
                                            <td class="">{{ $row->subject }}</td>
                                            <td >{{ Common_function::show_date_time($row->created_at) }} </td>
                                            <td class="text-center list-action actBtn-td">
                                                <a href="{{ url('manage/contact-us/information/'.$row->id) }}" class="info" data-toggle="tooltip" title="View Details"><i class="icon fas fa-eye fa-lg"></i></a>
                                                @php /*@if($row->admin_type != "super_admin")
                                                <a href="{{ url('manage/admins/edit/'.$row->id) }}" class="" data-toggle="tooltip" title="Edit"><i class="icon fas fa-edit fa-lg"></i></a>
                                                <a href="javascript:;" class="delete" data-toggle="tooltip" data-module="{{@$main_module}}"  title="Delete"><i class="icon fas fa-trash fa-lg"></i></a>
                                                @endif*/@endphp
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