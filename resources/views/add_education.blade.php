@extends('layouts.page')
@section('title', @$title)

@section('pageContent')
    <div class="inner_back_center_row row">
        <div class="inner_back_col">
            <a href="javascript:;" data-url="{{route('education.index')}}" class="btn_back">
                <img src="{{ asset('images/icons/back_btn.svg') }}" class="img-fluid" alt="VioGraf" />
            </a>
        </div>
        
        <div class="inner_center_page">
            <div class="sectionbox">
                <h1 class="main_title">{{@$title}}</h1>
                <!-- <input type="file" multiple onchange="previewMultiple(event)" id="adicionafoto">
                <div id="galeria"></div> -->
                @include('include.notification')
                <div class="inner_form_area">
                    <form method="post" action="{{@$action}}" id="{{@$frn_id}}" class="form {{(@$method == 'Add' ? 'js-draft-form' : '')}}" enctype="multipart/form-data" >
                    {{ csrf_field() }}
                    <input type="hidden" name="method" id="method" value="{{@$method}}">
                        <div class="form-group form_box upload_photo" >
                            <p>Upload Photo or Video</p> 
                            <div class="upload_photo_box upload_photo_video" id="file">                                
                                <input type="file" name="file" class="upload_photobtn" id="adicionafoto" accept="image/jpeg,image/png,image/jpg,video/mp4,video/ogg,.webm">
                                <input type="hidden" name="oldPhoto" id="oldPhoto" data-id="{{@$result->getUserEducation->feed_id}}" data-url="delete-educationfile" value="{{@$result->getUserEducation->file}}">
                            </div>
                            <div class="upload_show">
                                <ul class="addimgs_area" >
                                    @if(@$method != 'Add' && @$result->getUserEducation->file != '')
                                        @php
                                            $extn = explode(".",@$result->getUserEducation->file);
                                        @endphp
                                        @if ($extn[1] == 'mp4' || @$extn[1] == 'ogv' || @$extn[1] == 'webm')
                                            <li class="addimg"> 
                                            <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button> <video><source id="videoSource"/ src="{{Common_function::get_s3_file(config('custom_config.s3_education_video'),$result->getUserEducation->file)}}"></video>
                                            </li>
                                        @else
                                            <li class="addimg"> 
                                            <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button>  <img src="{{Common_function::get_s3_file(config('custom_config.s3_education_thumb'),$result->getUserEducation->file)}}" alt="Vio Graf" /> 
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group form_box">
                                    <p>Name of School</p>
                                    <input type="text" name="name" class="form-control required" placeholder="Name of school" value="{{old('name',@$result->getUserEducation->name)}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 save-draft-div">
                                <div class="form-group form_box">
                                    <p>Time Period</p>
                                    <div class="date_birthrow">                                    
                                        <div class="date_birth_col">                                       
                                            <div class="date_of_birth_box education_start_date">
                                            <span>
                                                <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                            </span>                                             
                                                <input type="text" class="" placeholder="Start date" readonly='true' id="startdate" name="start_date" autocomplete="off" value="{{$method != 'Add' ? old('start_date',(@$result->getUserEducation->start_date == '' ? '' : date_format( date_create(@$result->getUserEducation->start_date), 'd M, Y' ))): ''}}">
                                                
                                            </div>
                                        </div>
                                        <div class="to">
                                            <p>To</p>
                                        </div>
                                        <div class="date_birth_col">
                                            <div class="date_of_birth_box education_end_date">
                                            <span>
                                                <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                                            </span>                                            
                                                <input type="text" class="" placeholder="End date" readonly='true' id="enddate" name="end_date" autocomplete="off" value="{{$method != 'Add' ? old('end_date',(@$result->getUserEducation->end_date == '' ? '' : date_format( date_create(@$result->getUserEducation->end_date), 'd M, Y' ))): ''}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="container">Pursuing
                                    <input type="checkbox" class="form-check-input" name="education-check" id="education-check" {{(@$result->getUserEducation->end_date == '' &&  @$result->getUserEducation->start_date == ''? '' : 'checked')}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group form_box">
                            <p>My School Mates</p>
                            <input type="text" name="my_buddies" placeholder="e.g. Bob Smtih, Lisa Adwert" class="form-control required" value="{{old('my_buddies',@$result->getUserEducation->my_buddies)}}">
                        </div>
                        <div class="form-group form_box">
                            <p>Class/Grade</p>
                            <input type="text" name="achievement" placeholder="Class/Grade" class="form-control required" value="{{old('achievement',@$result->getUserEducation->achievements)}}">
                        </div>
                        <div class="form-group form_box">
                            <p>Description</p>
                            <textarea class="form-control form_textarea" name="description" placeholder="Description"
                            >{{old('description',@$result->getUserEducation->description)}}</textarea>
                        </div>
                        <div class="form-group form_box upload_photo">
                            <p>Upload Document<span class="span_extension_note">{{trans('custom.document_upload_note')}}</span></p>
                            <div class="upload_photo_box upload_doc_box">
                                <span>
                                    <input type="file" class="upload_photobtn js-multi-file img-fluid " name="uploaddoc[]"   id="uploaddoc" data-maxlength="{{ ($method == 'Edit') ? 5 - @$result->getdocuments->count() : 5}}" accept="gif|jpg|jpeg|png|xlsx|xls|ods|pdf|txt|doc|docx" maxsize=1024>
                                </span>
                            </div>                                                      
                            @if ($method == 'Edit')
                                <div class="filediv mt-4">
                                @foreach ($result->getdocuments as $dockey => $docval)
                                <div id="doc_{{$docval->id}}" class="row MultiFile-label-doc">
                                    <a href="{{Common_function::get_s3_file(str_replace('[id]',$result->id,config('custom_config.s3_feed_document')),$docval->file)}}" target="_blank" class="docfiles text-truncate" download>
                                    <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid" alt="VioGraf"></span>{{$docval->file}}</a><button type="button" class="deletedoc" data-file="{{$docval->file}}" data-id="{{$docval->id}}" data-feed="{{$result->id}}"><i class="far fa-times-circle"></i></button><p class="col-xl-6 col-lg-6 col-md-4 col-sm-3"></p>
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
                                            <input type="checkbox" class="form-check-input save-check" name="save_draft" id="save_draft" value="1" {{(@$result->is_save_as_draft == 1 ? 'checked' : '')}}>
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
    <input type="hidden" id="module" value="{{@$module}}">   
@stop
@section('js')
  <!--// plugin-specific resources //-->
  <script src="{{ url('js/jquery.MultiFile.js')}}"></script>
@stop