@php $brands = app(Sarga\Brand\Repositories\BrandRepository::class)->actives(); @endphp
<div class="control-group" :class="[errors.has('brand_id') ? 'has-error' : '']">
    <label for="brand_id" class="required">{{ __('brand::app.brand') }}</label>
    <select class="control" v-validate="'required'" id="brand_id" name="brand_id" data-vv-as="&quot;{{ __('brand::app.brand') }}&quot;">
        <option value="">Select</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ $product->brand_id ==  $brand->id ? 'selected' : '' }}>
                {{  $brand->name }}
            </option>
        @endforeach

    </select>

    <span class="control-error" v-if="errors.has('brand_id')">@{{ errors.first('brand_id') }}</span>
</div>