@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="max-width-1200" id="main-order">
        <mark-products-as-sold :currency="'{{ get_application_currency()->symbol }}'" :zip_code_enabled="{{ (int)EcommerceHelper::isZipCodeEnabled() }}"
            :tax_percentage="{{\DB::table('ec_taxes')->find(get_ecommerce_setting('ecommerce_default_tax_rate', 1))->percentage}}">
        </mark-products-as-sold>
    </div>
@stop

@push('header')
    <script>
        'use strict';

        window.trans = window.trans || {};

        window.trans.order = JSON.parse('{!! addslashes(json_encode(trans('plugins/ecommerce::order'))) !!}');
    </script>
@endpush
