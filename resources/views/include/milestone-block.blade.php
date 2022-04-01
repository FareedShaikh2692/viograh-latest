@foreach(@$results as $key=>$row) 
    <li class="milestonebox viograf_col">
        <div class="milestone-col maindetail ">
            <a href="{{route('milestone.information',$row->feed_id)}}">
                <div class="milestone-detail">
                    @php
                        @$ext = explode(".",@$row->file);
                    @endphp
                    @if(@$ext[1] == 'mp4' || @$ext[1] == 'ogv' || @$ext[1] == 'webm')
                        <div class="mile-img viografbox_img milestoneimage">
                            <video class="milestone-video"><source id="videoSource"/ src="{{ Common_function::get_s3_file(config('custom_config.s3_milestone_video'),@$row->file) }}"></video>
                        </div>
                    @else
                        <div class="mile-img viografbox_img milestoneimage" style="background-image:url('{{@$row->file ? Common_function::get_s3_file(config('custom_config.s3_milestone_thumb'),@$row->file) : url('images/default/milestone_default.jpg')}}')">
                        </div>
                    @endif  
                    @if(@$row->getUserMilestoneFeed->is_save_as_draft == 1)
                    <span class="draft-class">Draft</span>
                    @endif
                    <div class="mile-col viografbox_text">
                        <h3>{{ @$row->title }}</h3>
                        <p>{{ @$row->description }}</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="milestone-col">
            <div class="milestone-date">
                @php
                    if (@$row->achieve_date != ''){
                        $fulldate=@$row->achieve_date; 
                        $explodedate=explode('-',$fulldate);
                        $year=@$explodedate[0];
                        $date=@$explodedate[2];
                        $month=DateTime::createFromFormat('!m', @$explodedate[1]);
                        $monthname=$month->format('F');
                    }
                    else{
                        $year = '-';
                        $date = '-';
                        $monthname = '-';
                    }
                    
                   
                @endphp
                <h4> 
                    {{$year}}</h4>
                <p><b>{{$date}}</b> {{$monthname}}</p>
            </div>
        </div>
    </li>
@endforeach