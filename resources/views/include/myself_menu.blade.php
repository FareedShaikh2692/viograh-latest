<?php
$page_name = basename($_SERVER['PHP_SELF']);
?>
<div class="title_btn_row">
     <ul class="tabrow">
        <li  class="{{Request::is('myself') || Request::is('myself/edit')|| Request::is('myself/insert') ?'active':'' }}">
            <a href="{{route('myself.index')}}">About</a>
        </li>
        <li  class="{{Request::is('education') || Request::is('education/edit/*') || Request::is('education/add_education') || Request::is('education/insert') || Request::is('education/information/*') ?'active':'' }}">
            <a href="{{route('education.index')}} ">Education</a>
        </li>
        <li  class="{{Request::is('career') || Request::is('career/edit/*') || Request::is('career/add_career') || Request::is('career/insert') || Request::is('career/information/*') ?'active':'' }}">
            <a href="{{route('career.index')}}">Career</a>
        </li>
        <li   class="{{Request::is('my-networth') || Request::is('assets/edit-asset/*') || Request::is('assets/add-assets') || Request::is('assets/assets-insert') ||  Request::is('assets/asset-information/*') ||Request::is('liability/edit-liabilit/*') || Request::is('liability/add-liability') || Request::is('liability/liability-insert') ||  Request::is('liability/liability-information/*')  ?'active':'' }} ">
            <a   data-link="{{route('asset.index')}}" class="networth" href="{{route('asset.index')}}">Net Worth</a>
          
        </li>
        <li   class="{{Request::is('nominee') || Request::is('nominee/myself_added')  ?'active':'' }}  ">
            <a data-link="{{route('nominee.index')}}"  class="networth "  href="{{route('nominee.index')}}">Nominee</a>
        </li>
     </ul>
</div>          
