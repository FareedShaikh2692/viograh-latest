@extends('layouts.page')
@php $title = 'Verify Account'; @endphp
@section('title', @$title)
@section('pageContent')

            <div class="my-profile-page sectionbox">
                <h1 class="main_title">Myself</h1>
                @include('include.myself_menu')
                <div class="assets_liabilitiearea">
                    <div class="progressbar_area">
                    <form method="post" action="javascript:;" id="verfiy_otp_password" autocomplete="off">
                         {{ csrf_field() }}  
                         @include('include.notification')
                         <div class="alert alert-danger alert-dismissible " style="display:none;">
                           
                        </div>
                        <div class="alert alert-success alert-dismissible " style="display:none;">
                            
                        </div>
                         <div class="inner_form_area"> 
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <div class="error-msg-otp"></div>
                                     <div class="passmsg">
                                        @if(@$results->password == '')
                                            <span class="passmsg verify_otp" style="display:none;">You need to verify your account to access Net Worth or Nominee section, Please enter OTP below(Havn't received OTP yet? <a href="javascript:;" class="clickhere_link" id="send-otp">click here</a>).</span>
                                            <p class="send_otp">Please click on below button to get OTP to access Networth. OTP will be send to your Google Email.</p>
                                        @else
                                            <span class="passmsg">You need to verify your account to access Net Worth or Nominee section, Please enter your account password below.</span>
                                        @endif
                                     </div>
                                </div>
                                <div class="col-lg-5 col-md-7">
                                    @if(@$results->password == '')
                                    <div class="send_otp">
                                        <a href="javascript:;" class="form_btn" id="send-otp" style="min-width:100px">Send OTP</a>
                                    </div>
                                    <div class="verify_otp" style="display:none;">
                                        <div class="form-group form_box" >
                                            <input type="password" name="password" data-type = "password" class="form-control required passwordwidth" placeholder="Enter OTP Here" value="" id="password-networth"  autocomplete="off"  >
                                            <button type="button"  class="right-icons right-icon-password  netowrth-pass"><i id="netowrth-pass" class="fas fa-eye" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="btn_submit">
                                            <button class="form_btn" type="submit" style="min-width:100px">Verify OTP</button>
                                        </div>
                                    </div>
                                    @else
                                        <div class="form-group form_box">
                                            <input type="password" name="password" data-type = "password" class="form-control required passwordwidth" placeholder="Enter Password Here" value="" id="password-networth"  autocomplete="off"  >
                                            <button type="button"  class="right-icons right-icon-password  netowrth-pass"><i id="netowrth-pass" class="fas fa-eye" aria-hidden="true"></i></button>
                                        </div>
                                        <div class="btn_submit">
                                            <button class="form_btn" type="submit" style="min-width:100px">Verify Password</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
       @stop
       @section('js')
       <script> 
            $("a").click(function (event) {
                if ($(this).hasClass("disabled")) {
                    event.preventDefault();
                }
                $(this).addClass("disabled");
            });
        </script>
       @stop