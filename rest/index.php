<?php
/**
 * Created by PhpStorm.
 * User: stefa
 * Date: 04-May-19
 * Time: 12:54
 */

require '../vendor/autoload.php';
Flight::register('db', 'PDO', array('mysql:host=localhost:3306;dbname=pet_services','root',''));


Flight::route('GET /services', function(){
    $service = Flight::db()->query('SELECT * FROM services', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($service);
});
Flight::route('GET /pets', function(){
    $pet = Flight::db()->query('SELECT * FROM pets', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($pet);
});
Flight::route('GET /appointments', function(){
    $appointment = Flight::db()->query('SELECT * FROM appointments', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($appointment);
});

Flight::route('POST /pets', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['id']) && $request['id'] !=''){
        $update = "UPDATE pets SET id= :id, Name= :name, Age = :age, Adopttime = :adopttime, Type = :type, City = :city, Gender = :gender, Sterilized = :sterilized WHERE id=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Pet ".$request['Name']." has been updated successfully"]);
    }else{
        unset($request['id']);
        $insert = "INSERT INTO pets (id, Name, Age, Adopttime, Type, City, Gender, Sterilized) VALUES(:id, :Name, :Age, :Adopttime, :Type, :City, :Gender, :Sterilized)";
        $stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "Pet ".$request['Name']." has been added successfully"]);
    }
});
Flight::route('POST /appointments', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['idappointments']) && $request['idappointments'] !=''){
        $update = "UPDATE appointments SET idappointments= :idappointments, serviceID = :serviceID, City = :city, userID = :userID, Time = :time, RequestApproved = :requestApproved WHERE id=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Appointment ".$request['idappointments']." has been updated successfully"]);
    }else{
        unset($request['idappointments']);
        $insert = "INSERT INTO appointments (idappointments, serviceID, City, userID, Time, RequestApproved) VALUES( :idappointments, :serviceID, :city, :userID, :time, :requestApproved)";
        $stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "Appointments ".$request['idappointments']." has been added successfully"]);
    }
});
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

Flight::route('DELETE /appointments/@id', function($id){
    $query = "DELETE FROM appointments WHERE id = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['idappointments' => $id]);
    Flight::json(['message' => "Appointment has been deleted successfully"]);
});
Flight::route('GET /appointments/@id', function($id){
    $query = "SELECT * FROM appointments WHERE idappointments = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['idappointments' => $id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($appointment);
});

Flight::route('DELETE /services/@id', function($id){
    $query = "DELETE FROM service WHERE idServices = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['idServices' => $id]);
    Flight::json(['message' => "Service has been deleted successfully"]);
});
Flight::route('GET /services/@id', function($id){
    $query = "SELECT * FROM services WHERE idServices = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['idServices' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($service);
});
Flight::start();
?>