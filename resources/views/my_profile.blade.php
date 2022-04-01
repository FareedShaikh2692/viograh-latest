@extends('layouts.page')
@php $title = 'Edit Profile'; @endphp
@section('title', @$title)
@section('pageContent')
    <div class="my-profile-page sectionbox sectionbox-passwordchange">
        <h1 class="main_title myaccount">My Account</h1>
        @include('include.profile_menu')
        @include('include.notification')
        <div class="my-profile-main">
        <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form" enctype="multipart/form-data" >
                    {{ csrf_field() }}
            <div class="row">
           
                <div class="col-md-3">
                    <div class="profile-img-box profile-img-div">
                        <div class="profile-img upload_photo_video">
                            <input type="file" name="file" class=" profileimage crop_photo" id="profileimage" data-crop='cropimage' accept="image/jpeg,image/png,image/jpg" value="{{@$result->profile_image}}">

                            <img class="upload_photobtn profile-user-img  img-responsive img-circle" data-src="{{@$result->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$result->profile_image) : url('images/userdefault-1.svg')}}" src="{{@$result->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$result->profile_image) : url('images/userdefault-1.svg')}}"  id="preview_img" >
                          
                            <input type="hidden"  name="oldPhoto" id="oldPhoto" data-id="{{@$result->id}}"  value="">
                            <input type="hidden" name="image" id="image" value="{{@$result->profile_image}}">
                        </div>
                        <div class="profile-change-div">
                        <a href="javascript:;" class="profile-Change" id="profile-Change">Change Photo</a></div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="inner_form_area">
                   
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form_box">
                                    <p>First Name</p>
                                    <input type="text" name="first_name" maxlength="200" minlength="2"
                                        class="form-control required" value="{{old('first_name',@$result->first_name)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form_box">
                                    <p>Last Name</p>
                                    <input type="text" name="last_name" maxlength="200" minlength="2"
                                        class="form-control required" value="{{old('last_name',@$result->last_name)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form_box blood-group-gender-zindex">
                                    <p>Gender</p>
                                    <select class="js-example-responsive" name="gender" >
                                        <option value="male" {{ old('gender',@$result->gender)=='male' ? 'selected' : ''  }}>Male</option>
                                        <option value="female"  {{ old('gender',@$result->gender)=='female' ? 'selected' : ''  }}>Female</option>
                                        <option value="other" {{ old('gender',@$result->gender)=='other' ? 'selected' : ''  }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form_box">
                                    <p>Email Address</p>
                                    <input type="email" name="email" data-type = "password"
                                        class="form-control" value="{{old('email',@$result->email)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form_box phone-number-div">
                                    <p>Phone Number</p>
                                    <input type="text" name="phone_number_profile" class="form-control phone-number"  value="{{old('phone_number',@$result->phone_number)}}">
                                </div>
                                <div class="form-group form_box dial-code-div">
                                    <input type="text" name="dial_code" minlength="2" maxlength="7" class="form-control dial-code-input"  value="{{old('dial_code',@$result->dial_code)}}">
                                </div>

                            </div>
                           
                            <div class="col-lg-6 col-md-6">
                                    <div class="form-group form_box">
                                        <p>Date Of Birth</p>
                                        <div class="date_of_birth_box experiencedate">
                                            <span>
                                                <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid " alt="">
                                            </span>
                                            <input type="text" name="birth_date" class="datepicker profle-dob" value="{{@$result->birth_date!=''  ? Common_function::date_formate1(old('birth_date',(@$result->birth_date), 'm/d/Y' )) : $date }}" id="dobpicker" autocomplete="off" readonly="readonly"/>  
                                        </div>
                                    </div>  
                                </div>
                            
                            <div class="col-md-6">
                                <div class="form-group form_box blood-group-gender-zindex">
                                    <p>Blood Group</p> 
                                    <select class="js-example-responsive" name="blood_group">
                                    <option value="">Select Blood Group</option>
                                        <option value="A RhD positive (A+)" {{ old('blood_group',@$result->blood_group)=='A RhD positive (A+)' ? 'selected' : ''  }}>A RhD positive (A+)</option>
                                        <option value="A RhD negative (A-)" {{ old('blood_group',@$result->blood_group)=='A RhD negative (A-)' ? 'selected' : ''  }}>A RhD negative (A-)</option>
                                        <option value="B RhD positive (B+)" {{ old('blood_group',@$result->blood_group)=='B RhD positive (B+)' ? 'selected' : ''  }}>B RhD positive (B+)</option>
                                        <option value="B RhD negative (B-)" {{ old('blood_group',@$result->blood_group)=='B RhD negative (B-)' ? 'selected' : ''  }}>B RhD negative (B-)</option>
                                        <option value="O RhD positive (O+)" {{ old('blood_group',@$result->blood_group)=='O RhD positive (O+)' ? 'selected' : ''  }}>O RhD positive (O+)</option>
                                        <option value="O RhD negative (O-)" {{ old('blood_group',@$result->blood_group)=='O RhD negative (O-)' ? 'selected' : ''  }}>O RhD negative (O-)</option>
                                        <option value="AB RhD positive (AB+)" {{ old('blood_group',@$result->blood_group)=='AB RhD positive (AB+)' ? 'selected' : ''  }}>AB RhD positive (AB+)</option>
                                        <option value="AB RhD negative (AB-)" {{ old('blood_group',@$result->blood_group)=='AB RhD negative (AB-)' ? 'selected' : ''  }}>AB RhD negative (AB-)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group form_box">
                                    <p>Profession</p>
                                    <input type="text" name="profession" class="form-control " value="{{ old('profession',@$result->profession) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form_box">
                                    <p>Places Lived</p>
                                   
                                    <input type="text" name="places_lived"  
                                        class="form-control " value="{{ old('places_lived',@$result->places_lived) }}">
                                   
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form_box blood-group-gender-zindex">
                                    <p>Currency</p> 
                                    <select class="currency" id="currency" name="currency_id" > 
                                        @foreach($currency_data as $cur)
                                            <option value="{{ $cur->id }}" {{(@$result->currency_id != 0) ? ($cur->id == old('currency_id',@$result->currency_id)  ? 'selected' : '')  : $cur->id == 5 ? 'selected' : '' }}>{{ $cur->currency_code }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form_box">
                                    <p>Address</p>
                                    <textarea class="form-control form_textarea addressbox" name="address" placeholder=""  maxlength="500">{{ old('address',@$result->address) }}</textarea>
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                               <!--  <div class="form-group form_box">
                                    <p>About Me</p>
                                    <textarea class="form-control form_textarea" name="about_me" placeholder="" >{{ old('about_me',@$result->about_me) }}</textarea>
                                    </div> -->
                                    <div class="btn_submit">
                                        <button type="submit" class="form_btn" id="save_profile">Update Profile</button>
                                        <a href="{{route('profile.index')}}"><button type="button" class="form_btn cancel_btn">Cancel</button></a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
          
            </div>
        </div>
    </div>

@stop