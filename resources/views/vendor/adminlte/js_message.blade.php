<script>
    var SITE_URL = "{{url('')}}";
    var TIME_ZONE = "{{env('TIME_ZONE')}}";
    var REDIRECT    =   "{{url('auth/google/callback')}}";
    var _url = "https://accounts.google.com/o/oauth2/auth?scope=openid email profile&client_id={{config('services.google.client_id')}}&redirect_uri={{url('auth/google/callback')}}&response_type=token"; 
    var csrf_error = "{{ __('adminlte::custom.csrf_error') }}";
    var js_required = "{{ __('adminlte::custom.js_required') }}";
    var js_email = "{{ __('adminlte::custom.js_email') }}";
    var js_pass_min = "{{ __('adminlte::custom.js_pass_min') }}";
    var js_pass_max = "{{ __('adminlte::custom.js_pass_max') }}";
    var js_pass_mis_match = "{{ __('adminlte::custom.js_pass_mis_match') }}";
    var js_min_lengths = "{{ __('adminlte::custom.js_min_lengths') }}";
    var js_max_lengths = "{{ __('adminlte::custom.js_max_lengths') }}";
    var js_number = "{{ __('adminlte::custom.js_number') }}";
    var js_filesize = "{{ __('adminlte::adminlte.js_filesize') }}";
    var js_admin_donateable_amount = parseFloat("{{config('custom_config.settings.indirect_donation_amount') - config('custom_config.settings.indirect_donation_paid_amount')}}");
</script>