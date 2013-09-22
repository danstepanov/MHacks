<?php 

class Application_Model_Api_Cad_ArticleSeed extends Application_Model_Api_Abstract_API
{
    private $mongo;
    public function __construct()
    {
        $this->mongo = new MongoClient(); // connect
        $count = $this->mongo->articles->collection->count();
        $this->url = 'http://hearstcars.api.mashery.com/v1/api/content/index/'.$count.'/20/desc/json';
       /* $this->mongo->articles->collection->insert(
        );*/
    }
    
    public function seedArticles()
    {
        $res = $this->execute('');
        $images = array();
        foreach($res['content']['images'] as $id => $image)
        {
            $images[$id] = $image['url'];
        }
        $articles = $res['content']['articles'];
        foreach($articles as $a_id => $article)
        {
            $article['image'] = $images[$a_id];
            try 
            {
                $this->mongo->articles->collection->insert($article);
            } catch( MongoCursorException $e )
            {
            }
        }
    }
}