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
            <a href="{{ url('/manage/settings/add/') }}" data-toggle="tooltip" title="Add New" class="a_link"><i class="fa fa-fw fa-plus-circle"></i>Add New</a>
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
                            <table id="example1" class="table table-bordered table-striped table-responsive">
                                <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th width="18%">Title</th>
                                    <th width="18%" class="tcenter no-sort">key</th>
                                    <th width="20%" class="tcenter no-sort">Value</th>
                                    <th class="tcenter no-sort">Comment</th>
                                    <th width="10%" class="text-center no-sort" width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($results->total()==0)
                                    <tr class="data">
                                        <td colspan="6" align="center">No Records Found</td>
                                    </tr>
                                @else
                                    @php
                                        $i = $results->firstItem() - 1;
                                    @endphp
                                    @foreach($results as $key=>$row)
                                        <tr class="data" id="data-{{ $row->id }}">
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $row->name}}</td>
                                            <td>{{ $row->setting_key }}</td>
                                            <td>{{ $row->value }}</td>
                                            <td title="{{$row->comment}}">{{ ($row->comment!='')?$row->comment:'-' }}</td>
                                            <td class="action text-center">
                                                <a href="{{ url('manage/settings/edit/'.$row->id) }}" class="tool-tip" title="Edit"><span class="icon fa fa-edit"></span></a>
                                                <a href="javascript:;" data-module="{{@$main_module}}"  class="delete tool-tip" title="Delete"><span class="icon fa fa-trash"></span></a>
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
                                <div class="pagination-txt">Showing <span>{{$results->firstItem()}} - {{$results->lastItem()}} </span> of <span>{{$results->total()}}</span> entries</div>
                            </div>
                            <div class="col-sm-7">
                                <div class="float-right search-pagination">
                                    {{$results->render()}}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <input type="hidden" id="hdn" value="{{$tbl}}">
@stop