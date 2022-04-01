<?php 
$page_name =  basename($_SERVER['PHP_SELF']);
?>
<div class="title_btn_row">
     <ul class="tabrow">
        <li  class="{{Request::is('nominee')  ?'active':'' }}">
            <a href="{{route('nominee.index')}}">Nominees added by me</a>
        </li>
        <li  class="{{Request::is('nominee/myself_added')  ?'active':'' }}">
            <a href="{{route('nominee.myself_added')}} ">Where I am added as nominee</a>
        </li>
       
       
     </ul>
</div>          

