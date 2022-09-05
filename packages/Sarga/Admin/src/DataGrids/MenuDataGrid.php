<?php

namespace Sarga\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Locale;
use Webkul\Ui\DataGrid\DataGrid;

class MenuDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $index = 'menu_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Locale.
     *
     * @var string
     */
    protected $locale = 'all';

    /**
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'locales',
    ];

    /**
     * Create a new datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->locale = core()->getRequestedLocaleCode();
    }

    public function prepareQueryBuilder()
    {
        if ($this->locale === 'all') {
            $whereInLocales = Locale::query()->pluck('code')->toArray();
        } else {
            $whereInLocales = [$this->locale];
        }

        $queryBuilder = DB::table('menus  as m')
            ->select(
                'm.id as menu_id',
                'mt.name',
                'm.position',
                'm.status',
                'mt.locale'
            )
            ->leftJoin('menu_translations as mt', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('m.id', '=', 'mt.cmenu_id')
                    ->whereIn('mt.locale', $whereInLocales);
            })

            ->groupBy('m.id', 'mt.locale',);


        $this->addFilter('status', 'm.status');
        $this->addFilter('menu_id', 'm.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'menu_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('admin::app.datagrid.position'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

    }
    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.menus.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.catalog.menus.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'menu']),
            'icon'         => 'icon trash-icon',
        ]);

        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.menus.massdelete'),
            'method' => 'POST',
        ]);
    }
}