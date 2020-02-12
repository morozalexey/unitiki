<?php $this->layout('layout', ['title' => 'Admin Page']) ?>

<div class="col-md-12">
    <div class="card">
        <div class="card-header"><h3>Админ панель</h3></div>

        <div class="col-md-12" style="margin-top: 20px;">                        
    <div class="card">
        <div class="card-header"><h3>Добавить статью</h3></div>

        <div class="card-body">            
            <form action="new_post" method="post">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Название</label>
                    <input name="title" class="form-control" id="exampleFormControlTextarea1" />
                </div>                               
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Текст статьи</label>
                    <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>               
                </div>
                <div class="form-group">
                <p><select name="category">
                    <option>Выберите из списка</option>
                    <?php foreach($categories as $category):?>                    
                        <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                    <?php endforeach; ?>
                </select></p>              
                </div>


                <button type="submit" class="btn btn-success">Отправить</button>                                
            </form>    
        </div>
    </div>
</div>


        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>                        
                        <th>Название</th>
                        <th>Дата</th>
                        <th>Текст</th>
                        <th>Действия</th>
                    </tr>
                </thead>

                <tbody>
				<?php foreach($posts as $post):?>
				
                    <tr>										
                        
                        <td><?= $post['title'] ?></td>
                        <td><?= $post['dt_add'] ?></td>
                        <td><?= $post['text'] ?></td>
                        <td>

							<?php if ($post['hide'] != 1) : ?>
                            <a href="/show?id=<?= $post['id'] ?>" class="btn btn-success">Разрешить</a>							
							<?php else : ?>
                            <a href="/hide?id=<?= $post['id'] ?>" class="btn btn-warning">Запретить</a>
							<?php endif ; ?>
                            <a href="/delete?id=<?= $post['id'] ?>" onclick="return confirm('are you sure?')" class="btn btn-danger">Удалить</a>
                            <a href="/post_page?id=<?= $post['id'] ?>" class="btn btn-info">Редактировать</a>
                        </td>											
                    </tr>
				<?php endforeach; ?>									
				
				
                </tbody>
            </table>
        </div>
    </div>
</div>