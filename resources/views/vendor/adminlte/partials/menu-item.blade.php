@php
        $permission  = config('custom_config.admin_module_permission');
        $user = Auth::guard('manage')->user();
        $curClass = isset($item['module']) ? $item['module'] : '';
        $curMethod = 'index';
        $curUserType = $user->admin_type;
        if($curClass == 'home'){$curClass = 'dashboard';}
@endphp

@if ((!isset($item['topnav']) || (isset($item['topnav']) && !$item['topnav'])) && (!isset($item['topnav_right']) || (isset($item['topnav_right']) && !$item['topnav_right'])) && (!isset($item['topnav_user']) || (isset($item['topnav_user']) && !$item['topnav_user'])))
    @if (is_string($item))
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-header">{{ $item }}</li>
    @elseif (isset($item['header']))
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-header">{{ $item['header'] }}</li>
    @elseif (isset($item['search']) && $item['search'])
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif>
            <form action="{{ $item['href'] }}" method="{{ $item['method'] }}" class="form-inline">
              <div class="input-group">
                <input class="form-control form-control-sidebar" type="search" name="{{ $item['input_name'] }}" placeholder="{{ $item['text'] }}" aria-label="{{ $item['aria-label'] ?? $item['text'] }}">
                <div class="input-group-append">
                  <button class="btn btn-sidebar" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
        </li>
    @else
        @if(key_exists($curMethod,$permission[$curClass]) && in_array($curUserType,$permission[$curClass][$curMethod]))
        <li @if (isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-item @if (isset($item['submenu'])){{ $item['submenu_class'] }}@endif">
            <a class="nav-link {{ $item['class'] }} @if(isset($item['shift'])) {{ $item['shift'] }} @endif" href="{{ $item['href'] }}"
               @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
            >
                <i class="{{ $item['icon'] ?? 'far fa-fw fa-circle' }} {{ isset($item['icon_color']) ? 'text-' . $item['icon_color'] : '' }}"></i>
                <p>
                    {{ $item['text'] }}

                <?php 
                
                    $contact_inquiry_counter = DB::table('contact_us')->where([['is_read','=',0],['status','=','enable']])->count();
                    $item['label_inquiry'] = $contact_inquiry_counter;
                
                ?>
                @if (isset($item['label_inquiry']) && $item['text'] == 'Contact Us' && $item['label_inquiry']!=0)
                    <span class="pull-right-container" style="margin-left:85px;">
                        <span class="badge badge-light" id="contact">{{ $item['label_inquiry'] }}</span>
                    </span>
                @elseif (isset($item['submenu']))
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                @endif
                </p>
                
            </a>
            @if (isset($item['submenu']))
                <ul class="nav nav-treeview">
                    @each('adminlte::partials.menu-item', $item['submenu'], 'item')
                </ul>
            @endif
        </li>
        @endif
    @endif
@endif
