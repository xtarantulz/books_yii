<?php

class BooksController extends Controller
{
    public function actionIndex()
    {
        $arg['authors'] = array();
        $arg['genre'] = array();
        $arg['from_year'] = -10000; //быстрый вариант, чтобы не писать отдельную переменую для запроса
        $arg['to_year'] = 10000; //быстрый вариант, чтобы не писать отдельную переменую для запроса
        
        if(isset($_POST['aids'])||isset($_POST['gid'])||isset($_POST['from_year'])||isset($_POST['to_year'])){
            $post=$_POST;
        }
        //прием постов фильтрации - сори без валидации нет времени)
        if(isset($post['aids'])&&$post['aids']!='') $arg['authors'] = array('aid'=>$post['aids']);
        if(isset($post['gid'])&&$post['gid']!='') $arg['genre'] = array('gid'=>$post['gid']);
        if(isset($post['from_year'])&&$post['from_year']!='') $arg['from_year'] = $post['from_year'];
        if(isset($post['to_year'])&&$post['to_year']!='') $arg['to_year'] = $post['to_year'];
               
        //вызов функции фильтрации и в результате чистый масив книжек с авторами и жанрами
        $books = $this->getBooksFromFiltr($arg);
        
        //для представления беру все автора и жанры
        $authors = Authors::model()->findAll();
        $genres = Genres::model()->findAll();
       
        //переход на представление
        $this->render('index',array(
            'books' => $books,
            'authors' => $authors,
            'genres' => $genres,
            'post' => $arg
   
        ));
    }

    private function getBooksFromFiltr($arg){
        //прием аргументов фильтрации, снова повторюсь сори что без валидации
        $authors = $arg['authors'];
        $genre = $arg['genre'];
        $from_year = $arg['from_year'];
        $to_year = $arg['to_year'];
        
        //все индификаторы книг что прошли фильтр по автору и по жанрам
        $book_author = BookAuthor::model()->with('books')->findAllByAttributes($authors);
        $book_gerne = BookGenre::model()->with('books')->findAllByAttributes($genre);
        
        foreach ($book_author as $class){
            $flag[$class->bid] = true; //методом флажок - выясню есть ли в другом масиве теже bid
        }
        
        foreach ($book_gerne as $class){
            if($flag[$class->bid]) $bids[] = $class->bid; //если была создана ячека флажка, то значит что первый фильтр bid прошел
        }
        
        //выборка всех книг по фильтрованым индификаторам
        $books=Books::model()->findAllByAttributes(array('bid' => $bids),'year>='.$from_year.' AND year<='.$to_year);

        //хочу свормировать красивый масив без ячеек, которые сотворил yii
        $result = array();$i=0;
        foreach($books as $book){
            $result[$i]['authors'] = $this->getAuthorsFromBid($book->bid);
            $result[$i]['genres'] = $this->getGenresFromBid($book->bid);
            $result[$i]['book']['bid'] = $book->bid;
            $result[$i]['book']['name'] = $book->name;
            $result[$i]['book']['year'] = $book->year;
            $i=$i+1;
        }

        return $result;
    }

    private function getAuthorsFromBid($bid){
        //все класы авторов, что привязаны к индификатору книги
        $book_author = BookAuthor::model()->with('books')->findAllByAttributes(array('bid' => $bid));

        $aids = array();
        foreach ($book_author as $class){
            $aids[] = $class->aid;
        }
        $authors = Authors::model()->findAllByAttributes(array('aid' => $aids));

        //формирую красивый масив для результата функции
        $result = array();
        if(!empty($authors)){
            foreach ($authors as $author){
                $result['name'][] = $author->name;
                $result['surname'][] = $author->surname;
            }
        }
        return $result;
    } 

    private function getGenresFromBid($bid){
        //все класы жанров, что привязаны к индификатору книги
        $book_genre = BookGenre::model()->with('books')->findAllByAttributes(array('bid' => $bid));

        $gids = array();
        foreach ($book_genre as $class){
            $gids[] = $class->gid;
        }
        $genres = Genres::model()->findAllByAttributes(array('gid' => $gids));

        //формирую красивый масив для результата функции
        $result = array();
        if(!empty($genres)){
            foreach ($genres as $genre){
                $result['name'][] = $genre->name;
            }
        }
        return $result;
    } 
}