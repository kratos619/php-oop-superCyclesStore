<?php

class Bicycle {

  static protected $database;
static protected $db_columnn=['id','brand','model','year','category','color','description','gender','price','weight_kg','condition_id'];
  static public function set_database($database){
    self::$database = $database;
  }
static public function find_by_sql($sql){
    $result = self::$database ->query($sql);
    if(!$result){
      exit("Database query failed");
    }

    // result in to object
    $object_array = [];
    while($record = $result->fetch_assoc()){
      $object_array[] = self::instantiate($record);
    }
    $result->free();
      return $object_array;
}

  static public function find_all(){
      $sql = "select * from bicycles";
      return self::find_by_sql($sql);
  }

  static public function find_by_id($id){
    $sql = "select * from bicycles ";
    $sql .= "where id='" . self::$database->escape_string($id) . "'";
    $object_array = self::find_by_sql($sql); 
    if (!empty($object_array)){
    //Remove the first element (id) from an array, and return the value of the removed element:
    //$a=array("a"=>"red","b"=>"green","c"=>"blue");
    // echo array_shift($a);
    // print_r ($a);
    //output red
    //Array ( [b] => green [c] => blue )
      return array_shift($object_array);
    }else{
      redirect_to('index.php');
    }
  }

  static protected function instantiate($record){
    $object = new self;
    

    foreach ($record as $property => $value){
      if(property_exists($object ,$property)){
        $object->$property = $value;
        
      }
    }
    return $object;
  }

  public function create(){
    $attributes = $this->attribute();
    $sql = "INSERT INTO bicycles (";
    $sql .= join(', ',array_keys($attributes)) ;
    $sql .= ") values('";
    $sql .= join("', '",array_values($attributes));
    $sql .= "')"; 
    $result = self::$database->query($sql);
    if($result){
      $this->id = self::$database->insert_id;
    }
    return $result;
  }

  public function attribute(){
    $attributes =[];
    foreach(self::$db_columnn as $column){
      if($column == 'id'){continue;}
      $attributes[$column] = $this->$column;
    }
    return $attributes;
  }


  public $id;
  public $brand;
  public $model;
  public $year;
  public $category;
  public $color;
  public $description;
  public $gender;
  public $price;
  protected $weight_kg;
  protected $condition_id;

  public const CATEGORIES = ['Road', 'Mountain', 'Hybrid', 'Cruiser', 'City', 'BMX'];

  public const GENDERS = ['Mens', 'Womens', 'Unisex'];

  public const CONDITION_OPTIONS = [
    1 => 'Beat up',
    2 => 'Decent',
    3 => 'Good',
    4 => 'Great',
    5 => 'Like New'
  ];

  public function __construct($args=[]) {
    //$this->brand = isset($args['brand']) ? $args['brand'] : '';
    $this->brand = $args['brand'] ?? '';
    $this->model = $args['model'] ?? '';
    $this->year = $args['year'] ?? '';
    $this->category = $args['category'] ?? '';
    $this->color = $args['color'] ?? '';
    $this->description = $args['description'] ?? '';
    $this->gender = $args['gender'] ?? '';
    $this->price = $args['price'] ?? 0;
    $this->weight_kg = $args['weight_kg'] ?? 0.0;
    $this->condition_id = $args['condition_id'] ?? 3;

    // Caution: allows private/protected properties to be set
    // foreach($args as $k => $v) {
    //   if(property_exists($this, $k)) {
    //     $this->$k = $v;
    //   }
    // }
  }

  public function weight_kg() {
    return number_format($this->weight_kg, 2) . ' kg';
  }

  public function set_weight_kg($value) {
    $this->weight_kg = floatval($value);
  }

  public function weight_lbs() {
    $weight_lbs = floatval($this->weight_kg) * 2.2046226218;
    return number_format($weight_lbs, 2) . ' lbs';
  }

  public function set_weight_lbs($value) {
    $this->weight_kg = floatval($value) / 2.2046226218;
  }

  public function condition() {
    if($this->condition_id > 0) {
      return self::CONDITION_OPTIONS[$this->condition_id];
    } else {
      return "Unknown";
    }
  }

}

?>
