@extends('layouts.page')
@php $title = @$title; @endphp
@section('title', @$title)
@section('pageContent')
            <div class="my-profile-page sectionbox">
                <h1 class="main_title">Myself</h1>
                     @include('include.myself_menu')
                     <div class="assets_liabilitiearea">
                       @include('include.nominee_menu')
                       @if(count(@$results)==0)
                                <div class="text-center">
                                    <p class="cls-no-record">No Records Found</p>
                                </div>
                        @else
                        <div class="row">
                        @foreach($results as $key=>$row)
                            <div class="col-lg-6">
                                <a href="{{Request::segment(2)=='myself_added' ? url('assets/asset-information/'.@$row->id.'?section=myselfadded') :  url('assets/asset-information/'.@$row->id.'?section=nomineeadded') }}" class="al-detail assetadd">
                                    <div class="assets_liabilitiebox">
                                        <div class="title_price">
                                            <h3 class="assets_liab_title">{{ucfirst(@$row->title)}}</h3>
                                            @php            
                                                $num = $row->amount;
                                                $var=explode(',',$num);
                                                $imvar=implode('',$var);
                                                $amount = strlen($imvar);
                                                $amountfinal = $imvar;
                    
                                                setlocale(LC_MONETARY, 'en_IN');
                                                $j = number_format((float)$amountfinal,2);
                                            @endphp
                                            <h4 class="assets_liab_price">
                                                @if (@$row->amount != '')
                                                    {{$j}} 
                                                    @if(@$row->getUser->currency_id != 0)
                                                        {{@$row->getUser->UserCurrency->currency_code}}
                                                    @endif
                                                @else
                                                    0.00
                                                @endif
                                        </div>
                                    
                                        <div class="assets_liab_detail">
                                            <div class="al-name-col bankname common">
                                        @if(@$row->nominee_name=='' && @$row->nominee_email=='' && @$row->nominee_phone_number=='' )
                                            <p>Nominee</p>
                                        Add Nominee
                                            </div>
                                            
                                            <div class="al-days-col common">
                                                <p>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}} </p>
                                                
                                            </div>
                                            </div>
                                        </div>
                                        @else
                                            <p>Nominee</p>
                                            @if(!empty(@$row->nominee_name))
                                                <p class="al-detail">
                                                {{ucfirst(@$row->nominee_name)}}
                                                </p>
                                                @else
                                            <span>-</span>
                                        @endif
                                            </div>
                                            <div class="al-email-col common">
                                                <p>Email Address</p>
                                                @if(!empty(@$row->nominee_email))
                                            {{@$row->nominee_email}}
                                                @else
                                            <span>-</span>
                                        @endif
                                            </div>
                                            <div class="al-phone-col common">
                                                <p>Phone</p>
                                                @if(!empty(@$row->nominee_phone_number))
                                            {{@$row->nominee_phone_number}}
                                                @else
                                            <span>-</span>
                                        @endif
                                            </div>
                                            <div class="al-days-col common">
                                                <i>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}} </i>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    @endif
                            
                           
                                </a>
                            
                            </div>
                            @endforeach
                        </div>
                        @endif
                   
                </div>
            </div>
       @stop