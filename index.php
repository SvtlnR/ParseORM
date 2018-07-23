<?php
error_reporting(E_ALL);
mb_internal_encoding("UTF-8");
use Symfony\Component\DomCrawler\Crawler;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$curl = new \parser\utils\Curl();
$list=[];
$baseUrl = 'https://top100.rambler.ru';
$isContent=true;
$pageIndex=1;
$config=require_once 'config.php';
print_r($config['db']);
parser\utils\Db::initConnection($config['db']);
$con=parser\utils\Db::getConnection();
do{
	$params=[
		'query'=>'веб-студия',
 		'page'=>$pageIndex
 	];
	$response=$curl->call("https://top100.rambler.ru/?".http_build_query( $params));
	$crawler = new Crawler($response);
	if($crawler->filter('table.projects-table_catalogue')===null){
	 	$isContent=false;
	}
	else{		   
		$crawler->filter('tr.projects-table__row')->each(function(Crawler $node,$i) use (&$list,$baseUrl) {
			 $_node = $node->getNode(0);
			 echo"crawler";
        	$list[$i] = isset($list[$i]) ? $list[$i] : [];
        	$list[$i]['url'] = '';
        	$list[$i]['name'] = '';
        	$list[$i]['uid'] = '';    
        	$list[$i]['visitors'] = 0;
        	$list[$i]['popularity'] = 0;   
        	$list[$i]['views'] = 0;
        	$node
            	->filter('.link_catalogue-site-link')
            	->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                	$_node = $node->getNode(0);
	                $url = $_node->getAttribute('href');
	                $name = $_node->getAttribute('title');
	                $id = $_node->getAttribute('name'); 
	                $list[$i]['url'] = $url;
	                $list[$i]['name'] = $name;
	                $list[$i]['uid'] = $id;

            });
            $node
	            ->filter('.projects-table__cell[data-content="visitors"] .projects-table__textline')
                ->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                    $_node = $node->getNode(0); 
                    $vstrs = $_node->nodeValue;
                    $vstrs = preg_replace("/[^x\d|*\.]/", "", $vstrs);
                  	$list[$i]['visitors'] = $vstrs; 

                });	
            $node
               ->filter('.projects-table__cell[data-content="views"] .projects-table__textline')
               ->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                   $_node = $node->getNode(0);
                    $vws = $_node->nodeValue;
                    $vws = preg_replace("/[^x\d|*\.]/", "", $vws);
                    $list[$i]['views'] = $vws;
                      
                });
            $node
            ->filter('.projects-table__cell[data-content="popularity"] .projects-table__textline')
            ->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                $_node = $node->getNode(0);
                $pop = $_node->nodeValue;
                $pop = preg_replace("/[^x\d|*\.]/", "", $pop);
                $list[$i]['popularity'] =  $pop;
                  
            });
        });
        foreach ($list as $i =>$item) {
    		$modelTexts = Texts::query()
    		->where('url', '=', $item['url'])
    		->where('name', '=', $item['name'])
    		->where('uid', '=', $item['uid'])
    		->where('visitors', '=', $item['visitors'])
    		->where('views', '=', $item['views'])
    		->where('popularity', '=', $item['popularity'])
    		->first();
            if ($modelTexts !== null) {
                continue;
            }
            $modelTexts = new Texts();
            $modelTexts->url = $item['url'];
            $modelTexts->name = $item['name'];
            $modelTexts->uid = $item['uid'];
            $modelTexts->visitors = $item['visitors'];
            $modelTexts->views = $item['views'];
            $modelTexts->popularity = $item['popularity'];
            $modelTexts->save();
        }
		$pageIndex++;
	}
}while($isContent);


        