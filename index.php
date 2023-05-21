<?php
ini_set('display_errors', 1);

require_once '../libs/flight/Flight.php';
require_once '../libs/Parsedown.php';

require_once ("../libs/blade/BladeOne.php");
use eftec\bladeone\BladeOne;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new BladeOne($views,$cache,BladeOne::MODE_DEBUG);
Flight::set('blade', $blade);

Flight::route('/datas', function(){//################################################## datas
//echo "test";
    $catId = Flight::request()->query->catId;


    $db = new PDO('sqlite:./data.db');

    if($catId != ""){
        $stmt = $db->prepare("select * from data where catId = ? order by sort desc, updated desc");
        $stmt->execute(array($catId));

    }else{
        $stmt = $db->prepare("select * from data order by sort desc, updated desc");
        $stmt->execute();
    }


    $rows = makeRows($stmt);

    $stmt = $db->prepare("select distinct catId from data order by catId desc");
    $stmt->execute();

    $rows2 = makeRows($stmt);

/*
echo "<pre>";
var_dump($rows);
echo "</pre>";
*/

$rows = markdownParse($rows);

//markdownParseOne($row)

  $blade = Flight::get('blade');//
  echo $blade->run("datas",array("rows"=>$rows, "rows2"=>$rows2)); //

});

Flight::route('/dataInsExe', function(){//################################################## dataInsExe

    //$catId = Flight::request()->data->catId;
    $text = Flight::request()->data->text;
    //$limited = Flight::request()->data->limited;

    $db = new PDO('sqlite:data.db');
    $stmt = $db->prepare("insert into data (date,text,updated) values (?,?,?)");
    $array = array(date('Y-m-d'),$text,time());
//    $stmt = $db->prepare("insert into data (date,catId,text,updated,sort) values (?,?,?,?,?)");
//$array = array(date('Y-m-d'),$catId,$text,time(),0);

    $stmt->execute($array);
//    $stmt->execute();

    //$rows = $stmt;

    Flight::redirect('/datas');
});

Flight::route('/dataUpd/@id', function($id){//################################################## dataUpd

    $db = new PDO('sqlite:data.db');
    $stmt = $db->prepare("select * from data where id = ?");
    $array = array($id);
    $stmt->execute($array);

    $rows = makeRows($stmt);
    $row = $rows[0];

  $blade = Flight::get('blade');//
  echo $blade->run("dataUpd",array("row"=>$row)); //
});

Flight::route('/dataUpdExe', function(){//################################################## dataUpdExe
    $id = Flight::request()->data->id;
    $catId = Flight::request()->data->catId;//
    $text = Flight::request()->data->text;
    $date2 = Flight::request()->data->date2;
    $sort = Flight::request()->data->sort;


    $db = new PDO('sqlite:data.db');
    $stmt = $db->prepare("update data set catId = ?,text = ?,date2 = ?,sort = ? where id = ?");
    $array = array($catId, $text, $date2, $sort, $id);
    $stmt->execute($array);

    Flight::redirect('/datas');
});

Flight::route('/dataUp/@id', function($id){//################################################## dataUp

    $db = new PDO('sqlite:data.db');
    $stmt = $db->prepare("update data set updated = ? where id = ?");
    $array = array(time(), $id);
    $stmt->execute($array);

    Flight::redirect('/datas');
});

Flight::route('/dataDel/@id', function($id){//################################################## dataDel

    $db = new PDO('sqlite:data.db');
    $stmt = $db->prepare("delete from data where id = ?");
    $array = array($id);
    $stmt->execute($array);

    Flight::redirect('/datas');
});

Flight::route('/test', function(){//################################################## dataDel
    $db = new PDO('sqlite:./data.db');
    $stmt = $db->prepare("select distinct catId from data order by catId desc");
    $stmt->execute();

    $rows = makeRows($stmt);

echo "<pre>";
var_dump($rows);
echo "</pre>";
/*

*/

});


function makeRows($stmt){
    $i = 0;
    $rows = [];
    while($row = $stmt->fetch()){
        $rows[$i] = $row;
        $i++;
    }
    return $rows;
}

function markdownParse($rows){
  $parse = new Parsedown();
  $parse->setBreaksEnabled(true);
  $parse->setMarkupEscaped(false);
  $i = 0;
   foreach($rows as $row){
     $rows[$i]["text"] = $parse->text($row["text"]);
     $i++;
   }
  return $rows;
}

function markdownParseOne($row){
  $parse = new Parsedown();
  $parse->setBreaksEnabled(true);
  $parse->setMarkupEscaped(false);
  $row["text"] = $parse->text($row["text"]);
  return $row;
}



Flight::start();