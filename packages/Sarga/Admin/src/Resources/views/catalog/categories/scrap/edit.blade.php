<accordian :title="'Scrap'" :active="true">
    <div slot="body">
        <div class="control-group" :class="[errors.has('trendyol_url') ? 'has-error' : '']">
            <label for="trendyol_url">Trendyol URL</label>
            <input type="text" class="control" id="trendyol_url" name="trendyol_url"
                   value="{{ old('trendyol_url',$category->trendyol_url)}}" data-vv-as="&quot;Trendyol URL&quot;"/>
            <span class="control-error" v-if="errors.has('trendyol_url')">@{{ errors.first('trendyol_url') }}</span>
        </div>
        <div class="control-group" :class="[errors.has('lcw_url') ? 'has-error' : '']">
            <label for="lcw_url">LCW URL</label>
            <input type="text" class="control" id="lcw_url" name="lcw_url"
                   value="{{ old('lcw_url',$category->lcw_url) }}" data-vv-as="&quot;LCW URL&quot;"/>
            <span class="control-error" v-if="errors.has('lcw_url')">@{{ errors.first('lcw_url') }}</span>
        </div>


        <div class="control-group" :class="[errors.has('default_weight') ? 'has-error' : '']">
            <label for="default_weight" class="required">Default weight(kg)</label>
            <input type="text" v-validate="'required|decimal'" class="control" id="default_weight" name="default_weight"
                   value="{{ old('default_weight',$category->default_weight)}}" data-vv-as="&quot;Default weight&quot;"/>
            <span class="control-error" v-if="errors.has('default_weight')">@{{ errors.first('default_weight') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('product_limit') ? 'has-error' : '']">
            <label for="product_limit" class="required">Products limit</label>
            <input type="text" v-validate="'required|numeric'" class="control" id="product_limit" name="product_limit"
                   value="{{ old('product_limit',$category->product_limit)}}" data-vv-as="&quot;Products limit&quot;"/>
            <span class="control-error" v-if="errors.has('product_limit')">@{{ errors.first('product_limit') }}</span>
        </div>
        <div class="control-group multi-select" :class="[errors.has('channels[]') ? 'has-error' : '']">
            <label for="channels" class="required">Vendors</label>
            <?php $selectedaVendors = old('vendors') ?? $category->vendors()->all()->pluck('id')->toArray() ?>
            <select class="control" name="vendors[]" v-validate="'required'" data-vv-as="&quot;Vendors&quot;" multiple>
                @foreach (app('Sarga\Shop\Repositories\VendorRepository')->all() as $vendor)
                    <option value="{{ $vendor->id }}" {{ in_array($vendor->id, $selectedaVendors) ? 'selected' : ''}}>
                        {{ $vendor->shop_title }}
                    </option>
                @endforeach
            </select>

            <span class="control-error" v-if="errors.has('vendors[]')">
                @{{ errors.first('vendors[]') }}
            </span>
        </div>
    </div>
</accordian>