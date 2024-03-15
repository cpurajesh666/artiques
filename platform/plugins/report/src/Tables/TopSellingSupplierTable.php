<?php

namespace Botble\Report\Tables;
;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Supplier;
use Botble\Ecommerce\Repositories\Eloquent\ProductRepository;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\DB;
use RvMedia;
use Yajra\DataTables\DataTables;

class TopSellingSupplierTable extends TableAbstract
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
     * ProductTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ProductInterface $productRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator)
    {
        parent::__construct($table, $urlGenerator);

        parent::setAjaxUrl(route('reports.topSellingSupplierData'));

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
                return Html::link(route('suppliers.edit', $item->id), clean($item->name));
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
        $supplierQuery = DB::table('ec_order_product as op')
        ->selectRaw('sum(qty) as qty, supplier_id')
        ->join('ec_orders as o', 'o.id', 'op.order_id')
        ->join('ec_products as p', 'p.id', 'op.product_id')
        ->where('o.status', 'completed')
        ->groupBy('p.supplier_id');

        if(request()->input("fromDate")) {
            $supplierQuery = $supplierQuery->whereDate('o.created_at','>=', Carbon::parse(request()->input("fromDate"))->format('Y-m-d'));
        }

        if(request()->input("toDate")) {
            $supplierQuery = $supplierQuery->whereDate('o.created_at','<=', Carbon::parse(request()->input("toDate"))->format('Y-m-d'));
        }

        $supplierOrderProductsCount = $supplierQuery->get();


        $suppliers = Supplier::whereIn('id', $supplierOrderProductsCount->pluck('supplier_id'))->get();


        $output = $suppliers->map(function($item, $key) use($supplierOrderProductsCount){
            $item['quantity'] =  $supplierOrderProductsCount->where('supplier_id', $item->id)->first()->qty;
            return $item;
        });
        
        return $output->sortByDesc('quantity')->take(500);
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
            'name'         => [
                'title' => trans('core/base::tables.name'),
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
