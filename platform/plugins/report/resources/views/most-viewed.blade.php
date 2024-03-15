@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')

<section">
    <div class="row mt-5">
        <div class="col-lg-12">
            <div class="rp-card bg-white h-100">
                <div class="rp-card__header">
                    <h5 class="px-2 py-3">Most Viewed Products</h5>
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
                    <h5 class="px-2 py-3">Most Viewed Categories</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $categoryTable->renderTable() !!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="rp-card bg-white h-100">
                <div class="rp-card-header">
                    <h5 class="px-2 py-3">Most Viewed SubCategories</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $subCategoryTable->renderTable() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-lg-6 m">
            <div class="rp-card bg-white h-100">
                <div class="rp-card-header">
                    <h5 class="px-2 py-3">Most Viewed SubSubCategories</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $subSubCategoryTable->renderTable() !!}
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 m">
            <div class="rp-card bg-white h-100">
                <div class="rp-card-header">
                    <h5 class="px-2 py-3">Most Searched Keywords</h5>
                </div>
                <div class="rp-card-content equal-height">
                    {!! $searchTable->renderTable() !!}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('footer')

@endpush