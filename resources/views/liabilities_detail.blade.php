@extends('layouts.page')
@php $title = 'Liability Information'; @endphp
@section('title', @$title)
@section('pageContent')
    <div class="inner_back_center_row row">
        <div class="inner_back_col">
            <a href="{{route('asset.index')}}" class="btn_back">
                <img src="{{asset('images/icons/back_btn.svg') }}" class="img-fluid" alt="VioGraf" />
            </a>
        </div>

        <div class="inner_center_page">
            <div class="sectionbox">
                <div class="assets_liabilities_area">
                    <div class="row">
                        <div class="col-md-4 order-md-2">
                            <div class="edit_delete_icon">
                                <a href="{{url('/liability/edit-liability/'.@$results->id)}}" data-toggle="tooltip" title="Edit">
                                    <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid" alt="VioGraf">
                                </a>
                                <a href="javascript:;" class="delete_feed" data-id="{{@$results->id }}"  data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                    <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid" alt="VioGraf" />
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8 order-md-1">
                            <div class="assets_liabilities_title">
                                <h1 class="main_title asset_liab_title  ">{{ucfirst(@$results->title)}} 
                                    <a href="" class="btn_web yellow">liability</a>
                                    @if(@$results->is_save_as_draft == 1)
                                        <span class="draft-class draft-class-asset-liab">Draft</span>
                                    @endif
                                </h1>
                                <span class="time">{{ Carbon\Carbon::parse(@$results->created_at)->diffForHumans()}} </span>
                            </div>
                        </div>
                    </div>
                    <div class="liabilities_detail" >
                        <div class="user-name common-class">
                            <h5>Bank Name</h5>
                            @if(!empty(@$results->bank_name))
                                <p class="usertext">{{ucfirst(@$results->bank_name)}}</p>
                            @else
                                <span>-</span>
                            @endif
                        </div>
                        <div class="email-name common-class">
                            <h5>Account Number</h5>
                            @if(!empty(@$results->account_number))
                                <a href="" class="usertext">{{@$results->account_number}}</a>
                            @else
                                <span>-</span>
                            @endif
                        </div>
                        <div class="user-price common-class">
                            @php   
                                if (@$results->amount != ''){
                                    $num = @$results->amount;
                                    $var=explode(',',$num);
                                    $imvar=implode('',$var);
                                    $amount = strlen($imvar);
                                    $amountfinal = $imvar;

                                    setlocale(LC_MONETARY, 'en_IN');
                                    if(strpos($amountfinal, '.') !== false){
                                        $j = number_format($amountfinal,2);
                                    } else{
                                        $j= number_format($amountfinal);
                                        
                                    }
                                }
                            @endphp
                            <h2>
                                @if (@$results->amount != '')
                                    {{$j}} 
                                    @if(@$results->getUserLiability->currency_id != 0)
                                        {{$results->getUserLiability->UserCurrency->currency_code}}
                                    @endif
                                @else
                                    0
                                @endif
                            </h2>
                        </div>
                            
                    </div>
                    <div class="liabilities_detail" >
                        @if(!(@$results->getUserLiabilityDocuments->isEmpty()))
                            <div class="Documentsarea common-class">
                                <h5 class="docs">Documents</h5>
                                <div class="Documentsrow">
                                    <div class="row">
                                        @foreach(@$results->getUserLiabilityDocuments as $docval)      
                                            <div class="col-md-4 col-sm-4 ">                          
                                                <div class="Documentsbox">
                                                    <a class="document-link-css" href="{{Common_function::get_s3_file(config('custom_config.s3_liability_document'),$docval->file)}}"  target="_blank">
                                                        <span>
                                                            <img src="{{asset('images/icons/link.svg')}}" class="img-fluid" alt="VioGraf">
                                                        </span>
                                                        {{$docval->file}}
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif 
                    </div>
                    <p class="desc-info description-lib " style="overflow-wrap:anywhere;">{!! Common_function::string_read_more(nl2br(@$results->description)) !!}</p>  
                </div>
            </div>      
        </div>
    </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="feed_id" value="{{@$results->id}}">

@stop