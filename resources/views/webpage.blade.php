@extends('layouts.page') 
@section('title', @$title) 
@section('metasection') 
  <meta name="description" content="{{$webpage->meta_description}}">
  <meta name="keywords" content="{{$webpage->meta_tag}}">
@stop 
@section('content') 
  <div class="web_area">
    <div class="web_header">
      <div class="container">
        <div class="logo_backbtn">
          @if ($sessiondata != '')
            <a href="{{url('/home')}}" title="" class="logo_web">
              <img src="{{asset('images/logo.png')}}" class="img-fluid" alt=""/>
            </a>
            <a href="{{url('/home')}}" title="" class="back_btn">
              <span><img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt=""/> </span> Back to Home
            </a>  
          @else
            <a href="{{url('/')}}" title="" class="logo_web">
              <img src="{{asset('images/logo.png')}}" class="img-fluid" alt=""/>
            </a>
            <a href="{{url('/')}}" title="" class="back_btn">
              <span><img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt=""/> </span> Back to Login
            </a>  
          @endif
        </div>
      </div>
    </div>
    <div class="web_body">
      <div class="container">
        @if($webpage->page_title == 'About Us')
        <div class="about-us-page sectionbox">
                    <div class="container">
                        <div class="your-story-area main-bottom-padding">
                            <div class="text-center">
                                <h2 class="about-main-title">VioGraf - Your Digital Biography</h2>
                            </div>
                            <div class="row"> 
                                <div class="col-lg-6 order-lg-2">
                                    <div class="your-story-img">
                                        <img src="{{asset('images/about_us_page/what_is_your_story.jpg')}}" class="img-fluid" alt=""/>
                                    </div>
                                </div>
                                <div class="col-lg-6 order-lg-1">
                                    <div class="your-story-box">
                                        <h3>Everyone's life is an interesting story, what is your story?</h3>
                                         <div>
                                         @if ($sessiondata != '')
                                            <a href="{{route('index')}}" class="about-main-button">Get VioGraf</a>
                                         @else
                                            <a href="{{url('/')}}" class="about-main-button">Get VioGraf</a>
                                        @endif
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="capture-every-area main-bottom-padding">
                            <div class="row">
                                <div  class="col-lg-6">
                                    <div class="capture-every-detail">
                                    <h3>Capture every aspect of your life and share it with generations to come.</h3>
                                    <p>VioGraf is derived from the Greek word viografia, which means biography in English. We realized that there aren't enough stories to tell our children, there isn't a book our parents wrote or their parents. There isn't a place where we could store our own stories, our memories. There aren't too many biographies written, and even a few are read by people.</p>
                                    </div>
                                    </div>
                                <div class="col-lg-6">
                                    <ul class="capture-every-row">
                                        <li class="capture-every-col">
                                            <img src="{{asset('images/about_us_page/capture_every_1.jpg')}}" class="img-fluid" alt="Capture every"/>
                                        </li>
                                        <li class="capture-every-col">
                                           <img src="{{asset('images/about_us_page/capture_every_2.jpg')}}" class="img-fluid" alt="Capture every"/>
                                       </li>
                                       <li class="capture-every-col">
                                           <img src="{{asset('images/about_us_page/capture_every_3.jpg')}}" class="img-fluid" alt="Capture every"/>
                                       </li>
                                       <li class="capture-every-col">
                                           <img src="{{asset('images/about_us_page/capture_every_4.jpg')}}" class="img-fluid" alt="Capture every"/>
                                       </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="place-for-loved-area main-bottom-padding">
                            <div class="place-for-loved-img">
                                <img src="{{asset('images/about_us_page/place_for_loved.jpg')}}" class="img-fluid" alt="place for loved" />
                               <div class="svg-waves">
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                    <path fill="#ffffff" fill-opacity="1" d="M0,256L80,240C160,224,320,192,480,186.7C640,181,800,203,960,202.7C1120,203,1280,181,1360,170.7L1440,160L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"></path>
                                  </svg>
                               </div>
                            </div>
                            <div class="place-for-loved-detail">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="place-for-detail">
                                            <h3>A place for you and your loved ones.</h3>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="place-for-detail">
                                            <p>We wanted to create something that will allow people to capture the essence of their life along memories, key achievements, successes & failures.</p>
                                            <p>We wanted to create a place where one can store precious information about their education, profession, ideas, secret recipes, and net worth.</p>
                                            <p>We wanted to create a place where you can write about your experiences & life lessons from which next generations can take inspiration to make their life better.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="memories-area main-bottom-padding memory-padding">
                            <div class="memories-detail">
                                <h3>Memories that will never fade</h3>
                                <p>Let there be no shortage of stories to be shared with your children and their children. Your memories will remain fresh forever, with all generations.
                                   <br/> Your biography will become priceless for your children, and even more for their children.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        @else
        <div class="ckeditor_box sectionbox">
          <h1 style="text-align:center;">{!! $webpage->page_title !!}</h1>
          {!! $webpage->page_content !!}
        </div>
        @endif
      </div>
    </div>
    <div class="web_footer">
      <div class="container">
        <div class="copyright_menu">
          <div class="copyright"><p>© 2021 Viograf All rights reserved</p></div>
          <div class="bottom_menu">
            <ul class="">
              <li class = "{{@$title == 'About Us'?'active':''}}"><a href="{{url('/p/about-us')}}" title="About Us">About Us</a></li>
              <li class = "{{@$title == 'Terms & Conditions'?'active':''}}"><a href="{{url('/p/terms-and-conditions')}}" title="Terms &amp; Conditions">Terms &amp; Conditions</a></li>
              <li class = "{{@$title == 'Privacy Policy'?'active':''}}"><a href="{{url('/p/privacy-policy')}}" title="Privacy Policy">Privacy Policy</a></li>
              <li><a href="{{url('/contact-us')}}" title="Contact Us">Contact Us</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop 