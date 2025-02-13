@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.catalog.products.assing-edit-title') }}
@endsection

@section('content')

    <div class="account-layout right m10">

        <form method="POST" action="" enctype="multipart/form-data" @submit.prevent="onSubmit" class="account-table-content">

            <div class="account-head mb-10">
                <span class="account-heading">
                    {{ __('marketplace::app.shop.sellers.account.catalog.products.assing-edit-title') }}
                </span>

                <div class="account-action">
                    <button type="submit" class="theme-btn">
                        {{ __('marketplace::app.shop.sellers.account.catalog.products.save-btn-title') }}
                    </button>
                </div>

                <div class="horizontal-rule"></div>
            </div>

            {!! view_render_event('marketplace.sellers.account.catalog.product.edit-assign.before') !!}

            <div class="account-table-content">

                @csrf()

                <div class="product-information">

                    <div class="product-image">
                        <img src="{{ asset($product->product->base_image_url ?? 'vendor/webkul/ui/assets/images/product/meduim-product-placeholder.png') }}"/>
                    </div>

                    <div class="product-details">
                        <div class="product-name">
                            <a href="{{ url()->to('/').'/products/'.$product->product->url_key }}" target="_blank" title="{{ $product->product->name }}">
                                <span>
                                    {{ $product->product->name }}
                                </span>
                            </a>
                        </div>

                        @include ('shop::products.price', ['product' => $product->product])
                    </div>

                </div>

                <input name="_method" type="hidden" value="PUT">

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.catalog.products.general') }}'" :active="true">
                    <div slot="body">

                        <div class="control-group" :class="[errors.has('condition') ? 'has-error' : '']">
                            <label for="condition" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.catalog.products.product-condition') }}</label>

                            <?php $selectedOption = old('condition') ?: $product->condition ?>

                            <select class="control" v-validate="'required'" id="condition" name="condition" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.catalog.products.product-condition') }}&quot;">
                                <option value="new" {{ $selectedOption == 'new' ? 'selected' : '' }}>{{ __('marketplace::app.shop.sellers.account.catalog.products.new') }}</option>
                                <option value="old" {{ $selectedOption == 'old' ? 'selected' : '' }}>{{ __('marketplace::app.shop.sellers.account.catalog.products.old') }}</option>
                            </select>
                            <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('price') ? 'has-error' : '']">
                            <label for="price" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.catalog.products.price') }}</label>
                            <input type="text" v-validate="'required'" class="control" id="price" name="price" value="{{ old('price') ?: $product->price }}" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.catalog.products.price') }}&quot;" {{ $product->product->type == 'configurable' ? 'disabled' : '' }}/>
                            <span class="control-error" v-if="errors.has('price')">@{{ errors.first('price') }}</span>
                        </div>

                        <div class="control-group form-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="description" class="required mandatory">{{ __('marketplace::app.shop.sellers.account.catalog.products.description') }}</label>
                            <textarea v-validate="'required'" class="control" id="description" name="description" data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.account.catalog.products.description') }}&quot;">{{ old('description') ?: $product->description }}</textarea>
                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                    </div>
                </accordian>

                <accordian :title="'{{ __('marketplace::app.shop.sellers.account.catalog.products.images') }}'" :active="true">
                    <div slot="body">

                        <image-wrapper :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'" input-name="images" :images='@json($product->images)'></image-wrapper>

                    </div>
                </accordian>

                @include ('marketplace::shop.sellers.account.catalog.products.accordians.assign-videos')

                @if ($product->product->type != 'configurable' && $product->product->type != 'downloadable')
                    <accordian :title="'{{ __('marketplace::app.shop.sellers.account.catalog.products.inventory') }}'" :active="true">
                        <div slot="body">

                            @foreach ($inventorySources as $inventorySource)

                                <?php

                                    $qty = 0;
                                    foreach ($product->product->inventories as $inventory) {
                                        if ($inventory->inventory_source_id == $inventorySource->id && $inventory->vendor_id == $product->marketplace_seller_id) {
                                            $qty = $inventory->qty;
                                            break;
                                        }
                                    }

                                    $qty = old('inventories[' . $inventorySource->id . ']') ?: $qty;

                                ?>

                                <div class="control-group" :class="[errors.has('inventories[{{ $inventorySource->id }}]') ? 'has-error' : '']">
                                    <label>{{ $inventorySource->name }}</label>

                                    <input type="text" v-validate="'numeric|min:0'" name="inventories[{{ $inventorySource->id }}]" class="control" value="{{ $qty }}" data-vv-as="&quot;{{ $inventorySource->name }}&quot;"/>

                                    <span class="control-error" v-if="errors.has('inventories[{{ $inventorySource->id }}]')">@{{ errors.first('inventories[{!! $inventorySource->id !!}]') }}</span>
                                </div>

                            @endforeach

                        </div>
                    </accordian>
                @endif
                @if ($product->product->type == 'downloadable')
                    @include('marketplace::shop.velocity.sellers.account.catalog.products.accordians.downloadable', ['product' => $product])
                @endif
                @if ($product->product->type == 'configurable')
                    <accordian :title="'{{ __('marketplace::app.shop.sellers.account.catalog.products.variations') }}'" :active="true">
                        <div slot="body">

                            <variant-list></variant-list>

                        </div>
                    </accordian>
                @endif

            </div>

            {!! view_render_event('marketplace.sellers.account.catalog.product.edit-assign.after') !!}

        </form>

    </div>

@endsection

@if ($product->product->type == 'configurable')
@push('scripts')
    @parent

    <script type="text/x-template" id="variant-list-template">
        <div class="table" style="margin-top: 20px; overflow-x: unset;">
            <table>

                <thead>
                    <tr>
                        <th class=""></th>

                        <th>{{ __('admin::app.catalog.products.name') }}</th>

                        <th class="qty">{{ __('admin::app.catalog.products.qty') }}</th>

                        @foreach ($product->product->super_attributes as $attribute)
                            <th class="{{ $attribute->code }}" style="width: 150px">{{ $attribute->admin_name }}</th>
                        @endforeach

                        <th class="price" style="width: 100px;">{{ __('admin::app.catalog.products.price') }}</th>
                    </tr>
                </thead>

                <tbody>

                    <variant-item v-for='(variant, index) in variants' :variant="variant" :key="index" :index="index"></variant-item>

                </tbody>

            </table>
        </div>
    </script>

    <script type="text/x-template" id="variant-item-template">
        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" :id="variant.id" name="selected_variants[]" :value="variant.id" v-model="selected_variant">
                    <label :for="variant.id" class="checkbox-view"></label>
                </span>
            </td>

            <td data-value="{{ __('admin::app.catalog.products.name') }}">
                @{{ variant.name }}
            </td>

            <td data-value="{{ __('admin::app.catalog.products.qty') }}">
                <button style="width: 100%;" type="button" class="dropdown-btn dropdown-toggle" :disabled="!selected_variant">
                    @{{ totalQty }}
                    <i class="icon arrow-down-icon"></i>
                </button>

                <div class="dropdown-list">
                    <div class="dropdown-container">
                        <ul>
                            <li v-for='(inventorySource, index) in inventorySources'>
                                <div class="control-group" :class="[errors.has(variantInputName + '[inventories][' + inventorySource.id + ']') ? 'has-error' : '']">
                                    <label>@{{ inventorySource.name }}</label>
                                    <input type="text" v-validate="'numeric|min:0'" :name="[variantInputName + '[inventories][' + inventorySource.id + ']']" v-model="inventories[inventorySource.id]" class="control" v-on:keyup="updateTotalQty()" :data-vv-as="'&quot;' + inventorySource.name  + '&quot;'"/>
                                    <span class="control-error" v-if="errors.has(variantInputName + '[inventories][' + inventorySource.id + ']')">@{{ errors.first(variantInputName + '[inventories][' + inventorySource.id + ']') }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </td>

            <td v-for='(attribute, index) in superAttributes' :data-value="attribute.admin_name">
                @{{ optionName(variant[attribute.code]) }}
            </td>

            <td data-value="{{ __('admin::app.catalog.products.price') }}">
                <div class="control-group" :class="[errors.has(variantInputName + '[price]') ? 'has-error' : '']">
                    <input type="text" v-validate="'required'" :name="[variantInputName + '[price]']" class="control" :value="price" data-vv-as="&quot;{{ __('admin::app.catalog.products.price') }}&quot;" :disabled="!selected_variant"/>
                    <span class="control-error" v-if="errors.has(variantInputName + '[price]')">@{{ errors.first(variantInputName + '[price]') }}</span>
                </div>
            </td>
        </tr>
    </script>

    <script>
        var super_attributes = @json(app('\Webkul\Product\Repositories\ProductRepository')->getSuperAttributes($product->product));
        var variants = @json($product->product->variants()->with(['inventories'])->get());
        var assignVariants = @json($product->variants);

        Vue.component('variant-list', {

            template: '#variant-list-template',

            inject: ['$validator'],

            data: () => ({
                variants: variants,
                assignVariants: assignVariants,
                superAttributes: super_attributes
            }),

            created () {
                this_this = this;

                this.variants.forEach(function(variant) {
                    this_this.assignVariants.forEach(function(assignVariant) {
                        if (variant.id == assignVariant.product_id) {
                            variant.assignVariant = assignVariant;
                        }
                    });
                });
            },
        });

        Vue.component('variant-item', {

            template: '#variant-item-template',

            props: ['index', 'variant'],

            inject: ['$validator'],

            data: () => ({
                inventorySources: @json($inventorySources),
                inventories: {},
                totalQty: 0,
                superAttributes: super_attributes,
                selected_variant: false
            }),

            created () {
                var this_this = this;
                this.inventorySources.forEach(function(inventorySource) {
                    this_this.inventories[inventorySource.id] = this_this.sourceInventoryQty(inventorySource.id)
                    this_this.totalQty += parseInt(this_this.inventories[inventorySource.id]);
                })

                if (this.variant.assignVariant) {
                    this.selected_variant = this.variant.assignVariant.id;
                }
            },

            computed: {
                variantInputName () {
                    return "variants[" + this.variant.id + "]";
                },

                price () {
                    if (this.variant.assignVariant) {
                        return this.variant.assignVariant.price;
                    }

                    return 0;
                }
            },

            methods: {
                optionName (optionId) {
                    var optionName = '';

                    this.superAttributes.forEach(function(attribute) {
                        attribute.options.forEach(function(option) {
                            if (optionId == option.id) {
                                optionName = option.admin_name;
                            }
                        });
                    })

                    return optionName;
                },

                sourceInventoryQty (inventorySourceId) {
                    var inventories = this.variant.inventories.filter(function(inventory) {
                        return inventorySourceId === inventory.inventory_source_id && inventory.vendor_id == "{{ $product->marketplace_seller_id }}";
                    })

                    if (inventories.length)
                        return inventories[0]['qty'];

                    return 0;
                },

                updateTotalQty () {
                    this.totalQty = 0;
                    for (var key in this.inventories) {
                        this.totalQty += parseInt(this.inventories[key]);
                    }
                }
            }

        });
    </script>
@endpush
@endif