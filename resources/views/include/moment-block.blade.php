@foreach(@$result as $key=>$row) 
    @php 
    if(@$row->getUserMoment->getUserMomentImage != ''){
        $images = @$row->getUserMoment->getUserMomentImage;     
        $imgaecount = (!empty(@$images)) ? @$images->count() : 0;  
        $remianig_count = 1  ;
        $moreimgclass='';
        $datatext='';
        for($j=1;$j<=$imgaecount-4;$j++){
            if($imgaecount > 4 && $imgaecount-$remianig_count){
                $moreimgclass='more_imgs';
                $datatext=$j;    
            }  
        }
    } else{
        $images = @$row->getUserMoment->getUserMomentImage;     
        $imgaecount = 0;  
        $remianig_count = 1  ;
        $moreimgclass='';
        $datatext='';
        for($j=1;$j<=$imgaecount-4;$j++){
            if($imgaecount > 4 && $imgaecount-$remianig_count){
                $moreimgclass='more_imgs';
                $datatext=$j;    
            }  
        }
    }
    @endphp

    @if(@$title == 'Home')
        <li class="col-md-3 col-sm-4 col-6  col-sm-4 moments_col moments_col_img">
    @else
        <li class="col-lg-3 col-md-3  col-6 moments_col">
    @endif
        <a href="{{ route('moments.information',$row->id) }} " class="moments_box {{$imgaecount==1 ? 'imagewidth' : ''}}" id="parentId">
        <ul class="momentsimg_row">
        @if($imgaecount>0)
                @foreach($images as $k=>$r)
                    @if($imgaecount > 4 &&  $k>2)
                        <li class="momentsimg_col {{$moreimgclass }}">
                            <div class="momentsimg" data-text="+{{$datatext}}">
                                @php
                                    @$ext = explode(".",@$r->file);
                                @endphp
                                @if(@$r->file != '')
                                    @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm' )
                                        <div class="videoboxarea {{$k==3?'plus-sign-play':''}}">
                                        <video ><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_moment_video'),@$r->file) }}"></video>
                                        </div>
                                    @else
                                        <div class="momentsimg momentimageprop" {{ $imgaecount == 1  ? $imgheightwidth : ''}}" style="background-image: url('{{  Common_function::get_s3_file(config('custom_config.s3_moment_thumb'),@$r->file) }}');"></div>
                                    @endif 
                                @else 
                                    <div class="momentsimg momentimageprop" style="background-image:url('{{asset('images/default/moment_default.jpg')}}')">
                                    </div>
                                @endif  
                            </div>
                        </li>
                    @else
                @php
                    $list_img_classes = '';
                        @$ext = explode(".",@$r->file);
                    if(@$ext[1] == 'mp4'){
                        $list_img_class = 'videoboxarea_single';
                        $imgheightwidth='imageheightwidth';
                    } else{
                        $imgheightwidth='imageheightwidth';
                    }
                    
                    if($imgaecount != 1){
                        $list_img_classes = 'momentsimg_col';
                        if($imgaecount == 2){
                            $list_img_classes .= ' momentsimg_col_100';
                        }else if ($imgaecount == 3){
                            if($k == 0){
                                $list_img_classes .= ' momentsimg_col_100';
                            }
                        }
                    }

                @endphp
                <li class="{{$list_img_classes}} {{$imgaecount == 1  ? $imgheightwidth : ''}}">
                    <div class="momentsimg {{ $imgaecount == 1  ? $imgheightwidth : ''}}">
                        @php
                            @$ext = explode(".",@$r->file);
                        @endphp

                        @if(@$r->file != '')
                            @if(@$ext[1] == 'mp4')
                            <div class="{{ $imgaecount == 1 && (@$ext[1]=='mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm') ? $list_img_class : 'videoboxarea'}} ">
                                    <video ><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_moment_video'),@$r->file) }}"></video>
                                </div>
                            @else
                        
                                <div class="momentsimg {{ $imgaecount == 1  ? $imgheightwidth : ''}} momentimageprop " style="background-image: url('{{ Common_function::get_s3_file(config('custom_config.s3_moment_thumb'),@$r->file) }}');" ></div>
                            @endif   
                        
                        @endif  

                    </div>
                </li>
                @endif
                @endforeach
            @else
            <div class="momentsimg momentimageprop" style="background-image:url('{{asset('images/default/moment_default.jpg')}}')">
            </div>
        @endif
        @if(@$row->is_save_as_draft == 1)
            <span class="draft-class-moment">Draft</span>
        @endif
        </ul>
            <div class="moments_text">
                
                <h3 class="main_title titlelimit">{{ @$row->getUserMoment->title}}</h3>
                <div class="time_like">
                    <p> {{ Carbon\Carbon::parse(@$row->getUserMoment->created_at)->diffForHumans()}} </p>
                    <p>
                        <span><i class="fa fa-heart" aria-hidden="true"></i>
                        {{@$row->like_count=='' ? 0 : Common_function::like_comment_count(@$row->like_count)}}</span>
                        <span class="commentspace"><i class="fa fa-comments" aria-hidden="true"></i>
                        {{@$row->comment_count=='' ? 0 : Common_function::like_comment_count(@$row->comment_count) }}</span>
                    </p>
                </div>
            </div> 
        </a>
        
    </li>
    <input type="hidden" id="type_id" value="{{@$row->type_id}}">
@endforeach
