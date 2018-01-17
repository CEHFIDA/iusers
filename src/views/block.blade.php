@push('scripts')
    <script src="{{ asset('vendor/adminamazing/assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <script>
        $(function(){
            var sparklineLogin2 = function() { 
                $('#sparklinedash2').sparkline([ {{$imploade_data}}], {
                    type: 'bar',
                    height: '100',
                    barWidth: '4',
                    resize: true,
                    barSpacing: '5',
                    barColor: '#55ce63'
                });
            }
            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineLogin2, 500);
            });
            sparklineLogin2();
        })
    </script>
@endpush
<div style="margin-top: 1.25rem;">
    <div class="row">
        <div class="col-8">
            <span class="display-6">{{$users_today}} 
                @if($users_yesterday >= $users_today)
                    <i class="ti-angle-down font-14 text-danger"></i>
                @else
                    <i class="ti-angle-up font-14 text-success"></i>
                @endif
            </span>
            <h6>Пользователей сегодня</h6>
        </div>
        <div class="col-4 align-self-center text-right p-l-0">
            <div id="sparklinedash2"></div>
        </div>
    </div>
</div>