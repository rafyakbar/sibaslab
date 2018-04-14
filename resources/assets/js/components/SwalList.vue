<template>
<div>
    <ul class="list-group" v-if="nama == null">
        <li :class="{'list-group-item': true, 'pointer': data_belum_menyetujui}" v-for="item in list" :key="item.id" @click="tampilkanCatatan($event, item)">
            {{ item.nama }}
        </li>
    </ul>

    <div style="text-align: left;padding: 0" v-if="nama != null">
        <b>Catatan dari {{ nama }} : </b><br/>
        <p class="alert alert-info">{{ catatan }}</p>
        <button @click="tampilkanDaftarBelumMenyetujui" class="btn btn-primary">Kembali</button>
    </div>
</div>
</template>

<script>
    export default {
        props: [
            'belum-menyetujui'
        ],
        data() {
            return {
                list: [],
                data_belum_menyetujui: false,
                nama: null,
                catatan: null
            }
        },
        mounted() {
            this.list = this.$root.list
            this.data_belum_menyetujui = this.belumMenyetujui == 'true'
        },
        methods: {
            tampilkanCatatan(e, item) {
                this.nama = item.nama
                this.catatan = item.pivot.catatan
            },
            tampilkanDaftarBelumMenyetujui() {
                this.nama = null
                this.catatan = null
            }
        }
    }
</script>

<style>
.list-group {
    margin: 0 -20px 0;
    padding: 0;
    max-height: 300px;
    overflow: scroll;
}

.list-group > .list-group-item {
    border-radius: 0;
    text-align: left;
    font-weight: bold;
    border-right: none;
    border-left: none;
}

.pointer {
    cursor: pointer;
}
</style>
