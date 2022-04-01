@foreach($results as $key=>$row)
    <li class="col-lg-3 col-md-3 col-sm-4  col-6 viograf_col">
        <a href="{{ url('/last/information/'.$row->id) }}" class="inner_diary_box">
                @php
                    @$ext = explode(".",@$row->getUserLast->file);  
                @endphp
                @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                    <div class="viografbox_img">
                        <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_last_video'),$row->getUserLast->file) }}"></video>
                    </div>
                @else
                    <div class="viografbox_img feedImage" style="background-image:url('{{@$row->getUserLast->file ? Common_function::get_s3_file(config('custom_config.s3_last_thumb'),@$row->getUserLast->file) : url('images/default/firstlast_default.jpg')}}')">
                    </div>
                @endif
                @if(@$row->is_save_as_draft == 1)
                    <span class="draft-class">Draft</span>
                @endif
            <div class="viografbox_text">
                <h3>{{ @$row->getUserLast->title }}</h3>
                <div class="time_like">
                    <p>{{Common_function::get_time_ago(strtotime($row->created_at))}}</p>
                    <p>
                        <span><i class="fa fa-heart" aria-hidden="true"></i>
                        {{Common_function::like_comment_count(@$row->like_count)}}</span>
                        <span><i class="fa fa-comments" aria-hidden="true"></i>
                        {{Common_function::like_comment_count(@$row->comment_count)}}</span>
                    </p>
                </div>
            </div>
        </a>
    </li>
@endforeach