@foreach($results as $key=>$row)
    <li class="col-lg-3 col-md-4 col-6 family-feedsec-box viograf_col family-feed-col">
        @php
            $type_info = Common_function::feed_info_home($row->type_id,@$row->family_feed->type); 
        @endphp
        
        <a href="{{ $type_info.$row->id }}" class="inner_family_feed_box">
            <div class="family-feedsec-imgsarea">
                <div class="familyimg  img-fluid " >
                    @php
                        if(@$row->type_id == 29){
                            $ext = explode(".",@$row->getUserMoment->getUserMomentImage[0]->file);
                        }
                        else{
                            $ext = explode(".",@$row->family_feed->file);
                        }
                    @endphp
                    @if (!empty(@$row->family_feed->file || !empty(@$row->getUserMoment->getUserMomentImage[0]->file)))  
                        @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                       
                             @if(@$row->type_id == 23)
                                <div class="homeimge"> 
                                    <video style="height:222px;width:100%; object-fit:cover;background-size: cover;background-position: center;"><source id="videoSource"/ src="{{ Common_function::feed_images_home(@$row->type_id,$row->family_feed->file,'video',@$row->family_feed->type) }}"></video>
                                </div>
                            @elseif(@$row->type_id == 29)
                                <div class="homeimge"> 
                                    <video style="height:222px;width:100%; object-fit:cover;background-size: cover;background-position: center;"><source id="videoSource"/ src="{{ Common_function::feed_images_home(@$row->type_id,@$row->getUserMoment->getUserMomentImage[0]->file,'video','') }}"></video>
                                </div>
                            @else   
                                <div class="homeimge"> 
                                    <video style="height:222px;width:100%; object-fit:cover;background-size: cover;background-position: center;"><source id="videoSource"/ src="{{ Common_function::feed_images_home(@$row->type_id,$row->family_feed->file,'video','') }}"></video>
                                </div>
                            @endif
                        @else
                            @if(@$row->type_id == 23)
                                <div class=" img-fluid homeimge" style="background-image:url('{{  Common_function::feed_images_home(@$row->type_id,$row->family_feed->file,'file',@$row->family_feed->type) }}'); height:222px;width:100%;background-size: cover;background-position: center; "></div>
                            @elseif(@$row->type_id == 29)
                                <div class=" img-fluid homeimge" style="background-image:url('{{  Common_function::feed_images_home(@$row->type_id,@$row->getUserMoment->getUserMomentImage[0]->file,'file','') }}'); height:222px;width:100%;background-size: cover;background-position: center; "></div>
                            @else
                                <div class=" img-fluid homeimge" style="background-image:url('{{  Common_function::feed_images_home(@$row->type_id,@$row->family_feed->file,'file','') }}'); height:222px;width:100%;background-size: cover;background-position: center; ">
                                </div>
                            @endif
                          
                        @endif
                    @else   
                        <div class="familyimg  homeimge  img-fluid" style="background-image:url('{{ Common_function::feed_images_home($row->type_id,'','') }}'); height:222px;width:100%;background-size: cover;background-position: center;">
                        </div>
                    @endif
                </div>
                <div class="family-nameiimgara">
                    <div>
                        <button class="btn_web cls_feed_{{ @$row->type_id }}">{{ ($row->type_id == '23' && @$row->family_feed->type == 'first') ? 'First' : (($row->type_id == '23' && @$row->family_feed->type == 'last') ? 'Last' :  config('custom_config.user_feed_type_name')[$row->type_id])  }}</button>
                    </div>
                    <div class="family-nameiimgbox">
                   
                        <span class="{{ (@$title == 'Home' ? 'home-familyfeed-span-img' : 'family-feed-span-img') }} publicinfo" data-link="{{route('public-profile.index',@$row->getUser->id)}}">
                            <img src="{{ @$row->getUser->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_big'),  @$row->getUser->profile_image) : url('images/userdefault-1.svg') }}" alt="Family Feed" class="img-fluid familyfeed-img" />
                        </span>
                            <p class="publicinfo name-feed" data-link="{{route('public-profile.index',@$row->getUser->id)}}">{{@$row->getUser->first_name.' '.@$row->getUser->last_name}} </p>
                      
                    </div>
                </div>
            </div>
            <div class="family-feedsec-detailarea">
          
                <h3 class="feedtext">{{   ((@$row->type_id == 2) ? @$row->family_feed->objective : ((@$row->type_id == 18) ? @$row->family_feed->name : ((@$row->type_id == 26) ? @$row->family_feed->practice : ((@$row->type_id == 19) ? @$row->family_feed->name : @$row->family_feed->title )))) }}</h3>
                <div class="time_linkbox">
                    <p>{{Common_function::get_time_ago(strtotime(@$row->family_feed->created_at))}}</p>
                    <p>
                        <span><i class="fa fa-heart" aria-hidden="true"></i> {{@$row->like_count}}</span>
                        <span><i class="fa fa-comments" aria-hidden="true"></i> {{@$row->comment_count}}</span>
                    </p>
                </div>
            </div>
        </a>
    </li>
@endforeach