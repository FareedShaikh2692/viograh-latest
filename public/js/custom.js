/* ========== Navigation mobile view js Strat ========== */
$(document).on('ready', function() {
    $(document).on('keydown', 'input, textarea', function() {
        var type = $(this).attr('data-type');
        if (type != "email" && type != "password") {
            $(this).val($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
        }
    });
    $(document).on('change', 'input[type=email]', function() {
        $(this).val($(this).val().toLowerCase());
    });

    $('.span-load-more, .js-readless').hide();

    //Read More and Read Less Click Event binding
    $(document).on("click", ".js-readMore", function() {
        $(this).closest('.desc-info').find('.span-load-more').show();
        $(this).closest('.desc-info').find('.js-readless').show();
        $(this).hide();
    });

    $(document).on("click", ".js-readless", function() {
        $(this).closest('.desc-info').find('.span-load-more').hide();
        $(this).closest('.desc-info').find('.js-readMore').show();
        $(this).hide();
    });

    $(document).on('click', '#sidebarCollapse', function() {
        $('#sidebar').toggleClass('active');
        $('body').toggleClass('active');
        $('.mainmainubtn').toggleClass('active');
        $('.btnjs').toggleClass('active');
        $('.overlay').fadeIn();
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('.notification-btn').on('click', function() {
        setTimeout(function() {
            $('body').toggleClass('notification_active');
        });
    });

    //CLOSE BUTTON ON DOCUMENTS
    var documentId = '';
    var documentFileName = '';
    var FeedId = '';
    var tbl = '';
    var cls = '';
    $(".deletedoc,.js-close-remove-file,.js-close-remove-file-asset").on('click', function() {

        $('#delete_docfile_popup').modal('show');
        documentId = $(this).data('id');
        documentFileName = $(this).data('file');
        feedId = $(this).data('file');
        tbl = $("#module").val();
        cls = $(this).prop("id");

        if (cls == 'js-close-remove-file') {
            $('.docfile-body').html("<p>Do you really want to delete this File? You cannot recover it back.</p>");
        } else {
            $('.docfile-body').html("<p>Do you really want to delete this Document File? You cannot recover it back.</p>");
        }
    });

    $(document).on('submit', '#verfiy_otp_password', function() {
        var url = window.location.href;
        var parts = url.split("/");
        var last_part = parts[parts.length - 1];
        var redirect_url = (last_part == 'nominee') ? baseUrl + '/nominee' : baseUrl + '/my-networth';
        form_data = new FormData();
        form_data.append('password', $('#password-networth').val());
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/my-networth/verify_password',
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    window.location.href = redirect_url;
                } else if (obj.code == 0) {
                    $('.alert-danger').html('');
                    $('.alert-danger').show();
                    $('.alert-danger').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> ' + obj.error);
                }
            }
        });
    });

    $(document).on("click", "#send-otp", function() {
        $(this).text('Processing...');
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/my-networth/send-otp',
            method: "POST",
            data: '',
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    console.log("Success otp send");
                    $('#send-otp').text('Send OTP');
                    $('.clickhere_link').text('click here')
                    $('.alert-success').html('');
                    $('.alert-success').show();
                    $('.alert-success').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + obj.msg);
                    $(document).find('.verify_otp').css('display', 'block');
                    $(document).find('.send_otp').css('display', 'none');

                } else if (obj.code == 0) {
                    $(this).text('Send OTP');
                    $('.clickhere_link').text('click here')
                    $('.alert-danger').html('');
                    $('.alert-danger').show();
                    $('.alert-danger').html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + obj.error);
                }
            }
        });

    });

    $('#delete_docfile_popup .confirm').on("click", function() {
        $('.confirm').prop('disabled', true);
        form_data = new FormData();
        form_data.append('id', documentId);
        form_data.append('file', documentFileName);
        form_data.append('feed_id', feedId);
        form_data.append('tbl', tbl);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: (cls == 'js-close-remove-file') ? baseUrl + '/moments/delete-momentsfile' : (cls == 'js-close-remove-file-asset' ? baseUrl + '/assets/delete-assetfile' : (cls == 'js-close-remove-file-liability' ? baseUrl + '/liability/delete-liabilityfile' : baseUrl + '/delete-document')),
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    $('.confirm').prop('disabled', false);
                    $("#" + obj.docId).remove();
                    $('#delete_docfile_popup').modal('hide');
                }
            }
        });

    });

    $(".education-imgs-lider").show();
    $(".education-imgs-lider").slick({
        dots: false,
        pauseOnHover: false,
        infinite: true,
        arrows: true,
        slidesToShow: 1,
        fade: true,
        cssEase: 'linear',
        autoplay: false,
        speed: 100,
        autoplaySpeed: 1000,
        slidesToScroll: 1
    });

    $(document).on('click', '#feedbackbtn', function() {
        $('.modal-ttl').text('Feedback Form');
        $('.feedback_form')[0].reset();
        $(document).find('.form-group').removeClass('has-error');
        $(document).find('.help-block').remove();
        $('#feedbackpopup').modal('show');
    });

    $(document).on('submit', '.feedback_form', function() {
        form_data = new FormData($(this)[0]);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/feedback',
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    $('#feedbackpopup').modal('hide');
                    $(document).find('.feedback-msg').css('display', 'none');
                    $(document).find('.website-bodytop ').after('<div class="alert alert-success alert-dismissible feedback-msg"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + obj.msg + '</div>');
                } else {
                    $('.alert-danger').html('');
                    $('.alert-danger').show();
                    $('.alert-danger').append(obj.msg);
                }
            }
        });
    });

    var interval = null;
    //CLOSE BUTTON ON IMAGE
    $(".close-btn").on('click', function() {
        $('#delete_file_popup').modal('show');

    });
    $('#delete_file_popup .confirm').on("click", function() {
        var file = $("#oldPhoto").val();
        var id = $("#oldPhoto").data('id');
        var requrl = $("#oldPhoto").data('url');

        form_data = new FormData();
        form_data.append('file', file);
        form_data.append('id', id);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/' + requrl,
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    $(".close-btn").closest(".addimg").remove();
                    $("#adicionafoto").val('');
                    $("#close-btn").hide();
                    $("#oldPhoto").val('');
                    $('#delete_file_popup').modal('hide');
                }
            }
        });

    });

    //PREVENT FORM FORM MULTIPLE SUBMIT
    $(document).on('submit', '.form', function() {
        var imagee = document.getElementsByClassName("addimg");
        var quantos = imagee.length;

        if (quantos > 20) {
            $(".upload_photo_video").addClass("has-error");
            $('.upload_photo_video .upload_photobtn').after('<label id="adicionafoto-error" class="help-block">Maximum file limit is 20</label>');
            return false;
        }

        $('.form_btn').attr('disabled', true);
    });

    $('.form_btn').attr('disabled', false);

    //LOAD MORE COMMENT CODE
    $(document).on('click', '#btn-more', function() {
        $(this).prop('disabled', true);
        if ($(this).data('id') != '') {
            var id = $(this).data('id');
        } else {
            var id = 0;
        }
        var module = $("#module").val();
        var currCommentType = $("#commenttable").val();
        var currId = $("#feed_id").val();

        form_data = new FormData();
        form_data.append('module', module);
        form_data.append('id', id);
        form_data.append('type', currCommentType);
        form_data.append('feed_id', currId);

        $("#btn-more").html("Loading....");
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/load_more_comment',
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    $(this).prop('disabled', false);
                    clearInterval(interval);
                    if (obj.commentCount > 1) {
                        $("#countComment").text(obj.commentCount + " Comments");
                    } else {
                        $("#countComment").text(obj.commentCount + " Comment");
                    }
                    var tempHtml = '<div class="cmts">';
                    var i = 0;
                    obj.output.forEach(function(val, k) {
                        tempHtml += '<li><div class="img_col">' +
                            '<img src=' + val.user_image + ' alt="Family Feed" class="img-fluid">' +
                            '</div>' +
                            '<div class="commentdetail">' +
                            '<h4>' + val.user_name + ' <p class="commentTime">&nbsp; ' + val.createdTime + '</p></h4>' +
                            '<p>' + val.comment + '</p>' +
                            '</div>' +
                            '</li>';
                        $('#btn-more').attr('data-id', obj.lastId);
                        $("#btn-more").html("View more comments");
                        i = i + 1;
                    });
                    tempHtml += '</div>';
                    if (obj.commentCount != obj.ofCmtCount) {
                        tempHtml += '<div class="view_more_commentsarea" ><div id="view_more_commentsarea">' +
                            '<button class="btn-more" id="btn-more" data-id =' + obj.lastId + '>View more comments</button>' +
                            '</div><span>' + obj.ofCmtCount + ' of ' + obj.commentCount + '</span></div>';

                        $("#btn-more").html("View more comments");
                    } else {
                        tempHtml += '<div class="view_more_commentsarea" ><div id="view_more_commentsarea">' +
                            '<button style="display:none;" class="btn-more" id="btn-more" data-id =' + obj.lastId + '>View more comments</button>' +
                            '</div><span>' + obj.ofCmtCount + ' of ' + obj.commentCount + '</span></div>';
                    }
                    $('.view_more_commentsarea').remove();
                    $(".comment_list").append(tempHtml);
                    $("#btn-more").html("View more comments");
                }
            }
        });
    });

    /*** LOAD MORE SEARCH RESULT ****/

    //SEARCH MODULE
    $(document).on('click', '.for_search_section_focus', function(e) {

        $('#search').focus();
        e.preventDefault();

    });
    if ($('#search').val() != '') {
        var search = $('#search').val();
        $('#typing_text').append(search);
    }

    $(document).on('click', '.search_view_more', function() {
        $('.search_view_more').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading');
        if ($(this).data('id') != '') {
            var id = $(this).data('id');
        } else {
            var id = 0;
        }
        var module = $("#module").val();
        var search_inputed_text = $("#search").val();

        console.log(id + module + search_inputed_text);
        form_data = new FormData();
        form_data.append('module', module);
        form_data.append('id', id);
        form_data.append('search_inputed_text', search_inputed_text);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/load-more-' + module.toLowerCase(),
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    $('.text-center').remove();
                    var tempHtml = obj.html;

                    $('.appdata .search-result-row').append(tempHtml);
                    if (obj.op == 0) {
                        $('.text-center').remove();
                    } else {
                        $('.appdata ').after('<div class="text-center">' +
                            '<a href="javascript:;" data-id="' + obj.lastId + '" class="search_view_more"><i id="loader"></i>View More</a>' +
                            '</div>');
                        $("#btn-more").html("View more");
                    }
                } else {
                    $('.text-center').remove();
                    $('#btn-more').html("No Data");
                }
            }
        });
    });

    /*** END LOAD MORE SEARCH RESULT ****/

    //LOAD MORE DATA CODE
    $(document).on('click', '.main_btn', function() {
        $(this).prop('disabled', true);
        $('.main_btn').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading');
        if ($(this).data('id') != '') {
            var id = $(this).data('id');
        } else {
            var id = 0;
        }

        if ($("#public_profile_module").val() == 'public-profile') {
            var public_profile_module = $("#public_profile_module").val();
            var user_id = $("#user_id").val();
        } else {
            var public_profile_module = '';
            var user_id = '';
        }
        var module = $("#module").val();
        var currType = $("#maintable").val();
        var currId = $("#type_id").val();

        form_data = new FormData();
        form_data.append('module', module);
        form_data.append('id', id);
        form_data.append('type', currType);
        form_data.append('type_id', currId);
        form_data.append('public_profile_module', public_profile_module);
        form_data.append('user_id', user_id);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/load-more-' + module.toLowerCase(),
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                console.log(obj);
                if (obj.code == 1) {
                    $('.main_btn').prop('disabled', false);
                    $('.text-center').remove();
                    var tempHtml = obj.html;

                    $('.appdata .js-load_more_button').append(tempHtml);

                    if (obj.op == 0) {
                        console.log(obj.op);
                        $('.text-center').remove();
                    } else {
                        $('.appdata ').after('<div class="text-center">' +
                            '<a href="javascript:;" data-id="' + obj.lastId + '" class="main_btn"><i id="loader"></i>View More</a>' +
                            '</div>');
                        $("#btn-more").html("View more");
                    }
                } else {
                    $('.text-center').remove();
                    $('#btn-more').html("No Data");
                }
            }
        });
    });
    $(document).on('click', '.view_more_moment', function() {
        $(this).prop('disabled', true);
        $('.view_more_moment').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Loading');
        if ($(this).data('id') != '') {
            var id = $(this).data('id');
        } else {
            var id = 0;
        }
        if ($("#public_profile_module").val() == 'public-profile') {
            console.log("in custom if");
            var public_profile_module = $("#public_profile_module").val();
            var user_id = $("#user_id").val();
        } else {
            console.log("in custom else");
            var public_profile_module = '';
            console.log(public_profile_module);
            var user_id = '';
            console.log(user_id);
        }
        var module = $("#module").val();
        var currType = $("#maintable").val();
        var currId = $("#type_id").val();

        form_data = new FormData();
        form_data.append('module', module);
        form_data.append('id', id);
        form_data.append('type', currType);
        form_data.append('type_id', currId);
        form_data.append('public_profile_module', public_profile_module);
        form_data.append('user_id', user_id);

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            url: baseUrl + '/moments/load-more-moments',
            method: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(obj) {
                if (obj.code == 1) {
                    $('.view_more_moment').prop('disabled', false);
                    $('.text-center').remove();
                    var tempHtml = obj.html;
                    /*obj.output.forEach(function(v,k){
                        var imagecount=v.image.length;
                        tempHtml += '<li class="col-lg-3 col-md-3  col-6 moments_col">'+
                        '<a href="'+v.url+'"  class="moments_box">'+
                        '<ul class="momentsimg_row">';
                        v.image.forEach(function(val,key){
                          if(key >= 3  && imagecount>4){
                            tempHtml += '<li class="momentsimg_col more_imgs">'+
                                        '<div class="momentsimg" data-text="+1">';
                          }else{
                            tempHtml += '<li class="momentsimg_col">'+
                                        '<div class="momentsimg">';
                          }
                          if(val.ext == 'mp4'){
                            tempHtml +='<div class="videoboxarea">'+
                            '<video><source id="videoSource"/ src="'+val.imgsrc+'"></video>'+
                            '</div>';
                          }else{
                          
                            tempHtml += '<img src="'+val.imgsrc+'" class="img-fluid" alt="VioGraf" />';

                          }
                          tempHtml +='</div>'+
                          '</li>';
                        });
                            // <img src="{{ asset('images/default_banner.jpg') }}" class="img-fluid" alt="VioGraf" />   
                        tempHtml += '</ul>'+
                           ' <div class="moments_text">'+
                              '<h3>'+v.objective+'</h3>'+
                               ' <div class="time_like">'+
                                '<p>'+v.timeAgo+'</p>'+
                                  '<p>'+
                                      '<span><i class="fa fa-heart" aria-hidden="true"></i>'+
                                      ' '+v.like_count+'</span>'+
                                      '<span><i class="fa fa-comments" aria-hidden="true"></i>'+
                                      ' '+v.comment_count+'</span>'+
                                    '</p>'+
                               ' </div>'+
                          '</div> '+
                        '</a>'+
                    '</li>';              
                    });*/
                    $('.appdata .moments_row').append(tempHtml);
                    if (obj.op == 0) {
                        $('.text-center').remove();
                    } else {
                        $('.appdata ').after('<div class="text-center">' +
                            '<a href="javascript:;" data-id="' + obj.lastId + '" class="view_more_moment"><i id="loader"></i>View More</a>' +
                            '</div>');
                        $("#btn-more").html("View more");
                    }
                } else {
                    $('#btn-more').html("No Data");
                }
            }
        });
    });

    //FUNCTION TO GET COMMENT LIST
    function getCommentList() {
        var currCommentType = $("#commenttable").val();
        var currMainType = $('#maintable').val();
        var module = $("#module").val();
        var currId = $("#feed_id").val();
        form_data = new FormData();
        form_data.append('module', module);
        form_data.append('id', currId);
        form_data.append('type', currCommentType);
        form_data.append('maintype', currMainType);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: baseUrl + '/get_comment_feed',
            success: function(obj) {
                if (obj.code == 1) {
                    if (obj.commentCount > 1) {
                        $("#countComment").text(obj.commentCount + " Comments");
                    } else {
                        $("#countComment").text(obj.commentCount + " Comment");
                    }
                    $('.comment_list').empty();
                    var tempHtml = '';
                    var i = 0;
                    obj.output.forEach(function(v, k) {
                        tempHtml += '<div class="cmts"><li><div class="img_col">' +
                            '<img src=' + v.user_image + ' alt="Family Feed" class="img-fluid">' +
                            '</div>' +
                            '<div class="commentdetail">' +
                            '<h4>' + v.user_name + ' <p class="commentTime">&nbsp; . ' + v.createdTime + '</p></h4>' +
                            '<p>' + v.comment + '</p>' +
                            '</div>' +
                            '</li></div>';
                        $('#btn-more').attr('data-id', obj.lastId);
                        $("#btn-more").html("View more comments");
                        //$( ".comment_list" ).append(tempHtml);
                        i = i + 1;
                    });
                    if (obj.commentCount > 3) {
                        tempHtml += '<div class="view_more_commentsarea" ><div id="view_more_commentsarea">' +
                            '<button class="btn-more" id="btn-more" data-id =' + obj.lastId + '>View more comments</button>' +
                            '</div><span>' + i + ' of ' + obj.commentCount + '</span></div>';
                    }
                    $(".comment_list").append(tempHtml);
                }
            },
        });
    }

    //5 MINUTES INTERVAL FOR GETTING LATEST DATA FROM COMMENT TABLE
    var url = window.location.href;
    var segments = url.split('/');
    var action = segments[4];
    if (action == 'information') {
        interval = setInterval(function() {
            getCommentList();
        }, 300000);
    } else if (action == 'edit' || action == 'edit-asset' || action == 'edit-liability') {
        /*   $(document).ajaxStart(function(){
            $("#wait").css("display", "block");
          });
          $(document).ajaxComplete(function(){
            $("#wait").css("display", "none");
          });
         */

    } else {
        clearInterval(interval);
    }

    //INSERT COMMENT INTO TABLE
    $(document).on('click', '.send_message', function() {
        $('.send_message').attr('disabled', true);
        var currentcommentcount = ($('#btn-more').data('cmtcount') != '') ? $('#btn-more').data('cmtcount') : 0;
        var lastcmtid = ($('#btn-more').data('id') != '') ? $('#btn-more').data('id') : 0;
        console.log(lastcmtid);
        var currCommentType = $("#commenttable").val();
        var currMainType = $('#maintable').val();
        var comment = $('#comment').val();
        var module = $("#module").val();
        var currId = $("#feed_id").val();

        if ($('#comment').val().trim() != '') {
            $('.send_message').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Sending');
            form_data = new FormData();
            form_data.append('module', module);
            form_data.append('id', currId);
            form_data.append('comment', comment);
            form_data.append('lastCmtId', lastcmtid);
            form_data.append('currentCmtCount', currentcommentcount);
            form_data.append('type', currCommentType);
            form_data.append('maintype', currMainType);
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                url: baseUrl + '/add_comment_feed',
                success: function(obj) {
                    if (obj.code == 1) {
                        $('.send_message').text('Send');
                        $('input[name=comment]').val('');
                        $('.send_message').attr('disabled', false);
                        //getCommentList();
                        //$( ".comment_list" ).prepend(tempHtml);


                        if (obj.commentCount > 1) {
                            $("#countComment").text(obj.commentCount + " Comments");
                        } else {
                            $("#countComment").text(obj.commentCount + " Comment");
                        }

                        var tempHtml = '';
                        var tempHtml2 = '';
                        //var i = 0;
                        tempHtml += '<div class="cmts"><li><div class="img_col">' +
                            '<img src=' + obj.output['user_image'] + ' alt="Family Feed" class="img-fluid">' +
                            '</div>' +
                            '<div class="commentdetail">' +
                            '<h4>' + obj.output['user_name'] + ' <p class="commentTime">&nbsp;' + obj.output['createdTime'] + '</p></h4>' +
                            '<p>' + obj.output['comment'] + '</p>' +
                            '</div>' +
                            '</li></div>';
                        $('#btn-more').attr('data-id', obj.lastId);
                        $("#btn-more").html("View more comments");
                        //$( ".comment_list" ).append(tempHtml);
                        //i = i+1;
                        $(".view_more_commentsarea").remove();
                        if (obj.commentCount != obj.lastCmtcount && obj.commentCount > 3) {
                            tempHtml2 = '<div class="view_more_commentsarea" ><div id="view_more_commentsarea">' +
                                '<button class="btn-more" id="btn-more" data-cmtcount=' + obj.lastCmtcount + ' data-id =' + obj.lastId + '>View more comments</button>' +
                                '</div><span>' + obj.lastCmtcount + ' of ' + obj.commentCount + '</span></div>';
                        }
                        $(".comment_list").prepend(tempHtml);
                        $(".comment_list").append(tempHtml2);
                    } else {
                        errormsg(obj.message, 5000);
                        $('.send_message').attr('disabled', false);
                    }
                },
            });
        } else {
            $('input[name=comment]').val('');
            $('.send_message').attr('disabled', false);
            $("input[name=comment]").focus();
        }
    });

    //CONFIRMATION FOR BACK BUTTON
    var form_state = $('.form').serialize();
    $(document).on('click', '.btn_back', function() {
        if (form_state != $('.form').serialize()) {
            $('#confirm_popup').modal('show');
        } else {
            window.location.href = $('.btn_back').data('url');
        }
    });
    $('#confirm_popup .confirm').on("click", function() {
        window.location.href = $('.btn_back').data('url');
    });

    //COMMON LIKE UNLIKE
    $(document).on('click', '.like_unlike', function() {
        var currLikeType = $("#liketable").val();
        var currMainType = $('#maintable').val();
        var module = $("#module").val();
        var currId = $("#feed_id").val();
        form_data = new FormData();
        form_data.append('module', module);
        form_data.append('id', currId);
        form_data.append('type', currLikeType);
        form_data.append('maintype', currMainType);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: baseUrl + '/like_feed',
            success: function(obj) {
                if (obj.code == 1) {
                    $('#like_unlike').toggleClass("active");
                    if (obj.likeCount > 1) {
                        $("#countLike").text(obj.likeCount + " Likes");
                    } else {
                        $("#countLike").text(obj.likeCount + " Like");
                    }
                } else {
                    errormsg(obj.message, 5000);
                }
            },
        });
    });


    // COMMON DELETE
    var currDeleteId = '';
    var currDeleteType = '';
    var module = '';
    $(document).on('click', '.delete_feed,.delete_nominee,.delete_feed_liab', function() {
        module = $("#module").val()
        console.log("module " + module);
        cls = $(this).prop("id");
        console.log("propid " + cls);
        if (cls == 'delete_liab') {
            type = $("#mainTableLiab").val();
        } else {
            type = $("#maintable").val();
        }
        console.log("maintabl " + type);

        $('#delete_popup').modal('show');
        if (cls == 'delete_nominee') {
            $('.modal-body').html("<p> Do you really want to delete this nominee? You cannot recover it back.</p>");
        } else if (type == 33) {
            $('.modal-body').html("<p> Do you really want to delete this asset? You cannot recover it back.</p>");
        } else if (type == 34) {
            $('.modal-body').html("<p> Do you really want to delete this liability? You cannot recover it back.</p>");
        } else if (cls == 'delete_moment') {
            $('.modal-body').html("<p> Do you really want to delete this special moment? You cannot recover it back.</p>");
        } else {
            $('.modal-body').html("<p> Do you really want to delete this " + module + "? You cannot recover it back.</p>");
        }

        if ($("#feed_id").val() == '') {
            currDeleteId = $(this).data('id');
        } else {
            currDeleteId = $("#feed_id").val();
            console.log(currDeleteId);
        }

        if (cls == 'delete_liab') {
            currDeleteType = $("#mainTableLiab").val();
        } else {
            currDeleteType = $("#maintable").val();
        }

    });

    $('#delete_popup .confirm').on("click", function() {
        form_data = new FormData();
        form_data.append('id', currDeleteId);
        form_data.append('module', module);
        form_data.append('type', currDeleteType);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: (cls == 'delete_nominee') ? baseUrl + '/assets/delete-nominee' : baseUrl + '/delete_feed',
            success: function(obj) {
                if (obj.code == 1) {
                    var module = '/' + obj.module;
                    window.location.href = (cls == 'delete_nominee') ? baseUrl + '/assets/asset-information/' + currDeleteId : baseUrl + module.toLowerCase();

                    $('#delete_popup').modal('hide');

                } else {
                    errormsg(obj.message, 5000);
                }
            },
        });
    });

    //LOGOUT POPUP
    $(document).on('click', '.logout', function() {
        $('#logout_popup').modal('show');
    });

    $(document).on('change', '#adicionafoto', function() {
        var saida = document.getElementById("adicionafoto");
        var quantos = saida.files.length;
        var module = $("#module").val();
        var method = $("#method").val();
        var urls = '';
        var multiple_images = $(this).prop("multiple");
        if (multiple_images == false) {
            console.log(false);
            fileExtension = saida.files[0].name.split('.').pop().toLowerCase();
            console.log($("#adicionafoto").val());
            if (fileExtension == 'jpg' || fileExtension == 'jpeg' || fileExtension == 'png' || fileExtension == 'JPG' || fileExtension == 'JPEG' || fileExtension == 'PNG') {
                if (saida.files[0].size <= 10240000) {
                    $(".upload_photo_video").removeClass("has-error");
                    $("#adicionafoto-error").remove();
                    var urls = URL.createObjectURL(event.target.files[0]);
                    $(document).find('.addimgs_area').empty();

                    $(document).find('.addimgs_area').append('<li class="addimg oldimage"> <button type="button" class="close-btn "> <img src="' + baseUrl + '/images/icons/upload_file_close.svg" alt="Vio Graf" /></button> <img src="' + urls + '" alt="Vio Graf" /> </li>');
                } else {
                    $(document).find('.addimgs_area').empty();
                    $(".upload_photo_video").removeClass("has-error");
                    $("#adicionafoto-error").remove();
                    $(".upload_photo_video").addClass("has-error");
                    $('.upload_photo_video .upload_photobtn').after('<label style="bottom:-20px;" id="adicionafoto-error" class="help-block">Maximum file size for Image is 10 MB</label>');

                    $("#adicionafoto").val('');
                }
            } else if (fileExtension == 'mp4' || fileExtension == 'ogv' || fileExtension == 'webm') {
                if (saida.files[0].size <= 102400000) {
                    $(".upload_photo_video").removeClass("has-error");
                    $("#adicionafoto-error").remove();
                    var urls = URL.createObjectURL(event.target.files[0]);
                    console.log(urls);
                    $(document).find('.addimgs_area').empty();

                    $(document).find('.addimgs_area').append('<li class="addimg oldimage"><button type="button" class="close-btn"> <img src="' + baseUrl + '/images/icons/upload_file_close.svg" alt="Vio Graf" /></button><video><source id="videoSource"/ src="' + urls + '"></video></li>');
                } else {
                    $(document).find('.addimgs_area').empty();
                    $(".upload_photo_video").removeClass("has-error");
                    $("#adicionafoto-error").remove();
                    $(".upload_photo_video").addClass("has-error");
                    $('.upload_photo_video .upload_photobtn').after('<label style="bottom:-20px;" id="adicionafoto-error" class="help-block">Maximum file size for Video is 100 MB</label>');
                    $("#adicionafoto").val('');
                }
            } else {
                $(document).find('.addimgs_area').empty();
                $(".upload_photo_video").removeClass("has-error");
                $("#adicionafoto-error").remove();
                $(".upload_photo_video").addClass("has-error");
                $('.upload_photo_video .upload_photobtn').after('<label id="adicionafoto-error" class="help-block">Valid extensions are jpg, jpeg, png, mp4, ogv and webm</label>');

                $("#adicionafoto").val('');
            }
        } else {
            console.log(true);
            // MULTIPLE FILE UPLOADS
            if (method == 'Edit') {
                $(document).find('.addimgs_area').append('<input type="hidden" name="remove_new_file_hidden" id="remove_new_file_hidden">');
            } else {
                $(document).find('.addimgs_area').html('').append('<input type="hidden" name="remove_new_file_hidden" id="remove_new_file_hidden">');
            }
            var oldimage = $("li").hasClass("oldimage");
            if (oldimage) {
                $(document).find('.oldimage').remove();
            }
            for (i = 0; i < quantos; i++) {
                fileExtension = saida.files[i].name.split('.').pop().toLowerCase();
                if (fileExtension == 'jpg' || fileExtension == 'jpeg' || fileExtension == 'png' || fileExtension == 'JPG' || fileExtension == 'JPEG' || fileExtension == 'PNG') {
                    if (saida.files[0].size <= 10240000) {
                        $(".upload_photo_video").removeClass("has-error");
                        $("#adicionafoto-error").remove();
                        var cls = $('.upload_photobtn').val();
                        var urls = URL.createObjectURL(event.target.files[i]);

                        $(document).find('.addimgs_area').append('<li class="addimg oldimage"> <button type="button" class="js-close-remove-file" id="js-close-remove-file" data-index="' + i + '"> <img src="' + baseUrl + '/images/icons/upload_file_close.svg" alt="Vio Graf" /></button> <img src="' + urls + '" alt="Vio Graf" /> </li>');

                    } else {
                        $(".upload_photo_video").removeClass("has-error");
                        $("#adicionafoto-error").remove();
                        $(".upload_photo_video").addClass("has-error");
                        if (method == 'Edit' || module == 'Moments') {
                            $('.upload_moment').after('<label id="adicionafoto-error" class="help-block">Maximum file size for Image is 10 MB</label>');
                        } else {
                            $('.upload_photo_video .upload_photobtn').after('<label id="adicionafoto-error" class="help-block">Maximum file size for Image is 100 MB</label>');
                        }
                        $("#adicionafoto").val('');
                    }
                } else if (fileExtension == 'mp4' || fileExtension == 'ogv' || fileExtension == 'webm') {
                    if (saida.files[0].size <= 102400000) {
                        $(".upload_photo_video").removeClass("has-error");
                        $("#adicionafoto-error").remove();
                        var urls = URL.createObjectURL(event.target.files[i]);
                        console.log(urls);
                        $(document).find('.addimgs_area').append('<li class="addimg oldimage"><button type="button" class="js-close-remove-file" id="js-close-remove-file" data-index="' + i + '"> <img src="' + baseUrl + '/images/icons/upload_file_close.svg" alt="Vio Graf" /></button><video><source id="videoSource"/ src="' + urls + '"></video></li>');
                    } else {
                        $(".upload_photo_video").removeClass("has-error");
                        $("#adicionafoto-error").remove();
                        $(".upload_photo_video").addClass("has-error");
                        if (method == 'Edit' || module == 'Moments') {
                            $('.upload_moment').after('<label id="adicionafoto-error" class="help-block">Maximum file size for Video is 100 MB</label>');
                        } else {
                            $('.upload_photo_video .upload_photobtn').after('<label id="adicionafoto-error" class="help-block">Maximum file size for Video is 100 MB</label>');
                        }

                        $("#adicionafoto").val('');
                    }
                } else {
                    $(".upload_photo_video").removeClass("has-error");
                    $("#adicionafoto-error").remove();
                    $(".upload_photo_video").addClass("has-error");

                    if (method == 'Edit' || module == 'Moments') {
                        $('.upload_moment').after('<label id="adicionafoto-error" class="help-block">Valid extensions are jpg, jpeg, png, mp4, avi, mov and wmv</label>');
                    } else {
                        $('.upload_photo_box .upload_photobtn').after('<label id="adicionafoto-error" class="help-block">Valid extensions are jpg, jpeg, png, mp4, avi, mov and wmv</label>');
                    }
                    $("#adicionafoto").val('');
                }
            }
        }
        
        $(".close-btn").on('click', function() {
            $(".close-btn").parent(".addimg").remove();
            $("#adicionafoto").val('');
            $("#close-btn").hide();
            $("#oldPhoto").val('');
        });
        $(".form_btn").attr("disabled", false);

        $('.js-close-remove-file').on('click', function() {
            var filelist = document.getElementById("adicionafoto");
            var i = $(this).attr('data-index');
            var oldimage = $(this).closest("li").hasClass("oldimage");
            if (oldimage == true) {
                var remove_new = $('#remove_new_file_hidden').val();
                $('#remove_new_file_hidden').val(remove_new + (!remove_new ? '' : ',') + i);
            }

            if ($(this).parent(".addimg").prop('id') == '') {
                $(this).parent(".addimg").remove();
                $(this).hide();
                //$("#adicionafoto").val(''); 
            }

            $(".upload_photo_video").removeClass("has-error");
            $("#adicionafoto-error").remove();

        });

    });

    // FILE SIZE VALIDATION FOR DOCUMENT
    $(document).on('change', 'input[type="file"]#uploaddoc', function() {
        return false;
        var docById = document.getElementById("uploaddoc");
        //console.log(docById);
        var docCount = docById.files.length;
        // console.log(docCount);
        var numOfDocs = $('.filediv').children('div').length;
        var fileCounts = docById.files.length;

        //console.log(numOfDocs+fileCounts);

        if (numOfDocs == 5) {

            $(".upload_doc_box").removeClass("has-error");
            $("#uploaddoc-error").remove();
            $(".upload_doc_box").addClass("has-error");
            $('.upload_doc_box .upload_photobtn').after('<label id="uploaddoc-error" class="help-block">Maximum 5 documents are allowed.</label>');
            //$('.upload_doc_box').after('<label id="uploaddoc-error" class="help-block doc-error">Maximum 5 documents are allowed.</label>');
            $("#uploaddoc").val('');
        } else if ((numOfDocs + fileCounts) > 5) {
            var lmt = (5 - numOfDocs);
            $(".upload_doc_box").removeClass("has-error");
            $("#uploaddoc-error").remove();
            $(".upload_doc_box").addClass("has-error");
            $('.upload_doc_box .upload_photobtn').after('<label id="uploaddoc-error" class="help-block">Maximum 5 documents are allowed.</label>');
            //$('.upload_doc_box').after('<label id="uploaddoc-error" class="help-block">Maximum 5 documents are allowed.</label>');
            $("#uploaddoc").val('');
        } else {
            for (i = 0; i < docCount; i++) {
                var docSize = docById.files[i].size;
                fileExtension = docById.files[i].name.split('.').pop().toLowerCase();

                if (fileExtension == 'png' || fileExtension == 'jpg' || fileExtension == 'jpeg' || fileExtension == 'xlsx' || fileExtension == 'xls' || fileExtension == 'ods' || fileExtension == 'pdf' || fileExtension == 'txt' || fileExtension == 'doc') {
                    if (docSize <= 10000000) {
                        $(".upload_doc_box").removeClass("has-error");
                        $("#uploaddoc-error").remove();
                        //$('span').remove();        
                    } else {
                        $(".upload_doc_box").removeClass("has-error");
                        $("#uploaddoc-error").remove();
                        $(".upload_doc_box").addClass("has-error");
                        $('.upload_doc_box .upload_photobtn').after('<label id="uploaddoc-error" class="help-block doc_size_val_message">Document size must be less than 10 mb</label>');

                        $("#uploaddoc").val('');
                    }
                } else {

                    $(".upload_doc_box").removeClass("has-error");
                    $("#uploaddoc-error").remove();
                    $(".upload_doc_box").addClass("has-error");
                    // $('.upload_doc_box .upload_photobtn').after('<label id="uploaddoc-error" class="help-block">Only jpg, jpeg, png, doc, xlsx, xls, ods, pdf and txt files are allowed.</label>');
                    $('.upload_doc_box').after('<label id="uploaddoc-error" class="help-block">Only jpg, jpeg, png, doc, xlsx, xls, ods, pdf and txt files are allowed.</label>');
                }
            }

        }
    });
    // PASSWORD HIDE/SHOW TOGGLE
    $(".netowrth-pass").on('click', function() {
        $('#netowrth-pass').toggleClass("fa-eye fa-eye-slash");
        if ($('#password-networth').attr("type") == "password") {
            $('#password-networth').attr("type", "text");
        } else {
            $('#password-networth').attr("type", "password");
        }
    })

    $(".pass").on('click', function() {

        $('#pass').toggleClass("fa-eye fa-eye-slash");
        if ($('#password').attr("type") == "password") {
            $('#password').attr("type", "text");
        } else {
            $('#password').attr("type", "password");
        }
    });

    // CONFIRM PASSWORD HIDE/SHOW TOGGLE
    $(".cnfpass").on('click', function() {

        $('#cnfPass').toggleClass("fa-eye fa-eye-slash");
        if ($('#confirm_password').attr("type") == "password") {
            $('#confirm_password').attr("type", "text");
        } else {
            $('#confirm_password').attr("type", "password");
        }
    });
    // NEW PASSWORD HIDE/SHOW TOGGLE
    $(".newpass").on('click', function() {

        $('#newpass').toggleClass("fa-eye fa-eye-slash");
        if ($('#new_password').attr("type") == "password") {
            $('#new_password').attr("type", "text");
        } else {
            $('#new_password').attr("type", "password");
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#imageshow').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#adicionafoto").on('change', function() {
        readURL(this);
    });

    // $(document).on('click','.notification-btn', function () {
    //   setTimeout(function(){
    //     $('body').toggleClass('notification_active');
    //   });
    // });

    $('body').on('click', function() {
        $('body').removeClass('notification_active');
    });

    //Burger Menu JS
    $('#dismiss1, .overlay').on('click', function() {
        $('.menuicon').removeClass('active');
        $('.navbarLink1').removeClass('active');
        $('body').removeClass('active1');
        $('.overlay').fadeOut();
    });

    //Multiple Dropdown Click JS
    // $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
    //     event.preventDefault();
    //     event.stopPropagation();

    //     $(this).siblings().toggleClass("show");

    //     if (!$(this).next().hasClass('show')) {
    //       $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    //     }
    //     $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
    //       $('.submenu .show').removeClass("show");
    //     });
    // });  

    // TO SEND VERIFICATION MAIL TO USER 
    $(document).on('click', '#resend_ver_email', function() {
        $('#resend_ver_email').text("Processing...");
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: '',
            type: 'post',
            url: SITE_URL + '/resend',
            success: function(obj) {
                if (obj.code == 1) {
                    $('#resend_ver_email').text("Resend");
                } else {
                    $('.row').find('.alert').remove();
                    if ($('.row').find('.alert').length == 0) {
                        $(".logo").before('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + obj.msg + '</div>');
                    }
                }
            },
            error: function(obj) {
                //  
            },
        });

    });



    $(function() {
        //DATEPICKER FOR WISHLIST PAGE

        $("#datepicker").datepicker({
            minDate: new Date(),
            changeYear: true,
            yearRange: "-0:+100",
            changeMonth: true,
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',
        });

        //DATEPICKER FOR EXPERIENCE PAGE

        $("#datepicker2").datepicker({
            maxDate: new Date(),
            changeYear: true,
            yearRange: "-100:+0",
            changeMonth: true,
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',
        });

        //DATEPICKER FOR SIGNUP PAGE

        $("#dobpicker").datepicker({
            maxDate: new Date(),
            changeYear: true,
            yearRange: "-100:+0",
            changeMonth: true,
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',
        });

        //DATEPICKER FOR PROFILE
        $("#milestonedate").datepicker({
            changeYear: true,
            yearRange: "-100:+100",
            changeMonth: true,
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',

        });
        $(this).siblings().toggleClass("show");

        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.submenu .show').removeClass("show");
        });
    });

    $(document).on('click', '.milestonedate', function() {
        $("#milestonedate").datepicker("show");
    });
    $(document).on('click', '.date_of_birth_box', function() {
        $("#dobpicker").datepicker("show");
    });
    $(document).on('click', '.experiencedate', function() {
        $("#datepicker2").datepicker("show");
    });

    $(document).on('click', '.wishlistdate', function() {
        $("#datepicker").datepicker("show");
    });
    $(document).on('click', '.education_start_date', function() {
        $("#startdate").datepicker("show");
    });
    $(document).on('click', '.education_end_date', function() {
        $("#enddate").datepicker("show");
    });
    $(document).on('click', '.career_start_date', function() {
        $("#startdate").datepicker("show");
    });
    $(document).on('click', '.career_end_date', function() {
        $("#enddate").datepicker("show");
    });

    // TO SEND VERIFICATION MAIL TO USER 
    $(document).on('click', '#resend_ver_email', function() {
        $('#resend_ver_email').text("Processing...");
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: '',
            type: 'post',
            url: SITE_URL + '/resend',
            success: function(obj) {
                if (obj.code == 1) {
                    $('#resend_ver_email').text("Resend");
                } else {
                    $('.row').find('.alert').remove();
                    if ($('.row').find('.alert').length == 0) {
                        $(".logo").before('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + obj.msg + '</div>');
                    }
                }
            },
            error: function(obj) {
                //  
            },
        });
    });

    //************* CAREER AND EDUCATION PAGE CURRENT CHECKBOX FUNCTIONALITY START ****************//

    $('#enddate').datepicker({
        maxDate: ((module != 'education' && module != 'career') ? new Date() : ''),
        changeYear: true,
        yearRange: "c-121:c+100",
        changeMonth: true,
        showMonthAfterYear: true,
        dateFormat: 'dd M, yy'
    });
    if ($(document).find('#education-check').is(':checked')) {
        $("#enddate").datepicker('disable');
        $(".to").css('display', 'none');
        $("#enddate").parent().parent().css('display', 'none');

        $(this).parent().parent().removeClass('col-md-5');
        $(this).parent().parent().addClass('col-md-8');
    } else {
        // $("#enddate").datepicker('enable');
        $('#enddate').datepicker('enable', {
            maxDate: ((module != 'education' && module != 'career') ? new Date() : ''),
            changeYear: true,
            yearRange: "c-121:c+100",
            changeMonth: true,
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy'
        });

        $(this).parent().parent().removeClass('col-md-8');
        $(this).parent().parent().addClass('col-md-5');
        $(".to").css('display', 'block');
        $("#enddate").parent().parent().css('display', 'block');
    }
    $(document).on('change', '#education-check', function() {
        if ($(this).is(':checked')) {
            $("#enddate").datepicker('disable', {
                maxDate: ((module != 'education' && module != 'career') ? new Date() : ''),
                changeYear: true,
                yearRange: "c-121:c+100",
                changeMonth: true,
                showMonthAfterYear: true,
                dateFormat: 'dd M, yy'
            });
            $(this).parent().parent().removeClass('col-md-8');
            $(this).parent().parent().addClass('col-md-5');
            $(".to").css('display', 'none');
            $('#enddate').parent().parent().css('display', 'none');
        } else {
            // $("#enddate").datepicker().datepicker('enable');
            $('#enddate').datepicker('enable', {
                maxDate: ((module != 'education' && module != 'career') ? new Date() : ''),
                changeYear: true,
                yearRange: "c-121:c+100",
                changeMonth: true,
                showMonthAfterYear: true,
                dateFormat: 'dd M, yy'
            });

            $(this).parent().parent().removeClass('col-md-5');
            $(this).parent().parent().addClass('col-md-8');
            $(".to").css('display', 'block');
            $("#enddate").parent().parent().css('display', 'block');
        }
    });
    //************* CAREER AND EDUCATION PAGE CURRENT CHECKBOX FUNCTIONALITY END ****************//

    $(document).on('ready', function() {
        $(".education-imgs-lider").show();
        $(".education-imgs-lider").slick({
            dots: false,
            pauseOnHover: false,
            infinite: true,
            arrows: true,
            slidesToShow: 1,
            fade: true,
            cssEase: 'linear',
            autoplay: false,
            speed: 100,
            autoplaySpeed: 1000,
            slidesToScroll: 1
        });
    });
    $(function() {
        var module = $("#module").val();

        $("#startdate").datepicker({

            maxDate: ((module != 'education' && module != 'career') ? -1 : ''),
            changeYear: true,
            yearRange: "c-121:c+100",
            changeMonth: true,
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',

            onSelect: function() {
                var dat1 = $('#startdate');
                var dt2 = $('#enddate');

                //FOR CURRENT DATE
                var d = new Date();
                console.log(d);

                var startDate = $(this).datepicker('getDate');
                var minDate = $(this).datepicker('getDate');
                minDate.setDate(minDate.getDate() + 1);
                var dt2Date = dt2.datepicker('getDate');
                if (dt2Date == null || startDate.getDate() < dt2Date.getDate()) {
                    //dt2.datepicker('setDate', minDate);                
                }
                dt2.datepicker('option', 'maxDate', ((module != 'education' && module != 'career') ? d : ''));
                dt2.datepicker('option', 'minDate', minDate);
            }
        });

    });
    $('.publicbtn').select2({
        templateResult: formatState,
        templateSelection: formatState,
        minimumResultsForSearch: -1
    });

    function formatState(opt) {
        if (!opt.id) {
            return opt.text;
        }

        //var optimage = $(opt.element).attr('data-image'); 
        var optimage = $(opt.element).data('image');
        //console.log(optimage);
        if (!optimage) {
            return opt.text;
            console.log(1);
        } else {

            var $opt = $(

                //'<span><img class="applianceIcon" src="' + opt.image + '" /> <span class="optText">' + opt.text + '</span></span>'
                '<span><img class="xyz" src="' + optimage + '" /><span class="optText">' + opt.text + '</span></span>'
            );
            return $opt;
        }
    };

    //$('.ui.dropdown').dropdown('');
   
    $(".js-example-responsive").select2({
        width: 'resolve',
        minimumResultsForSearch: -1
    });

    $(document).on('click', '#profile-Change', function() {
        var file = $(document).find('#profileimage');
        file.trigger('click');

    });

    $(document).on('click', '.addnominee', function() {
        var dataUrl = $(this).attr("data-link");
        window.location.href = dataUrl;
        return false;
    })

    $(document).on('click', '.addliability', function() {
        var dataUrl = $(this).attr("data-link");
        window.location.href = dataUrl;
        return false;

    })
    $(document).on('click', '.publicinfo', function() {
        var dataUrl = $(this).attr("data-link");
        window.location.href = dataUrl;
        return false;

    })
    $(document).on('click', '.public-info', function() {
        var dataUrl = $(this).attr("data-link");
        window.location.href = dataUrl;
        return false;

    })
    $(document).on('click', '.info-link', function(e) {
        var target = $(event.target);
        var dataid = $(this).data("id");
        /*  console.log(target.is('a') );
         console.log( target.parent().is('a'));return false; */
        if (target.is('a') == false && target.parent().is('a') == false) {
            var dataUrl = $(this).attr("data-link");
            window.location.href = dataUrl;
        }
    });
    

    $('.amount').mask("#,##0.00", { reverse: true });

    // invoke plugin
    if ($('.js-multi-file').length > 0) {
        $('.js-multi-file').MultiFile();

    }
    
    $("#currency").select2({
        placeholder: "Select Currency",        
    });
    
    /******* IMAGE CROPPER FOR PROFILE IMAGE *******/
    $(function() {
        var inputImage = '';
        $(document).on('change', '.crop_photo', function() {
            inputImage = $(this).attr('data-crop');
            inputId = $(this).attr('id');
            console.log(inputId);
            $('.img-container').css('display', 'block');
            $('#crop_image').cropper("destroy");
            var input = this;
            var file_info = input.files[0];
            var validImageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $('#profilepic-error').remove();
            //The file uploaded is an image
            if (typeof(file_info) != 'undefined') {
                if ($.inArray(file_info.type, validImageTypes) >= 0) {
                    if (input.files[0].size <= 10240000) {
                        if (input.files && input.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('#crop_image').attr('src', e.target.result);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }
                        $('#crop_image').hide();
                        $('.preview-container').remove();
                        $('#iconModal').modal('show');

                    } else {
                        $(document).find('.addimgs_area').empty();
                        if (inputImage == 'cropimage') {
                            $(".profile-help-block").remove();
                            $(".upload_photo_video").removeClass("has-error");
                            $(".upload_photo_video").addClass("has-error");
                            $('.upload_photo_video .upload_photobtn').after('<label style="bottom:-20px;left:0;" id="crop-photo-error" class="help-block  profile-help-block">The File may not be greater than 10 MB.</label>');
                        } else {
                            $('.profile-Change').after('<label style="bottom:-20px;" id="profilepic-error" class="help-block  profile-help-block">The File may not be greater than 10 MB.</label>');
                        }
                    }
                } else {
                    $(document).find('.addimgs_area').empty();
                    if (inputImage == 'cropimage') {
                        $(".profile-help-block").remove();
                        $(".upload_photo_video").removeClass("has-error");
                        $(".upload_photo_video").addClass("has-error");
                        $('.upload_photo_video .upload_photobtn').after('<label style="bottom:-20px;left:0; id="crop-photo-error" class="help-block profile-help-block" ">Invalid file selection, only image file is allowed.</label>');
                    } else {
                        $('.profile-Change').after('<label id="profilepic-error" class="help-block profile-help-block" ">Invalid file selection, only image file is allowed.</label>');
                    }
                }
            }
        });

        $(document).on('shown.bs.modal', '#iconModal', function() {
            var options = {
                aspectRatio: (inputId == 'profileimage') ? 1 / 1 : 5 / 2,
                viewMode: 1,
                enableResize: true,
            };
            $('#crop_image').cropper(options);
        });

        $(document).on('click', '#reset', function() {
            $('.preview-container').remove();
            $('.img-container').show();
            $('#crop_image').cropper("reset");
        });

        $(document).on('click', '#preview', function() {
            $('.preview-container').remove();
            result = $('#crop_image').cropper('getCroppedCanvas', { width: 500, height: 500 });
            $('.img-container').hide();
            $('<div class="preview-container" style="text-align:center"></div>').insertAfter($('.img-container'));
            $('.preview-container').html(result);
        });

        $(document).on('click', '#apply', function() {
            result = $('#crop_image').cropper('getCroppedCanvas', { width: 600, height: 300 });
            var dataURL = result.toDataURL("image/jpeg");
            $(".profile-help-block").remove();
            $(".upload_photo_video").removeClass("has-error");
            if (inputImage == 'cropimage') {
                $(document).find('.addimgs_area').empty();
                $(document).find('.addimgs_area').append('<li class="addimg oldimage"> <button type="button" class="close-btn "> <img src="' + baseUrl + '/images/icons/upload_file_close.svg" alt="Vio Graf" /></button> <img src="' + dataURL + '" alt="Vio Graf" /> </li>');

                $('#preview_img').val(dataURL);
                $('#oldPhoto').val(dataURL);
                $('.profile-user-img').attr('src', dataURL);
                //$('.profile-user-img').css("background-image","url("+dataURL+")");
            } else {
                $('#oldPhoto').val(dataURL);
                $('.profile-user-img').attr('src', dataURL);
            }
            $('#iconModal').modal('hide');
            $(".close-btn").on('click', function() {
                $(".close-btn").parent(".addimg").remove();
                $("#adicionaphoto").val('');
                $("#close-btn").hide();
                $("#oldPhoto").val('');
            });
        });

        $(document).on('click', '#close', function() {
            $('#iconModal').modal('hide');
            var oldimage_src = $('#preview_img').attr('data-src');
            if (inputImage == 'cropimage') {
                $('.crop_photo').val('');
                $('#oldprofile').val('');
                //  $('#image').val('default-image');
                $('.profile-user-img').attr("src", oldimage_src);
            }
        });
    });
    /*END of IMAGE CROPPER*/
    /* OTP CODE*/
    $(document).on('click', '.networth', function() {
        var curr_url = $(this).attr('data-link');
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: '',
            type: 'post',
            url: curr_url,
            success: function(obj) {
                if (obj.code == 1) {
                    //  window.location.href =  baseUrl + '/my-networth';
                } else {
                    //$(".error-msg-otp").html(obj.error);
                    errormsg(obj.message, 5000);
                }
            },
            error: function(obj) {
                //  
            },
        });
    })
    if ($('#save_draft').is(':checked')) {
        $('#save_draft').val(1);
        $('#draft').val(1);
    } else {
        $('#save_draft').val(2);
        $('#draft').val(2);
    }
    $(document).on('change', '#save_draft', function() {
        if ($(this).is(':checked')) {
            $(this).val(1);
            $('#draft').val(1);
        } else {
            $(this).val(2);
            $('#draft').val(2);
        }
    });

    //TIMER FOR SAVE AS DRAFT
    var count_time = counter_draft;
    var remain__counter = remain_draft_counter;
    $(document).ready(function() {

        var find_class = $('form').hasClass('js-draft-form');
        if (find_class == true) {
            // loadPopupBox();

            timerDecrement();
            let idleInterval =
                setInterval(timerDecrement, 1000);
            $(this).mousemove(resetTimer);
            $(this).keypress(resetTimer);
            $(this).click(resetTimer);
        }
    });
    $('#js_timer_popup_close').click(function() {
        unloadPopupBox();
    });

    function resetTimer() {
        count_time = counter_draft;
        $('#timer_popup').modal('hide');
    }

    function unloadPopupBox() { // TO Unload the Popupbox
        $('#timer_popup').modal('hide');
    }

    function timerDecrement() {
        var id;
        count_time--;
        if (count_time <= remain__counter) { //if its is less then 10 seconds condition
            if (count_time <= 0) {
                resetTimer();
                $('#timer_popup').modal('hide');
                // FORM SUBMIT CODE                  
                if (!$(this).is(':checked')) {
                    $('.save-check').prop('checked', true);
                }
                $('.js-draft-form').submit();
            } else {
                // TO OPEN MODEL IF NOT OPEN
                $(".js-counter").text(count_time);
                $(".js-counter").css('font-size', '40px');
                $('#timer_popup').modal('show');
                $(".js-cancel").on('click', function() {
                    $('#timer_popup').modal('hide');
                })
            }
        }
    }

});