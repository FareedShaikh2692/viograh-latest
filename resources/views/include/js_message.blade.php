<script>
    var SITE_URL = "{{url('')}}";    
    var TIME_ZONE = "{{env('TIME_ZONE')}}";
    var csrf_error = "{{ __('custom.csrf_error') }}";
    var js_required = "{{ __('custom.js_required') }}";
    var js_email = "{{ __('custom.js_email') }}";
    var js_pass_min = "{{ __('custom.js_pass_min') }}";
    var js_pass_max = "{{ __('custom.js_pass_max') }}";
    var js_pass_regex = "{{ __('custom.password_regex') }}";
    var js_pass_mis_match = "{{ __('custom.js_pass_mis_match') }}";
    var js_min_lengths = "{{ __('custom.js_min_lengths') }}";
    var js_max_lengths = "{{ __('custom.js_max_lengths') }}";
    var js_number = "{{ __('custom.js_number') }}";
    var js_filesize = "{{ __('custom.js_filesize') }}";
    var js_docsize = "{{ __('custom.js_docsize') }}";
    var js_alphaonly = "{{ __('custom.js_alphaonly') }}";
    var js_address = "{{ __('custom.js_address') }}";
    var js_alpha_comma = "{{ __('custom.js_alpha_comma') }}";
    var js_url = "{{ __('custom.js_url') }}";
    // var js_min_num = "{{ __('custom.js_min_num') }}";
    // var js_max_num = "{{ __('custom.js_max_num') }}";
    var js_admin_donateable_amount = "{{config('custom_config.settings.indirect_donation_amount') - config('custom_config.settings.indirect_donation_paid_amount')}}";
    var counter_draft =  300;
    var remain_draft_counter = 10;
</script>