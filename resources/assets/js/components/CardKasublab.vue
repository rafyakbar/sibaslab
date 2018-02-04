<template>
	<div class="col-md-6">
		<div class="card">
			<div class="card-block">
				<div class="title-block">
					<h4 class="title text-success">{{ kasublab.nama }}</h4>
				</div>

				<h6>{{ kasublab.id }}</h6>

				<ul class="list-group">
					<li class="list-group-item">Belum menanggapi {{ kasublab.belum_menanggapi }} surat</li>
					<li class="list-group-item">Masih belum menyetujui {{ kasublab.menolak }} surat</li>
				</ul>

				<div class="btn-group">
					<button class="btn btn-danger" @click="hapus">Hapus</button>
				</div>
			</div>

			<!-- -->
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			kasublab: [Object]
		},
		methods: {
			hapus() {
				let that = this

				swal({
					icon: 'warning',
					title: 'Apa anda yakin ?',
					buttons: ['Batal', 'Yakin'],
					closeOnConfirm: false
				}).then((clicked) => {
					if(clicked) {
						$.ajax({
							url: that.$root.url_hapus,
							type: 'POST',
							data: 'nip=' + that.kasublab.id,
							success: (response) => {
								if(response.success) {
									swal({
										icon: 'success',
										text: response.success
									})

									that.$root.removeData(that.kasublab.id)
								}
							},
							error: (response) => {
								response = response.responseJSON

								if(response.error) {
									swal({
										icon: 'error',
										text: response.error
									})
								}
							}
						})
					}
				})
			}
		}
	}
</script>

<style lang="scss">
.title-block {
	margin-bottom: 10px !important;
}

.btn-group {
	margin-top: 10px;
}

.list-group {
	margin: 0 -15px 0;
	border-radius: 0;

	> .list-group-item {
		border-left: none;
		border-right: none;
	}
}
</style>

