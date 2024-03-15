<?php

namespace Botble\Promotions\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Promotions\Repositories\Interfaces\PromotionInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class PromotionTable extends TableAbstract
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
     * SimpleSliderTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PromotionInterface $promotionRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        PromotionInterface $promotionRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $promotionRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('promotions.edit')) {
                    return $item->name;
                }

                return Html::link(route('promotions.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('text', function ($item) {
                return $item->text;
            })
            ->editColumn('type', function ($item) {
                return $item->type;
            })
            ->editColumn('from', function ($item) {
                return $item->type == 'daily' ? '-' : $item->from;
            })
            ->editColumn('never_expires', function ($item) {
                return $item->never_expires ? "yes" : "no";
            })
            ->editColumn('to', function ($item) {
                return $item->never_expires ||$item->type == 'daily' ? '-' : $item->to;
            })
            // ->editColumn('image', function ($item) {
            //     return $this->displayThumbnail($item->image);
            // })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('promotions.edit', 'promotions.destroy', $item);
            });

        // if (function_exists('shortcode')) {
        //     $data = $data->editColumn('key', function ($item) {
        //         return shortcode()->generateShortcode('promotion', ['key' => $item->key]);
        //     });
        // }

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'name',
            'status',
            'text',
            'type',
            'from',
            'to',
            'never_expires',
            'image',
            'created_at',
        ]);

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
            'name'       => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'text'       => [
                'title' => 'Description',
                'class' => 'text-start',
            ],
            'type'       => [
                'title' => 'Type',
                'class' => 'text-start',
            ],
            // 'from'       => [
            //     'title' => 'From Date',
            //     'class' => 'text-start',
            // ],
            'never_expires'       => [
                'title' => 'Never Expires',
                'class' => 'text-start',
            ],
            // 'to'       => [
            //     'title' => 'To',
            //     'class' => 'text-start',
            // ],
            // 'image'       => [
            //     'title' => 'Image',
            //     'class' => 'text-start',
            // ],
            'from' => [
                'title' => "From",
                'width' => '60px',
            ],
            'to' => [
                'title' => "To",
                'width' => '60px',
            ],
            'status'     => [
                'title' => trans('core/base::tables.status'),
                'width' => '50px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        return $this->addCreateButton(route('promotions.create'), 'promotions.create');
    }

}
