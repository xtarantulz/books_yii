<?php
    /* @var $this BooksController */
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile('/css/books.css');

    $this->breadcrumbs=array('Книги');
?>

<div class='books'>
    <div class='left_block'>
        <h2>Фильтры</h2>
        <form action='' method='post'>
            <div class='one_filtr'>
                <h3>Авторы:</h3>
                <?php $i=0;   
                foreach($authors as $author):
                    $i=$i+1;?>
                    <p>
                        <?php if(isset($post['authors']['aid'][$i])): ?>
                            <input checked id='aid<?=$i;?>' type='checkbox' name='aids[<?=$i;?>]' value='<?=$author->aid;?>'> 
                        <?php else: ?>
                            <input id='aid<?=$i;?>' type='checkbox' name='aids[<?=$i;?>]' value='<?=$author->aid;?>'>
                        <?php endif; ?>
                        <label for='aid<?=$i;?>'><?=$author->name.' '.$author->surname;?></label>
                    </p>
                <?php endforeach;?>
            </div>
            
            <div class='one_filtr'>
                <h3>Жанр:</h3>
                <select name='gid'>
                    <option value=''>Не выбран</option>
                    <?php foreach($genres as $genre): ?>
                        <?php if($post['genre']['gid']==$genre->gid): ?>
                            <option selected value='<?=$genre->gid;?>'><?=$genre->name;?></option>
                        <?php else: ?>
                            <option value='<?=$genre->gid;?>'><?=$genre->name;?></option>
                        <?php endif; ?>
		    <?php endforeach;?>
                </select>
            </div>
            
            <div class='one_filtr'>
                <h3>Года:</h3>
                <p>
                    <?php if($post['from_year']!=-10000): ?>
                        <i>с </i><input type='text' value='<?=$post['from_year'];?>' name='from_year'>
                    <?php else: ?>
                        <i>с </i><input type='text' value='' name='from_year'>
                    <?php endif; ?>
                </p>
                <p>
                    <?php if($post['to_year']!=10000): ?>
                        <i>по </i><input type='text' value='<?=$post['to_year'];?>' name='to_year'>
                    <?php else: ?>
                        <i>по </i><input type='text' value='' name='to_year'>
                    <?php endif; ?>
                </p>
            </div>
            <input type='submit' value='Найти'>
        </form>
    </div>
    <div class='right_block'>
        <h2>Книги</h2>
        <table>
            <tr>
                <td>id</td>
                <td>Название книги</td>
                <td>Авторы</td>
                <td>Жанры</td>
                <td>Год</td>
            </tr>
            
            <?php for($i=0;$i<count($books);$i=$i+1): ?>
                <tr>
                    <td><?=$books[$i]['book']['bid'];?></td>
                    <td><?=$books[$i]['book']['name'];?></td>
                    <td>
                        <?php for($j=0;$j<count($books[$i]['authors']['name']);$j=$j+1): ?>
                            <?=$books[$i]['authors']['name'][$j].' '.$books[$i]['authors']['surname'][$j].'<br />'; ?>
                        <?php endfor;?>
                    </td>
                    <td>
                        <?php for($j=0;$j<count($books[$i]['genres']['name']);$j=$j+1): ?>
                            <?=$books[$i]['genres']['name'][$j].'<br />'; ?>
                        <?php endfor;?>
                    </td>
                    <td><?=$books[$i]['book']['year'];?></td>
                </tr>
            <?php endfor;?>
        </table>
    </div>
</div> 