@extends('layouts.page')
@section('title', @$title)
@section('pageContent')
<div class="welcome-familyarea">
    <div class="inner_back_center_row row">
      <div class="inner_back_col">
          <a href="javascript:;" data-url="{{route('wishlist.index')}}" class="btn_back">
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
              <div class="form-group form_box upload_photo" >
                  <p>Upload Photo or Video</p> 
                  <div class="upload_photo_box upload_photo_video" id="file">
                    <input type="file" name="file" class="upload_photobtn" id="adicionafoto" accept="image/jpeg,image/png,image/jpg,video/mp4,video/ogg,.webm">
                    <input type="hidden" name="oldPhoto" id="oldPhoto" data-id="{{@$result->getUserWishlist->feed_id}}" data-url="delete-wishlistfile" value="{{@$result->getUserWishlist->file}}">
                  </div>
                  <div class="upload_show">
                    <ul class="addimgs_area" >
                        @if(@$method != 'Add' && @$result->getUserWishlist->file != '')
                          @php
                            $extn = explode(".",@$result->getUserWishlist->file);
                          @endphp
                          @if (@$extn[1] == 'mp4' || @$extn[1] == 'ogv' || @$extn[1] == 'webm')
                            <li class="addimg"> 
                              <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button> <video><source id="videoSource"/ src="{{Common_function::get_s3_file(config('custom_config.s3_wishlist_video'),$result->getUserWishlist->file)}}"></video>
                            </li>
                          @else
                            <li class="addimg"> 
                              <button type="button" class="close-btn"> <img src="{{asset('images/icons/upload_file_close.svg')}}" alt="Vio Graf" /></button>  <img src="{{Common_function::get_s3_file(config('custom_config.s3_wishlist_thumb'),$result->getUserWishlist->file)}}" alt="Vio Graf" /> 
                            </li>
                          @endif
                        @endif
                    </ul>
                  </div> 
              </div>
              <div class="form-group form_box">
                <p>What is the objective?</p>
                <input type="text"  name="objective" id="objective" value="{{old('objective',@$result->getUserWishlist->objective)}}" placeholder="e.g. Holiday at my dream destination" class="form-control">
              </div>
              <div class="form-group form_box">
                <p>What is your Wish?</p>
                <input type="text"  name="wish" id="wish" placeholder="e.g. To spend a month in Switzerland" value="{{old('wish',@$result->getUserWishlist->wish)}}" class="form-control">
              </div>
              <!-- <div class="form-group form_box">
                <p>By When?</p>
                <input type="text"  name="by_when" id="by_when" value="{{old('by_when',@$result->getUserWishlist->by_when)}}" class="form-control">
              </div> -->
              <div class="row">
                <div class="col-lg-4">
                <div class="form-group form_box">
                <p>By When?</p>
                    <div class="date_of_birth_box wishlistdate">
                      <span>
                          <img src="{{asset('images/icons/date_of_birth.svg')}}" class="img-fluid" alt="">
                      </span>
                      <input type="text" name="by_when" class="datepicker" value="{{$method == 'Add' ? '': old('by_when',(@$result->getUserWishlist->by_when == '' ? '' : date_format( date_create(@$result->getUserWishlist->by_when), 'd M, Y' )))}}" id="datepicker" autocomplete="off" readonly="readonly">
                    </div>
              </div>  
                </div>
            </div>
              <div class="form-group form_box">
                <p>Description</p>
                <textarea class="form-control form_textarea" name="description"  id="description">{{old('description',@$result->getUserWishlist->description)}}</textarea>
              </div>
              <div class="form-group form_box upload_photo">
                  <p>Upload Documents<span class="span_extension_note">{{trans('custom.document_upload_note')}}</span></p> 
                  <div class="upload_photo_box upload_doc_box">
                      <input type="file" class="upload_photobtn js-multi-file" data-maxlength="{{ ($method == 'Edit') ? 5 - @$result->getdocuments->count() : 5}}" accept="gif|jpg|jpeg|png|xlsx|xls|ods|pdf|txt|doc|docx" maxsize=1024  id="uploaddoc" name="uploaddoc[]" >
                  </div>
                  @if ($method == 'Edit')
                    <div class="filediv">
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
</div>

@stop
@section('js')
 
  <script>   

        var placeholder = 'Switzerland is one of the most attractive vacation destinations today in the world because of its lakes, beautiful ountain ranges, snowfall and many interesting places. My 1 month Switzerland itinerary overview\n\nWeggis (Lake Lucerne) ??? 3 nights\nSchaffhausen ??? 1 night\n\nLauterbrunnen ??? 9 nightsAppenzell ??? 4 nights\nSt. Moritz ??? 2 nights\nAscona ??? 7 nights\n';
            $('textarea').attr('placeholder', placeholder);
       
    </script>
      <!--// plugin-specific resources //-->
  <script src="{{ url('js/jquery.MultiFile.js')}}"></script>
@stop