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
			</div>

			<!-- -->
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			kasublab: [Object]
		}
	}
</script>