<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Event detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/users">Event</a></li>
                        <li class="breadcrumb-item active">Event detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td><label>Colom 1 : </label> <?php echo 'Colom 1' ?></td>
										<td><label>Colom 2 : </label> <?php echo 'Colom 2' ?></td>
									</tr>
									
									
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
</div>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
    $(function () {
        $("#example1").DataTable();
        $("#example2").DataTable();
    });
</script>
<style>
	div#example2_filter {
		margin-left: 280px;
	}
</style>