@component('shop::emails.layouts.master')
    <div style="text-align: center;">
        <a href="{{ config('app.url') }}">
            <img src="{{ asset('themes/default/assets/images/logo.svg') }}">
        </a>
    </div>

    <div style="padding: 30px;">
        <div style="font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 34px;">
            <span style="font-weight: bold;">
                {{ __('shop::app.mail.order.heading') }}
            </span> <br>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {{ __('shop::app.mail.payment.dear', ['customer_name' => core()->getConfigData('emails.configure.email_settings.admin_name')]) }}
            </p>
            {{ __('shop::app.mail.payment.request', ['seller_name' => core()->getConfigData('emails.configure.email_settings.admin_name')]) }}
            <p>

            </p>

            <p style="font-size: 16px;color: #5E5E5E;line-height: 24px;">
                {!! __('marketplace::app.mail.sales.order.greeting', [
                    'order_id' => '<a href="' . route('marketplace.account.orders.view', $sellerOrder->order_id) . '" style="color: #0041FF; font-weight: bold;">#' . $sellerOrder->order_id . '</a>',
                    'created_at' => $sellerOrder->created_at
                    ])
                !!}
            </p>
        </div>

        <div style="font-weight: bold;font-size: 20px;color: #242424;line-height: 30px;margin-bottom: 20px !important;">
            {{ __('shop::app.mail.order.summary') }}
        </div>



        <div style="width: 100%;overflow-x: auto;">
            <table style="width: 100%;border-collapse: collapse;text-align: left;">
                <thead>
                    <tr>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.SKU') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.product-name') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.price') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.subtotal') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.tax-percent') }}</th>
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.tax-amount') }}</th>

                        @if ($sellerOrder->discount_amount)
                            <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.discount-amount') }}</th>
                        @endif
                        <th style="font-weight: 700;padding: 12px 10px;background: #f8f9fa;color: #3a3a3a;">{{ __('shop::app.customer.account.order.view.grand-total') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($sellerOrder->items as $sellerOrderItem)
                        <tr>
                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                {{ $sellerOrderItem->item->type == 'configurable' ? $sellerOrderItem->item->child->sku : $sellerOrderItem->item->sku }}
                            </td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">
                                {{ $sellerOrderItem->item->name }}

                                @if (isset($sellerOrderItem->additional['attributes']))
                                    <div class="item-options">

                                        @foreach ($sellerOrderItem->additional['attributes'] as $attribute)
                                            <b>{{ $attribute['attribute_name'] }} : </b>{{ $attribute['option_label'] }}</br>
                                        @endforeach

                                    </div>
                                @endif
                            </td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ core()->formatPrice($sellerOrderItem->item->price, $sellerOrder->order->order_currency_code) }}</td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ core()->formatPrice($sellerOrderItem->item->total, $sellerOrder->order->order_currency_code) }}</td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ number_format($sellerOrderItem->item->tax_percent, 2) }}%</td>

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ core()->formatPrice($sellerOrderItem->item->tax_amount, $sellerOrder->order->order_currency_code) }}</td>

                            @if ($sellerOrder->discount_amount)
                                <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ core()->formatPrice($sellerOrderItem->item->discount_amount, $sellerOrder->order->order_currency_code) }}</td>
                            @endif

                            <td style="padding: 10px;border-bottom: solid 1px #d3d3d3;color: #3a3a3a;vertical-align: top;">{{ core()->formatPrice($sellerOrderItem->item->total + $sellerOrderItem->item->tax_amount, $sellerOrder->order->order_currency_code) }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <div style="font-size: 16px;color: #242424;line-height: 30px;float: right;width: 40%;margin-top: 20px;">
            <div>
                <span>{{ __('shop::app.mail.order.subtotal') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($sellerOrder->sub_total, $sellerOrder->order->order_currency_code) }}
                </span>
            </div>

            <div>
                <span>{{ __('shop::app.mail.order.shipping-handling') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($sellerOrder->shipping_amount, $sellerOrder->order->order_currency_code) }}
                </span>
            </div>

            <div>
                <span>{{ __('shop::app.mail.order.tax') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($sellerOrder->tax_amount, $sellerOrder->order->order_currency_code) }}
                </span>
            </div>

            @if ($sellerOrder->discount_amount)
                <div>
                    <span>{{ __('shop::app.customer.account.order.view.discount-amount') }}</span>
                    <span style="float: right;">
                        {{ core()->formatPrice($sellerOrder->discount_amount, $sellerOrder->order->order_currency_code) }}
                    </span>
                </div>
            @endif

            <div style="font-weight: bold">
                <span>{{ __('shop::app.mail.order.grand-total') }}</span>
                <span style="float: right;">
                    {{ core()->formatPrice($sellerOrder->grand_total, $sellerOrder->order->order_currency_code) }}
                </span>
            </div>
        </div>
    </div>
@endcomponent
