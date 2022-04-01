/* GOOGLE LOGIN */
$(document).ready(function() {

   

    //GOOGLE LOGIN/SIGNUP 
    $(document).on('click','#google-button', function(e){  
        //var google_save_data;
        var acToken;
        var tokenType;
        var expiresIn;
        console.log("1");  
        // $('#sign-up-modal').modal('hide');    
        //var that = $(this);
        //var acToken;
        //var win = window.open("https://accounts.google.com/o/oauth2/auth?scope=openid email profile&client_id=365668368155-39jpsmu5eppke7fk10q8b64n0s2nccnm.apps.googleusercontent.com&redirect_uri=http://127.0.0.1:8000/auth/google/callback&response_type=token", 'windowname1', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
        var win = window.open(_url, "windowname1", 'width=800, height=600');
        var pollTimer = window.setInterval(function(){
            var url = win.document.URL;
            try {
                if (url.indexOf(REDIRECT) != -1){
                    console.log('succ - 1');
                    console.log(url);
                    window.clearInterval(pollTimer);
                    var tmp = url.split('&');
                    var tmp_toke = tmp[0].split('access_token=');
                    var tmp_toketype = tmp[1].split('token_type=');
                    var expe = tmp[2].split('expires_in=');
                    acToken =  tmp_toke[1];
                    tokenType = tmp_toketype[1];
                    expiresIn = expe[1];
                    console.log(tmp);
                    //win.close();
                    getUserInfo();
                }else{
                    console.log('succ - 2');
                }
            }catch(e) {
                console.log('fail - 2');
                console.log(e);
            }
            
        },700); 
        
        function getUserInfo() {
            console.log(acToken);
            $.ajax({
                url: 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' + acToken,                
                data: null,
                success: function(resp){
                    google_ajax_call(resp)
                }
            });
        }

        function google_ajax_call(resp) {
            console.log(resp);
            form_data = new FormData();
            form_data.append('email', resp.email);
            // form_data.append('family_name', resp.family_name);
            // form_data.append('given_name', resp.given_name);
            form_data.append('id', resp.id);
            form_data.append('name', resp.name);
           $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    
            $.ajax({
                url: SITE_URL+'/auth/google/callback',
                dataType: 'json',  // what to expect back from the PHP script, if anything
                type: 'POST',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(obj) {
    
                    if (obj.code == 1) {
                        window.location = obj.redirection;
                    }
                    else {
                        $('#user-login-form').closest('.modal-dialog').find('.alert-section').html('<div class="alert alert-danger alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">Ã—</button><h4><i class="zmdi zmdi-block"></i> Error(s):</h4><p>' + obj.error + '</p></div>').addClass('error').show();
                    }
                },
                complete: function(obj){
                    obj=obj.responseJSON;
                    $('#csrf_token').val(obj.csrf_token);
                    $('#csrf_name').val(obj.csrf_name);
                },
            });
        }
    });
        
    });