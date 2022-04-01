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
            <a href="{{ url('/manage/mail-settings/add/') }}" data-toggle="tooltip" title="Add New" class="a_link"><i class="fa fa-fw fa-plus-circle"></i>Add New </a>
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
                        <div class="offset-sm-4 col-sm-8">
                            <div id="" class="dataTables_filter custom-filter">
                              <form class="form-inline float-right form-sm active-cyan-2" action="{{url('manage/mail-settings')}}" method="get">
                                  <div class="input-group md-form module-frm form-sm form-2 pl-0">
                                    <select class="mdb-select form-control" name="module" onchange="this.form.submit()">
                                      <option value="">All</option>
                                      <?php foreach(@$modules as $k=>$r){ ?>
                                        <option value="{{@$r['module']}}" {{(@$module_name == @$r['module'])?'selected':''}}>{{@$r['module']}}</option>
                                      <?php } ?>  
                                    </select>
                                  </div>
                                  @if(@$search!='')
                                  <div class="input-group input-group-sm col-xs-1 all-btn">
                                      <a href="{{url('manage/mail_settings')}}" class="btn detail-hdr all-link btn-md pull-right">ALL</a>
                                  </div>
                                  @endif
                              </form>
                          </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-striped table-responsive">
                            <thead>
                              <tr class="module-list-hdr">
                                <th width=1%>#</th>
                                <th>About</th>
                                <th>Subject & From</th>
                                <th>Emails</th>
                                <th width="15%"class="text-center">Action<i class="ml-1"></i></th>
                              </tr>
                            </thead>
                            <tbody>
                              @if($results->total()==0)
                                  <tr class="data module-list">
                                      <td colspan="5" align="center">No Records Found</td>
                                  </tr>
                              @else
                                @php
                                    $i = $results->firstItem() - 1;
                                @endphp
                                @foreach($results as $key=>$row)
                                  <tr class="data module-list" id="data-{{ $row->id }}">
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <strong>Title :</strong>
                                        {{ $row->title }}<br>
                                        <strong>Module :</strong>
                                        {{ $row->module }}<br>
                                        <strong>Comment :</strong>
                                        {{ $row->comment }}
                                    </td>
                                    <td>
                                        <strong>Subject :</strong>
                                        {{ $row->subject }}<br>
                                        <strong>From :</strong>
                                        {{ $row->from_text }}
                                    </td>
                                    <td>
                                        <strong>From Email :</strong>
                                        {{ $row->from_email }}<br>
                                        <strong>To Email  :</strong>
                                        {{ $row->to_email }}
                                    </td>
                                    <td class="text-center list-action">
                                      <a href="{{ url('manage/mail-settings/information/'.$row->id) }}" class="" data-toggle="tooltip" title="View Details"><i class="icon fas fa-eye fa-lg"></i></a>
                                      <a href="{{ url('manage/mail-settings/edit/'.$row->id) }}" class="" data-toggle="tooltip" title="Edit"><i class="icon fas fa-edit fa-lg"></i></a>
                                      <a href="javascript:;" class="delete" data-module="{{@$main_module}}"  data-toggle="tooltip" title="Delete"><i class="icon fas fa-trash fa-lg"></i></a>
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