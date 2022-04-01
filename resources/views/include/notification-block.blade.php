@foreach($results as $key=>$row)   
    <li class="viograf_col inner_notifications_area">
        @php
            if(@$row->type_id == 23){
                $type_info=Common_function::feed_info_home($row->type_id,@$row->UserFeedNotification->family_feed->type);
                
            } else if(@$row->type_id == 0){
                $type_info_nominee='/nominee'; 
            } else{
                $type_info=Common_function::feed_info_home($row->type_id,$row->type);
            }
            if(@$row->type_id == 39){
                @endphp
                    <a href="javascript:;" class="inner_notifications_box inner_wishlistbox">
                @php
            }else{
                @endphp
                    <a href="{{ (@$row->type_id != 0) ? $type_info.$row->unique_id : $type_info_nominee}}" class="inner_notifications_box inner_wishlistbox">
            @php
            }
            @endphp
            <div class="notifications_icon">
                <img src="{{@$row->UserInfo->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),@$row->UserInfo->profile_image) : url('images/userdefault-1.svg')}}" class="img-fluid" alt="Notifications icon"/>
            </div>
            <div class="notifications_text">
                <p><b class="public-info" data-link="{{route('public-profile.index',@$row->UserInfo->id)}}">{{@$row->UserInfo->first_name}} {{$row->UserInfo->last_name}}</b>{{' '.@$row->message}}</p>
                <i>
                    {{ Carbon\Carbon::parse(@$row->created_at)->diffForHumans()}} 
                </i>
            </div>
        </a>
    </li>
@endforeach