@extends('layouts.master')
@section('content')
<div class="website-columnarea">
    <div class="website-menuarea">
        @include("include.sidebar_menu")
    </div>
    <div class="website-bodyarea">
        @include("include.header")
        @yield('pageContent')
        <div id="wait">
        <img src="{{ asset('images/loading.gif') }}" width="64" height="64" /><br/>Loading..
        </div>
        
        <div id="sempop" class="modal fade popup-modal add-member-popup" data-keyboard="false" data-backdrop="static" >
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header modal-ttl">	
                        <h4 class="modal-title text-center"></h4>	
                        <button type="button" class="close treemodalclose" aria-hidden="true">&times;</button>
                    </div>
                    <div class="alert alert-danger mt-3" style="display:none;width:95%;margin:auto;"></div>
                    <form name="" id="" action="javascript:;" class="family_tree_form" method="post">
                        <div class="modal-body add-member">
                            <div class="form-group memform row editNotDisplay">
                                <div class="col-sm-12 memfield  abs"> <!-- abs -->
                                    <label for="inputName" class="col-sm-12 frmlabel control-label pl-0">Relationship</label>
                                    <select class="js-example-responsive js-select-box add-member-form" name="relation" id="pk-relation">
                                        <option value="">Select Relationship</option>
                                        <option value="Mother">Mother</option>
                                        <option value="Father">Father</option>
                                        <option value="Sibling">Sibling</option>
                                        <option value="Child">Child</option>
                                        <option value="Spouse">Spouse</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group memform row">
                                <label for="inputName" class="col-sm-12 frmlabel control-label">Name</label>
                                <div class="col-sm-12 memfield">
                                    <input type="text" class="form-control add-member-form" name="name" id="pk-name" placeholder="Name" >
                                </div>
                            </div>
                            <div class="form-group memform row">
                                <div class="col-sm-6 memfield">
                                    <label for="inputName" class="col-sm-12 frmlabel control-label pl-0">Contact Number</label>
                                    <input type="text" class="form-control add-member-form" name="contact" id="pk-contact" placeholder="Contact Number">
                                </div>
                                <div class="col-sm-6 memfield">
                                    <label for="inputName" class="col-sm-12 frmlabel control-label pl-0">Age</label>
                                    <input type="text" class="form-control add-member-form" name="age" id="pk-age" placeholder="Age">
                                </div>
                            </div>
                            <div class="form-group memform row">
                                <label for="inputName" class="col-sm-12 frmlabel control-label">Email</label>
                                <div class="col-sm-12 memfield">
                                    <input type="email" class="form-control add-member-form" name="email" id="pk-email" data-type = "email" placeholder="example@abc.com">
                                </div>
                            </div>
                            <div class="form-group memform row editNotDisplay">
                                <div class="col-sm-12 memfield abs">
                                    <label for="inputName" class="col-sm-12 frmlabel control-label pl-0">Gender</label>
                                    <select class="js-example-responsive" name="gender" id="pk-gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" >Male</option>
                                        <option value="female" >Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group memform row">
                                <label for="inputName" class="col-sm-12 frmlabel control-label">Upload Photo</label>
                                <div class="col-sm-12 memfield">
                                    <input type="file" class="form-control add-member-form" name="image" id="pk-picture" accept="image/*">
                                </div>
                            </div>
                            <div class="save-draft-div">
                                <label class="container">Not Alive
                                    <input type="checkbox" class="form-check-input save-check" name="not_alive" id="not_alive" value="1">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <input type="hidden" class="memberId" name="memberId" value="">
                            <input type="hidden" class="storedImage" name="storedImage" value="">
                        </div>
                        <div class="modal-footer family-tree-footer">
                            <button type="button" class="btn btn-secondary btn-xs closebtn treemodalclose">Close</button>
                            <button type="submit" id="saveData" class="btn btn-xs addbtn savebutton">Add Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="remove_member_popup" class="modal fade popup-modal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="delete-icon-box">
                            <i class="far fa-times-circle"></i>
                        </div>						
                        <h5 class="w-100 text-center mt-4">Are you sure?</h5>	
                    </div>
                    <div class="modal-body text-center">
                        <p>You want to remove your Family Member</p>
                    </div>
                    <div class="modal-footer family-tree-footer">
                        <button type="button" class="btn btn-xs btn-secondary closes" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-xs btn-danger confirm">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="delete_popup" class="modal fade popup-modal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="delete-icon-box">
                            <i class="far fa-times-circle"></i>
                        </div>						
                        <h5 class="text-center w-100 mt-4">Are you sure?</h5>	
                    </div>
                    <div class="modal-body modal-cont delete-cont text-center">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary closes" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-danger confirm">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="logout_popup" class="modal fade popup-modal logout-modal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <button type="button" class="close js-logout-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="delete-icon-box">
                            <i class="far fa-times-circle"></i>
                        </div>						
                        <h5 class="txt-center w-100 mt-4">Confirm Logout</h5>	
                    </div>
                    <div class="modal-body modal-cont logout-cont">
                        <p class="txt-center"> Are you sure you want to logout? </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary closes" data-dismiss="modal">Cancel</button>
                        <a href="{{route('weblogout')}}"><button type="button" class="btn btn-sm btn-danger confirm_logout">Logout</button></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE AS DRAFT TIME MODAL START -->
        <div id="timer_popup" class="modal fade popup-modal logout-modal" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <div class="draft-icon-box">
                            <div class="circle">
                                <span class="js-counter"></span>
                            </div>
                        </div>						
                    </div>
                    <div class="modal-body modal-cont logout-cont">
                        <p class="txt-center"> Form will be automatically submit  after few seconds. If you want to cancel this submission then please use below cancel button. </p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <a href="javascript:;"><button type="button" class="btn btn-sm btn-danger js-cancel">Cancel</button></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- SAVE AS DRAFT TIME MODAL END -->
        <div id="delete_file_popup" class="modal fade popup-modal " >
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-header flex-column">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="delete-icon-box">
                            <i class="far fa-times-circle"></i>
                        </div>						
                        <h5 class="text-center w-100 mt-4">Are you sure?</h5>	
                    </div>
                    <div class="modal-body modal-cont text-center">
                        <p>Do you really want to delete this File? You cannot recover it back.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary closes" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-danger confirm">Delete</button>
                    </div>
                </div>
            </div>
        </div> 

        <div id="confirm_popup" class="modal fade popup-modal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="delete-icon-box">
                            <i class="fa fa-question-circle"></i>
                        </div>						
                        <h5 class="text-center w-100 mt-4">Confirmation</h5>	
                    </div>
                    <div class="modal-body modal-cont text-center">
                        <p>Please, note that you may lose your details by returning to the previous page.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary closes" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-sm btn-danger confirm">Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ICON CROPPER MODAL -->
    <div class="modal fade crop-picture-modal docs-cropped" id="iconModal"  data-backdrop="static" data-keyboard="false" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1" style="z-index:9999999!important">
        <div class="modal-dialog modal-lg crop-modal-dilog ">
            <div class="modal-content crop-content">
                <div class="modal-header">
                <h5 class="modal-title" id="getCroppedCanvasTitle">Crop Picture</h5>
                <button type="button" class="close js-close-crop-modal" id="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body flex-container"> 
                <div class="img-container crop-div flex-item" style="margin: 40px 0;min-height: 300px;max-height: 300px;"> 
                    <img id="crop_image"  class="crop-image" src="" alt="Icon" style="display:block;">
                </div>
                </div>
                <div class="modal-footer">
                    <div class="modal-footer-btn-row">
                        <button type="button" class="btn btn-primary cls-modal-button " id="reset">Reset</button>
                        <button type="button" class="btn btn-primary cls-modal-button" id="preview">Preview</button>
                        <button type="button" class="btn btn-primary cls-modal-button" id="apply">Apply</button>
                        <button type="button" class="btn btn-secondary" id="close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- ICON CROPPER MODAL -->
        <div id="delete_docfile_popup" class="modal fade popup-modal">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header flex-column">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <div class="delete-icon-box">
                            <i class="far fa-times-circle"></i>
                        </div>						
                        <h5 class="text-center w-100 mt-4">Are you sure?</h5>	
                    </div>
                    <div class="modal-body modal-cont text-center docfile-body">
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary closes" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-sm btn-danger confirm">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="web_footer front_footer">
    <div class="containerbox">
        <div class="copyright_menu">
            <div class="copyright"><p>© 2021 Viograf All rights reserved</p></div>
            <div class="bottom_menu">
                <ul class="">
                    <li class = "{{@$title == 'About Us'?'active':''}}"><a href="{{url('/p/about-us')}}" title="About Us">About Us</a></li><span class="footerline">|</span>
                    <li class = "{{@$title == 'Terms & Conditions'?'active':''}}"><a href="{{url('/p/terms-and-conditions')}}" title="Terms &amp; Conditions">Terms &amp; Conditions</a></li><span class="footerline">|</span>
                    <li class = "{{@$title == 'Privacy Policy'?'active':''}}"><a href="{{url('/p/privacy-policy')}}" title="Privacy Policy">Privacy Policy</a></li><span class="footerline">|</span>
                    <li class="{{@$title == 'Contact Us'?'active':''}}"><a href="{{url('/contact-us')}}" title="Contact Us">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="feedbackpopup" class="modal fade popup-modal add-member-popup feedback-popup">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-ttl">	
                <h4 class="text-center">Feedback Form</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="alert alert-danger mt-3" style="display:none;width:95%;margin:auto;"></div>
            <form name="" id="feedback_form" action="javascript:;" class="feedback_form" method="post">
                <div class="modal-body">
                    <div class="text_area row">
                        <!-- <div class="form-group form_box col-md-12">
                            <label class="placeholdertext">Type </label><br>
                            <div class="cont">
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="suggestion" name="type" value="suggestion">
                                    <label class="form-check-label" for="radio1">Suggestion</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="issue" name="type" value="issue">
                                    <label class="form-check-label" for="radio2">Issue</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="radio" class="form-check-input" id="other" name="type" value="other" >
                                    <label class="form-check-label" for="radio3">Other</label>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group form_box memfield  abs col-md-12">
                            <p class="mb-0">Type</p>
                            <select class="js-example-responsive add-member-form" name="type" id="type">
                                <option value="">Select Type</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="issue">Issue</option>
                                <option value="other">Others</option>
                            </select>
                        </div>
                        <div class="form-group form_box col-sm-12 mb-4">
                            <p class="mb-0">Subject</p>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" style="line-height:2.0 !important">
                        </div>
                        <div class="form-group form_box col-sm-12 mb-4">
                            <p class="mb-0">Message</p>
                            <textarea class="form-control form_textarea" rows="4" name="message" id="message" placeholder="Message"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="userid" value="{{@Auth::user()->id}}">
                    <input type="hidden" name="url" value="{{url()->current()}}">
                </div>
                <div class="modal-footer family-tree-footer">
                    <button type="button" class="btn btn-secondary btn-xs closebtn" data-dismiss="modal">Close</button>
                    <button type="submit" id="saveData" class="btn btn-xs addbtn savebutton">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
@yield('js')
<script>
        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            var scrollpos1 = $('.website-columnarea').offset().top - -10;
            if (scroll >= scrollpos1) {
                $('header').addClass('active');
            }
            if (scroll <= scrollpos1) {
                $('header').removeClass('active');
            }

        });

        $(window).scroll();

        var baseUrl= "{{url('/')}}";
       
    </script>
@stop
