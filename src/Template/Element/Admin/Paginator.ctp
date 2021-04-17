<div class="box-footer clearfix ">
    <ul class="pagination pagination-sm no-margin pull-right">
	<li>

	    <?= $this->Paginator->prev('<  ' . __('previous')) ?>
	    <?= $this->Paginator->numbers() ?>
	    <?= $this->Paginator->next(__('next') . '  >') ?>
	</li>
    </ul>
    <p><?= $this->Paginator->counter() ?></p>
</div>
<script>


    /*$(document).ready(function () {
	$(".pagination a").bind("click", function (event) {
	    event.preventDefault();
	    var url = this.href;
	    var result = url.substring('index');
	    var newurl = url.replace('', '');

	    history.pushState(null, newurl, newurl);
	    if (!$(this).attr('href'))
		return false;
	    $.ajax({
		dataType: "html",
		beforeSend: function () {
		    $(".ajax_loader").show();
		},
		complete: function () {
		    $(".ajax_loader").hide();
		},
		evalScripts: true,
		success: function (data, textStatus) {
		    $("#ajax-container").html(data);
		},
		url: $(this).attr('href')});
	    return false;
	});
    });*/
</script>