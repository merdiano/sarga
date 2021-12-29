@php
    $attributes = app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere(['type'=>'select']);
@endphp
<div class="control-group" :class="[errors.has('brand_attribute_id') ? 'has-error' : '']">
    <label for="brand_attribute_id" class="required">Brand Attribute</label>
    <select name="brand_attribute_id" id="brand_attribute_id" class="control"
            data-vv-as="&quot;Brand Attribute&quot;"
    >
        @foreach ($attributes as $attribute)
            <option value="{{$attribute->id}}" @if(old('brand_attribute_id',$seller->brand_attribute_id)==$attribute->id)selected @endif>
                {{$attribute->admin_name}}</option>
        @endforeach

    </select>
    <span class="control-error" v-if="errors.has('brand_attribute_id')">@{{ errors.first('brand_attribute_id') }}</span>
</div>


