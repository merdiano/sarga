@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.catalog.products.title') }}</h1>
            </div>

        </div>
        <div class="table">
            <datagrid-filters></datagrid-filters>

            @if (isset($results['paginated']) && $results['paginated'])
                @include('ui::datagrid.pagination', ['results' => $results['records']])
            @endif

            @push('scripts')
                <script type="text/x-template" id="datagrid-filters">
                    <div class="grid-container">

                        <div class="datagrid-filters">
                            <div class="filter-left">
                                @if (isset($results['extraFilters']['channels']))
                                    <div class="dropdown-filters per-page">
                                        <div class="control-group">
                                            <select class="control" id="channel-switcher" name="channel"
                                                    onchange="reloadPage('channel', this.value)">
                                                <option value="all" {{ ! isset($channel) ? 'selected' : '' }}>
                                                    {{ __('admin::app.admin.system.all-channels') }}
                                                </option>
                                                @foreach ($results['extraFilters']['channels'] as $channelModel)
                                                    <option
                                                        value="{{ $channelModel->code }}"
                                                        {{ (isset($channel) && ($channelModel->code) == $channel) ? 'selected' : '' }}>
                                                        {{ core()->getChannelName($channelModel) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </script>

                <script>
                    Vue.component('datagrid-filters', {
                        template: '#datagrid-filters',

                        data: function () {
                            return {
                                massActionsToggle: false,
                                massActionTarget: null,
                                massActionConfirmText: '{{ __('ui::app.datagrid.click_on_action') }}',
                                massActionType: null,
                                massActionValues: [],
                                massActionTargets: [],
                                massActionUpdateValue: null,
                                url: new URL(window.location.href),
                                currentSort: null,
                                dataIds: [],
                                allSelected: false,
                                sortDesc: 'desc',
                                sortAsc: 'asc',
                                sortUpIcon: 'sort-up-icon',
                                sortDownIcon: 'sort-down-icon',
                                currentSortIcon: null,
                                isActive: false,
                                isHidden: true,
                                searchValue: '',
                                filterColumn: true,
                                filters: [],
                                columnOrAlias: '',
                                type: null,
                                stringCondition: null,
                                booleanCondition: null,
                                numberCondition: null,
                                datetimeCondition: null,
                                stringValue: null,
                                booleanValue: null,
                                datetimeValue: '2000-01-01',
                                numberValue: 0,
                                stringConditionSelect: false,
                                booleanConditionSelect: false,
                                numberConditionSelect: false,
                                datetimeConditionSelect: false,
                                perPageProduct: [10, 20, 30, 40, 50],
                                extraFilters: @json($results['extraFilters']),
                            }
                        },

                        mounted: function () {
                            this.setParamsAndUrl();

                            this.checkedSelectedCheckbox();

                            if (this.filters.length) {
                                for (let i = 0; i < this.filters.length; i++) {
                                    if (this.filters[i].column === 'perPage') {
                                        this.perPage = this.filters[i].val;
                                    }
                                }
                            }

                            if (this.perPageProduct.indexOf(parseInt(this.perPage)) === -1) {
                                this.perPageProduct.unshift(this.perPage);
                            }
                        },

                        methods: {
                            getColumnOrAlias: function (columnOrAlias) {
                                this.columnOrAlias = columnOrAlias;

                                for (column in this.columns) {
                                    if (this.columns[column].index === this.columnOrAlias) {
                                        this.type = this.columns[column].type;

                                        switch (this.type) {
                                            case 'string': {
                                                this.stringConditionSelect = true;
                                                this.datetimeConditionSelect = false;
                                                this.booleanConditionSelect = false;
                                                this.numberConditionSelect = false;

                                                this.nullify();
                                                break;
                                            }
                                            case 'datetime': {
                                                this.datetimeConditionSelect = true;
                                                this.stringConditionSelect = false;
                                                this.booleanConditionSelect = false;
                                                this.numberConditionSelect = false;

                                                this.nullify();
                                                break;
                                            }
                                            case 'boolean': {
                                                this.booleanConditionSelect = true;
                                                this.datetimeConditionSelect = false;
                                                this.stringConditionSelect = false;
                                                this.numberConditionSelect = false;

                                                this.nullify();
                                                break;
                                            }
                                            case 'number': {
                                                this.numberConditionSelect = true;
                                                this.booleanConditionSelect = false;
                                                this.datetimeConditionSelect = false;
                                                this.stringConditionSelect = false;

                                                this.nullify();
                                                break;
                                            }
                                            case 'price': {
                                                this.numberConditionSelect = true;
                                                this.booleanConditionSelect = false;
                                                this.datetimeConditionSelect = false;
                                                this.stringConditionSelect = false;

                                                this.nullify();
                                                break;
                                            }
                                        }
                                    }
                                }
                            },

                            nullify: function () {
                                this.stringCondition = null;
                                this.datetimeCondition = null;
                                this.booleanCondition = null;
                                this.numberCondition = null;
                            },

                            filterNumberInput: function(e){
                                this.numberValue = e.target.value.replace(/[^0-9\,\.]+/g, '');
                            },

                            getResponse: function() {
                                label = '';

                                for (let colIndex in this.columns) {
                                    if (this.columns[colIndex].index == this.columnOrAlias) {
                                        label = this.columns[colIndex].label;
                                        break;
                                    }
                                }

                                if (this.type === 'string' && this.stringValue !== null) {
                                    this.formURL(this.columnOrAlias, this.stringCondition, encodeURIComponent(this.stringValue), label)
                                } else if (this.type === 'number') {
                                    indexConditions = true;

                                    if (this.filterIndex === this.columnOrAlias
                                        && (this.numberValue === 0 || this.numberValue < 0)) {
                                        indexConditions = false;

                                        alert('{{__('ui::app.datagrid.zero-index')}}');
                                    }

                                    if (indexConditions) {
                                        this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue, label);
                                    }
                                } else if (this.type === 'boolean') {
                                    this.formURL(this.columnOrAlias, this.booleanCondition, this.booleanValue, label);
                                } else if (this.type === 'datetime') {
                                    this.formURL(this.columnOrAlias, this.datetimeCondition, this.datetimeValue, label);
                                } else if (this.type === 'price') {
                                    this.formURL(this.columnOrAlias, this.numberCondition, this.numberValue, label);
                                }
                            },

                            sortCollection: function (alias) {
                                let label = '';

                                for (let colIndex in this.columns) {
                                    if (this.columns[colIndex].index === alias) {
                                        matched = 0;
                                        label = this.columns[colIndex].label;
                                        break;
                                    }
                                }

                                this.formURL("sort", alias, this.sortAsc, label);
                            },

                            searchCollection: function (searchValue) {
                                this.formURL("search", 'all', searchValue, '{{ __('ui::app.datagrid.search-title') }}');
                            },

                            /**
                             * Function triggered to check whether the query exists or not and then
                             * call the make filters from the url.
                             */
                            setParamsAndUrl: function () {
                                params = (new URL(window.location.href)).search;

                                if (params.slice(1, params.length).length > 0) {
                                    this.arrayFromUrl();
                                }

                                for (let id in this.massActions) {
                                    targetObj = {
                                        'type': this.massActions[id].type,
                                        'action': this.massActions[id].action,
                                        'confirm_text': this.massActions[id].confirm_text
                                    };

                                    this.massActionTargets.push(targetObj);

                                    targetObj = {};

                                    if (this.massActions[id].type === 'update') {
                                        this.massActionValues = this.massActions[id].options;
                                    }
                                }
                            },

                            findCurrentSort: function () {
                                for (let i in this.filters) {
                                    if (this.filters[i].column === 'sort') {
                                        this.currentSort = this.filters[i].val;
                                    }
                                }
                            },

                            changeMassActionTarget: function () {
                                if (this.massActionType === 'delete') {
                                    for (let i in this.massActionTargets) {
                                        if (this.massActionTargets[i].type === 'delete') {
                                            this.massActionTarget = this.massActionTargets[i].action;
                                            this.massActionConfirmText = this.massActionTargets[i].confirm_text ? this.massActionTargets[i].confirm_text : this.massActionConfirmText;

                                            break;
                                        }
                                    }
                                }

                                if (this.massActionType === 'update') {
                                    for (let i in this.massActionTargets) {
                                        if (this.massActionTargets[i].type === 'update') {
                                            this.massActionTarget = this.massActionTargets[i].action;
                                            this.massActionConfirmText = this.massActionTargets[i].confirm_text ? this.massActionTargets[i].confirm_text : this.massActionConfirmText;

                                            break;
                                        }
                                    }
                                }

                                document.getElementById('mass-action-form').action = this.massActionTarget;
                            },

                            /**
                             * Make array of filters, sort and search.
                             */
                            formURL: function (column, condition, response, label) {
                                var obj = {};

                                if (column === "" || condition === "" || response === ""
                                    || column === null || condition === null || response === null) {
                                    alert('{{ __('ui::app.datagrid.filter-fields-missing') }}');

                                    return false;
                                } else {

                                    if (this.filters.length > 0) {
                                        if (column !== "sort" && column !== "search") {
                                            let filterRepeated = false;

                                            for (let j = 0; j < this.filters.length; j++) {
                                                if (this.filters[j].column === column) {
                                                    if (this.filters[j].cond === condition && this.filters[j].val === response) {
                                                        filterRepeated = true;

                                                        return false;
                                                    } else if (this.filters[j].cond === condition && this.filters[j].val !== response) {
                                                        filterRepeated = true;

                                                        this.filters[j].val = response;

                                                        this.makeURL();
                                                    }
                                                }
                                            }

                                            if (filterRepeated === false) {
                                                obj.column = column;
                                                obj.cond = condition;
                                                obj.val = response;
                                                obj.label = label;

                                                this.filters.push(obj);
                                                obj = {};

                                                this.makeURL();
                                            }
                                        }

                                        if (column === "sort") {
                                            let sort_exists = false;

                                            for (let j = 0; j < this.filters.length; j++) {
                                                if (this.filters[j].column === "sort") {
                                                    if (this.filters[j].column === column && this.filters[j].cond === condition) {
                                                        this.findCurrentSort();

                                                        if (this.currentSort === "asc") {
                                                            this.filters[j].column = column;
                                                            this.filters[j].cond = condition;
                                                            this.filters[j].val = this.sortDesc;

                                                            this.makeURL();
                                                        } else {
                                                            this.filters[j].column = column;
                                                            this.filters[j].cond = condition;
                                                            this.filters[j].val = this.sortAsc;

                                                            this.makeURL();
                                                        }
                                                    } else {
                                                        this.filters[j].column = column;
                                                        this.filters[j].cond = condition;
                                                        this.filters[j].val = response;
                                                        this.filters[j].label = label;

                                                        this.makeURL();
                                                    }

                                                    sort_exists = true;
                                                }
                                            }

                                            if (sort_exists === false) {
                                                if (this.currentSort === null)
                                                    this.currentSort = this.sortAsc;

                                                obj.column = column;
                                                obj.cond = condition;
                                                obj.val = this.currentSort;
                                                obj.label = label;

                                                this.filters.push(obj);

                                                obj = {};

                                                this.makeURL();
                                            }
                                        }

                                        if (column === "search") {
                                            let search_found = false;

                                            for (let j = 0; j < this.filters.length; j++) {
                                                if (this.filters[j].column === "search") {
                                                    this.filters[j].column = column;
                                                    this.filters[j].cond = condition;
                                                    this.filters[j].val = encodeURIComponent(response);
                                                    this.filters[j].label = label;

                                                    this.makeURL();
                                                }
                                            }

                                            for (let j = 0; j < this.filters.length; j++) {
                                                if (this.filters[j].column === "search") {
                                                    search_found = true;
                                                }
                                            }

                                            if (search_found === false) {
                                                obj.column = column;
                                                obj.cond = condition;
                                                obj.val = encodeURIComponent(response);
                                                obj.label = label;

                                                this.filters.push(obj);

                                                obj = {};

                                                this.makeURL();
                                            }
                                        }
                                    } else {
                                        obj.column = column;
                                        obj.cond = condition;
                                        obj.val = encodeURIComponent(response);
                                        obj.label = label;

                                        this.filters.push(obj);

                                        obj = {};

                                        this.makeURL();
                                    }
                                }
                            },

                            /**
                             * Make the url from the array and redirect.
                             */
                            makeURL: function () {
                                newParams = '';

                                for(let i = 0; i < this.filters.length; i++) {
                                    if (this.filters[i].column == 'status' || this.filters[i].column == 'value_per_locale' || this.filters[i].column == 'value_per_channel' || this.filters[i].column == 'is_unique') {
                                        if (this.filters[i].val.includes("True")) {
                                            this.filters[i].val = 1;
                                        } else if (this.filters[i].val.includes("False")) {
                                            this.filters[i].val = 0;
                                        }
                                    }

                                    let condition = '';
                                    if (this.filters[i].cond !== undefined) {
                                        condition = '[' + this.filters[i].cond + ']';
                                    }

                                    if (i == 0) {
                                        newParams = '?' + this.filters[i].column + condition + '=' + this.filters[i].val;
                                    } else {
                                        newParams = newParams + '&' + this.filters[i].column + condition + '=' + this.filters[i].val;
                                    }
                                }

                                var uri = window.location.href.toString();

                                var clean_uri = uri.substring(0, uri.indexOf("?")).trim();

                                window.location.href = clean_uri + newParams;
                            },

                            /**
                             * Make the filter array from url after being redirected.
                             */
                            arrayFromUrl: function () {

                                let obj = {};
                                const processedUrl = this.url.search.slice(1, this.url.length);
                                let splitted = [];
                                let moreSplitted = [];

                                splitted = processedUrl.split('&');

                                for (let i = 0; i < splitted.length; i++) {
                                    moreSplitted.push(splitted[i].split('='));
                                }

                                for (let i = 0; i < moreSplitted.length; i++) {
                                    const key = decodeURI(moreSplitted[i][0]);
                                    let value = decodeURI(moreSplitted[i][1]);

                                    if (value.includes('+')) {
                                        value = value.replace('+', ' ');
                                    }

                                    obj.column = key.replace(']', '').split('[')[0];
                                    obj.cond = key.replace(']', '').split('[')[1]
                                    obj.val = value;

                                    switch (obj.column) {
                                        case "search":
                                            obj.label = "{{ __('ui::app.datagrid.search-title') }}";
                                            break;
                                        case "channel":
                                            obj.label = "{{ __('ui::app.datagrid.channel') }}";
                                            if ('channels' in this.extraFilters) {
                                                obj.prettyValue = this.extraFilters['channels'].find(channel => channel.code == obj.val);

                                                if (obj.prettyValue !== undefined) {
                                                    obj.prettyValue = obj.prettyValue.name;
                                                }
                                            }
                                            break;
                                        case "locale":
                                            obj.label = "{{ __('ui::app.datagrid.locale') }}";
                                            if ('locales' in this.extraFilters) {
                                                obj.prettyValue = this.extraFilters['locales'].find(locale => locale.code === obj.val);

                                                if (obj.prettyValue !== undefined) {
                                                    obj.prettyValue = obj.prettyValue.name;
                                                }
                                            }
                                            break;
                                        case "customer_group":
                                            obj.label = "{{ __('ui::app.datagrid.customer-group') }}";
                                            if ('customer_groups' in this.extraFilters) {
                                                obj.prettyValue = this.extraFilters['customer_groups'].find(customer_group => customer_group.id === parseInt(obj.val, 10));

                                                if (obj.prettyValue !== undefined) {
                                                    obj.prettyValue = obj.prettyValue.name;
                                                }
                                            }
                                            break;
                                        case "sort":
                                            for (let colIndex in this.columns) {
                                                if (this.columns[colIndex].index === obj.cond) {
                                                    obj.label = this.columns[colIndex].label;
                                                    break;
                                                }
                                            }
                                            break;
                                        default:
                                            for (let colIndex in this.columns) {
                                                if (this.columns[colIndex].index === obj.column) {
                                                    obj.label = this.columns[colIndex].label;

                                                    if (this.columns[colIndex].type === 'boolean') {
                                                        if (obj.val === '1') {
                                                            obj.val = '{{ __('ui::app.datagrid.true') }}';
                                                        } else {
                                                            obj.val = '{{ __('ui::app.datagrid.false') }}';
                                                        }
                                                    }
                                                }
                                            }
                                            break;
                                    }

                                    if (obj.column !== undefined && obj.column !== 'admin_locale' && obj.val !== undefined) {
                                        this.filters.push(obj);
                                    }

                                    obj = {};
                                }
                            },

                            removeFilter: function (filter) {
                                for (let i in this.filters) {
                                    if (this.filters[i].column === filter.column
                                        && this.filters[i].cond === filter.cond
                                        && this.filters[i].val === filter.val) {
                                        this.filters.splice(i, 1);

                                        this.makeURL();
                                    }
                                }
                            },

                            paginate: function (e) {
                                for (let i = 0; i < this.filters.length; i++) {
                                    if (this.filters[i].column == 'perPage') {
                                        this.filters.splice(i, 1);
                                    }
                                }

                                this.filters.push({"column": "perPage", "cond": "eq", "val": e.target.value});

                                this.makeURL();
                            },

                            /**
                             * Get current page ids.
                             */
                            getCurrentIds: function () {
                                let currentIds = [];

                                if (this.gridCurrentData.hasOwnProperty("data")) {
                                    for (let currentData in this.gridCurrentData.data) {
                                        currentIds.push(this.gridCurrentData.data[currentData][this.filterIndex]);
                                    }
                                } else {
                                    for (let currentData in this.gridCurrentData) {
                                        let i = 0;
                                        for (let currentId in this.gridCurrentData[currentData]) {
                                            if (i === 0) {
                                                currentIds.push(this.gridCurrentData[currentData][currentId]);
                                            }
                                            i++;
                                        }
                                    }
                                }

                                return currentIds;
                            },

                            /**
                             * Get page key.
                             */
                            getPageKey: function () {
                                let routeSegments = this.gridCurrentData.path.split('/');

                                return routeSegments[routeSegments.length - 1];
                            },

                            /**
                             * Set selected indexes.
                             */
                            setSelectedIndexes: function () {
                                let routeIndexObj = {};

                                routeIndexObj[this.getPageKey()] = this.dataIds;

                                localStorage.dataGridIndexes = JSON.stringify(routeIndexObj);
                            },

                            /**
                             * Get selected indexes.
                             */
                            getSelectedIndexes: function () {
                                let selectedIndexes = localStorage.getItem("dataGridIndexes");

                                if (selectedIndexes !== null && selectedIndexes !== '') {
                                    return selectedIndexes;
                                }

                                return JSON.stringify({});
                            },

                            /**
                             * Triggered when any select box is clicked in the datagrid.
                             */
                            select: function (event) {
                                let checkboxElement = event.target;

                                if (checkboxElement.checked) {
                                    if (! this.dataIds.includes(checkboxElement.value)) {
                                        this.dataIds.push(checkboxElement.value);
                                    }
                                } else {
                                    if (this.dataIds.includes(checkboxElement.value)) {
                                        this.dataIds.pop(checkboxElement.value);
                                    }
                                }

                                this.allSelected = false;

                                if (this.dataIds.length === 0) {
                                    this.massActionsToggle = false;
                                    this.massActionType = null;
                                } else {
                                    this.massActionsToggle = true;
                                }

                                this.setSelectedIndexes();
                            },

                            /**
                             * Triggered when master checkbox is clicked.
                             */
                            selectAll: function () {
                                this.massActionsToggle = true;

                                if (this.allSelected) {
                                    if (this.gridCurrentData.hasOwnProperty("data")) {
                                        for (let currentData in this.gridCurrentData.data) {
                                            this.dataIds.push(this.gridCurrentData.data[currentData][this.filterIndex]);
                                        }
                                    } else {
                                        for (let currentData in this.gridCurrentData) {
                                            let i = 0;
                                            for (let currentId in this.gridCurrentData[currentData]) {
                                                if (i === 0) {
                                                    this.dataIds.push(this.gridCurrentData[currentData][currentId]);
                                                }
                                                i++;
                                            }
                                        }
                                    }

                                    this.setSelectedIndexes();
                                }
                            },

                            /**
                             * Triggered when master checkbox is unchecked.
                             */
                            removeMassActions: function () {
                                let currentIds = this.getCurrentIds();

                                this.dataIds = this.dataIds.filter(id => currentIds.indexOf(parseInt(id)) == -1);

                                this.massActionsToggle = false;

                                this.allSelected = false;

                                this.massActionType = null;

                                this.setSelectedIndexes();
                            },

                            /**
                             * Triggered when page load for checking the selected ids.
                             */
                            checkedSelectedCheckbox: function () {
                                let pageKey = this.getPageKey();

                                let selectedIndexes = JSON.parse(this.getSelectedIndexes());

                                if (Object.keys(selectedIndexes).length) {
                                    for (key in selectedIndexes) {
                                        if (key === pageKey) {
                                            this.dataIds = selectedIndexes[key];
                                            this.massActionsToggle = true;
                                            this.allSelected = false;

                                            if (this.dataIds.length === 0) {
                                                this.massActionsToggle = false;
                                                this.massActionType = null;
                                            }
                                        } else {
                                            delete selectedIndexes[key];
                                        }
                                    }
                                }

                                this.setSelectedIndexes();
                            },

                            /**
                             * Do actions.
                             */
                            doAction: function (e, message, type) {
                                let element = e.currentTarget;

                                if (message) {
                                    element = e.target.parentElement;
                                }

                                message = message || '{{__('ui::app.datagrid.massaction.delete') }}';

                                if (confirm(message)) {
                                    axios.post(element.getAttribute('data-action'), {
                                        _token: element.getAttribute('data-token'),
                                        _method: element.getAttribute('data-method')
                                    }).then(function (response) {
                                        this.result = response;

                                        if (response.data.redirect) {
                                            window.location.href = response.data.redirect;
                                        } else {
                                            location.reload();
                                        }
                                    }).catch(function (error) {
                                        location.reload();
                                    });

                                    e.preventDefault();
                                } else {
                                    e.preventDefault();
                                }
                            }
                        },
                    });
                </script>
            @endpush
        </div>
    </div>

@stop
