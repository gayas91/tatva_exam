<?php
	use Cake\Core\Configure;
	if(!isset($className)){
		$className = $this->request->params['controller'];
	}
	$totalRecords	=	$this->Paginator->params()['count'];
	// $pageCount		=	$this->Paginator->params()['pageCount'];
	$limit			=	Configure::read('ADMIN_PAGE_LIMIT');
	/* pr($this->Paginator->params());
	$prevRecords=	($this->Paginator->params()['page']-1) * $limit;
	pr($prevRecords);
	$toRecord	=	$this->Paginator->params()['page'] * $limit;
	$fromRecord	=	$toRecord- ($limit - 1); */
	// pr(();
	// pr($totalRecords);
?>
<?php if($totalRecords > $limit) { ?>
	<div class="box-footer clearfix ">
		<ul class="pagination pagination-sm no-margin pull-right">
			<li>
				<?php echo $this->Paginator->prev(__('Previous')) ?>
				<?php echo $this->Paginator->numbers(array('separator' => '','tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active')); ?>
				<?php echo  $this->Paginator->next(__('Next')) ?>
			</li>
		</ul>
		<?php /* <p>
			<?php echo 'Showing '.$fromRecord.' to '.$toRecord.' of '.$totalRecords.' entries'; ?>
		</p> */ ?>
		<p>Total Record: <?php echo $totalRecords.', '; ?>Page of pages: <?php echo $this->Paginator->counter(); ?></p>
	</div>
<?php } ?>