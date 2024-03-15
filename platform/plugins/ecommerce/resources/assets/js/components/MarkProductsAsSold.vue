<template>
    <div class="flexbox-grid no-pd-none">
        <div class="flexbox-content">
            <div class="wrapper-content">
                <div class="pd-all-20">
                    <label class="title-product-main text-no-bold">Mark Sold Products</label>
                </div>
                <div class="pd-all-10-20 border-top-title-main">
                    <div class="clearfix">
                        <div class="table-wrapper p-none mb20 ps-relative z-index-4" v-if="child_products.length">
                            <table class="table-normal">
                                <tbody>
                                <tr v-for="variant in child_products">
                                    <td class="width-60-px min-width-60-px">
                                        <div class="wrap-img vertical-align-m-i">
                                            <img class="thumb-image" :src="variant.image_url"
                                                 :alt="variant.product_name">
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 min-width-200-px">
                                        <a class="hover-underline pre-line" :href="variant.product_link"
                                           target="_blank">{{ variant.product_name }}</a>
                                        <p class="type-subdued"
                                           v-if="variant.variation_items && variant.variation_items.length">
                                            <span v-for="(productItem, index) in variant.variation_items">
                                                {{ productItem.attribute_title }}
                                                <span v-if="index !== variant.variation_items.length - 1">/</span>
                                            </span>
                                        </p>
                                    </td>
                                    <td class="pl5 p-r5 width-100-px min-width-100-px text-center">
                                        <div class="dropup dropdown-priceOrderNew">
                                            <div class="inline_block dropdown">
                                                <a class="wordwrap hide-print">{{ variant.price }} {{ currency }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pl5 p-r5 width-20-px min-width-20-px text-center"> x</td>
                                    <td class="pl5 p-r5 width-100-px min-width-100-px">
                                        <input class="next-input p-none-r" v-model="variant.select_qty" type="number"
                                               min="1" @change="handleChangeQuantity()">
                                    </td>
                                    <td class="pl5 p-r5 width-100-px min-width-100-px text-center">{{ variant.price }}
                                        {{ currency }}
                                    </td>
                                    <td class="pl5 p-r5 text-end width-20-px min-width-20-px">
                                        <a href="#" @click="handleRemoveVariant($event, variant)">
                                            <svg class="svg-next-icon svg-next-icon-size-12">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-remove"></use>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-search-advance product">
                            <div>
                                <input type="text" class="next-input textbox-advancesearch product"
                                       :placeholder="__('order.search_or_create_new_product')"
                                       @click="loadListProductsAndVariations()"
                                       @keyup="handleSearchProduct($event.target.value)">
                            </div>
                            <div class="panel panel-default"
                                 v-bind:class="{ active: list_products, hidden : hidden_product_search_panel }">
                                <div class="panel-body">
                                    <!-- <div class="box-search-advance-head" v-b-modal.add-product-item>
                                        <img width="30"
                                             src="/vendor/core/plugins/ecommerce/images/next-create-custom-line-item.svg" alt="icon">
                                        <span class="ml10">{{ __('order.create_a_new_product') }}</span>
                                    </div> -->
                                    <div class="list-search-data">
                                        <div class="has-loading" v-show="loading">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                        <ul class="clearfix" v-show="!loading">
                                            <li v-for="product_item in list_products.data"
                                                v-bind:class="{ 'item-selectable' : !product_item.variations.length, 'item-not-selectable' : product_item.variations.length }"
                                                v-on="!product_item.variations.length ? { click : () => selectProductVariant(product_item) } : {}">
                                                <div class="wrap-img inline_block vertical-align-t float-start">
                                                    <img class="thumb-image"
                                                         :src="product_item.image_url"
                                                         :title="product_item.name" :alt="product_item.name">
                                                </div>
                                                <label class="inline_block ml10 mt10 ws-nm"
                                                       style="width:calc(100% - 100px);">{{
                                                    product_item.name  }}
                                                    <span  v-if="product_item.sku"> ({{product_item.sku}}) </span>
                                                    <span v-if="!product_item.variations.length">
                                                        <span v-if="product_item.is_out_of_stock" class="text-danger"><small>&nbsp;({{ __('order.out_of_stock') }})</small></span>
                                                        <span v-if="!product_item.is_out_of_stock && product_item.quantity > 0"><small>&nbsp;({{ product_item.quantity }} {{ __('order.products_available') }})</small></span>
                                                    </span>
                                                </label>
                                                <div v-if="product_item.variations.length">
                                                    <div class="clear"></div>
                                                    <ul>
                                                        <li class="clearfix product-variant"
                                                            v-for="variation in product_item.variations"
                                                            @click="selectProductVariant(product_item, variation)"
                                                            v-if="variation.variation_items.length">
                                                            <a class="color_green float-start">
                                                                <span v-for="(productItem, index) in variation.variation_items">
                                                                    {{ productItem.attribute_title }}
                                                                    <span v-if="index !== variation.variation_items.length - 1">/</span>
                                                                </span>
                                                            </a>
                                                            <span v-if="variation.is_out_of_stock" class="text-danger"><small>&nbsp;({{ __('order.out_of_stock') }})</small></span>
                                                            <span v-if="!variation.is_out_of_stock && variation.quantity > 0"><small>&nbsp;({{ variation.quantity }} {{ __('order.products_available') }})</small></span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li v-if="list_products.data.length === 0">
                                                <span>{{ __('order.no_products_found')}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-footer"
                                     v-if="list_products.next_page_url || list_products.prev_page_url">
                                    <div class="btn-group float-end">
                                        <button type="button"
                                                @click="loadListProductsAndVariations((list_products.prev_page_url ? list_products.current_page - 1 : list_products.current_page), true)"
                                                v-bind:class="{ 'btn btn-secondary': list_products.current_page !== 1, 'btn btn-secondary disable': list_products.current_page === 1}"
                                                :disabled="list_products.current_page === 1">
                                            <svg role="img"
                                                 class="svg-next-icon svg-next-icon-size-16 svg-next-icon-rotate-180">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-chevron"></use>
                                            </svg>
                                        </button>
                                        <button type="button"
                                                @click="loadListProductsAndVariations((list_products.next_page_url ? list_products.current_page + 1 : list_products.current_page), true)"
                                                v-bind:class="{ 'btn btn-secondary': list_products.next_page_url, 'btn btn-secondary disable': !list_products.next_page_url }"
                                                :disabled="!list_products.next_page_url">
                                            <svg role="img" class="svg-next-icon svg-next-icon-size-16">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#next-chevron"></use>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="pd-all-10-20 p-none-t">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label class="text-title-field" for="txt-note">{{ __('order.note') }}</label>
                                <textarea class="ui-text-area textarea-auto-height" id="txt-note" rows="2"
                                          :placeholder="__('order.note_for_order')" v-model="note"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="table-wrap">
                                <table class="table-normal table-none-border table-color-gray-text text-end">
                                    <tbody>
                                    <tr>
                                        <td class="color-subtext">{{ __('order.amount') }}</td>
                                        <td class="pl10">{{ child_sub_amount | formatPrice }} {{ currency }}</td>
                                    </tr>
                                    <tr>
                                        <td class="color-subtext">Tax</td>
                                        <td class="pl10">{{ child_tax_amount }} {{ currency }}</td>
                                    </tr>
                                    <!-- <tr>
                                        <td>
                                            <a href="#" v-b-modal.add-discounts class="hover-underline">
                                                <span v-if="!has_applied_discount"><i class="fa fa-plus-circle"></i> {{ __('order.add_discount') }}</span>
                                                <span v-if="has_applied_discount">{{ __('order.discount')}}</span>
                                            </a>
                                            <p class="mb0 font-size-12px"
                                               v-if="child_discount_description && has_applied_discount">{{
                                                child_discount_description }}</p>
                                        </td>
                                        <td class="pl10">{{ has_applied_discount ? child_discount_amount : 0 | formatPrice }} {{ currency }}</td>
                                    </tr> -->
                                   
                                    <tr class="text-no-bold">
                                        <td>{{ __('order.total_amount') }}</td>
                                        <td class="pl10">{{ child_total_amount | formatPrice }} {{ currency }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pd-all-10-20 border-top-color">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-12 col-lg-6">
                            <div class="flexbox-grid-default mt5 mb5">
                                <div class="flexbox-auto-left p-r10">
                                    <!-- <i class="fa fa-credit-card fa-1-5 color-blue"></i> -->
                                </div>
                                <div class="flexbox-auto-content">
                                    <!-- <div class="text-upper ws-nm">{{ __('order.confirm_payment_and_create_order') }}</div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-12 col-lg-6 text-end">
                            <button class="btn btn-primary" v-b-modal.make-paid @click="createOrder($event, 1)"
                                    :disabled="!child_product_ids.length">Mark as Sold
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            products: {
                type: Array,
                default: () => [],
            },
            product_ids: {
                type: Array,
                default: () => [],
            },
            customer_id: {
                type: Number,
                default: () => null,
            },
            customer: {
                type: Object,
                default: () => {
                    return {
                        email: 'sgowtham3005@gmail.com',
                    };
                },
            },
            customer_addresses: {
                type: Array,
                default: () => [],
            },
            customer_address: {
                type: Object,
                default: () => ({
                    name: null,
                    email: null,
                    address: null,
                    phone: null,
                    country: 'AF',
                    state: null,
                    city: null,
                    zip_code: null,
                }),
            },
            customer_order_numbers: {
                type: Number,
                default: () => 0,
            },
            sub_amount: {
                type: Number,
                default: () => 0,
            },
            tax_amount: {
                type: Number,
                default: () => 0,
            },
            total_amount: {
                type: Number,
                default: () => 0,
            },
            discount_amount: {
                type: Number,
                default: () => 0,
            },
            discount_description: {
                type: String,
                default: () => null,
            },
            shipping_amount: {
                type: Number,
                default: () => 0,
            },
            shipping_method: {
                type: String,
                default: () => 'default',
            },
            shipping_option: {
                type: String,
                default: () => '',
            },
            is_selected_shipping: {
                type: Boolean,
                default: () => false,
            },
            shipping_method_name: {
                type: String,
                default: () => 'Default',
            },
            payment_method: {
                type: String,
                default: () => 'cod',
            },
            currency: {
                type: String,
                default: () => null,
                required: true
            },
            zip_code_enabled: {
                type: Number,
                default: () => 0,
                required: true
            },
            tax_percentage : {
                type: Number,
                default: () => 13,
                required: true
            }
        },
        data: function () {
            return {
                list_products: {
                    data: [],
                },
                hidden_product_search_panel: true,
                loading: false,
                note: null,
                customers: {
                    data: [],
                },
                hidden_customer_search_panel: true,
                customer_keyword: null,
                countries: [],
                shipping_type: 'custom',
                product: {
                    name: null,
                    price: 0,
                    sku: null,
                    with_storehouse_management: false,
                    allow_checkout_when_out_of_stock: false,
                    quantity: 0,
                },
                shipping_methods: {
                    'default': {
                        name: 'Default',
                        price: 0,
                    }
                },
                discount_type_unit: this.currency,
                discount_type: 'amount',
                coupon_code: null,
                child_discount_description: this.discount_description,
                has_invalid_coupon: false,
                has_applied_discount: this.discount_amount > 0,
                discount_custom_value: 0,
                child_customer: this.customer,
                child_customer_id: this.customer_id,
                child_customer_order_numbers: this.customer_order_numbers,
                child_customer_addresses: this.customer_addresses,
                child_customer_address: this.customer_address,
                child_products: this.products,
                child_product_ids: this.product_ids,
                child_sub_amount: this.sub_amount,
                child_tax_amount: this.tax_amount,
                child_tax_percentage: this.tax_percentage,
                child_total_amount: this.total_amount,
                child_discount_amount: this.discount_amount,
                child_shipping_amount: this.shipping_amount,
                child_shipping_method: this.shipping_method,
                child_shipping_option: this.shipping_option,
                child_shipping_method_name: this.shipping_method_name,
                child_is_selected_shipping: this.is_selected_shipping,
                child_payment_method: this.payment_method,
            }
        },
        mounted: function () {
            let context = this;
            $(document).on('click', 'body', e => {
                let container = $('.box-search-advance');

                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    context.hidden_customer_search_panel = true;
                    context.hidden_product_search_panel = true;
                }
            });        
        },
        methods: {
            loadListProductsAndVariations: function (page = 1, force = false) {
                let context = this;
                context.hidden_product_search_panel = false;
                $('.textbox-advancesearch.product').closest('.box-search-advance.product').find('.panel').addClass('active');
                if (_.isEmpty(context.list_products.data) || force) {
                    context.loading = true;
                    axios
                        .get(route('products.get-all-products-and-variations', {
                            keyword: context.product_keyword,
                            page: page
                        }))
                        .then(res => {
                            context.list_products = res.data.data;
                            context.loading = false;
                        })
                        .catch(res => {
                            Botble.handleError(res.response.data);
                        });
                }
            },
            handleSearchProduct: function (value) {
                if (value !== this.product_keyword) {
                    let context = this;
                    this.product_keyword = value;
                    setTimeout(() => {
                        context.loadListProductsAndVariations(1, true);
                    }, 500);
                }
            },
            selectProductVariant: function (product, variation = null) {
                if ((!_.isEmpty(variation) && variation.is_out_of_stock) || (_.isEmpty(variation) && product.is_out_of_stock)) {
                    Botble.showError(__('order.cant_select_out_of_stock_product'));
                    return false;
                }

                if (!_.isEmpty(variation)) {
                    if (!_.includes(this.child_product_ids, variation.product_id)) {
                        let productItem = variation;
                        productItem.product_name = product.name;
                        productItem.image_url = product.image_url;
                        productItem.price = variation.price;
                        productItem.product_link = route('products.edit', variation.product_id);
                        productItem.select_qty = 1;
                        this.child_products.push(productItem);
                        this.child_product_ids.push(variation.product_id);
                    }
                } else if (!_.includes(this.child_product_ids, product.id)) {
                    let productItem = product;
                    productItem.product_name = product.name;
                    productItem.image_url = product.image_url;
                    productItem.price = product.price;
                    productItem.product_link = route('products.edit', product.id);
                    productItem.select_qty = 1;
                    this.child_products.push(productItem);
                    this.child_product_ids.push(product.id);
                }
                this.hidden_product_search_panel = true;
            },
            handleRemoveVariant: function ($event, variant) {
                $event.preventDefault();
                if (variant.product_id) {
                    this.child_product_ids = _.reject(this.child_product_ids, (item) => {
                        return item === variant.product_id;
                    });

                    this.child_products = _.reject(this.child_products, (item) => {
                        return item.product_id === variant.product_id;
                    });
                } else {
                    this.child_product_ids = _.reject(this.child_product_ids, (item) => {
                        return item === variant.id;
                    });

                    this.child_products = _.reject(this.child_products, (item) => {
                        return item.id === variant.id;
                    });
                }
            },
            createOrder: function ($event, paid = false) {
                $event.preventDefault();
                $($event.target).find('.btn-primary').addClass('button-loading');
                let context = this;

                let products = [];
                _.each(this.child_products, function (item) {
                    products.push({
                        id: (item.configurable_product_id ? item.product_id : item.id),
                        quantity: item.select_qty
                    });
                });

                axios
                    .post(route('orders.store-order'), {
                        products: products,
                        payment_status: 'completed',
                        payment_method: this.child_payment_method,
                        shipping_method: this.child_shipping_method,
                        shipping_option: this.child_shipping_option,
                        shipping_amount: this.child_shipping_amount,
                        discount_amount: this.child_discount_amount,
                        tax_amount: this.child_tax_amount,
                        discount_description: this.child_discount_description,
                        note: this.note,
                        amount: this.child_sub_amount,
                    })
                    .then(res => {
                        let data = res.data.data;
                        if (res.data.error) {
                            Botble.showError(res.data.message);
                            $($event.target).find('.btn-primary').removeClass('button-loading');
                        } else {
                            Botble.showSuccess(res.data.message);
                            if (paid) {
                                context.$root.$emit('bv::hide::modal', 'make-paid');
                            } else {
                                context.$root.$emit('bv::hide::modal', 'make-pending');
                            }

                            setTimeout(() => {
                                window.location.href = route('orders.store-order-success', data.id);
                            }, 1000);
                        }
                    })
                    .catch(res => {
                        Botble.handleError(res.response.data);
                        $($event.target).find('.btn-primary').removeClass('button-loading');
                    });
            },
            calculateAmount: function (products) {
                let context = this;
                console.log(context.child_tax_amount);
                context.child_sub_amount = 0;
                _.each(products, function (item) {
                    context.child_sub_amount += parseFloat(item.price) * parseInt(item.select_qty);
                });
                context.child_tax_amount = (parseFloat(context.child_sub_amount) * parseFloat(context.child_tax_percentage/100)); 
                context.child_total_amount = parseFloat(context.child_sub_amount) - parseFloat(context.child_discount_amount) + parseFloat(context.child_shipping_amount)+ parseFloat(context.child_tax_amount);
                if (context.child_total_amount < 0) {
                    context.child_total_amount = 0;
                }
            },
            handleChangeQuantity: function () {
                this.calculateAmount(this.child_products);
            },
            resetProductData: function () {
                this.product = {
                    name: null,
                    price: 0,
                    sku: null,
                    with_storehouse_management: false,
                    allow_checkout_when_out_of_stock: false,
                    quantity: 0,
                };
            }
        },
        watch: {
            'child_products': function (value) {
                this.calculateAmount(value);
            },
            'child_discount_amount': function (value) {
                let context = this;
                context.child_total_amount = parseFloat(context.child_sub_amount) - parseFloat(value) + parseFloat(context.child_shipping_amount);
            },
            'child_shipping_amount': function (value) {
                let context = this;
                context.child_total_amount = parseFloat(context.child_sub_amount) - parseFloat(context.child_discount_amount) + parseFloat(value);
            },
            'shipping_type': function (value) {
                if (value === 'free-shipping') {
                    this.child_shipping_amount = 0;
                }
            },
        }
    }
</script>
