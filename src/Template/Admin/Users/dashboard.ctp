<style type="text/css">
.pagination {
	display: flex;
	padding-left: 144px;
}
div#example1_filter {
	margin-left: 280px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Dashboard</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
	</div>
	<!-- Main content -->
	<section class="content dashboard_outer">
		<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
			
			
		</div>
	</section>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
	$(function () {
	    $("#example1").DataTable();
	});
</script>