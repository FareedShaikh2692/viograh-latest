@extends('layouts.page')
@php $title = 'Add Career'; @endphp
@section('title', @$title)
@section('pageContent')
    <div class="welcome-familyarea">
        <div class="inner_back_center_row row">
            <div class="inner_back_col">
                <a href="" class="btn_back">
                <img src="images/icons/back_btn.svg" class="img-fluid" alt="VioGraf" />
                </a>
            </div>
            <div class="inner_center_page">
            <div class="sectionbox">
                <h1 class="main_title">Write a Diary Article</h1>
                <!-- <input type="file" multiple onchange="previewMultiple(event)" id="adicionafoto">
                <div id="galeria"></div> -->
                <div class="inner_form_area">
                    <div class="form-group form_box upload_photo">
                        <p>Upload Photo</p> 
                        <div class="upload_photo_box">
                        <input type="file" class="upload_photobtn" multiple onchange="previewMultiple(event)" id="adicionafoto">
                    </div>
                    <div class="upload_show">
                        <ul class="addimgs_area" >
                            
                        </ul>
                        </div>
                    </div>
                    <div class="form-group form_box">
                    <p>Title</p>
                    <input type="text"  name="txtfname" maxlength="200" minlength="2" class="form-control required">
                    </div>
                    <div class="form-group form_box">
                    <p>Description</p>
                    <textarea class="form-control form_textarea" name="txtmessage" placeholder="" maxlength="500"></textarea>
                    </div>
                    <div class="dropdownsubmit_row">
                            <div class="d-flex publicbtn-flex">
                                <div class="new-drop">
                                <select class="publicbtn" name="privacy">
                                    <option value="public" {{ old('privacy',@$result->privacy)=='public' ? 'selected' : ''  }} data-image="{{asset('images/icons/public_icon.svg')}}" >Public</option>
                                    <option value="family" {{ old('privacy',@$result->privacy)=='family' ? 'selected' : ''  }} data-image="{{asset('images/icons/family_icon.svg')}}">Family</option>
                                    <option value="private" {{ old('privacy',@$result->privacy)=='private' ? 'selected' : ''  }} data-image="{{asset('images/icons/private_icon.svg')}}">Private</option>
                                </select>
                            </div>
                                <div class="btn_submit">
                                    <button class="form_btn"> Post</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@stop