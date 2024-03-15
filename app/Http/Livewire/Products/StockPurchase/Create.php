<?php

namespace App\Http\Livewire\Products\StockPurchase;

use App\Models\StockProduct;
use Botble\Ecommerce\Models\ProductCategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public mixed $image = null;

    public int|null $categoryId = null;

    public StockProduct $product;

    public $allCategories;

    public array $categories = [];

    protected $validationAttributes = [
        'categoryId' => 'category',
    ];

    protected $rules = [
        'categoryId' => 'required|integer',
        'product.description' => 'required|string',
        'image' => 'required|image',
    ];

    public function mount(StockProduct $product)
    {
        $this->product = $product;
        $this->allCategories = collect(ProductCategory::get())->groupBy('parent_id');
        $this->categories = [];
        $this->buildCategories($this->allCategories[0]);
    }

    public function buildCategories($categories, $prefix = '')
    {
        foreach ($categories as $category) {
            $name = ($prefix ? ($prefix . ' - ') : '') . $category->name;
            $this->categories[] = [
                'id' => $category->id,
                'name' => $name,
            ];
            if (!empty($this->allCategories[$category->id])) {
                $this->buildCategories(
                    $this->allCategories[$category->id],
                    $name,
                );
            }
        }
    }

    public function save()
    {
        $this->validate();

        $this->product->category_id = $this->categoryId;
        $this->product->images = [
            $this->image->storePublicly('stock-products/images', 'public'),
        ];
        $this->product->save();

        $this->redirect(route('stock-purchase.index'));
    }

    public function render()
    {
        return view('livewire.products.stock-purchase.create')->layout('components.admin');
    }
}
