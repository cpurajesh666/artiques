<?php

namespace Botble\Report\Tables;

use Botble\Ecommerce\Facades\ProductCategoryHelperFacade;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Botble\Ecommerce\Repositories\Eloquent\ProductCategoryRepository;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;
use RvMedia;
use Illuminate\Support\Facades\DB;

class MostViewedSubCategoryTable extends TableAbstract
{

     /**
     * @var string
     */
    protected $type = self::TABLE_TYPE_SIMPLE;

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

        parent::setAjaxUrl(route('reports.mostViewedSubCategoryData'));

        $this->repository = new ProductCategoryRepository(new ProductCategory);

    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->collection($this->query())
            ->editColumn('name', function ($item) {
                return Html::link(route('product-categories.edit', $item['id']), clean($item['name']) );
            })
            ->editColumn('image', function ($item) {
                return Html::image(RvMedia::getImageUrl($item['image'], 'thumb', false, RvMedia::getDefaultImage()),
                    clean($item['name']), ['width' => 50]);
            })
            ->editColumn('views', function ($item) {
                return $item['views'];
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {

        $allCategories = ProductCategoryHelperFacade::getAllProductCategories();

        $categories = $allCategories->where('depth', 1);
        
        $mostViewedCategories = [];

        foreach($categories as $category){
            $views = DB::table('ec_products as p')->join('ec_product_category_product as pcp', 'pcp.product_id', 'p.id')
            ->whereIn('pcp.category_id', $category->getChildrenIds($category, [$category->id]))->sum('p.views');

            $mostViewedCategories[] = [
                'id' => $category->id,
                'name' => $category->name,
                'views' => $views,
                'image' => $category->image,
            ];

        }

        return collect($mostViewedCategories)->sortByDesc('views')->take(10);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'image'      => [
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'views'      => [
                'title' => 'Views',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
        ];
    }
}
