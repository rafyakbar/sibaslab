<template>
    <ul class="list-group">
        <li class="list-group-item" v-for="item in list" :key="item.id">
            {{ item.nama }}
        </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
                list: []
            }
        },
        mounted() {
            this.list = this.$root.list
        }
    }
</script>

<style>
.list-group {
    margin: 0 -20px 0;
    padding: 0;
}

.list-group > .list-group-item {
    border-radius: 0;
    text-align: left;
    font-weight: bold;
    border-right: none;
    border-left: none;
}
</style>
