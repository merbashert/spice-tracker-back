<?php

$dbconn = null;
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
    $dbconn = pg_connect("host=localhost dbname=spicetracker");
}

class Spice {
    public $id;
    public $name;
    public $category;
    public $date_purchased;
    public function __construct($id, $name, $category, $date_purchased){
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->date_purchased = $date_purchased;
    }
}

class Spices {
    static function create($spice){
        $query = "INSERT INTO spices (name, category, date_purchased) VALUES ($1, $2, $3)";
        $query_params = array($spice->name, $spice->category, $spice->date_purchased);
        pg_query_params($query, $query_params);
        return self::all();
    }
    static function update($updated_spice){
        $query = "UPDATE spices SET name=$1, category=$2, date_purchased=$3 WHERE id=$4";
        $query_params = array($updated_spice->name, $updated_spice->category, $updated_spice->date_purchased, $updated_spice->id);
        pg_query_params($query,$query_params);

        return self::all();
    }
    static function delete($id){
        $query = "DELETE FROM spices WHERE id = $1";
        $query_params = array($id);
        pg_query_params($query, $query_params);

        return self::all();
    }
    static function all(){
        $spices = array();

        $results = pg_query("SELECT * FROM spices ORDER BY category, name");

        $row_object = pg_fetch_object($results);
        while($row_object) {
            $new_spice = new Spice(
                intval($row_object->id),
                $row_object->name,
                $row_object->category,
                $row_object->date_purchased
            );
            $spices[] = $new_spice;

            $row_object = pg_fetch_object($results);
        }
        return $spices;
    }
}
?>
