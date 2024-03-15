@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')

    <h5 style="font-weight: 800">Filters</h3>

    <div class="row mt-3 p-2" style="background: white">

        <div class="form-group form-group-no-margin col-md-4">
            <p class="">Categories</p>
            <select class="w-100 categories" name="categories[]" id="category" multiple="multiple">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{in_array($category->id, request()->input('category', [])) ? "selected" : ''}}>
                        {!! $category->indent_text . $category->name !!}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group col-md-4">
            <label class="mb-2">From Date</label>
            <input type="date" class="form-control mt-1" id="from-date" name="from-date" 
            value="{{ request()->input('fromDate') }}" >
        </div>

        <div class="form-group col-md-4">
            <label class="mb-2">To Date</label>
            <input type="date" class="form-control mt-1" name="to-date" id="to-date"
            value="{{ request()->input('toDate') }}">
        </div>

        <div class="row">            
            <div class="form-group col-md-4">
                <label class="mb-2">Supplier</label>
                <select class="form-control" id="supplier">
                    <option value="">Choose the Supplier</option>
                    @foreach($suppliers as $key => $value)
                    <option value={{$value->id}} {{request()->input('supplier') == $value->id ? "selected" : ''}}>{{$value->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="mb-2">Missing Fields</label>
                <select class="form-control" id="fields" multiple>
                    <option value="">Choose the Fields</option>
                    <option value="description" {{in_array('description', request()->input('fields', [])) ? "selected" : ''}} >Description</option>
                    <option value="content" {{in_array('content', request()->input('fields', [])) ? "selected" : ''}} >Content</option>
                    <option value="supplier_id" {{in_array('supplier_id', request()->input('fields', [])) ? "selected" : ''}} >Supplier</option>
                    <option value="sku" {{in_array('sku', request()->input('fields', [])) ? "selected" : ''}} >Sku</option>
                    <option value="quantity" {{in_array('quantity', request()->input('fields', [])) ? "selected" : ''}}>Quantity</option>
                    <option value="is_featured" {{in_array('is_featured', request()->input('fields', [])) ? "selected" : ''}}>Featured</option>
                    <option value="cost_price" {{in_array('cost_price', request()->input('fields', [])) ? "selected" : ''}}>Cost Price</option>
                    <option value="price" {{in_array('price', request()->input('fields', [])) ? "selected" : ''}}>Price</option>
                    <option value="sale_price" {{in_array('sale_price', request()->input('fields', [])) ? "selected" : ''}}>Discount</option>
                    <option value="length" {{in_array('length', request()->input('fields', [])) ? "selected" : ''}}>Length</option>
                    <option value="wide" {{in_array('wide', request()->input('fields', [])) ? "selected" : ''}}>Width</option>
                    <option value="height" {{in_array('height', request()->input('fields', [])) ? "selected" : ''}}>Height</option>
                    <option value="weight" {{in_array('weight', request()->input('fields', [])) ? "selected" : ''}}>Weight</option>
                    <option value="images" {{in_array('images', request()->input('fields', [])) ? "selected" : ''}}>Images</option>
                    <option value="image" {{in_array('image', request()->input('fields', [])) ? "selected" : ''}}>Featured Image</option>
                    
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="mb-2">Stock Status</label>
                <select class="form-control" id="stock">
                    <option value="">All</option>
                    <option value="in_stock" {{request()->input('stock') == 'in_stock' ? "selected" : ''}} >In Stock</option>
                    <option value="out_of_stock" {{request()->input('stock') == 'out_of_stock' ? "selected" : ''}} >Out Of Stock</option>  
                </select>
            </div>
        </div>
        <div class="row justify-content-end">   
            <div class="d-flex align-items-center col-md-1">
                <button type="button" onclick="applyFilter()" class="btn btn-primary" id="exampleCheck1">Filter</button>
            </div>
        </div>
    </div>
    <div class="mt-5">
        {!! $productsTable->renderTable() !!}
    </div>

@endsection

@push('footer')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>


        
        $(document).ready(function() {

            var Utils = $.fn.select2.amd.require('select2/utils');
            var Dropdown = $.fn.select2.amd.require('select2/dropdown');
            var DropdownSearch = $.fn.select2.amd.require('select2/dropdown/search');
            var CloseOnSelect = $.fn.select2.amd.require('select2/dropdown/closeOnSelect');
            var AttachBody = $.fn.select2.amd.require('select2/dropdown/attachBody');

            var dropdownAdapter = Utils.Decorate(Utils.Decorate(Utils.Decorate(Dropdown, DropdownSearch), CloseOnSelect), AttachBody);


            $('.categories').select2({
                multiple: true,
                dropdownAdapter: dropdownAdapter,
                minimumResultsForSearch: 0,
            }).on('select2:opening select2:closing', function (event) {
                //Disable original search (https://select2.org/searching#multi-select)
                var searchfield = $(this).parent().find('.select2-search__field');
                searchfield.prop('disabled', true);
            });

            $('#fields').select2({
                multiple: true,
            });
        });

        function applyFilter() {
            let url = window.location.protocol + "//" + window.location.hostname + window.location.pathname + '?';
            let fromDate = $('#from-date').val();
            let toDate = $('#to-date').val();
            let categories = $('#category').val();
            let supplier = $('#supplier').val();
            let fields = $('#fields').val();
            let stock = $('#stock').val();
            
            if(fromDate){
                url += ('fromDate=' + fromDate);
            }
            
            if(toDate){
                url += ('&toDate=' + toDate);
            }

            if(supplier){
                url += ('&supplier=' + supplier);
            }

            if(stock){
                url += ('&stock_status=' + stock);
            }

            if(categories.length > 0){
                let index = 0;
                categories.forEach(category => {
                    url += ('&category[' + index + ']=' + category);
                    index++;
                });
            }

            if(fields.length > 0){
                let index = 0;
                fields.forEach(field => {
                    url += ('&fields[' + index + ']=' + field);
                    index++;
                });
            }
            
            window.location =  url;
        }

    </script>
    @endpush