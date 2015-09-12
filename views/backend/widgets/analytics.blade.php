@inject('analytics', 'Whole\Core\Injections\AnalyticsInjection')

@if ($analytics->todayAndYesterday() && $analytics->lastWeek() && $analytics->thisWeek())

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2" style="height:110px;">
            <div class="display">
                <div class="number">
                    <small>Ziyaretçi Sayısı</small>
                    <h3 class="font-green-sharp">{{ $analytics->todayAndYesterday()[1]['visitors'] }}</h3>
                    <small>Bugün</small>
                </div>
                <div class="icon">
                    <i class="icon-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2" style="height:110px;">
            <div class="display">
                <div class="number">
                    <small>Sayfa Gösterİmİ</small>
                    <h3 class="font-green-sharp">{{ $analytics->todayAndYesterday()[1]['pageViews'] }}</h3>
                    <small>Bugün</small>
                </div>
                <div class="icon">
                    <i class="icon-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2" style="height:110px;">
            <div class="display">
                <div class="number">
                    <small>Ziyaretçi Sayısı</small>
                    <h3 class="font-green-sharp">{{ $analytics->todayAndYesterday()[0]['visitors'] }}</h3>
                    <small>Dün</small>
                </div>
                <div class="icon">
                    <i class="icon-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat2" style="height:110px;">
            <div class="display">
                <div class="number">
                    <small>Sayfa Gösterİmİ</small>
                    <h3 class="font-green-sharp">{{ $analytics->todayAndYesterday()[0]['pageViews'] }}</h3>
                    <small>Dün</small>
                </div>
                <div class="icon">
                    <i class="icon-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-7 col-lg-7" style="height:545px;">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light" style="height: 100%;">
            <div class="portlet-title">
                <div class="caption font-green-haze" style="width: 100%;">
                    <i class="icon-users font-green-haze"></i>
                    <span class="caption-subject bold uppercase">Haftalık ZİyaretÇİ İstatİstİĞİ</span>
                </div>
            </div>
            <div class="portlet-body">
                <div id="weekly_visitors" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>
    </div>


@section('include_js')
    @parent
    {!! Html::script('assets/backend/global/plugins/highcharts/js/highcharts.js') !!}
    {!! Html::script('assets/backend/global/plugins/highcharts/js/modules/exporting.js') !!}
@endsection


@section('footer')
    @parent
    <script type="text/javascript">
        $(function () {
            $('#weekly_visitors').highcharts({
                chart: {
                    type: 'area'
                },
                title: {
                    text: 'Haftalık Ziyaretçi İstatistiği'
                },
                xAxis: {
                    categories: [
                        @foreach($analytics->lastWeek() as $k=>$visitors)
                        @if((count($analytics->lastWeek())-($k+1))==0) "{!! day_localize($visitors['date']->format('l'),'en','tr') !!} ( Bugün )"
                        @elseif((count($analytics->lastWeek())-($k+1))==1) "{!! day_localize($visitors['date']->format('l'),'en','tr') !!} ( Dün )"
                        @else
                    "{!! day_localize($visitors['date']->format('l'),"en","tr") !!}"
                        @endif
                        ,
                        @endforeach
                ]
                },
                yAxis: {
                    title: {
                        text: 'Ziyaretçi Sayısı'
                    }
                },
                tooltip: {
                    headerFormat: '{series.name}<br />{point.key} ',
                    pointFormat: ' günü ziyaretçi sayısı <b>{point.y:,.0f}</b>'
                },
                plotOptions: {
                    area: {
                        marker: {
                            enabled: false,
                            symbol: 'circle',
                            radius: 2,
                            states: {
                                hover: {
                                    enabled: true
                                }
                            }
                        }
                    }
                },
                series: [
                    {
                        name: 'Bu Hafta',
                        data:[
                            @foreach($analytics->thisWeek() as $visitors)
                            {{ $visitors['visitors']."," }}
                            @endforeach
                            ]
                    },
                    {
                        name: 'Geçen Hafta',
                        data:[
                            @foreach($analytics->lastWeek() as $visitors)
                            {{ $visitors['visitors']."," }}
                            @endforeach
                            ]
                    }
                ]
            });
        });
    </script>
@endsection

@endif