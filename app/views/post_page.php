<?php $this->layout('layout', ['title' => 'User Profile']) ?>

<div class="col-md-12">
    <div class="card">
        <div class="card-header"><h3>Изменить запись</h3></div>

        <div class="card-body">
          <?php echo flash()->display();?>
          <?php d($posts);?> 
            <form action="/change_post" method="POST" enctype="multipart/form-data">
            
                <div class="row">			
				    <input type="hidden" name="id" value="<?= $posts['id'] ?>" >
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Название</label>
                            <input type="text" class="form-control" name="title" id="exampleFormControlInput1" value="<?= $posts['title'] ?>">                                                                      
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Текст</label>
                            <input type="text" class="form-control" name="text" id="exampleFormControlInput1" value="<?= $posts['text'] ?>">                            
                        </div>
                    </div>
						
                    <div class="col-md-12">                        
                        
                        <button type="submit" class="btn btn-success">Изменить</button>
                    </div>
                </div>
                </form>
        </div>
    </div>
</div>
