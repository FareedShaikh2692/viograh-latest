@foreach(@$results as $key=>$row)
    <li class="search-col">
        <a href="{{route('public-profile.index',@$row->id)}}" class="search-box" target="_blank">
            <div class="search-img">
                <img src="{{@$row->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),@$row->profile_image) : url('images/userdefault-1.svg')}}" class="img-fluid" alt="VioGraf"/> 
            </div>
            <div class="search-detail">
                @if(@$row->first_name != '' || @$row->last_name != '')
                    <p>{{@$row->first_name}} {{@$row->last_name}}</p>
                @endif
                <span>
                    <i><img src="images/icons/location.svg" class="img-fluid" alt="VioGraf"/></i>
                    {{@$row->places_lived ? @$row->places_lived : '-'}}
                </span>
                
            </div>
        </a>
    </li>          
@endforeach
