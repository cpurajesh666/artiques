<?php

namespace Botble\Report\Tables;

use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Repositories\Eloquent\OrderAddressRepository;
use Botble\Report\Exports\GuestUsersExport;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Html;


class GuestUsersTable extends TableAbstract
{

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
     * @var string
     */
    protected $exportClass = GuestUsersExport::class;

    /**
     * ContactTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator)
    {
        parent::__construct($table, $urlGenerator);
        $this->repository = new OrderAddressRepository(new OrderAddress);
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('order', function ($item) {
                return Html::link(route('orders.edit', $item->order_id), $item->order_id);
            })
            ->editColumn('name', function ($item) {
                return $item->name;
            })
            ->editColumn('phone', function ($item) {
                return $item->phone;
            })
            ->editColumn('email', function ($item) {
                return $item->email;
            })
            ->editColumn('country', function ($item) {
                return $item->country;
            })
            ->editColumn('state', function ($item) {
                return $item->state;
            })
            ->editColumn('city', function ($item) {
                return $item->city;
            })
            ->editColumn('address', function ($item) {
                return $item->address;
            })
           ;

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {

        $guestOrderIds = Order::where('user_id', 0)->where('status', 'completed')->pluck('id')->toArray();

        $addressIds = $this->repository->getModel()->whereIn('order_id', $guestOrderIds)->groupBy('email')->pluck('id')->toArray();

        $query =$this->repository->getModel()->whereIn('id', $addressIds)->select(
            [
                'id',
                'name',
                'phone',
                'email',
                'country',
                'state',
                'city',
                'zip_code',
                'address',
                'order_id'
            ]
        );

        return $this->applyScopes($query);
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
            ],
            'order'       => [
                'title' => 'Order',
                'class' => 'text-start',
            ],
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'email'      => [
                'title' => trans('plugins/contact::contact.tables.email'),
                'class' => 'text-start',
            ],
            'phone'      => [
                'title' => trans('plugins/contact::contact.tables.phone'),
                'class' => 'text-start',
            ],
            'country'      => [
                'title' => 'Country',
                'class' => 'text-start',
            ],
            'state'      => [
                'title' => 'State',
                'class' => 'text-start',
            ],
            'city'      => [
                'title' => 'City',
                'class' => 'text-start',
            ],
            'address'      => [
                'title' => 'Address',
                'class' => 'text-start',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
