<?php

namespace Botble\Report\Tables;

use Botble\Blog\Repositories\Eloquent\CategoryRepository;
use Botble\Ecommerce\Facades\ProductCategoryHelperFacade;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\SearchHistories;
use Botble\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use RvMedia;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;

class MostSearchedTable extends TableAbstract
{

     /**
     * @var string
     */
    protected $view = 'core/table::simple-table';

    /**
     * @var bool
     */
    protected $hasActions = false;

    /**
     * @var bool
     */
    protected $hasFilter = false;

     /**
     * @var bool
     */
    protected $hasOperations = false;

    /**
     * @var bool
     */
    protected $hasCheckbox = false;

    /**
     * ContactTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator)
    {
        parent::__construct($table, $urlGenerator);

        parent::setAjaxUrl(route('reports.mostSearchedData'));

        $this->repository = new ProductCategoryRepository(new ProductCategory);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('search', function ($item) {
                return $item['text'];
            })
            ->editColumn('views', function ($item) {
                return $item['count'];
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {

        $query = SearchHistories::selectRaw('count(*) as count, text')->groupBy('text');
    
        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'search'       => [
                'title' => 'Search Text',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'count'      => [
                'title' => 'Count',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
        ];
    }
}
