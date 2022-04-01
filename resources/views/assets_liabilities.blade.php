@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
<div class="my-profile-page sectionbox">
    <h1 class="main_title">Myself</h1>
    @include('include.myself_menu')

    <div class="assets_liabilitiearea"> 
        <div class="progressbar_area">
            <ul class="progressbar_box">
                <li class="pr-name">
                    <p class="">Assets</p>
                </li>
                <li class="pr-bar">
                    <div class="progressbar blue" id="assets"></div>
                    @php            
                        $num = @$assetfinalsum;
                        $amount = strlen($num);
                        $amountfinal = $num;
                        setlocale(LC_MONETARY, 'en_IN');
                        if(strpos($amountfinal, '.') !== false){
                            $j = number_format($amountfinal,2);
                        } else{
                            $j= number_format($amountfinal,2);
                        }
                    @endphp
                    <p class="amountshow">
                        @if($num==0)
                            <span>00.00</span>
                        @else   
                            {{$j}}
                            @if(@$currency->currency_id != 0)
                                {{@$currency->UserCurrency->currency_code}}
                            @endif
                        @endif
                    </p>
                </li>
                <li class="pr-total">
                    <p class="amounthide" {{Common_function::amount_format  ($amountfinal)}}>
                        @if($num==0)
                            <span>00.00</span>
                        @else
                            {{$j}}
                            @if(@$currency->currency_id != 0)
                                {{@$currency->UserCurrency->currency_code}}
                            @endif
                        @endif
                    </p>
                </li>              
            </ul>
            <ul class="progressbar_box">
                <li class="pr-name">
                    <p class="">Liabilities</p>
                </li>
                <li class="pr-bar liabtotal">
                    <div class="progressbar rad" id="liabilities"></div>
                    @php            
                        $numnew = @$liabfinalsum;
                        $amountnew = strlen($numnew);
                        $amountfinalnew = $numnew;
                        setlocale(LC_MONETARY, 'en_IN');
                        if(strpos($amountfinal, '.') !== false){
                            $j = number_format($amountfinalnew,2);
                        } else{
                            $j= number_format($amountfinalnew,2);
                        }      
                    @endphp
                    <p class="amountshow" >
                        @if($numnew==0)
                            <span>00.00</span>
                        @else 
                           {{$j}}
                            @if(@$currency->currency_id != 0)
                                {{@$currency->UserCurrency->currency_code}}
                            @endif
                             
                        @endif
                    </p>
                </li>
                <li class="pr-total">
                    <p class="amounthide" >
                        @if($numnew==0)
                            <span>00.00</span>
                        @else
                           {{$j}}
                            @if(@$currency->currency_id != 0)
                                {{@$currency->UserCurrency->currency_code}}
                            @endif
                        @endif 
                    </p>
                </li>
            </ul>
        </div>
        @include('include.notification')
        <div class="row">
            <div class="col-lg-6">
                <div class="top_assets_liabilitiearea">
                    <h2 class="font22">Assets</h2>
                    <div class="text-right">
                        <a href="{{route('assets.asset-add')}}" class="pink_btn addplus"><span> </span> Add Asset</a>
                    </div>
                </div>
                @if(count($results)==0)
                    <div class="text-center">
                        <p class="cls-no-record">No Assets Found</p>
                    </div>
                @else
                    @foreach($results as $key=>$row)
                        @php
                            if(@$row->amount != ''){
                                $num = $row->amount;
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
                        
                        <div class="assets_liabilitiebox info-link assetadd"  data-link="{{route('assets.asset-information',@$row->id)}}">
                            <div class="title_price">
                                <h3 class="assets_liab_title">{{($row->title != '') ? $row->title: '-'}}</h3>
                                <h4 class="assets_liab_price">
                                    @if(@$row->amount != '')
                                      {{$j}}
                                        @if($row->getUser->currency_id != 0)
                                            {{$row->getUser->UserCurrency->currency_code}}
                                        @endif
                                    @else
                                        0
                                    @endif
                                </h4>
                            </div>
                            <div class="assets_liab_detail">
                                @if(@$row->nominee_name=='' && @$row->nominee_email=='' && @$row->nominee_phone_number=='' )
                                    <div class="al-name-col bankname common nominee_name_add ">
                                        <p>Nominee</p>
                                        <span class="assetadd addnominee" id="add"  data-link="{{url('/assets/edit-asset/'.@$row->id.'#assetNominee')}}" >Add Nominee</span>
                                    </div>
                                    <div class="al-days-col common">
                                        <i>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}} </i>
                                    </div>
                                    <div class="al-days-col common">
                                        <div class="edit_delete_icon edit_delete_icon_networthlist" id="editdelete">
                                            <a href="{{url('/assets/edit-asset/'.@$row->id)}}" class="edit-nominee-href"  data-toggle="tooltip" id="editbutton" title="Edit">
                                                <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid edit-nominee-icon" alt="VioGraf">
                                            </a>
                                            <a href="javascript:;" class="edit-nominee-href delete_feed_liab" id="assetid"  data-id="{{@$row->id }}"  data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                                <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid edit-nominee-icon" alt="VioGraf" />
                                            </a>
                                        </div>
                                    </div>
                                    @if(@$row->is_save_as_draft == 1)
                                        <span class="draft-class-other">Draft</span>
                                    @endif
                            </div>     
                        </div>
                                @else
                                    <div class="al-name-col bankname common ">
                                        <p>Nominee</p>
                                        <p class="al-detail">
                                            {{(!empty(@$row->nominee_name)) ? ucfirst(@$row->nominee_name) : '-'}} 
                                        </p>
                                    </div>      
                                    <div class="al-email-col common">
                                        <p>Email Address</p>
                                        {{(!empty(@$row->nominee_email)) ? @$row->nominee_email : '-'}}
                                    </div>
                                    <div class="al-phone-col common">
                                        <p>Phone</p>
                                        {{ (!empty(@$row->nominee_phone_number)) ? @$row->nominee_phone_number : '-'}}
                                    </div>
                                    <div class="al-days-col common">
                                        <i>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}} </i>
                                    </div>
                                    <div class="al-days-col common">
                                        <div class="edit_delete_icon edit_delete_icon_networthlist">
                                            <a href="{{url('/assets/edit-asset/'.@$row->id)}}" class="edit-nominee-href "   data-toggle="tooltip" id="editbutton" title="Edit">
                                                <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid edit-nominee-icon" alt="VioGraf">
                                            </a>
                                            <a href="javascript:;"  class="edit-nominee-href delete_feed_liab" id="assetid"  data-id="{{@$row->id}}"  data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                                <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid edit-nominee-icon" alt="VioGraf" />
                                            </a>
                                        </div>
                                    </div>
                            </div>
                            @if(@$row->is_save_as_draft == 1)
                                        <span class="draft-class-other">Draft</span>
                            @endif
                        </div>
                            @endif
                           
                    @endforeach
                @endif
            </div>
            <div class="col-lg-6">
                <div class="top_assets_liabilitiearea">
                    <h2 class="font22">Liabilities</h2>
                    <div class="text-right">
                        <a href="{{route('liability.liability-add')}}" class="pink_btn addplus"><span> </span> Add Liability</a>
                    </div>
                </div>
                @if(count(@$results_liability)==0)
                    <div class="text-center">
                        <p class="cls-no-record">No Liabilities Found</p>
                    </div>
                @else
                    @foreach(@$results_liability as $key=>$row)
                        @php
                            if (@$row->amount > 0){
                                $num = @$row->amount;
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
                            }else{
                                $j = 0;
                            }
                        @endphp
                           
                        <div class="assets_liabilitiebox info-link assetadd"  data-link="{{route('liability.liability-information',@$row->id)}}">
                            <div class="title_price">
                                <h3 class="assets_liab_title">{{($row->title != '') ? $row->title: '-'}}</h3>
                                <h4 class="assets_liab_price">
                                    @if (@$row->amount != '')
                                        {{$j}}
                                        @if(@$row->getUserLiability->currency_id != 0)
                                            {{@$row->getUserLiability->UserCurrency->currency_code}}
                                        @endif
                                    @else
                                        0
                                    @endif
                                </h4>
                            </div>
                                
                            <div class="assets_liab_detail">
                                <div class="al-name-col common liab-name">
                                    @if(@$row->bank_name=='' && @$row->account_number=='' )
                                        <p>Bank Details</p>
                                        <span class="assetadd addliability  " id="addliability" data-link="{{url('/liability/edit-liability/'.@$row->id.'#bankdetails')}}">  Add Bank Details</span>
                                </div>
                                <div class="al-email-col common"></div>
                                <div class="al-days-col common">
                                    <i>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}}</i>
                                </div>
                                <div class="al-days-col common">
                                    <div class="edit_delete_icon edit_delete_icon_networthlist" id="editdelete">
                                        <a href="{{url('/liability/edit-liability/'.@$row->id)}}" class="edit-nominee-href"  data-toggle="tooltip" id="editbutton" title="Edit">
                                            <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid edit-nominee-icon" alt="VioGraf">
                                        </a>
                                        <a href="javascript:;" id="delete_liab"  class="edit-nominee-href delete_feed_liab"  data-id="{{@$row->id }}"  data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                            <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid edit-nominee-icon" alt="VioGraf" />
                                        </a>
                                    </div>
                                </div>
                                @if(@$row->is_save_as_draft == 1)
                                    <span class=" draft-class-other">Draft</span>
                                @endif
                            </div>
                            @else
                                <p>Bank Name</p>
                                <span class="bankname"> {{$row->bank_name}}</span>
                        </div>
                                <div class="al-email-col common">
                                    <p>Account Number</p>
                                    {{$row->account_number}}
                                </div>
                                <div class="al-days-col common">
                                    <i>{{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}}</i>
                                </div>
                                <div class="al-days-col common liability">
                                    <div class="edit_delete_icon edit_delete_icon_networthlist" id="editdelete">
                                        <a href="{{url('/liability/edit-liability/'.@$row->id)}}" class="edit-nominee-href"  data-toggle="tooltip" id="editbutton" title="Edit">
                                            <img src="{{asset ('images/icons/edit_icon.svg') }}" class="img-fluid edit-nominee-icon" alt="VioGraf">
                                        </a>
                                        <a href="javascript:;" id="delete_liab" class="edit-nominee-href delete_feed_liab"  data-id="{{@$row->id }}"  data-toggle="tooltip"  data-module="{{@$module}}" title="Delete">
                                            <img src="{{asset('images/icons/delete_icon.svg')}}" class="img-fluid edit-nominee-icon" alt="VioGraf" />
                                        </a>
                                    </div>
                                    <input type="hidden" id="feed_id_liab" class="feed_id_liab" value="{{@$row->id}}">
                                </div>
                                @if(@$row->is_save_as_draft == 1)
                                        <span class="draft-class-other">Draft</span>
                            @endif
                        </div>
                            @endif
                    </div>   
                @endforeach
            @endif    
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="module" value="{{@$module}}">
<input type="hidden" id="maintable" value="{{@$mainTable}}">
<input type="hidden" id="mainTableLiab" value="{{@$mainTableLiab}}">
<input type="hidden" id="method" value="{{@$method}}">
<input type="hidden" id="feed_id" value="">
                
@stop
@section('js')
    <script>
        $(function () {
            assetval="{{@$totlcalasset}}";
            
            $("#assets").progressbar({
                value: parseFloat(assetval)
                
            });
        });

        $(function () {
            liablityval="{{@$totlcalliab}}";
            $("#liabilities").progressbar({
                value:  parseFloat(liablityval)
            });
        });
    </script>
@endsection