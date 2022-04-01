@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    <div class="welcome-familyarea">
        <div class="inner_back_center_row row">
            <div class="inner_back_col">
                <a href="javascript:;" data-url="{{route('diary.index')}}" class="btn_back">
                <img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt="VioGraf" />
                </a>
            </div>
            <div class="inner_center_page">
                <div class="sectionbox">
                    @include('include.notification')
                    <h1 class="main_title">{{@$title}}</h1>
                    <!-- <input type="file" multiple onchange="previewMultiple(event)" id="adicionafoto">
                    <div id="galeria"></div> -->
                    <div class="inner_form_area">
                        <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form {{(@$method == 'Add' ? 'js-draft-form' : '')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="method" id="method" value="{{@$method}}">
                            <div class="form-group form_box upload_photo">
                                <p>Upload Photo or Video</p> 
                                <div class="upload_photo_box upload_photo_video">
                                <input type="file" class="upload_photobtn" name="file" id="adicionafoto"  accept="image/jpeg,image/png,image/jpg,video/mp4,video/ogg,.webm">
                                <input type="hidden" name="oldPhoto" id="oldPhoto" data-id="{{@$result->getUserDiary->feed_id}}" data-url="delete-diaryfile" value="{{@$result->getUserDiary->file}}">
                            </div>
                            <div class="upload_show">
                                <ul class="addimgs_area" >
                                    @if(@$method != 'Add' && @$result->getUserDiary->file != '')
                                        @php
                                            @$extn = explode(".",@$result->getUserDiary->file);
                                        @endphp
                                        @if ($extn[1] == 'mp4' || @$extn[1] == 'ogv' || @$extn[1] == 'webm')
                                            <li class="addimg"> 
                                                <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button> <video><source id="videoSource"/ src="{{Common_function::get_s3_file(config('custom_config.s3_diary_video'),$result->getUserDiary->file)}}"></video>
                                            </li>
                                        @else
                                            <li class="addimg"> 
                                                <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button>  <img src="{{ Common_function::get_s3_file(config('custom_config.s3_diary_thumb'),$result->getUserDiary->file)}}" alt="Vio Graf" /> 
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group form_box">
                                        <p>Date</p>
                                        <div class="date_of_birth_box experiencedate">
                                            <span>
                                                <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                            </span>
                                            <input type="text" name="date" class="datepicker" id="datepicker2" value="{{$method == 'Add' ? '': old('date',(@$result->getUserDiary->date == '' ? '' : date_format( date_create(@$result->getUserDiary->date), 'd M, Y' )))}}" autocomplete="off" readonly="readonly">
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group form_box">
                                <p>Description</p>
                                <textarea class="form-control form_textarea" name="description" id="description" placeholder="" >{{old('description',@$result->getUserDiary->description)}}</textarea>
                            </div>
                            <div class="form-group form_box upload_photo">
                                <p>Upload Documents<span class="span_extension_note">{{trans('custom.document_upload_note')}}</span></p> 
                                <div class="upload_photo_box upload_doc_box" id="upload_doc_box">
                                    <input type="file" class="upload_photobtn js-multi-file" data-maxlength="{{ ($method == 'Edit') ? 5 - @$result->getdocuments->count() : 5}}" accept="gif|jpg|jpeg|png|xlsx|xls|ods|pdf|txt|doc|docx" maxsize=1024  id="uploaddoc" name="uploaddoc[]" >
                                </div>
                                @if ($method == 'Edit')
                                    <div class="filediv mt-4">
                                    @foreach ($result->getdocuments as $dockey => $docval)
                                    <div id="doc_{{@$docval->id}}" class="row MultiFile-label-doc">
                                        <a href="{{Common_function::get_s3_file(str_replace('[id]',$result->id,config('custom_config.s3_feed_document')),@$docval->file)}}" target="_blank" class="docfiles text-truncate" download>
                                        <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid" alt="VioGraf"></span>{{@$docval->file}}</a><button type="button" class="deletedoc" data-file="{{@$docval->file}}" data-id="{{@$docval->id}}" data-feed="{{$result->id}}"><i class="far fa-times-circle"></i></button><p class="col-xl-6 col-lg-6 col-md-4 col-sm-3"></p>
                                    </div>
                                    @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="dropdownsubmit_row">
                                <div class="form-group form_box draft_box">
                                    <div class="save-draft-div">
                                        @if(@$method == 'Add')
                                            <!-- <label for="save_draft" class="mt-1 checkbox label-draft">
                                                <input type="checkbox" class="form-check-input save-check" name="save_draft" id="save_draft" value="1" >
                                                <span class="checkmark">Save As Draft</span>
                                            </label> -->
                                            <label class="container">Save As Draft
                                                <input type="checkbox" class="form-check-input save-check" name="save_draft" id="save_draft" value="1" >
                                                <span class="checkmark"></span>
                                            </label>
                                        @endif
                                        @if(@$method == 'Edit' && @$result->is_save_as_draft == 1)
                                            <!-- <label for="save_draft" class="mt-1 checkbox label-draft">
                                                <input type="checkbox" class="form-check-input save-check" name="save_draft" id="save_draft" value="1" >
                                                <span class="checkmark">Save As Draft</span>
                                            </label> -->
                                            <label class="container">Save As Draft
                                                <input type="checkbox" class="form-check-input save-check" name="save_draft" id="save_draft" value="1" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                            <input type="hidden" name="draft" id="draft" value="1">
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex publicbtn-flex">
                                    <div class="new-drop">
                                        <select class="publicbtn" name="privacy">
                                            <option value="public" {{ old('privacy',@$result->privacy)=='public' ? 'selected' : ''  }} data-image="{{asset('images/icons/public_icon.svg')}}" >Public</option>
                                            <option value="family" {{ old('privacy',@$result->privacy)=='family' ? 'selected' : ''  }} data-image="{{asset('images/icons/family_icon.svg')}}">Family</option>
                                            <option value="private" {{ old('privacy',@$result->privacy)=='private' ? 'selected' : ''  }} data-image="{{asset('images/icons/private_icon.svg')}}">Private</option>
                                        </select>
                                    </div>
                                    <div class="btn_submit">
                                        <button class="form_btn"> {{(@$method == 'Add') ? 'Save' : 'Update' }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
  <!--// plugin-specific resources //-->
  <script src="{{ url('js/jquery.MultiFile.js')}}"></script>
@stop