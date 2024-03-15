<template>
    <div class="col-12">
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>

        <div v-if="!isLoading" class="mt-md-0 row p-2">
            <div class="item col-6 col-md-6 col-lg-3 mt-1" style="padding-left: 5px; padding-right: 5px;" v-for="item in data">
                <div class="categories_box">
                    <a :href="item.url">
                        <img :src="item.image" :alt="item.name" class="w-100" loading="lazy" />
                        <span>{{ item.name }} {{ item.count > 0 ? '(' + item.count + ')' : '' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            isLoading: true,
            data: []
        };
    },
    props: {
        url: {
            type: String,
            default: () => null,
            required: true
        },
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            this.data = [];
            this.isLoading = true;
            axios.get(this.url)
                .then(res => {
                    this.data = res.data.data ? res.data.data : [];
                    this.isLoading = false;
                })
                .catch(res => {
                    this.isLoading = false;
                    console.log(res);
                });
        },
    },
}
</script>
