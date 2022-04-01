@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
    
  <div class="welcome-familyarea">
    <div class="inner_back_center_row row">
      <div class="inner_back_col">
      <a href="javascript:;" data-url="{{route('asset.index')}}" class="btn_back">
                   <img src="{{asset('images/icons/back_btn.svg')}}" class="img-fluid" alt="VioGraf" />
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
                <div class="form-group form_box upload_photo">
                    <p>Upload Documents<span class="span_extension_note">{{trans('custom.document_upload_note')}}</span></p> 
                    <div class="upload_photo_box upload_doc_box">
                      <input type="file"  class="upload_photobtn js-multi-file"  name="uploaddoc[]"  id="uploaddoc" data-maxlength="{{ ($method == 'Edit') ? 5 - @$result->getUserDocuments->count() : 5}}" accept="gif|jpg|jpeg|png|xlsx|xls|ods|pdf|txt|doc|docx" maxsize='10240'>
                      <input type="hidden" name="oldPhoto" id="oldPhoto"  data-url="delete-assetfile" value="">
                    </div>
                    @if ($method == 'Edit')
                      <div class="filediv mt-4">
                        @foreach ($result->getUserDocuments as $dockey => $docval)
                        <div id="doc_{{$docval->id}}" class="row MultiFile-label-doc">
                            <a href="{{Common_function::get_s3_file(str_replace('[id]',$result->id,config('custom_config.s3_asset_document')),$docval->file)}}" target="_blank" class=" docfiles text-truncate" download>
                            <span><img src="{{asset('images/icons/link.svg')}}" class="img-fluid" alt="VioGraf"></span>{{@$docval->file}}</a><button type="button" id="js-close-remove-file-asset" class="deletedoc js-close-remove-file-asset" name="deletedocs" data-file="{{$docval->file}}" data-id="{{$docval->id}}" data-feed="{{$result->id}}"><i class="far fa-times-circle"></i></button><?php //<p class="col-xl-6 col-lg-6 col-md-4 col-sm-3"></p> ?>
                        </div>
                        @endforeach
                      </div>
                    @endif
                </div>
               
                <div class="form-group form_box">
                      <p>Title</p>
                      <input type="text" name="title" class="form-control required" value="{{old('title',@$result->title)}}">
                </div> 
                <div class="row">
                    <div class="col-lg-7">
                    <div class="form-group form_box">
                    <p>Amount<span class="span_extension_note">{{trans('custom.asset_note')}}</span></p>
                      <input type="text" name="amount" id="numb" class="form-control required amount" value="{{old('amount',@$result->amount)}}">
                     
                    </div>  
                    </div>
                </div> 
                <div class="form-group form_box">
                  <p>Description</p>
                  <textarea class="form-control form_textarea" name="description">{{old('description',@$result->description)}}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-12">
                      <h3 class="sub_title mt-4">Nominee Detail</h3>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form_box"  id="assetNominee">
                      <p>Nominee</p>
                        <input type="text" name="nominee_name" id="nominee_name"  class="form-control"  value="{{old('nominee_name',@$result->nominee_name)}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form_box">
                      <p>Email Address</p>
                        <input type="text" name="nominee_email" id="nominee_email" data-type="email" class="form-control" value="{{old('nominee_email',@$result->nominee_email)}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group form_box">
                      <p>Phone</p>
                        <input type="text" name="nominee_phone_number" class="form-control" value="{{old('nominee_phone_number',@$result->nominee_phone_number)}}">
                      </div>
                    </div> 
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
                  <div class="btn_submit">
                      <button class="form_btn" >{{(@$method == 'Add') ? 'Save' : 'Update' }}</button>
                  </div>
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" id="module" value="{{@$module}}">
  <input type="hidden" id="method" value="{{@$method}}">
 
@stop

@section('js')
<!--// plugin-specific resources for document -->
<script src="{{ url('js/jquery.MultiFile.js')}}"></script>
<script>
var method=$('#method').val();

if (method == 'Edit'){
  $('html,body').animate({
      scrollTop: $(window.location.hash).offset().top
    });
}
</script>
@stop