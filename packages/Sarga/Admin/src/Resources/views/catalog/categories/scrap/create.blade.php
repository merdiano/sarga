<accordian :title="'Scrap'" :active="true">
    <div slot="body">
        <div class="control-group" :class="[errors.has('trendyol_url') ? 'has-error' : '']">
            <label for="trendyol_url">Trendyol URL</label>
            <input type="text" class="control" id="trendyol_url" name="trendyol_url"
                   value="{{ old('trendyol_url')}}" data-vv-as="&quot;Trendyol URL&quot;"/>
            <span class="control-error" v-if="errors.has('trendyol_url')">@{{ errors.first('trendyol_url') }}</span>
        </div>
        <div class="control-group" :class="[errors.has('lcw_url') ? 'has-error' : '']">
            <label for="lcw_url">LCW URL</label>
            <input type="text" class="control" id="lcw_url" name="lcw_url"
                   value="{{ old('lcw_url') }}" data-vv-as="&quot;LCW URL&quot;"/>
            <span class="control-error" v-if="errors.has('lcw_url')">@{{ errors.first('lcw_url') }}</span>
        </div>


        <div class="control-group" :class="[errors.has('default_weight') ? 'has-error' : '']">
            <label for="default_weight" class="required">Default weight(kg)</label>
            <input type="text" v-validate="'required|decimal'" class="control" id="default_weight" name="default_weight" value="{{ old('default_weight',0.5)}}" data-vv-as="&quot;Default weight&quot;"/>
            <span class="control-error" v-if="errors.has('default_weight')">@{{ errors.first('default_weight') }}</span>
        </div>
    </div>
</accordian>