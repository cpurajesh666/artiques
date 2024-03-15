<div class="d-flex justify-content-end"><a href="{{url('/admin/ecommerce/product-categories')}}" target="_blank" class="btn-trigger-add-attribute">Add Category</a></div>

<div class="form-group form-group-no-margin @if ($errors->has($name)) has-error @endif">       
    <div class="multi-choices-widget list-item-checkbox mt-2">
        @if (isset($options['choices']) && (is_array($options['choices']) || $options['choices'] instanceof \Illuminate\Support\Collection))
            @include('plugins/ecommerce::product-categories.partials.categories-checkbox-option-line', [
                'categories' => $options['choices'],
                'value'      => $options['value'],
                'currentId'  => null,
                'name'       => $name
            ])
        @endif
    </div>
</div>
