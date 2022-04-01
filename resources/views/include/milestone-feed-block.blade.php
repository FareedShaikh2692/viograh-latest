@foreach($results as $key=>$row)
    <li class="col-lg-3 col-md-3 col-sm-4  col-6 viograf_col">
        <a href="{{ route('milestone.information',@$row->feed_id) }}" class="inner_diary_box">
                @php
                    @$ext = explode(".",@$row->file);
                @endphp
                @if (!empty(@$row->file))
                    @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm' )

                        <div class="viografbox_img">
                            <video><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_milestone_video'),$row->file) }}"></video>
                        </div>
                    @else
                        <div class="viografbox_img feedImage" style="background-image:url('{{ Common_function::get_s3_file(config('custom_config.s3_milestone_thumb'),$row->file) }}')">
                        </div>
                    @endif
                @else
                    <div class="viografbox_img feedImage" style="background-image:url('{{asset('images/default/milestone_default.jpg')}}')">
                    </div>
                @endif
                @if(@$row->getUserMilestoneFeed->is_save_as_draft == 1)
                    <span class="draft-class">Draft</span>
                @endif
            <div class="viografbox_text">
                <h3>{{ @$row->title }}</h3>
                <div class="time_like time_feed">
                    <p>{{date("jS M, Y", strtotime(@$row->achieve_date))}}</p>
                    <p>
                        <span><i class="fa fa-heart" aria-hidden="true"></i>
                        {{Common_function::like_comment_count(@$row->getUserMilestoneFeed->like_count)}}</span>
                        <span class="" ><i class="fa fa-comments" aria-hidden="true"></i>
                        {{Common_function::like_comment_count(@$row->getUserMilestoneFeed->comment_count)}}</span>
                    </p>
                </div>
            </div>
        </a>
    </li>
@endforeach