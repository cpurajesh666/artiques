<?php

namespace Botble\Report\Tables;
;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Eloquent\ProductRepository;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use RvMedia;
use Yajra\DataTables\DataTables;

class MostViewedProductTable extends TableAbstract
{
 

     /**
     * @var string
     */
    protected $view = 'core/table::base-table';

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
     * ProductTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ProductInterface $productRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator)
    {
        parent::__construct($table, $urlGenerator);

        parent::setAjaxUrl(route('reports.mostViewedProductData'));

        $this->repository = new ProductRepository(new Product);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                return Html::link(route('products.edit', $item->id), clean($item->name));
            })
            ->editColumn('image', function ($item) {
                return $this->displayThumbnail($item->image);
            })
            ->editColumn('price', function ($item) {
                return $item->price_in_table;
            })
            ->editColumn('sku', function ($item) {
                return clean($item->sku ?: '&mdash;');
            })
            ->editColumn('views', function ($item) {
                return $item->views;
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->where('status',  'published')
        ->where('views', '!=', 0)->orderBy('views', 'desc');

        return $this->applyScopes($query);
    }

 
    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'           => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'image'        => [
                'name'  => 'images',
                'title' => trans('plugins/ecommerce::products.image'),
                'width' => '100px',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'name'         => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'price'        => [
                'title' => trans('plugins/ecommerce::products.price'),
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'sku'          => [
                'title' => trans('plugins/ecommerce::products.sku'),
                'class' => 'text-start no-sort',
                'orderable' => false,
            ],
            'views'        => [
                'title' => "Views",
                'class' => 'text-center',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ]
        ];
    }
}
