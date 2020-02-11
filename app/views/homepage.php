<?php $this->layout('layout', ['title' => 'Posts']) ?>

<div class="col-md-12">
                     
	 <div class="card">
	    <div class="card-header"><h3>Статьи</h3></div>
        <?php foreach($posts as $post):?>	
	    <div class="card-body">	      
									  
	        <div class="media">	        			
	          <div class="media-body">
	            <h5 class="mt-0"><?= $post['title']?></h5> 								
	            <span><small><?= date("d/m/Y", strtotime($post['dt_add']))?></small></span>								
	            <p><?= $post['text']?></p>	
                <p><a href="/category?category_id=<?= $post['category_id'] ?>"><?= $post['category_name']?></a>	</p>							
	          </div>
	        </div>
		
	    </div>
        <?php endforeach; ?>
	</div>                   
</div>
                
<?php d($posts) ?>
