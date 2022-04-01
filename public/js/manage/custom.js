$(document).ready(function() {

    $(document).on('click','.info',function(){
        var changecolor = $(this).closest('tr').removeClass('.change_row_color');
      
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

    $('.hideTr').slideUp();  
    $('[data-toggle="toggle"]').click(function () {  
        $(this).find('i').css( 'transform','rotateZ(0deg)','transition','max-height 0.2s ease-out');
    if ($(this).parents().next(".hideTr").is(':visible')) {  

        $(this).parents().next('.hideTr').slideUp();  
    }  
    else {  
        $(this).parents().next('.hideTr').slideDown();  
        
        $(this).find('i').css( 'transform','rotateZ(90deg)');   
    }  
});  
   
    //DATERANGEPICKER FOR ALL MODULE    
    $('.date-range-filter,.cls-created-at,.cls-completed-at').daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        maxDate: new Date(),
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY',
           
        }
    });

    $('.date-range-filter,.cls-created-at,.cls-completed-at').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $(this).parent().parent('form').submit();
    });
   
    $('.date-range-filter,.cls-created-at,.cls-completed-at').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $(this).parent().parent('form').submit();       
    });  
    
    // $('.date-range-filter,.cls-created-at,.cls-completed-at').on('apply.daterangepicker', function(ev, picker) {
    //     $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    //     $(this).parent().parent('form').submit();
    // });
  
    
    if ($('#editor1').length >= 1) {
        CKEDITOR.replace( 'editor1', {
            allowedContent : true
        } );
    }

    //email check-box show/hide
    $(document).on('click','#is_admin_no',function(){
        $('#to_email_frm_grp').slideUp(); 
    });
    $(document).on('click','#is_admin_yes',function(){
        $('#to_email_frm_grp').slideDown();
        $("#to_email_frm_grp").css("display", "flex");
    });
     

    var timer;
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function(e) {
        
        if (typeof Pace != 'undefined') {
            Pace.restart();
        }
    });
    /*input style */
    $(document).on('click', '.file-input-browse', function() {
        var file = $(this).parent().parent().parent().find('.file-input');
        file.trigger('click');
    });
    $(document).on('change', '.file-input', function() {
        readURL(this);
        $(this).closest('.input-group').find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var fr = new FileReader;
            fr.onload = function(e) {
                var data = fr.result;
                closestImg = $(input).closest('.input-group');
                closestImg = closestImg.find('.file-input-img-span img');
                var node = closestImg.attr('src', e.target.result);
                var image = new Image();
                image.src = data;
                image.onload = function() {
                    /*EXIF.getData(image, function() {
                        var orientation = EXIF.getTag(this, "Orientation");
                        switch (orientation) {
                            case 3:
                                node.css('transform', 'rotate(180deg)');
                                break;
                            case 6:
                                node.css('transform', 'rotate(90deg)');
                                break;
                            case 8:
                                node.css('transform', 'rotate(-90deg)');
                                break;
                        }
                    });*/
                };
                $('.confomation_box').show();
                $('.edit-img .change-link').hide();
            };
            fr.readAsDataURL(input.files[0]);
        }
        else{
            var closestImg = $(input).closest('.input-group');
            closestImg = closestImg.find('.file-input-img-span img');
            closestImg.attr('src', $('#oldPhoto').val());
        }
    }
    // ERROR ALERT
    function errormsg($error, delay) {
        if (typeof delay !== "undefined") {
            // argument passed and not undefined
        } else {
            // argument not passed or undefined
            delay = 0;
        }
        $.notify({
            title: '<strong>Error!</strong>',
            message: $error,
        }, {
            type: 'danger',
            newest_on_top: true,
            animate: {
                enter: 'animated fadeInRight',
                exit: 'animated fadeOutRight'
            },
            z_index: 99999,
            delay: delay,
        });
    }
    // COMMON DELETE
    var currDeleteRowId = '';
    var currDeleteRowThat = '';
    $(document).on('click', '.delete', function() {
        currDeleteRowId = $(this).closest('tr').attr('id').replace('data-', '');
        currDeleteRowThat = $(this);
        var module = $(this).data('module');
        $('#delete_popup').modal('show');
        $('.modal-body').html("<p>Are you sure you want to delete this "+module+"? You cannot recover it back.</p>");
    });
    $('#delete_popup .confirm').on("click", function() 
    {
        var tbl = $("#hdn").val();

        form_data = new FormData();
        form_data.append('id', currDeleteRowId);
        form_data.append('tbl', tbl);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + '/delete',
            success: function(obj) 
            {
                if (obj.code == 1) 
                {
                    if(currDeleteRowThat.closest('.data').hasClass('not-read')) {
                        var count = parseInt($('#contact').text() - 1);
                        if(count > 0){
                            $('#contact').text(count);
                        }
                        else{
                            $('#contact').remove();
                        }
                    }                    
                    currDeleteRowThat.closest('.data').remove();              
                }
                else {
                    errormsg(obj.message, 5000);
                }
            },
            error: function(obj) {
                errormsg(csrf_error);
            },
        });
        $('#delete_popup').modal('hide');
    });

    /******* Added By Komal *******/

        //FOR TOOLTIP VIEW
        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
         });

    /******* End Added By Komal *******/

    //COMMON SWITCH ENABLE/DISABLE
    $(document).on('click', '.switch', function() {
        window.clearTimeout(timer);
        ele = $(this);
        id = $(this).closest('tr').attr('id').replace('data-', '');
        checked = $(ele).find('.switch-radio').removeAttr("checked");
        form_data = new FormData();
        form_data.append('id', id);
        form_data.append('tbl', $("#hdn").val());
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + '/status',
            success: function(obj) {
                if (obj.code == 1) {
                    if ($(ele).hasClass('on')) {
                        $(ele).removeClass('on');
                    } else {
                        $(ele).addClass('on');
                    }
                }
                else {
                    errormsg(obj.message, 5000);
                }
            },
            error: function(obj) {
                errormsg(csrf_error);
            },
        });
    });
    
    

    // ----------- ADDED BY JAYDEEP ---------------------------------

    function incrementValue(id) {
        var currentVal = parseInt($(document).find('#quantity_'+id).val());
        
        if (!isNaN(currentVal)) {
            $(document).find('#quantity_'+id).val(currentVal + 1);
        } else {
            $(document).find('#quantity_'+id).val(0);
        }
    }
    
    function decrementValue(id) {
        var currentVal = parseInt($(document).find('#quantity_'+id).val());
        
        if (!isNaN(currentVal) && currentVal > 0) {
            $(document).find('#quantity_'+id).val(currentVal - 1);
        } else {
            $(document).find('#quantity_'+id).val(0);
        }
    }

    //TO allow only numeric value on keypress 
    function onlyNumeric(evt) {
        var theEvent = evt || window.event;
    
        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
        // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
        }
    } 
/* ADDED BY DEVIKA DATEPICKER*/
    $(function () {
        
          $("#startdate").datepicker({      
           
            maxDate: new Date() ,    
            changeYear: true,
            yearRange: '2001:+0',      
            changeMonth: true,      
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',
              onSelect: function () {
                var dat1 = $('#startdate');           
                var dt2 = $('#enddate');
                
                //FOR CURRENT DATE
                var d = new Date() ;              
                var startDate = $(this).datepicker('getDate');              
                var minDate =  $(this).datepicker('getDate');
                minDate.setDate(minDate.getDate() + 0); 
                var dt2Date = dt2.datepicker('getDate');
                if(dt2Date == null || startDate.getDate() < dt2Date.getDate())
                {
                //dt2.datepicker('setDate', minDate);                
                }            
                dt2.datepicker('option', 'maxDate',d);                        
                dt2.datepicker('option', 'minDate', minDate);          
              }
          });
          $('#enddate').datepicker({        
            maxDate: new Date(),
            changeYear: true,
            yearRange: '2001:+0',      
            changeMonth: true,      
            showMonthAfterYear: true,
            dateFormat: 'dd M, yy',

            onSelect: function () {
                var dat1 = $('#startdate');           
                  var dt2 = $('#enddate');
                  
                  //FOR CURRENT DATE
                  var d = new Date() ;            
                  var enddate = $(this).datepicker('getDate');   
                  var minDate =  $(this).datepicker('getDate');
               
                  minDate.setDate(minDate.getDate() ); 
                  var dt1Date = dt2.datepicker('getDate');
                  console.log(dt1Date); 
                
                  if(dt1Date == null || enddate.getDate() > dt1Date.getDate())
                  {
                    //dt2.datepicker('setDate', minDate);                
                  }            
                  dat1.datepicker('option', 'maxDate',$(this).datepicker('getDate'));
                                
                  console.log(dat1.datepicker('option', 'maxDate',$(this).datepicker('getDate')));
                //   dt2.datepicker('option', 'minDate', );          
              }
            
          });
         
      });
    $(document).on('click','.users_start_date',function(){ 
        $("#startdate").datepicker("show"); 
      });
      $(document).on('click','.users_end_date',function(){ 
        $("#enddate").datepicker("show"); 
      });
    
});
 
