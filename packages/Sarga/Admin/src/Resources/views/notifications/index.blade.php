@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.notification.notification-title') }}
@endsection

@php
    $orderStatus = [
        'all' => trans('admin::app.notification.status.all'),
        'pending' => trans('admin::app.notification.status.pending'),
        'canceled'=> trans('admin::app.notification.status.canceled'),
        'closed' => trans('admin::app.notification.status.closed'),
        'completed'=> trans('admin::app.notification.status.completed'),
        'processing' => trans('admin::app.notification.status.processing')
    ];

    $orderStatusMessages = [
        'pending' => trans('admin::app.notification.order-status-messages.pending'),
        'canceled'=> trans('admin::app.notification.order-status-messages.canceled'),
        'closed' => trans('admin::app.notification.order-status-messages.closed'),
        'completed'=> trans('admin::app.notification.order-status-messages.completed'),
        'processing' => trans('admin::app.notification.order-status-messages.processing')
    ];
@endphp

@push('css')
    <style>
        .sr-only{
            display:none;
        }
        .notif li {
            display: flex;
            justify-content: space-between;
        }
        .pagination .page-item {
            height: 40px !important;
            width: 40px !important;
            margin-right: 5px;
            font-size: 16px;
            display: inline-block;
            color: #3a3a3a;
            vertical-align: middle;
            text-decoration: none;
            text-align: center;
        }

        .page-item .pagination-page-nav .active .page-link {
            color:#fff !important;
        }
    </style>
@endpush

@section('content')
    <bildiriler
        url="{{ route('admin.notification.get-notification') }}"
        order-delete-url="{{ route('admin.notification.delete-notification') }}"
        order-view-url="{{ \URL::to('/') }}/{{ config('app.admin_url')}}/viewed-notifications/"
        pusher-key="{{ env('PUSHER_APP_KEY') }}"
        pusher-cluster="{{ env('PUSHER_APP_CLUSTER') }}"
        locale-code={{ core()->getCurrentLocale()->code }}>
    </bildiriler>
@endsection

@push('scripts')
    <script type="text/x-template" id="bildiriler-template">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.notification.notification-title') }}</h1>
                </div>
                <div class="page-action">
                    <div class="control-group notif-filter">
                        <input type="text" class="form-control control" placeholder="Search Order" @keyup="applyFilter('search',$event)">
                        <i class="icon search-icon search-btn"></i>
                    </div>
                    <div class="control-group notif-filter">
                        <select @change="applyFilter('filter',$event)" class="control">
                            <option v-for="orderstatus in orderTypeStatus" :value="orderstatus">@{{orderstatus}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="page-content">

                <ul class="notif" v-if="notifications.length > 0" >
                    <li v-for="notification in notifications" :key="notification.id" :class="notification.read ? 'read' : ''">
                        <div style="display: none">
                            <span hidden>@{{ moment.locale(localeCode) }}</span>
                        </div>

                        <a :href="`${orderViewUrl + notification.order_id}`">
                            <div class="notif-icon" :class="notification.order.status">
                                <span :class="ordertype[notification.order.status].icon"></span>
                            </div>

                            <div class="notif-content">
                                #@{{ notification.order.id + ' ' + orderTypeMessages[notification.order.status]}}
                            </div>

                            <div class="notif-content">
                                @{{ moment(notification.order.created_at).fromNow() }}
                            </div>
                        </a>
                        <a :href="`${orderDeleteUrl + '?id='+notification.order_id}`"><span class="icon trash-icon"></span></a>
                    </li>
                </ul>
                <pagination align="center" :data="pagNotif" @pagination-change-page="getResults">
                    <span slot="prev-nav">&lt;</span>
                    <span slot="next-nav">&gt;</span>
                </pagination>
                <ul class="notif" v-if="notifications.length == 0">
                    <li>{{ __('admin::app.notification.no-record') }}</li>
                </ul>

            </div>
        </div>
    </script>
    <script>

        Vue.component('bildiriler', {
            template: '#bildiriler-template',
            props: [
                'url',
                'orderViewUrl',
                'orderDeleteUrl',
                'pusherKey',
                'pusherCluster',
                'localeCode'
            ],

            data: function() {
                return {
                    notifications: [],
                    pagNotif: {},
                    id: '',
                    status: '',
                    ordertype: {
                        pending : {
                            icon: 'pending-icon',
                            message: 'Order Pending'
                        },
                        processing : {
                            icon: 'processing-icon',
                            message: 'Order Processing'
                        },
                        canceled : {
                            icon: 'canceled-icon',
                            message: 'Order Canceled'
                        },
                        completed : {
                            icon: 'completed-icon',
                            message: 'Order Completed'
                        },
                        closed : {
                            icon: 'closed-icon',
                            message: 'Order Closed'
                        },
                    },
                    orderTypeStatus: @json($orderStatus),
                    orderTypeMessages: @json($orderStatusMessages)
                }
            },

            mounted() {

                this.getNotification();

                if(Echo){
                    Echo.channel('notification').listen('.create-notification', (e) => {
                        this.getNotification();
                    });

                    Echo.channel('notification').listen('.update-notification', (e) => {
                        this.notifications.forEach((notification)=>{
                            if(notification.order_id == e.id){
                                notification.order.status = e.status;
                            }
                        });
                    });
                }
            },

            methods: {
                async getNotification() {

                    const params = {};

                    if(this.id){
                        params.id = this.id;
                    }

                    if(this.status){
                        params.status = this.status;
                    }

                    let this_this = this;

                    await this.$http.get(this_this.url, {params: params})
                        .then (function(response) {
                            this_this.notifications = [];

                            this_this.notifications = response.data.search_results.data;
                            this_this.pagNotif = response.data.search_results;
                        }).catch (function (error) {})
                },
                applyFilter: function(type,$event){
                    type == 'search' ? this.id = $event.target.value : this.status = $event.target.value;

                    this.getNotification();
                },
                getResults(page  = 1){
                    let this_this = this;
                    axios.get(`${this.url}?page=${page}`)
                        .then(response => {
                            this_this.notifications = [];

                            this_this.notifications = response.data.search_results.data;
                            this_this.pagNotif = response.data.search_results;
                        });
                }
            }
        })
    </script>
@endpush