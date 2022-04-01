@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
        <div class="inner_back_center_row row">
            <div class="inner_back_col">
              @php 
                   $url = \Request::fullUrl();
                    $name = explode('?',$url);
                    $final = implode(',',$name);
                    $final_name = explode('=',$final);
             @endphp
            @if((empty($final_name[1])))
           
                <a href="{{route('asset.index')}}" class="btn_back ">
                        <img src="{{asset('images/icons/back_btn.svg') }}" class="img-fluid" alt="VioGraf" />
                </a>
            @else
                @if($final_name[1] == 'myselfadded')
              
                    <a href="{{route('nominee.myself_added')}}" class="btn_back ">
                            <img src="{{asset('images/icons/back_btn.svg') }}" class="img-fluid" alt="VioGraf" />
                    </a>
                @elseif($final_name[1] == 'nomineeadded')
                    <a href="{{route('nominee.index')}}" class="btn_back ">
                            <img src="{{asset('images/icons/back_btn.svg') }}" class="img-fluid" alt="VioGraf" />
                    </a>
               
                @endif
            @endif
                
            </div>
            <div class="inner_center_page">
                <div class="sectionbox">
                    <div class="assets_liabilities_area">
                            <div class="row">
                            @if(@$results->user_id==Auth::user()->id)
                                <div class="col-md-4 order-md-2">
                                    <div class="edit_delete_icon">
                                    <a href="{{url('/assets/edit-asset/'.@$results->id)}}" data-toggle="tooltip" title="Edit">
                                        <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid" alt="VioGraf">
                                    </a>
                                    <a href="javascript:;" class="delete_feed"   data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                        <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid" alt="VioGraf" />
                                    </a>
                                    </div>
                                </div>
                            @endif
                                <div class="col-md-8 order-md-1">
                                    <div class="assets_liabilities_title">
                                        <h1 class="main_title asset_liab_title">{{ucfirst(@$results->title)}} <a href="" class="btn_web yellow">asset</a>                                       
                                        @if(@$results->is_save_as_draft == 1)   
                                        <span class="draft-class draft-class-asset-liab">Draft</span>
                                        @endif
                                        </h1>
                                        <span class="time">{{ Carbon\Carbon::parse(@$results->created_at)->diffForHumans()}} </span>
                                    </div>
                                </div>
                            </div>
                            
                    
                        <div class="liabilities_detail">
                            <div class="user-price common-class asset-amount-space">
                                @php
                                if(@$results->amount != ''){
                                    $num = @$results->amount;
                                    $var=explode(',',$num);
                                    $imvar=implode('',$var);
                                    $amount = strlen($imvar);
                                    $amountfinal = $imvar;
                                    setlocale(LC_MONETARY, 'en_IN');
                                    if(strpos($amountfinal, '.') !== false){
                                      $j = number_format($amountfinal,2);
                                    } else{
                                        $j= number_format($amountfinal,2);
                                    
                                    } 
                                }
                                @endphp
                                @if(@$results->amount != '')    
                                <h2>{{$j}} 
                                @if(@$results->getUser->currency_id != 0)
                                    {{$results->getUser->UserCurrency->currency_code}}
                                @endif
                                </h2>@endif
                            </div>
                           
                        </div>
                        @if(!(@$results->getUserDocuments->isEmpty()))
                            <div class="Documentsarea common-class newDocAsset">
                                    <h5>Documents</h5>
                                    <div class="Documentsrow">
                                        <div class="row">
                                            @foreach(@$results->getUserDocuments as $docval)          
                                            <div class="col-md-4 col-sm-4 col-12">                      
                                                <div class="Documentsbox docswrap">
                                                    <a class="document-link-css" href="{{Common_function::get_s3_file(config('custom_config.s3_asset_document'),$docval->file)}}"  target="_blank">
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
                        <div class="assets_liabilities_details">
                        <p class="desc-info description-asset-lib" >{!! Common_function::string_read_more(@$results->description) !!}</p>
                        </div>
                    </div>
                   
                    @if(!empty(@$results->nominee_name) || !empty(@$results->nominee_emai) || !empty(@$results->nominee_phone_number))
                    <hr>
                 
                    <div class="assets_liabilities_area mt-4">
                  
                        <div class="row">
                          
                        @if(@$results->user_id==Auth::user()->id)
                            <div class="col-md-4 order-md-2">
                                <div class="edit_delete_icon ">
                                <a href="{{url('/assets/edit-asset/'.@$results->id.'#assetNominee')}}" class="edit-nominee-href" data-toggle="tooltip" id="editbutton" title="Edit">
                                    <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid edit-nominee-icon" alt="VioGraf">
                                </a>
                                <a href="javascript:;" id="delete_nominee" class="edit-nominee-href delete_nominee"   data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                    <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid edit-nominee-icon" alt="VioGraf" />
                                </a>
                                    
                                </div>
                            </div>
                        @endif
                            <div class="liabilities_detail nomineetitleinfo col-md-8 order-md-1">
                                
                                <h3 class="nominee_title ">Nominee Detail</h3>
                                
                            </div>
                        </div>
                            <div class="liabilities_detail" >
                                
                                <div class="user-name common-class nominee-name">
                                    <h5>Nominee</h5>
                                    <p class="usertext">{{ (!empty(@$results->nominee_name)) ? ucfirst(@$results->nominee_name) : '-'}}</p>
                                </div>
                                <div class="email-name common-class nominee-email">
                                    <h5>Email Address</h5>
                                    <span class="usertext">{{(!empty(@$results->nominee_email)) ? @$results->nominee_email : '-'}}</span>
                                </div>
                                <div class="user-number common-class nominee-number">
                                    <h5>Phone</h5>
                                    <span class="usertext"> {{ (!empty(@$results->nominee_phone_number)) ? @$results->nominee_phone_number : '-'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
               
            </div>
            
            
        </div>
    <input type="hidden" id="maintable" value="{{@$mainTable}}">
    <input type="hidden" id="module" value="{{@$module}}">
    <input type="hidden" id="feed_id" value="{{@$results->id}}">
   
@stop
