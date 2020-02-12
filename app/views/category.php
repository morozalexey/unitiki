<?php $this->layout('layout', ['title' => 'Category']) ?>

<div class="col-md-12">
                     
	 <div class="card">
	    <div class="card-header"><h3>Статьи по категории <?= $postByCategory['category_name']?></h3></div>
        <?php foreach($postsByCategory as $postByCategory):?>	

	    <div class="card-body">								  
	        <div class="media">	        			
	          <div class="media-body">
	            <h5 class="mt-0"><?= $postByCategory['title']?></h5> 								
	            <span><small><?= date("d/m/Y", strtotime($postByCategory['dt_add']))?></small></span>								
	            <p><?= $postByCategory['text']?></p>								
	          </div>
	        </div>
		
	    </div>
        <?php endforeach; ?>
        <?=d($postsByCategory)?>
	</div>                   
</div>
                

