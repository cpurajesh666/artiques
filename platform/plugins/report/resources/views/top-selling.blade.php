@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')

<section">
    
    <div class="row justify-content-center">
        <div class="col-3">
            <label class="mb-2 mt-2">From Date</label>
            <input type="date" class="form-control" id="from-date" name="from-date" 
            value="{{ $fromDate }}">
        </div>
        <div class="col-3">
            <label class="mb-2 mt-2">To Date</label>
            <input type="date" class="form-control" name="to-date" id="to-date"
            value="{{ $toDate }}">
        </div>
        <div class="col-2 mb-1 d-flex align-items-end">
            <button type="button" onclick="reloadData()" class="btn btn-primary">Filter</button>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-12">
            <div class="rp-card bg-white h-100">
                <div class="rp-card__header">
                    <h5 class="px-2 py-3">Top Selling Products</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $productTable->renderTable() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-lg-6">
            <div class="rp-card bg-white h-100">
                <div class="rp-card__header">
                    <h5 class="px-2 py-3">Top Selling Categories</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $categoryTable->renderTable() !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="rp-card bg-white h-100">
                <div class="rp-card-header">
                    <h5 class="px-2 py-3">Top Selling SubCategories</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $subCategoryTable->renderTable() !!}
                </div>
            </div>
        </div>

    </div>

    <div class="row mt-5">
        <div class="col-lg-6">
            <div class="rp-card bg-white h-100">
                <div class="rp-card-header">
                    <h5 class="px-2 py-3">Top Selling SubSubCategories</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $subSubCategoryTable->renderTable() !!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="rp-card bg-white h-100">
                <div class="rp-card-header">
                    <h5 class="px-2 py-3">Top Selling Suppliers</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $supplierTable->renderTable() !!}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('footer')
<script>
   
    
    function reloadData() {
        let url = window.location.protocol + "//" + window.location.hostname + window.location.pathname + '?';
        let fromDate = $('#from-date').val();
        let toDate = $('#to-date').val();
        if(fromDate){
            url += ('fromDate=' + fromDate);
        }
        if(toDate){
            url += ('&toDate=' + toDate);
        }
        
        window.location =  url;
    }

</script>
@endpush