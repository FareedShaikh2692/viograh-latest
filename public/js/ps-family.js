/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function($) {
    var rootDiv = '';
    var treeGround = null;
    var newMemberForm = '';
    var memberName = '';
    var memberGender = '';
    var memberAge = '';
    var memberPic = '';
    var memberRelation = '';
    var notAlive = '';
    var familyTree = new Array();
    var memberId = 0;
    var selectedMember = null; // refrence to selected member
    var self = true;
    var memberSpace = 92;
    var memberWidth = 115;
    var memberHeight = 107;
    var memberDetails = null;
    var options_menu = null;
    var object = new Object();
    var rut = null;
    var parent = null;

    $.fn.pk_family = function(options) {
        if (rootDiv == null) {
            // error message in console
            jQuery.error('wrong id given');
            return;
        }
        rootDiv = this;
        init();
    }

    // function to create tree from json data
    $.fn.pk_family_create = function(options) {
        if (rootDiv == null) {
            // error message in console
            jQuery.error('wrong id given');
            return;
        }
        rootDiv = this;
        var settings = $.extend({
            // These are the defaults.
            data: "",
        }, options);
        var obj = jQuery.parseJSON(settings.data);
        addBreadingGround();
        parent = $('<ul>');
        $(parent).appendTo(treeGround);
        //console.log(obj)
        traverseObj(obj, 0);

        //createNewMemberForm();
        //member_details();
        //createOptionsMenu();

        //DISBALE RIGHT CLICK ON WEBPAGE
        // document.oncontextmenu = function() {
        //     return false;
        // };

    }

    function tempTest(obj) {
        for (var i in obj) {
            document.write(i + " &nbsp;");
            if (i.indexOf('a') > -1 && i.length == 2) {;
            } else {
                tempTest(obj[i]);
            }
        }
        return;
    }

    var parentArr = {};

    function traverseObj(obj, tempCount) {
        for (var i in obj) {
            if (i.indexOf("li") > -1) {
                var li = $('<li>');
                var temp_li_c = parseInt(i.replace('li', ''));
                if (temp_li_c > 0) {
                    $(li).appendTo(parentArr[tempCount]);
                    parent = li;
                    traverseObj(obj[i], tempCount);
                } else {
                    $(li).appendTo(parent);

                    tempCount++;
                    parentArr[tempCount] = parent;

                    parent = li;

                    traverseObj(obj[i], tempCount);
                    parent = $(parent).parent();

                }
                // parent = li;
                // traverseObj(obj[i]);
                // parent = $(parent).parent();

                //var li = $('<li>');
                //$(li).appendTo(parent);
                //parent = li;
                //traverseObj(obj[i]);
                //parent = $(parent).parent();
            }

            if (i.indexOf("a") > -1 && i.length == 2) {

                var link = $('<a>');
                link.attr('data-name', obj[i].name);
                link.attr('data-id', obj[i].id);
                link.attr('data-age', obj[i].age);
                link.attr('data-gender', obj[i].gender);
                link.attr('data-relation', obj[i].relation);
                if (obj[i].relation == 'Spouse' || i == 'a1') {
                    link.attr('class', 'spouse');
                }
                if (obj[i].relation == 'Self') {
                    link.attr('id', 'my_self');
                }

                var center = $('<center>').appendTo(link);
                var div = $('<div class="actions">').appendTo(link);
                var pic = $('<img class="family-img">').attr('src', obj[i].pic);
                var extraData = '' + obj[i].relation + '';

                // if (obj[i].gender == "Male" || obj[i].gender == "male") {
                //     extraData = "(M)";
                // } else {
                //     extraData = "(F)";
                // }
                $(pic).appendTo(center);
                $(center).append($('<br>'));
                $('<span class="name">').html(obj[i].name).appendTo(center);
                $('<span class="relation">').html(extraData).appendTo(center);
                if (obj[i].relation == 'Self') {
                    var btn = $('<button class="addmember">').html("<i class='fas fa-plus'></i>").appendTo(div);
                } else {
                    var btn = $('<button class="addmember">').html("<i class='fas fa-plus'></i>").appendTo(div);
                    var bttn = $('<button class="removemember">').html("<i class='fas fa-minus'></i>").appendTo(div);
                    var editBtn = $('<button class="editmember">').html("<i class='fas fa-pen'></i>").appendTo(div);
                }
                // $(bttn).click(function(event) {
                //         //console.log($(this).parent().parent());
                //         //console.log(link);
                //         var membr = $(this).parent().parent()
                //         $('#remove_member_popup').modal('show');
                //         $('#remove_member_popup .confirm').on("click", function(){
                //             removeMember(membr);
                //         });
                //     //return true;
                // });
                $(btn).click(function(event) {
                    //if (event.button == 2) {
                    displayPopMenu(link, event);
                    //return false;
                    //}
                    return true;
                });
                // $(link).mouseenter(function(event) {
                //     //if (event.button == 2) {
                //         displayPopMenu(this, event);
                //         //return false;
                //     //}
                //     return true;
                // });     

                $(link).appendTo(parent);

            }

            if (i.indexOf("ul") > -1) {
                var ul = $('<ul class="js-ul">');
                $(ul).appendTo(parent);
                parent = ul;
                traverseObj(obj[i], tempCount);
                return;
            }
        }
        return;
    }

    // $.send_Family =  $.fn.pk_family_send = function(options) {

    //     if (rootDiv == null) {
    //         // error message in console#
    //         jQuery.error('wrong id given');
    //         return;
    //     }
    //     var settings = $.extend({
    //         // These are the defaults.
    //         url: baseUrl+'/family-tree',
    //     }, options);
    //     //console.log(settings.url);
    //     var data = createSendURL();
    //     data = data.replace(new RegExp(']', 'g'), ""); 
    //     data = data.replace(new RegExp('\\[', 'g'), ""); 
    //     //console.log(data);
    //     $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    //     jQuery.ajax({
    //         type: 'POST',
    //         url: settings.url,
    //         data: {"tree":data},
    //         dataType : "json",
    //         success : function (obj)
    //         {
    //             if(obj.code){

    //             }
    //         }
    //     });
    // }


    function init() {
        // addMemberButton();
        //addBreadingGround();
        //createNewMemberForm();
        //member_details();
        createOptionsMenu();
        //displayFirstForm();

        //DISBALE RIGHT CLICK ON WEBPAGE
        // document.oncontextmenu = function() {
        //     return false;
        // };
    }
    $(document).ready(function() {
        $(document).on('click', '.treemodalclose', function() {
            $('#sempop').modal('hide');
            $('.savebutton').attr('disabled', false);
            $('.closebtn').attr('disabled', false);
            jQuery('.alert-danger').hide();
            $('#sempop').modal('hide');
            //clear exsiting data from form
            $('#pk-name').val('');
            $('#pk-age').val('');
            $('#pk-contact').val('');
            $('#pk-email').val('');
            $('#pk-gender').val('');
            $('#pk-relation').val('');
            $('#pk-picture').val('');
            $('#not_alive').prop('checked', false);
        });
        $('.family-tree-area').find('.js-ul').each(function() {
            if ($(this).find('li').length == 0) {
                $(this).remove();
            }
        });
        var membr = '';
        $(document).on('click', '.editmember', function() {
            $('#pk-relation').attr('disabled', true);
            membr = $(this).parent().parent().data('id');
            form_data = new FormData();
            form_data.append('memberId', membr);
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            jQuery.ajax({
                type: 'POST',
                url: baseUrl + '/edit-tree',
                data: form_data,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(obj) {
                    if (obj.code == '1') {
                        $('.family_tree_form').attr('id', 'frm_edit_member');
                        $('.family_tree_form').attr('name', 'frm_edit_member');
                        $('#sempop').modal('show');
                        $('.modal-title').text('Edit Member');
                        $('#pk-name').val(obj.data['results']['name']);
                        $('#pk-age').val(obj.data['results']['age']);
                        $('#pk-contact').val(obj.data['results']['phone_number']);
                        $('#pk-email').val(obj.data['results']['email']);
                        $('#select2-pk-gender-container').text(obj.data['results']['gender']);
                        $('#select2-pk-gender-container').css('textTransform', 'capitalize');
                        $("#pk-gender").val(obj.data['results']['gender']);
                        $('#select2-pk-relation-container').text(obj.data['rlnship']);
                        $('#pk-relation').val(obj.data['rlnship']);
                        $('.memberId').val(obj.data['results']['id']);
                        $('.storedImage').val(obj.data['results']['image']);
                        if (obj.data['results']['is_alive'] == 1) {
                            $('#not_alive').prop('checked', true);
                        }
                        $('#saveData').text('Update Member');
                    }
                }
            });
            console.log(membr);
        });

        $(document).on('submit', '#frm_edit_member', function() {
            console.log("Success");
            $('.savebutton').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing');
            $('.savebutton').attr('disabled', true);
            $('.closebtn').attr('disabled', true);

            var form_data = new FormData($(this)[0]);

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            jQuery.ajax({
                type: 'POST',
                url: baseUrl + '/update-tree',
                data: form_data,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(objj) {
                    if (objj.code == '1') {

                        /********** SET DATA TO WEB INTERFACE ***********/

                        // Find </a> TAG 
                        var id = objj.data['result']['id'];
                        console.log($(`[data-id='${id}']`));
                        $(`[data-id='${id}']`).find('img').attr("src", objj.data['result']['image']);
                        $(`[data-id='${id}']`).attr("data-name", objj.data['result']['is_alive'] + '' + objj.data['result']['name']);
                        $(`[data-id='${id}']`).attr("data-age", objj.data['result']['age']);
                        $(`[data-id='${id}']`).attr("data-gender", objj.data['result']['gender']);
                        $(`[data-id='${id}']`).attr("data-relation", objj.data['result']['relationship']);
                        $(`[data-id='${id}']`).find('.name').text(objj.data['result']['is_alive'] + '' + objj.data['result']['name']);
                        $(`[data-id='${id}']`).find('.relation').text('(' + objj.data['result']['relationship'] + ')');

                        /****************  END  ****************/

                        $('.savebutton').text('Update Member');
                        $('.savebutton').attr('disabled', false);
                        $('.closebtn').attr('disabled', false);
                        jQuery('.alert-danger').hide();
                        $('#sempop').modal('hide');

                        // CLEAR EXISTING DATA FROM POPUP FORM
                        $('#pk-name').val('');
                        $('#pk-age').val('');
                        $('#pk-gender').val('');
                        $('#pk-contact').val('');
                        $('#pk-email').val('');
                        $('#pk-relation').val('');
                        $('#pk-picture').val('');

                        var data = createSendURL();
                        data = data.replace(new RegExp(']', 'g'), "");
                        data = data.replace(new RegExp('\\[', 'g'), "");

                        var formdata = new FormData();
                        formdata.append('tree', data);
                        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                        jQuery.ajax({
                            type: 'POST',
                            url: baseUrl + '/update-family-tree',
                            data: formdata,
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(objj) {
                                if (objj.code == '1') {
                                    console.log("success");
                                }
                            }
                        });

                    } else {

                        $('.savebutton').text('Update Member');
                        $('.closebtn').attr('disabled', false);
                        $('.savebutton').attr('disabled', false);
                        jQuery('.alert-danger').html('');

                        jQuery.each(objj.errors, function(key, value) {
                            jQuery('.alert-danger').show();
                            jQuery('.alert-danger').append('<li>' + value + '</li>');
                        });
                    }
                }
            });
        });

        $(document).on('click', '.removemember', function() {
            membr = $(this).parent().parent();
            $('#remove_member_popup').modal('show');
        });

        $('#remove_member_popup .confirm').on("click", function() {
            removeMember(membr);
        });

        $(document).on('click', '.addmember', function() {

            $('#not_alive').prop('checked', false);
            console.log($(this).parent().parent().data('relation'));
            $('#pk-gender').attr('disabled', false);
            $('#pk-relation').attr('disabled', false);
            $('#pk-email').attr('readonly', false);
            $('#saveData').text('Add Member');
            //closePopMenu();
            $('.modal-title').text('Add Member');
            $('#sempop').modal('show');
            /*  $(document).on('change','.js-select-box',function(){
                 if($(this).val() != ""){
                     $(this).closest('.form-group').find('.select2-selection').removeClass('has-error');
                        
                 }
             }); */
            if ($(this).parent().parent().data('relation') == 'Mother' || $(this).parent().parent().data('relation') == 'Father') {
                $("#pk-relation option[value='Spouse']").remove();
            } else {
                if ($("#pk-relation option[value='Spouse']").length == 0) {
                    $('#pk-relation').append($('<option>', {
                        value: 'Spouse',
                        text: 'Spouse'
                    }));
                }
            }
            $('#pk-gender').on("select2:unselecting", function(e) {
                $('#pk-gender').val();
            }).trigger('change');
            $('#pk-relation').on("select2:unselecting", function(e) {
                $('#pk-relation').val();
            }).trigger('change');
            $('.family_tree_form').attr('id', 'frm_add_member');
            $('.family_tree_form').attr('name', 'frm_add_member');
            var mbrId = $(this).parent().parent().data('id');
            $(".memberId").val(mbrId);
        });

        $(document).on('submit', '#frm_add_member', function() {
            $('.savebutton').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing');
            $('.savebutton').attr('disabled', true);
            $('.closebtn').attr('disabled', true);
            memberName = $('#pk-name').val();
            memberGender = $('#pk-gender').val();
            memberAge = $('#pk-age').val();
            memberPic = $('#pk-picture');
            memberRelation = $('#pk-relation').val();
            notAlive = ($('#not_alive').is(':checked')) ? 'Late' : '';
            if (memberRelation == null) {
                memberRelation = 'Self';
            }
            var fileInput = document.getElementById('pk-picture');
            var file = fileInput.files[0];

            var form_data = new FormData($(this)[0]);
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            jQuery.ajax({
                type: 'POST',
                url: baseUrl + '/family-tree',
                data: form_data,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(obj) {
                    if (obj.code == '1') {

                        $('.savebutton').text('Add Member');
                        $('.savebutton').attr('disabled', false);
                        $('.closebtn').attr('disabled', false);
                        jQuery('.alert-danger').hide();
                        $('#sempop').modal('hide');
                        //clear exsiting data from form
                        $('#pk-name').val('');
                        $('#pk-age').val('');
                        $('#pk-contact').val('');
                        $('#pk-email').val('');
                        $('#pk-relation').val('');
                        $('#pk-gender').val('');

                        addMember(obj.btnId, obj.image);
                        var data = createSendURL();
                        data = data.replace(new RegExp(']', 'g'), "");
                        data = data.replace(new RegExp('\\[', 'g'), "");
                        $('#pk-picture').val('');

                        var formdata = new FormData();
                        formdata.append('tree', data);
                        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                        jQuery.ajax({
                            type: 'POST',
                            url: baseUrl + '/insert-family-tree',
                            data: formdata,
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(objj) {
                                if (objj.code == '1') {
                                    console.log("success");
                                }
                            }
                        });

                    } else {

                        $('.savebutton').text('Add Member');
                        $('.closebtn').attr('disabled', false);
                        $('.savebutton').attr('disabled', false);
                        jQuery('.alert-danger').html('');
                        if (obj.emailError == 1) {
                            jQuery('.alert-danger').show();
                            jQuery('.alert-danger').append('<li style="list-style-type: none;">' + obj.errors + '</li>');
                        } else {
                            jQuery.each(obj.errors, function(key, value) {
                                jQuery('.alert-danger').show();
                                jQuery('.alert-danger').append('<li style="list-style-type: none;">' + value + '</li>');
                            });
                        }
                    }
                }
            });
        });
        // liAdd.click(function(event) {
        //     closePopMenu();
        //     $("#popup").modal('show');
        //     event.preventDefault();
        //     // displayForm();
        //     $("#popup").css('display', 'block');
        // });

    });

    function createOptionsMenu() {
        var div = $('<div>').attr('id', 'pk-popmenu');
        var ul = $('<ul>');
        // add member button
        var liAdd = $('<li class="addmember">').html('Add Member').appendTo(ul);

        // view member button
        var liDisplay = $('<li>').html('View Details').appendTo(ul);
        liDisplay.click(function(event) {
            displayData(selectedMember);
            $(options_menu).css('display', 'none');
        });
        // remove member button
        var liRemove = $('<li>').html('Remove Member').appendTo(ul);
        liRemove.click(function(event) {
            removeMember(selectedMember);
            $(options_menu).css('display', 'none');
        });
        // cancel the pop menu
        var liCancel = $('<li>').html('Cancel').appendTo(ul);
        liCancel.click(function(event) {
            //displayForm(this);
            $(options_menu).css('display', 'none');
        });
        $(div).append(ul);
        options_menu = div;
        $(options_menu).appendTo(rootDiv);

    }
    // function createNewMemberForm() {
    //     var memberForm = $('<div>').attr('id', 'pk-memberForm');
    //     var cross = $('<div>').attr('class', 'pk-cross');
    //     $(cross).text('x');
    //     $(cross).click(closeForm);
    //     $(cross).appendTo(memberForm);
    //     var table = $('<table class="table">').appendTo(memberForm);
    //     // name
    //     $('<tr >').html('<td ><label>Name</label></td><td><input class="form-control" type="text" value="" id="pk-name"/></td>').appendTo(table);
    //     $('<tr >').html(' <td ><label>Gender</label></td><td><select class="form-control" id="pk-gender"><option value="Male">Male</option><option value="Female">Female</option></select></td>').appendTo(table);
    //     $('<tr >').html('<td ><label>Age</label></td><td><input class="form-control" type="text" value="" id="pk-age"></td>').appendTo(table);
    //     $('<tr >').html(' <td  class="relations"><label>Relation</label></td><td class="relations"><select class="form-control" id="pk-relation">\n\\n\
    //     <option value="Mother">Mother</option>\n\
    //     <option value="Father">Father</option>\n\\n\
    //     <option value="Sibling">Sibling</option>\n\\n\
    //     <option value="Child">Child</option>\n\\n\
    //     <option value="Spouse">Spouse</option>\n\\n\
    //     </select></td>').appendTo(table);
    //     $('<tr>').html('<td><label>Upload Photo</label></td><td><input type="file" id="pk-picture"></td>').appendTo(table);
    //     var buttonSave = $('<input id="sendData" class="savebutton">').attr('type', 'button');
    //     $(buttonSave).attr('value', 'Add Member');
    //     $(buttonSave).click(saveForm);
    //     var td = $('<td>').attr('colspan', '2');
    //     td.css('text-align', 'center');
    //     td.append(buttonSave);
    //     $('<tr>').append(td).appendTo(table);
    //     newMemberForm = memberForm;
    //     $(newMemberForm).appendTo(rootDiv);
    // }

    // function member_details() {
    //     memberDetails = $('<div>').attr('id', 'pk-member-details');
    //     $(memberDetails).appendTo(rootDiv);
    // }

    // function closeForm() {
    //     $(newMemberForm).css('display', 'none');
    // }

    // function saveForm() {

    // }

    function addBreadingGround() {
        var member = $('<div>').attr('id', 'treeGround');
        $(member).attr('class', 'tree-ground');
        $(member).appendTo(rootDiv);
        treeGround = member;
        //$(treeGround).draggable();
    }

    // function addMemberButton() {
    //     var member = $('<input>').attr('type', 'button');
    //     $(member).attr('value', 'Add Member');
    //     $(member).click(displayForm);
    //     $(member).appendTo(rootDiv);
    // }
    // function displayForm(input) {
    //     $('.relations').css('display', '');
    //     $(newMemberForm).css('display', 'block');
    // }
    function displayPopMenu(input, event) {
        if ($(options_menu).css('display') == 'none') {
            selectedMember = input;
            self = false;
            //$(options_menu).css('display', 'block');
            $(options_menu).css('top', event.clientY);
            $(options_menu).css('left', event.clientX);
        }
    }
    // function closePopMenu(input, event) {
    //     if ($(options_menu).css('display') == 'block') {
    //         $(options_menu).css('display', 'none');
    //     }
    // }
    function displayFirstForm() {
        selectedMember = null;
        self = true;
        $('.relations').css('display', 'none');
        $(newMemberForm).css('display', 'block');
        $('#pk-relation').val('Main');
    }

    function addMember(id, image) {
        var MemberId = id;
        var aLink = $('<a>').attr('href', 'javascript:;');
        var center = $('<center>').appendTo(aLink);
        var div = $('<div class="actions">').appendTo(aLink);
        var pic = '';
        if (image != '') {
            pic = $('<img class="family-img">').attr('src', image);
        } else {
            if (memberGender == 'male') {
                pic = $('<img class="family-img">').attr('src', 'images/userdefault.jpg');
            } else {
                pic = $('<img class="family-img">').attr('src', 'images/female-user.jpg');
            }
        }
        var extraData = memberRelation;

        // if (memberGender == "Male") {
        //     extraData = "(M)";
        // } else {
        //     extraData = "(F)";
        // }

        //readImage(memberPic, pic);

        $(pic).appendTo(center);
        $(center).append($('<br>'));
        $('<span class="name">').html(notAlive + ' ' + memberName).appendTo(center);
        $('<span class="relation">').html(extraData).appendTo(center);
        var btn = $('<button class="addmember" id=' + MemberId + '>').html("<i class='fas fa-plus'></i>").appendTo(div);
        var bttn = $('<button class="removemember" id=' + MemberId + '>').html("<i class='fas fa-minus'></i>").appendTo(div);
        var editBtn = $('<button class="editmember">').html("<i class='fas fa-pen'></i>").appendTo(div);
        // $(bttn).click(function(event) {
        //         removeMember(aLink);
        //     return true;
        // });
        $(btn).click(function(event) {
            //if (event.button == 2) {
            displayPopMenu(aLink, event);
            //return false;
            //}
            return true;
        });
        var li = $('<li>').append(aLink);
        $(aLink).attr('data-id', MemberId);
        $(aLink).attr('data-name', notAlive + ' ' + memberName);
        $(aLink).attr('data-gender', memberGender);
        $(aLink).attr('data-age', memberAge);
        $(aLink).attr('data-relation', memberRelation);
        // $(aLink).mouseenter(function(event) {
        //     //if (event.button == 2) {
        //         displayPopMenu(this, event);
        //         //return false;
        //     //}
        //     return true;
        // });
        var sParent = $(selectedMember).parent(); // super parent

        if (selectedMember != null) {

            var parent = $(sParent).parent();
            var parentParent = $(parent).parent();
            if (memberRelation == 'Mother') {
                var fatherElement = $(parentParent).find("a:first");

                if ($(parentParent).prop('tagName') == 'LI') {
                    //console.log('adding adajecent to father');
                    $(aLink).attr('class', 'spouse');
                    var toPrepend = $(parentParent).find('a:first');
                    $(parentParent).prepend(aLink);
                    $(parentParent).prepend(toPrepend);

                } else {
                    //console.log('adding mother alone');
                    var ul = $('<ul>').append(li);
                    $(parent).appendTo(li);
                    $(parentParent).append(ul);
                }
            }

            if (memberRelation == 'Spouse') {
                $(aLink).attr('class', 'spouse');
                var toPrepend = $(sParent).find('a:first');
                $(sParent).prepend(aLink);
                $(sParent).prepend(toPrepend);
                if ($(".spouse").parent().find('> a').length > 1) {
                    $(".spouse").parent().addClass('spousey');
                }
            }
            if (memberRelation == 'Child') {
                var toAddUL = $(sParent).find('UL:first');
                if ($(toAddUL).prop('tagName') == 'UL') {
                    $(toAddUL).append(li);
                } else {
                    var ul = $('<ul>').append(li);
                    $(sParent).append(ul);
                }

            }
            if (memberRelation == 'Sibling') {
                $(sParent).parent().append(li);

            }
            if (memberRelation == 'Father') {
                var parent = $(sParent).parent();
                var parentParent = $(parent).parent();
                var fatherElement = $(parentParent).find("a:first");

                if ($(parentParent).prop('tagName') == 'LI') {
                    console.log('adding adajecent to mother');
                    $(aLink).attr('class', 'spouse');
                    var toPrepend = $(parentParent).find('a:first');
                    $(parentParent).prepend(aLink);
                    $(parentParent).prepend(toPrepend);

                } else {
                    console.log('adding father alone');
                    var ul = $('<ul>').append(li);
                    $(parent).appendTo(li);
                    $(parentParent).append(ul);
                }
            }
            $(document).find('.tree-ground').find('ul').each(function() {
                if ($(this).find('> li').length >= 2) {
                    $(this).addClass('multi-child');
                } else {
                    $(this).removeClass('multi-child');
                }
            });
        } else {
            var ul = $('<ul>').append(li);
            $(treeGround).append(ul);

        }
        $(document).find('.tree-ground').find('ul').find('a').on({
            mouseenter: function() {
                $(this).find('.actions').css('display', 'block');
            },
            mouseleave: function() {
                $(this).find('.actions').css('display', 'none');
            }
        });
    }

    function createSendURL() {
        rut = $(treeGround).find("ul:first");
        parent = object;
        object = createJson(rut);
        return (JSON.stringify(object));

    }

    function createJson(root) {
        var thisObj = [];
        if ($(root).prop('tagName') == "UL") {
            var item = {};
            var i = 0;
            $(root).children('li').each(function() {
                item["li" + i] = createJson(this);
                i++;
            });
            thisObj.push(item);
            return thisObj;
        }
        if ($(root).prop('tagName') == "LI") {
            var item = {};
            var i = 0;
            $(root).children('a').each(function() {
                var m = "a" + i;
                item[m] = {};
                item[m]["name"] = $(this).attr("data-name");
                item[m]["id"] = $(this).attr("data-id");
                item[m]["age"] = $(this).attr("data-age");
                item[m]["gender"] = $(this).attr("data-gender");

                try {
                    item[m]["relation"] = $(this).attr("data-relation");
                } catch (e) {
                    item[m]["relation"] = "self";
                }
                // if(item[m]["relation"] == ''){
                // 	$(this).attr('class', 'spouse');
                // }
                item[m]["pic"] = $(this).find('img').attr("src");
                //console.log($(this).find('img').attr("src"));
                i++;
            });

            if ($(root).find('ul:first').length) {
                parent = thisObj;
                item["ul"] = createJson($(root).find('ul:first'));
            }
            thisObj.push(item);
            return thisObj;
        }
    }

    // will show existing user info
    function displayData(element) {
        var innerContent = $('<table>');
        var content = '';
        var cross = $('<div>').attr('class', 'pk-cross');
        $(cross).text('X');
        $(cross).click(function() {
            $(memberDetails).css('display', 'none')
        });
        $(memberDetails).empty();
        $(cross).appendTo(memberDetails);
        content = content + '<tr><td>Name</td><td>' + $(element).attr('data-name') + '</td></tr>';
        content = content + '<tr><td>Age</td><td>' + $(element).attr('data-age') + '</td></tr>';
        content = content + '<tr><td>Gender</td><td>' + $(element).attr('data-gender') + '</td></tr>';
        if ($(element).attr('data-relation')) {
            content = content + '<tr><td>Relation</td><td>' + $(element).attr('data-relation') + '</td></tr>';
        } else {
            content = content + '<tr><td>Relation</td><td>Self</td></tr>';
        }
        content = content + '<tr><td colspan="2" style="text-align:center;"><img src="' + $(element).find('img').attr('src') + '"/></td></tr>';
        $(innerContent).html(content);
        $(memberDetails).append(innerContent);
        $(memberDetails).css('display', 'block');
    }

    function readImage(input, pic) {
        var files = $(input).prop('files');
        if (files && files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                console.log(pic);
                $(pic).attr('src', e.target.result);
            }
            reader.readAsDataURL(files[0]);
        }
    }

    function removeMember(member) {
        $(treeGround).find("ul").addClass("js-ul");
        $('.removemember').attr('disabled', true);
        var parentDiv = [];
        //console.log(member.parent().find("a").data('id'));

        $('#remove_member_popup').modal('hide');

        if ($(member).attr('data-relation') == 'Sibling') {

            //CHECK FOR CHILDREN A TAGS
            $(member.parent().find("a")).each(function() {
                //console.log($(this).data('id'));
                parentDiv.push($(this).data('id'));
            });
            $(member).parent().remove();

        } else if ($(member).attr('data-relation') == 'Child') {

            var cLen = $(member).parent().children('li').length;

            //CHECK FOR CHILDREN A TAGS
            $(member.parent().find("a")).each(function() {
                //console.log($(this).data('id'));
                parentDiv.push($(this).data('id'));
            });

            if (cLen > 1)
                $(member).remove();
            else {
                $(member).parent().remove();
            }

        } else if ($(member).attr('data-relation') == 'Father') {
            var child = $(member).children('ul');
            var parent = $(member).parent().parent();
            console.log(parent.parent().children().find('> li:not(:first)').find('a'));
            console.log(parent.parent().children().find('> li:not(:first)').length);

            //GET DIRECT CHILD OF PARENT CLASS
            //console.log(parent.parent().find('> a').length);

            console.log(parent.parent().find('li').length);

            if ($(member).parent().children().length == 2) {

                //REMOVE SIBLING IF ANY AND IT'S CHILD AND SPOUSE
                if (parent.parent().children().find('> li:not(:first)').length > 0) {

                    $(parent.parent().children().find('> li:not(:first)').find('a')).each(function() {
                        parentDiv.push($(this).data('id'));
                    });
                }

                //GET ALL PARENT ELEMENTS WITH </a> TAG
                if (parent.parents().find('> li').find('> a').length > 0) {
                    $(parent.parents().find('> li').find('> a')).each(function() {
                        parentDiv.push($(this).data('id'));
                        if ($(this).data('relation') == 'Sibling' && $(this).parent().children().length >= 1) {
                            // console.log($(this).parent().children().find('a').data('id'));
                            $($(this).parent().children().find('a')).each(function() {
                                parentDiv.push($(this).data('id'));
                            });
                        }
                    });
                }
                // if(parent.parents().find('> a').length > 0){
                //     $(parent.parents().find('> a')).each(function(){
                //         parentDiv.push($(this).data('id'));
                //     });
                //     $(parent.parent().find('> a')).remove();
                // }
                parentDiv.push($(member).data('id'));

                //PREPENDING UL TO TREEGROUND
                $(member).parent().find('ul:first').prependTo(treeGround);

                //REMOVING UNNECESSARY </UL> AND </LI>
                $(treeGround).find('.js-ul').next().remove();

                $(member).remove();
            } else {
                $(member).next().removeClass('spouse');
                $(child).appendTo(parent);
                $(member).remove();
            }

        } else if ($(member).attr('data-relation') == 'Mother') {

            var child = $(member).children('ul');
            var parent = $(member).parent().parent();

            console.log(parent.parent().children().find('> li').find('> a'));

            //GET DIRECT CHILD OF PARENT CLASS
            //console.log(parent.parent().find('> a').length);

            console.log();

            if ($(member).parent().children().length == 2) {

                //REMOVE SIBLING IF ANY AND IT'S CHILD AND SPOUSE
                if (parent.parent().children().find('> li:not(:first)').length > 0) {

                    $(parent.parent().children().find('> li:not(:first)').find('a')).each(function() {
                        parentDiv.push($(this).data('id'));
                    });
                }
                //GET ALL PARENT ELEMENTS WITH </a> TAG
                if (parent.parents().find('> li').find('> a').length > 0) {
                    $(parent.parents().find('> li').find('> a')).each(function() {
                        parentDiv.push($(this).data('id'));
                        if ($(this).data('relation') == 'Sibling' && $(this).parent().children().length >= 1) {
                            //console.log($(this).parent().children().find('a').data('id'));
                            $($(this).parent().children().find('a')).each(function() {
                                parentDiv.push($(this).data('id'));
                            });
                        }
                    });
                }
                // if(parent.parents().find('> a').length > 0){
                //     $(parent.parents().find('> a')).each(function(){
                //         parentDiv.push($(this).data('id'));
                //     });
                //     $(parent.parent().find('> a')).remove();
                // }
                parentDiv.push($(member).data('id'));

                //PREPENDING UL TO TREEGROUND
                $(member).parent().find('ul:first').prependTo(treeGround);

                //REMOVING UNNECESSARY </UL> AND </LI>
                $(treeGround).find('.js-ul').next().remove();

                $(member).remove();
            } else {
                $(member).next().removeClass('spouse');
                $(child).appendTo(parent);
                $(member).remove();
            }

        } else if ($(member).attr('data-relation') == 'Spouse') {
            $(member).remove();
        }
        $(document).find('.tree-ground').find('ul').each(function() {
            if ($(this).find('> li').length >= 2) {
                $(this).addClass('multi-child');
            } else {
                $(this).removeClass('multi-child');
            }
        });
        console.log(parentDiv);
        $('.removemember').attr('disabled', false);
        console.log($(member).attr('data-id'));
        var data = createSendURL();
        data = data.replace(new RegExp(']', 'g'), "");
        data = data.replace(new RegExp('\\[', 'g'), "");

        var formdata = new FormData();
        formdata.append('tree', data);
        formdata.append('ids', parentDiv);
        formdata.append('memberId', $(member).attr('data-id'));
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        jQuery.ajax({
            type: 'POST',
            url: baseUrl + '/remove-family-tree',
            data: formdata,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function(objj) {
                if (objj.code == '1') {
                    console.log("success");
                    $('.family-tree-area').find('.js-ul').each(function() {
                        if ($(this).find('li').length == 0) {
                            $(this).remove();
                        }
                    });
                }
            }
        });
    }
}(jQuery));