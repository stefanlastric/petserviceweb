<?php
/**
 * Created by PhpStorm.
 * User: stefa
 * Date: 04-May-19
 * Time: 12:54
 */

require '../vendor/autoload.php';
Flight::register('db', 'PDO', array('mysql:host=127.0.0.1:3306','root',''));


Flight::route('GET /services', function(){
    $service = Flight::db()->query('SELECT * FROM services', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($service);
});
Flight::route('GET /pets', function(){
    $pet = Flight::db()->query('SELECT * FROM pets', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($pet);
});
/*
Flight::route('POST /cars', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['id']) && $request['id'] !=''){
        $update = "UPDATE cars SET name= :name, power = :power, year = :year, fuel = :fuel, ccm = :ccm WHERE id=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Car ".$request['name']." has been updated successfully"]);
    }else{
        unset($request['id']);
        $insert = "INSERT INTO cars (name, power, year, fuel, ccm) VALUES(:name, :power, :year, :fuel, :ccm)";
        $stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "Car ".$request['name']." has been added successfully"]);
    }
});
*/
Flight::route('DELETE /pets/@id', function($id){
    $query = "DELETE FROM pets WHERE id = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['id' => $id]);
    Flight::json(['message' => "Pet has been deleted successfully"]);
});
Flight::route('GET /pets/@id', function($id){
    $query = "SELECT * FROM pets WHERE id = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['id' => $id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($pet);
});

Flight::route('DELETE /services/@id', function($id){
    $query = "DELETE FROM services WHERE idServices = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['id' => $id]);
    Flight::json(['message' => "Service has been deleted successfully"]);
});
Flight::route('GET /services/@id', function($id){
    $query = "SELECT * FROM services WHERE idServices = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($service);
});
Flight::start();
?>