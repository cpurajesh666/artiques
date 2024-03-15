    @extends(BaseHelper::getAdminMasterLayoutTemplate())
    @section('content')

    <div class="mt-2 p-2">
        <p style="font-size: large; font-weight: 600;">Inventory Report</p>
        <div class="p-2 row">
            <form id="orders-report-form-filter" class="col-md-8 row p-2" style="background-color: #fff; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
                <div class="form-group form-group-no-margin col-md-6">
                    <p class="mt-3">Categories</p>

                    <select class="w-100 categories" name="categories[]" id="inventory-report-category" multiple="multiple">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{!! $category->indent_text . $category->name !!}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 row align-items-center">
                    <div class="form-group">
                        <label class="mb-2 mt-2">From Date</label>
                        <input type="date" class="form-control" id="inventory-report-from-date" name="inventory-report-from-date" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label class="mb-2">To Date</label>
                        <input type="date" class="form-control" name="inventory-report-to-date" id="inventory-report-to-date">
                    </div>
                    <div class="form-group">
                        <label class="mb-2">Employee</label>
                        <select class="form-control" id="inventory-report-employee-name" placeholder="Password">
                            <option value="">Choose the Employee</option>
                            @foreach($employees as $key => $value)
                            <option value={{$value->id}}>{{$value->first_name.' '.$value->last_name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="button" onclick="reportInventoryFilter()" class="btn btn-primary" id="exampleCheck1">Filter</button>
                    </div>
                </div>
            </form>
            <div class="col-md-4 row justify-content-center align-items-center" style="background-color: #fff; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem;">
                <p class="card text-center col-md-7 px-0 py-2" style="background: rgb(238 241 245) !important;">
                    <span class="d-block" style="font-weight: bolder;">Total Number of Products </span>
                    <span class="d-block mt-1" style="font-weight: 600; font-size: 1.2rem;" id="inventory-product-count">0</span>
                </p> 
                <p class="card text-center col-md-7 px-0 py-2" style="background: rgb(238 241 245) !important;">
                    <span class="d-block" style="font-weight: bolder;">Cost value </span>
                    <span class="d-block mt-1" id="inventory-cost-value" style="font-weight: 600; font-size: 1.2rem;">0</span>
                </p> 
                <p class="card text-center col-md-7 px-0 py-2" style="background: rgb(238 241 245) !important;">
                    <span class="d-block" style="font-weight: bolder;">Selling value </span>
                    <span class="d-block mt-1" id="inventory-selling-value" style="font-weight: 600; font-size: 1.2rem;">0</span>
                </p>
            </div>
        </div>
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
        });

        function reportInventoryFilter() {

            $.ajax({
                url: route("reports.products-inventory"),
                type: 'post',
                data: {
                    fromDate: $('#inventory-report-from-date').val(),
                    toDate: $('#inventory-report-to-date').val(),
                    categories: $('#inventory-report-category').val(),
                    employeeId: $('#inventory-report-employee-name').val()
                },
                success: function(response) {
                    $("#inventory-product-count").html(response.count);
                    $("#inventory-cost-value").html(response.cost_value);
                    $("#inventory-selling-value").html(response.selling_value);
                },
                error: function(err) {
                    $("#inventory-product-count").html(0);
                    $("#inventory-cost-value").html(0);
                    $("#inventory-selling-value").html(0);
                }
            });
        }

    </script>
    @endpush