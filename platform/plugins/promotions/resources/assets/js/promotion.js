class EcommerceProduct {
    constructor() {
        this.$body = $('body');
        this.handleNeverExpires();
    }

    handleNeverExpires() {
        let _self = this;

        if($('.to-label').hasClass('hidden')){
            $('.to-label').parent().addClass('hidden');
        }

        _self.$body.on('click', '#never_expires', event => {
            let $toDateDiv = $('.to-label').parent();
            if ($(event.currentTarget).prop('checked') === true) {
                $toDateDiv.addClass('hidden');
                $('.to-label').addClass('hidden');
            } else {
                $toDateDiv.removeClass('hidden');
                $('.to-label').removeClass('hidden');

            }
        })
    }

}

$(window).on('load', () => {
    new EcommerceProduct();
});

