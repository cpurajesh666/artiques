<?php

namespace Botble\Report\Tables;
;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Eloquent\ProductRepository;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\DB;
use RvMedia;
use Yajra\DataTables\DataTables;

class TopSellingProductTable extends TableAbstract
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
     * ProductTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ProductInterface $productRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator)
    {
        parent::__construct($table, $urlGenerator);

        parent::setAjaxUrl(route('reports.topSellingProductData'));

        $this->repository = new ProductRepository(new Product);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->collection($this->query())
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
            ->editColumn('quantity', function ($item) {
                return $item->quantity;
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $productOrdersQuery = DB::table('ec_order_product as op')
        ->selectRaw('sum(qty) as qty, product_id')
        ->join('ec_orders as o', 'o.id', 'op.order_id')
        ->join('ec_products as p', 'p.id', 'op.product_id')
        ->where('o.status', 'completed')
        ->groupBy('op.product_id');

        if(request()->input("fromDate")) {
            $productOrdersQuery = $productOrdersQuery->whereDate('o.created_at','>=', Carbon::parse(request()->input("fromDate"))->format('Y-m-d'));
        }

        if(request()->input("toDate")) {
            $productOrdersQuery = $productOrdersQuery->whereDate('o.created_at','<=', Carbon::parse(request()->input("toDate"))->format('Y-m-d'));
        }

        $productOrdersCount = $productOrdersQuery->get();

        $products = Product::whereIn('id', $productOrdersCount->pluck('product_id'))->get();

        $output = $products->map(function($item, $key) use($productOrdersCount){
            $item['quantity'] =  $productOrdersCount->where('product_id', $item->id)->first()->qty;
            return $item;
        });
        
        return $output->sortByDesc('orders')->take(500);
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
            'quantity'        => [
                'title' => "Quantity",
                'class' => 'text-center',
                'class' => 'text-start no-sort',
                'orderable' => false,
            ]
        ];
    }
}
