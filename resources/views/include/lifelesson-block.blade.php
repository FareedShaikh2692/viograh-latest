@foreach($results as $key=>$row)
    <li class="col-lg-3 col-md-3 col-sm-4  col-6 viograf_col">
        <a href="{{ url('/lifelesson/information/'.@$row->id) }}" class="inner_diary_box">
                @php
                    @$ext = explode(".",@$row->getUserLifeLesson->file);
                @endphp
                @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                    <div class="viografbox_img">
                        <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_lifelesson_video'),$row->getUserLifeLesson->file) }}"></video>
                    </div>
                @else
                    <div class="viografbox_img feedImage" style="background-image:url('{{@$row->getUserLifeLesson->file ? Common_function::get_s3_file(config('custom_config.s3_lifelesson_thumb'),@$row->getUserLifeLesson->file) : url('images/default/lifelesson_default.jpg')}}')">
                    </div>
                @endif 
                @if(@$row->is_save_as_draft == 1)
                    <span class="draft-class">Draft</span>
                @endif
            <div class="viografbox_text">
                <h3>{{ @$row->getUserLifeLesson->title }}</h3>
                <div class="time_like">
                    <p>{{Common_function::get_time_ago(strtotime(@$row->created_at))}}</p>
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