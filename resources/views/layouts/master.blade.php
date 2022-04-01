<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="google-site-verification" content="kli5NbmdAxXdPEAlFHBpAH_vXQUh5_aMbuAskudS0oo" />
    <title>{{config('custom_config.settings.site_name')}} | {{@$title}}</title>
    @yield('metasection')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>VioGraf</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" rel="stylesheet" /> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    
    <link href="{{ asset('css/tree.css')}}" rel="stylesheet" />
    <link href="{{ asset('css/style.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/cropper.min.css') }}" > <!-- CSS FOR CROPPER -->
    @yield('font-style')
    
    <script src="{{ asset('js/jquery-1.12.0.min.js') }}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script> -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{ asset('js/validate.min.js') }}"></script>
    <script src="{{ asset('js/additional-methods.js') }}"></script>
    <script src="{{ asset('js/manage/moment.min.js') }}"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

   

    <script>
        $(".js-example-responsive").select2({
            width: 'resolve',
            minimumResultsForSearch: -1
        });
        </script>   
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>
  <script src="{{ asset('js/ps-family.js') }}"></script>
  
  <script src="{{ asset('js/validate_init.js').'?v='.config('custom_config.css_js_version')  }}"></script>
  <!-- @yield('cropper_js') -->
      <!-- Cropper JS -->  
    <script src="{{ asset('js/cropper.min.js') }}"></script>
    <!-- Cropper JS END -->
  <script src="{{ asset('js/custom.js').'?v='.config('custom_config.css_js_version')   }}"></script>
    
  @include("include.js_message")
</head>
<body>
    
    @yield('content')
    
    @yield('auth_js')
    
</body>
</html>