@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.dashboard.title') }}
@endsection

@section('content')

    <div class="account-layout dashboard right m10">

        <div class="account-head mb-10">
            <span class="account-heading">
                {{ __('marketplace::app.shop.sellers.account.dashboard.title') }}
            </span>

            <div class="account-action">
                <date-filter></date-filter>
            </div>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('marketplace.sellers.account.dashboard.before') !!}

        <div class="account-items-list" style="margin-top: 40px;">

            <div class="dashboard-stats">

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.total-orders') }}
                    </div>

                    <div class="data">
                        {{ $statistics['total_orders']['current'] }}

                        <span class="progress">
                            @if ($statistics['total_orders']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['total_orders']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['total_orders']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.total-sale') }}
                    </div>
                    @php  $currentCurrencyCode = core()->getCurrentCurrencyCode(); @endphp
                    <div class="data">
                        {{ core()->formatPrice($statistics['total_sales']['current'],$currentCurrencyCode ) }}

                        <span class="progress">
                            @if ($statistics['total_sales']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['total_sales']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['total_sales']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.average-sale') }}
                    </div>

                    <div class="data">
                        {{ core()->formatPrice($statistics['avg_sales']['current'], $currentCurrencyCode) }}

                        <span class="progress">
                            @if ($statistics['avg_sales']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('marketplace::app.shop.dashboard.total-payout') }}
                    </div>

                    <div class="data">
                        {{ core()->formatPrice($statistics['seller_payout']['total_payout'], $currentCurrencyCode) }}

                        {{-- <span class="progress">
                            @if ($statistics['avg_sales']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @endif --}}
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('marketplace::app.shop.dashboard.remaining-payout') }}
                    </div>

                    <div class="data">
                        {{ core()->formatPrice($statistics['seller_payout']['remaining_payout'], $currentCurrencyCode) }}

                        {{-- <span class="progress">
                            @if ($statistics['avg_sales']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @endif --}}
                        </span>
                    </div>
                </div>

            </div>

            <div class="graph-stats">
                <div class="card">
                    <div class="card-title" style="margin-bottom: 30px;">
                        {{ __('marketplace::app.shop.sellers.account.dashboard.sales-by-location') }}
                    </div>

                    <div class="card-info">
                        <div id="myMap" style="position: relative; width:100% ; height: 87%;"></div>
                    </div>
                </div>
            </div>

            <div class="graph-stats">

                <div class="card">
                    <div class="card-title" style="margin-bottom: 30px;">
                        {{ __('admin::app.dashboard.sales') }}
                    </div>

                    <div class="card-info">

                        <canvas id="myChart" style="width: 100%; height: 87%"></canvas>

                    </div>
                </div>

            </div>

            <div class="sale-stock">
                <div class="card">
                    <div class="card-title">
                        {{ __('admin::app.dashboard.top-selling-products') }}
                    </div>

                    <div class="card-info {{ !count($statistics['top_selling_products']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['top_selling_products'] as $item)
                            <?php
                                $getProductImageData = app('Webkul\Product\Repositories\ProductImageRepository')->where('product_id', $item->product_id)->get();

                                $productImage = $getProductImageData->toArray()

                            ?>
                                <li>
                                    <a href="{{ route('marketplace.account.products.edit', $item->product_id) }}">
                                        <div class="product image">
                                            @if (isset($item->path))
                                                <img class="item-image" src="{{bagisto_asset('storage/' . $item->path)}}" />
                                            @elseif (! empty($productImage))
                                                <img class="item-image" src="{{bagisto_asset('storage/' . $productImage[0]['path'])}}" />
                                            @endif
                                        </div>

                                        <div class="description">
                                            <div class="name">
                                                {{ $item->name }}
                                            </div>

                                            <div class="info">
                                                {{ __('admin::app.dashboard.sale-count', ['count' => $item->total_qty_ordered]) }}
                                            </div>
                                        </div>
                                    </a>
                                </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['top_selling_products']))

                            <div class="no-result-found">

                                <i class="icon no-result-icon"></i>
                                <p>{{ __('admin::app.common.no-result-found') }}</p>

                            </div>

                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">
                        {{ __('admin::app.dashboard.customer-with-most-sales') }}
                    </div>

                    <div class="card-info {{ !count($statistics['customer_with_most_sales']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['customer_with_most_sales'] as $item)

                                <li>
                                    <div class="image">
                                        <span class="icon profile-pic-icon"></span>
                                    </div>

                                    <div class="description">
                                        <div class="name">
                                            {{ $item->customer_full_name }}
                                        </div>

                                        <div class="info">
                                            {{ __('admin::app.dashboard.order-count', ['count' => $item->total_orders]) }}
                                                &nbsp;.&nbsp;
                                            {{ __('admin::app.dashboard.revenue', [
                                                'total' => core()->formatBasePrice($item->total_base_grand_total)
                                                ])
                                            }}
                                        </div>
                                    </div>
                                </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['customer_with_most_sales']))

                            <div class="no-result-found">

                                <i class="icon no-result-icon"></i>
                                <p>{{ __('admin::app.common.no-result-found') }}</p>

                            </div>

                        @endif
                    </div>

                </div>

                <div class="card">
                    <div class="card-title">
                        {{ __('admin::app.dashboard.stock-threshold') }}
                    </div>

                    <div class="card-info {{ !count($statistics['stock_threshold']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['stock_threshold'] as $item)
                                <li>
                                    <a href="{{ route('marketplace.account.products.edit', $item->product_id) }}">
                                        <div class="image">
                                            <?php $productBaseImage = productimage()->getProductBaseImage($item->product); ?>

                                            <img class="item-image" src="{{ $productBaseImage['small_image_url'] }}" />
                                        </div>

                                        <div class="description">
                                            <div class="name">
                                                {{ $item->product->name }}
                                            </div>

                                            <div class="info">
                                                {{ __('admin::app.dashboard.qty-left', ['qty' => $item->total_qty]) }}
                                            </div>
                                        </div>
                                    </a>
                                </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['stock_threshold']))

                            <div class="no-result-found">

                                <i class="icon no-result-icon"></i>
                                <p>{{ __('admin::app.common.no-result-found') }}</p>

                            </div>

                        @endif
                    </div>

                </div>
            </div>

        </div>

        {!! view_render_event('marketplace.sellers.account.dashboard.after') !!}

    </div>

@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js" integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.3/d3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datamaps/0.5.8/datamaps.all.js"></script>


<script>
$(document).ready(function () {
    var orders = {!! str_replace("'", "\'", $mapOrdersArray) !!}

    console.log(orders);

    // example data from server
    var series = [];

    orders.forEach(order => {
        series.push([order.country, order.seller_order_count, order.seller_total])
    });

    // Datamaps expect data in format:
    // { "USA": { "fillColor": "#42a844", numberOfWhatever: 75},
    //   "FRA": { "fillColor": "#8dc386", numberOfWhatever: 43 } }
    var dataset = {};

    // We need to colorize every country based on "numberOfWhatever"
    // colors should be uniq for every value.
    // For this purpose we create palette(using min/max series-value)
    var onlyValues = series.map(function(obj){ return obj[1]; });
    var minValue = Math.min.apply(null, onlyValues),
            maxValue = Math.max.apply(null, onlyValues);

    // create color palette function
    // color can be whatever you wish
    var paletteScale = d3.scale.linear()
            .domain([minValue,maxValue])
            .range(["#EFEFFF","#02386F"]); // blue color

    // fill dataset in appropriate format
    series.forEach(function(item){ //
        // item example value ["USA", 70]
        var iso = item[0],
                value = item[1];
        dataset[iso] = { numberOfThings: value, fillColor: paletteScale(value), sellerTotal: item[2] };
    });
    // console.log(dataset);
    // render map
   const map = new Datamap({
        element: document.getElementById('myMap'),
        responsive:true,
        projection: 'mercator', // big world map
        // countries don't listed in dataset will be painted with this color
        fills: { defaultFill: '#F5F5F5' },
        data: dataset,
        geographyConfig: {
            borderColor: '#DEDEDE',
            highlightBorderWidth: 2,

            // don't change color on mouse hover
            highlightFillColor: function(geo) {
                return geo['fillColor'] || '#F5F5F5';
            },
            // only change border
            highlightBorderColor: '#B7B7B7',
            // show desired information in tooltip
            popupTemplate: function(geo, data) {
                return ['<div class="hoverinfo">',
                    '<strong>', geo.properties.name, '</strong>',
                    '<br>Orders: <strong>', data.numberOfThings, '</strong>',
                    '<br>Total: <strong>', data.sellerTotal, '</strong>',
                    '</div>'].join('');
            }
        }
    });
    // console.log(map)
});
</script>

    <script type="text/x-template" id="date-filter-template">
        <div>
            <div class="control-group date">
                <date @onChange="applyFilter('start', $event)"><input type="text" class="control" id="start_date" value="{{ $startDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.from') }}" v-model="start"/></date>
            </div>

            <div class="control-group date">
                <date @onChange="applyFilter('end', $event)"><input type="text" class="control" id="end_date" value="{{ $endDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.to') }}" v-model="end"/></date>
            </div>
        </div>
    </script>

    <script>
        Vue.component('date-filter', {

            template: '#date-filter-template',

            data: () => ({
                start: "{{ $startDate->format('Y-m-d') }}",
                end: "{{ $endDate->format('Y-m-d') }}",
            }),

            methods: {
                applyFilter(field, date) {
                    this[field] = date;

                    window.location.href = "?start=" + this.start + '&end=' + this.end;
                }
            }
        });

        $(document).ready(function () {




            var ctx = document.getElementById("myChart").getContext('2d');

            var data = @json($statistics['sale_graph']);

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data['label'],
                    datasets: [{
                        data: data['total'],
                        backgroundColor: 'rgba(34, 201, 93, 1)',
                        borderColor: 'rgba(34, 201, 93, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            maxBarThickness: 20,
                            gridLines : {
                                display : false,
                                drawBorder: false,
                            },
                            ticks: {
                                beginAtZero: true,
                                fontColor: 'rgba(162, 162, 162, 1)'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                drawBorder: false,
                            },
                            ticks: {
                                padding: 20,
                                beginAtZero: true,
                                fontColor: 'rgba(162, 162, 162, 1)'
                            }
                        }]
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        displayColors: false,
                        callbacks: {
                            label: function(tooltipItem, dataTemp) {
                                return data['formated_total'][tooltipItem.index];
                            }
                        }
                    }
                }
            });
        });
    </script>

@endpush