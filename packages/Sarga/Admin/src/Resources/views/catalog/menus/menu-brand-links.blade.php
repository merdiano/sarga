
<accordian title="{{ __('sarga::app.catalog.menus.brands') }}" :active="false">
    <div slot="body">

        <linked-brands></linked-brands>

    </div>
</accordian>

@push('scripts')

<script type="text/x-template" id="linked-brands-template">
    <div>

        <div class="control-group" >


        </div>

    </div>
</script>

<script>

    Vue.component('linked-brands', {

        template: '#linked-brands-template',

        data: function() {
            return {
                brands: [],

                search_term:  '',

                addedBrands: [],

                is_searching: false,

                menuId: {{ $menu->id }},

                menuBrands: @json($menu->brands()->get()),

            }
        },

        created: function () {
            if (this.menuBrands.length > 0) {
                for (var index in this.menuBrands) {
                    this.addedBrands.push(this.menuBrands[index]);
                }
            }

        },

        methods: {
            addBrand: function (brand ) {
                this.addedBrands.push(brand);
                this.search_term = '';
                this.brands = []
            },

            removeBrand: function (brand) {
                for (var index in this.addedBrands) {
                    if (this.addedBrands[index].id == brand.id ) {
                        this.addedBrands.splice(index, 1);
                    }
                }
            },

            search: function () {
                this_this = this;

                this.is_searching = true;

                if (this.search_term.length >= 1) {
                    this.$http.get ("{{ route('admin.catalog.menus.brandsearch') }}", {params: {query: this.search_term}})
                        .then (function(response) {

                            for (var index in response.data) {
                                if (response.data[index].id == this_this.menuId) {
                                    response.data.splice(index, 1);
                                }
                            }

                            if (this_this.addedBrands.length ) {
                                for (var brand in this_this.addedBrands) {
                                    for (var menuId in response.data) {
                                        if (response.data[menuId].id == this_this.addedBrands[brand].id) {
                                            response.data.splice(menuId, 1);
                                        }
                                    }
                                }
                            }

                            this_this.brands = response.data;

                            this_this.is_searching = false;
                        })

                        .catch (function (error) {
                            this_this.is_searching = false;
                        })
                } else {
                    this_this.brands = [];
                    this_this.is_searching = false;
                }
            }
        }
    });

</script>

@endpush