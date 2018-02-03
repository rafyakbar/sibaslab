<template>
	<form>
		<div class="form-group">
			<label class="control-label" for="nip">NIP/NIDN</label>
			<input type="number" class="form-control boxed" id="nip" v-model="nip">
		</div>
		<p class="alert alert-danger" id="nip-error" v-show="errors.nip.check">
			{{ errors.nip.text }}
		</p>
		<div class="form-group">
			<label class="control-label" for="nama">Nama</label>
			<input type="text" class="form-control boxed" id="nama" v-model="nama">
		</div>
		<p class="alert alert-danger" id="nip-error" v-show="errors.nama.check">
			{{ errors.nama.text }}
		</p>
		<div class="form-group">
			<label class="control-label" for="nama">Program Studi</label>
			<select class="form-control" v-model="prodi" name="prodi">
				<option v-for="prodi in daftarProdi" :key="prodi.id" :value="prodi.id">{{ prodi.nama }}</option>
			</select>
		</div>
		<p class="alert alert-danger" id="nip-error" v-show="errors.prodi.check">
			{{ errors.prodi.text }}
		</p>
		<div class="form-group">
			<button class="btn btn-primary" @click.prevent="tambah">Tambah</button>
		</div>
	</form>
</template>

<script>
	export default {
		data() {
			return {
				daftarProdi: [],
				nama: '',
				nip: '',
				prodi: '',
				errors: {
					nip: {
						text: null,
						check: false
					},
					prodi: {
						text: null,
						check: false
					},
					nama: {
						text: null,
						check: false
					}
				}
			}
		},
		created() {
			this.daftarProdi = this.$root.daftarProdi	
		},
		methods: {
			tambah() {
				let that = this

				this.errors.nip.check = false
				this.errors.nama.check = false
				this.errors.prodi.check = false

				$.ajax({
					url: this.$root.url,
					type: 'POST',
					data: 'nip='+ this.nip +'&nama='+ this.nama +'&prodi=' + this.prodi,
					success: function (response) {
						if(response.success) {
							swal({
								icon: 'success',
								text: response.success
							}).then((value) => {
								window.location.reload()
							})
						}
					},
					error: function (request, status, error) {
						let response = request.responseJSON
						if(response.errors) {
							if(response.errors.nip) {
								that.errors.nip.check = true
								that.errors.nip.text = response.errors.nip[0]
							}
							if(response.errors.nama) {
								that.errors.nama.check = true
								that.errors.nama.text = response.errors.nama[0]
							}
							if(response.errors.prodi) {
								that.errors.prodi.check = true
								that.errors.prodi.text = response.errors.prodi[0]
							}
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss">
form {
	text-align: left;

	.control-label {
		font-size: 12px;
	}

	.alert {
		font-size: 13px;
		margin: 0 -20px 10px;		

		&.alert-danger {
			background-color: lighten($color: #FF4444, $amount: 30) !important;
			border: none;
			border-radius: 0;			
			color: darken($color: #FF4444, $amount: 20);
		}
	}
}
</style>

