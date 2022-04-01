
    <script src="{{ asset('js/jquery-1.12.0.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{ asset('js/manage/validate.min.js') }}"></script>
    <script src="{{ asset('js/manage/moment.min.js') }}"></script>
    <script src="{{ asset('js/manage/validate_init.js').'?v='.config('custom_config.css_js_version')  }}"></script>
    @include("include.js_message")
    <script>
        $(function () {
            $("#datepicker").datepicker();
        });
        $(document).on('ready', function () {
            $(".education-imgs-lider").show();
            $(".education-imgs-lider").slick({
                dots: false,
                pauseOnHover: false,
                infinite: true,
                arrows: true,
                slidesToShow: 1,
                fade: true,
                cssEase: 'linear',
                autoplay: false,
                speed: 100,
                autoplaySpeed: 1000,
                slidesToScroll: 1
            });
        });
        $(document).ready(function () {

            $("#startdate").datepicker({
                dateFormat: "dd-M-yy",
                minDate: 0,
                onSelect: function () {
                    var dt2 = $('#enddate');
                    var startDate = $(this).datepicker('getDate');
                    var minDate = $(this).datepicker('getDate');
                    var dt2Date = dt2.datepicker('getDate');
                    //difference in days. 86400 seconds in day, 1000 ms in second
                    var dateDiff = (dt2Date - minDate) / (86400 * 1000);

                    startDate.setDate(startDate.getDate() + 30);
                    if (dt2Date == null || dateDiff < 0) {
                        dt2.datepicker('setDate', minDate);
                    }
                    else if (dateDiff > 30) {
                        dt2.datepicker('setDate', startDate);
                    }
                    //sets dt2 maxDate to the last day of 30 days window
                    dt2.datepicker('option', 'maxDate', startDate);
                    dt2.datepicker('option', 'minDate', minDate);
                }
            });
            $('#enddate').datepicker({
                dateFormat: "dd-M-yy",
                minDate: 0
            });
        });
    </script>
    
    <script>
        function previewMultiple(event) {
            var saida = document.getElementById("adicionafoto");
            var quantos = saida.files.length;
            for (i = 0; i < quantos; i++) {
                var urls = URL.createObjectURL(event.target.files[i]);
                //   document.getElementById("galeria").innerHTML += '<li class="addimg"> <img src="'+urls+'" alt="Vio Graf" /> </li>';
                $(document).find('.addimgs_area').append('<li class="addimg"> <button class="close-btn"> <img src="images/icons/upload_file_close.svg" alt="Vio Graf" /></button> <img src="' + urls + '" alt="Vio Graf" /> </li>');
            }
        }  
    </script>
      <script>
        $(function () {
            $("#assets").progressbar({
                value: 80
            });
        });
        $(function () {
            $("#liabilities").progressbar({
                value: 45
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
    <script>
        $('.ui.dropdown').dropdown('');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
    <script src="{{ asset('js/custom.js') }}"></script>