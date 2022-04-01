@extends('layouts.page')
@section('title', @$title)

@section('pageContent')
    <div class="inner_back_center_row row">
        <div class="inner_back_col">
            <a href="javascript:;" data-url="{{route('myself.index')}}" class="btn_back">
                <img src="{{ asset('images/icons/back_btn.svg') }}" class="img-fluid" alt="VioGraf" />
            </a>
        </div>
        <div class="inner_center_page">
            <div class="sectionbox">
                <h1 class="main_title">{{@$title}}</h1>
                @include('include.notification')
                <div class=" inner_form_area">
                    <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group form_box upload_photo">
                            <p>Upload Banner Image</p> 
                            <div class="upload_photo_box upload_photo_video">
                            <input type="file" class="upload_photobtn crop_photo js-banner-image" name="file" id="adicionaphoto" data-crop='cropimage'  accept="image/jpeg,image/png,image/jpg">
                            <input type="hidden" name="oldPhoto" id="oldPhoto" data-id="{{@$result->id}}" data-url="delete-bannerfile" value="">
                            
                            </div>
                            <div class="upload_show">
                                <ul class="addimgs_area" >
                                    @if(@$result->banner_image != '')
                                    <li class="addimg"> 
                                                <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button>  <img src="{{@$result->banner_image ? Common_function::get_s3_file(config('custom_config.s3_banner_big'),$result->banner_image) : url('images/defaultweb.jpeg')}}" alt="Vio Graf" /> 
                                            </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="form-group form_box">
                            <p>Essence Of Life</p>
                            <div class="multiple_questions">
                                <div class="form-group form_box">
                                    <textarea placeholder="Beauty begins the moment you decide to be yourself." class="form-control  textarea_essence" name="essence_of_life">{{old('essence_of_life',@$result->essence_of_life)}}</textarea>
                                </div>  
                            </div>  
                        </div>   
                        <div class="form-group form_box">
                            <p>Something About Me</p>
                            <div class="multiple_questions">
                                <div class="form-group form_box">
                                    <textarea class="form-control form_textarea" name="biography">{{old('biography',@$result->biography)}}</textarea>
                                </div>  
                            </div>  
                        </div>                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Place of Birth</p>
                                    <input type="text" name="place_of_birth" placeholder="e.g. Barlin, New York etc"  value="{{old('place_of_birth',@$result->place_of_birth)}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Favorite Movies</p>
                                    <input type="text" name="favourite_movie" placeholder="e.g. Titanic, Avengers etc" value="{{old('favourite_movie',@$result->favourite_movie)}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Favorite Songs</p>
                                    <input type="text" name="favourite_song" placeholder="e.g. Fire Stome, Closer etc" value="{{old('favourite_song',@$result->favourite_song)}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Favorite Books</p>
                                    <input type="text" name="favourite_book" placeholder="e.g. Oliver Twist, The Player etc" value="{{old('favourite_book',@$result->favourite_book)}}" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Favorite Eating Joints</p>
                                    <input type="text" name="favourite_eating_joints" value="{{old('favourite_eating_joints',@$result->favourite_eating_joints)}}" placeholder="e.g. McDonald's, Jamba Juice etc"  class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Hobbies</p>
                                    <input type="text" value="{{old('hobbies',@$result->hobbies)}}" name="hobbies" placeholder="e.g. Traveling, Reading etc"  class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Food</p>
                                    <input type="text" name="food" value="{{old('food',@$result->food)}}"  placeholder="e.g. Mexican, Chinese etc"  class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Role Model</p>
                                    <input type="text" name="role_model" value="{{old('role_model',@$result->role_model)}}" placeholder="e.g. Barack Obama, Stephen Hawking etc" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Car</p>
                                    <input type="text" name="car" value="{{old('car',@$result->car)}}" placeholder="e.g. BMW, Audi etc" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Brand</p>
                                    <input type="text" name="brand"  value="{{old('brand',@$result->brand)}}" placeholder="e.g VAN Heusen, Calvin Klein etc" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>TV Shows</p>
                                    <input type="text" name="tv_shows" value="{{old('tv_shows',@$result->tv_shows)}}" placeholder="e.g. Friends, 24 etc" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Actors</p>
                                    <input type="text" name="actors"  value="{{old('actors',@$result->actors)}}" placeholder="e.g. Tom Cruise, Will Smith etc" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Sports Person</p>
                                    <input type="text" name="sports_person" value="{{old('sports_person',@$result->sports_person)}}" placeholder="e.g. Usain Bolt, Jos Buttler etc" class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Politician</p>
                                    <input type="text" name="politician" value="{{old('politician',@$result->politician)}}" placeholder="e.g Abraham Lincoln etc"  class="form-control ">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Diet</p>
                                    <input type="text" name="diet"  value="{{old('diet',@$result->diet)}}" placeholder="Veg, Non - Veg, Vegan"  class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group form_box">
                                    <p>Zodiac Sign</p>
                                    <input type="text" name="zodiac_sign"  value="{{old('zodiac_sign',@$result->zodiac_sign)}}"  placeholder="e.g Leo, Virgo etc " class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="btn_submit">
                        
                            <button class="form_btn">Update</button>
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
    
@stop
@section('js')
    <script>        
        var _placeholder1 = 'Did you hear anything from your parents how was the delivery? \n\nWas it a natural one or ceaserian? \n\nDid you parents tell you how they celebrated your birth? \n\nHow was the place the you were born at, any memories you still carry from that place? \n\nDo you still remember any friends from there? \n\nIs that place a still your favourite or you moved on?';
        $('.form_textarea').attr('placeholder', _placeholder1);
    
    </script>
@stop
