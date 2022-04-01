@extends('layouts.page')
@section('title', @$title)
@section('pageContent')

    <div class="inner-family-tree sectionbox">
        <div class="title_btn_row">
            <div class="create_title">
                <h1 class="main_title">Family Tree</h1>
            </div>
            <div class="createnew">
                <a href="{{route('familylist.index')}}" class="pink_btn">Go to List View</a>
            </div>
        </div>

        <div class="family-tree-area">
            <div id="pk-family-tree"></div>
        </div>
        
    </div>

    <script>
        $('#pk-family-tree').pk_family();
        $('#pk-family-tree').pk_family_create(
        {
            <?php if(@$results->getFamilyTreeUser->tree == ''){?>

                data: '{"li0":{"a0":{"name":"<?php echo Auth::user()->first_name; ?>","id":"<?php echo @$results->id; ?>","relation":"Self","pic":"<?php echo @Auth::user()->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),  @Auth::user()->profile_image) : url('images/userdefault-1.svg') ?>"}}}'

            <?php } else{?>

                data: '<?php echo $results->getFamilyTreeUser->tree;?>'

            <?php }?>
        });

        var MyPic = "<?php echo @Auth::user()->profile_image ? Common_function::get_s3_file(config('custom_config.s3_user_thumb'),  @Auth::user()->profile_image) : url('images/userdefault-1.svg') ?>";

        console.log($("[data-relation='Self']").find('img').attr('src'));

        $("[data-relation='Self']").find('img').attr("src","");
        $("[data-relation='Self']").find('img').attr("src",MyPic);
        if($(".spouse").parent().find('> a').length > 1){
            $(".spouse").parent().addClass('spousey');
        }


        $(document).find('.tree-ground').find('ul').find('a').on({
            mouseenter: function () {
                $(this).find('.actions').css('display','block');
            },
            mouseleave: function () {
                $(this).find('.actions').css('display','none');
            }
        });

        $(document).find('.tree-ground').find('ul').each(function() {
            if($(this).find('> li').length >= 2){
                $(this).addClass('multi-child');
            }
        });
    </script>
@stop