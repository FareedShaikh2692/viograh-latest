@foreach(@$results as $key=>$row) 
    <li class="col-lg-3 col-md-3 col-sm-4  col-6 viograf_col">
        <a href="{{ url('/education/information/'.$row->id) }}" class="inner_diary_box">
                        @php
                            @$ext = explode(".",@$row->getUserEducation->file);                            
                        @endphp
                        
                        @if(!empty(@$row->getUserEducation->file))
                            @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                            <div class="viografbox_img">
                                <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_education_video'),@$row->getUserEducation->file) }}"></video>
                            </div>
                            @else                            
                                <div class="viografbox_img feedImage" style="background-image:url({{ Common_function::get_s3_file(config('custom_config.s3_education_thumb'),@$row->getUserEducation->file) }});">
                                </div>
                            @endif 
                        @else
                            <div class="viografbox_img feedImage" style="background-image:url('{{asset('images/default/education_default.jpg')}}')">
                            </div>
                        @endif  
            <div class="viografbox_text">
                <h3>{{ @$row->getUserEducation->name }}</h3>
                <div class="time_like">
                    <p>{{Common_function::get_time_ago(strtotime($row->created_at))}}</p>
                    <p>
                        <span><i class="fa fa-heart" aria-hidden="true"></i>
                        {{@$row->like_count}}</span>
                        <span><i class="fa fa-comments" aria-hidden="true"></i>
                        {{@$row->comment_count}}</span>
                    </p>
                </div>
            </div>
        </a>
    </li>
@endforeach