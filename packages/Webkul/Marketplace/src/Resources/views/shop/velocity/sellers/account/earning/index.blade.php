@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.earning.title') }}
@endsection

@section('content')

    <div class="account-layout dashboard right m10">

        <div class="account-head mb-10">
            <span class="account-heading">
                {{ __('marketplace::app.shop.sellers.account.earning.title') }}
            </span>

            <div class="account-action">

            </div>

            <div class="horizontal-rule"></div>
        </div>

        {!! view_render_event('marketplace.sellers.account.earning.before') !!}

        <div class="account-items-list" style="margin-top: 40px;">

            <div class="">
                <earning-filter></earning-filter>
            </div>

            <div class="graph-stats">

                <div class="card">
                    <div class="card-title" style="margin-bottom: 30px;">
                        {{ __('admin::app.dashboard.sales') }}
                    </div>

                    <div class="card-info">

                        <canvas id="earningChart" style="width: 100%; height: 87%"></canvas>

                    </div>
                </div>

            </div>

            <div class="mt-5">
                <div class="table">
                    <div class="grid-container">
                        <table class="table display" id="earningTable">
                            <thead>
                              <tr style="height: 65px">
                                <th class="grid_head" >Interval</th>
                                <th class="grid_head" >Orders</th>
                                <th class="grid_head" >Total Amount</th>
                                <th class="grid_head" >Total Earning</th>
                                <th class="grid_head" >Total Discount</th>
                                <th class="grid_head" >Admin Commission</th>
                              </tr>
                            </thead>
                            <tbody>

                                @foreach ($statistics['sale_graph']['label'] as $key => $label)
                                @if ($statistics['sale_graph']['total'][$key] == 0)
                                    <?php continue; ?>
                                @endif
                                    <tr>
                                        <td data-value="Interval">{{$label}}</td>
                                        <td data-value="Orders">{{$statistics['sale_graph']['orders'][$key]}}</td>
                                        <td data-value="Total Amount">{{$statistics['sale_graph']['total'][$key]}}</td>
                                        <td data-value="Total Earning">{{$statistics['sale_graph']['total_earning'][$key]}}</td>
                                        <td data-value="Total Discount">{{$statistics['sale_graph']['discount'][$key]}}</td>
                                        <td data-value="Admin comission">{{$statistics['sale_graph']['commission'][$key]}}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>

        {!! view_render_event('marketplace.sellers.account.earning.after') !!}

    </div>

@endsection

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js" integrity="sha512-Wt1bJGtlnMtGP0dqNFH1xlkLBNpEodaiQ8ZN5JLA5wpc1sUlk/O5uuOMNgvzddzkpvZ9GLyYNa8w2s7rqiTk5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
        let stateCheck = setInterval(() => {
            if (document.readyState === 'complete') {
                console.log('jhj')
                clearInterval(stateCheck);
                var ctx = document.getElementById("earningChart").getContext('2d');

                var data = @json($statistics['sale_graph']);

                var earningChart = new Chart(ctx, {
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
            }
        }, 100);
</script>

<script type="text/x-template" id="earning-filter-template">
    <form action="" enctype="multipart/form-data" method="get"  @submit.prevent="applyFilter($event)">
        <div class="wk-mp-design">

        <div class="control-group">
            <label class="required" for="period">Period:</label>
            <select name="period" id="period" v-model="period" class="form-style" aria-required="true">
                <option @if (request()->get('period') == 'day') selected @endif value="day">Day</option>
                <option @if (request()->get('period') == 'month') selected @endif value="month">Month</option>
                <option @if (request()->get('period') == 'year') selected @endif value="year">Year</option>
            </select>
        </div>

        <div class="control-group date">
            <label for="start_date" class="required mandatory">start_date</label>
            <date ><input type="text" class="form-style" id="start_date" value="{{ $startDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.from') }}" v-model="start"/></date>
        </div>

        <div class="control-group date">
            <label for="end_date" class="required mandatory">end_date</label>
            <date ><input type="text" class="form-style" id="end_date" value="{{ $endDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.to') }}" v-model="end"/></date>
        </div>

        <div class="wk-mp-page-title mt-3" id="wk-mp-earning-form">
            <button class="btn theme-btn" title="Show Report" type="submit" id="save-btn"><span><span>Show Report</span>
            </button>
        </div>
    </form>
</script>

    <script>
        Vue.component('earning-filter', {

            template: '#earning-filter-template',

            data: () => ({
                start: "{{ $startDate->format('Y-m-d') }}",
                end: "{{ $endDate->format('Y-m-d') }}",
                period: "day"
            }),

            methods: {
                applyFilter(event) {
                    window.location.href = "?start=" + this.start + '&end=' + this.end + "&period=" + this.period;
                }
            }
        });




    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>

        $(document).ready( function () {

            $('#earningTable').dataTable( {
                "oLanguage": {
                "sLengthMenu": 'Items Per Page <select>'+
                    '<option value="10">10</option>'+
                    '<option value="20">20</option>'+
                    '<option value="30">30</option>'+
                    '<option value="40">40</option>'+
                    '<option value="50">50</option>'+
                    '</select> '
                }
            } );

            const paginator  = document.querySelector('#earningTable_paginate');
            paginator.setAttribute('class','pagination');
            paginator.children[0].classList.add('page-item');
            paginator.children[2].classList.add('page-item')

        } );


    </script>

@endpush