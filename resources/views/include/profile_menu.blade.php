<?php 
$page_name =  basename($_SERVER['PHP_SELF']);
?>
<div class="title_btn_row">
     <ul class="tabrow">
        <li  class="{{Request::is('profile') || Request::is('profile/edit') ?'active':'' }}">
            <a href="{{route('profile.index')}}">Profile</a>
        </li>
        <li class="{{ Request::is('privacy-settings') ?'active':'' }}">
            <a href="{{route('privacy.index')}}">Privacy Settings</a>
        </li>
        <li class="{{ Request::is('change-password') ?'active':'' }}">
            <a href="{{route('changepass.index')}}">Change Password</a>
        </li>
     </ul>
</div>


