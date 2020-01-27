
<?php
/**
 * Created by PhpStorm.
 * User: stefa
 * Date: 04-May-19
 * Time: 12:54
 */

require '../vendor/autoload.php';
Flight::register('db', 'PDO', array('mysql:host=localhost:3306;dbname=pet_services','root','password'));

/* CRUD for User */
Flight::route('POST /create_user', function () {
    $request = Flight::request();
    $input = array(
        "name" => $request->data->name,
        "surname" => $request->data->surname,
        "email" => $request->data->email,
        "password" => $request->data->password,
    );
    Flight::db()->$query = "INSERT INTO users(name, 
    surname, 
    email, 
    password)
VALUES(:name, :surname, :email, :password)";
$statement = $this->pdo->prepare($query);
$stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "User ".$request['email']." has been added successfully"]);
    }
});

Flight::route('POST /login', function(){
    $request = Flight::request();
    $db_user = Flight::pm()->get_user_by_email($request->data->email);
    print_r($db_user['password']);
    if ($db_user){
        if ($db_user['password'] == $request->data->password){
            unset($db_user['password']);
            $token = ["user" => $db_user, "iat" => time(), "exp" => time() + 3600];
            $jwt = JWT::encode($token, Config::JWT_SECRET);
            $db_user['token'] = $jwt;
            Flight::json($db_user);
        }else{
            Flight::halt(400, Flight::json(['message' => 'Invalid password for email address '. $request->data->email]));
        }
    }else{
        Flight::halt(400, Flight::json(['message' => 'Invalid email address']));
    }
});




Flight::route('GET /services', function(){
    $service = Flight::db()->query('SELECT * FROM services', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($service);
});
Flight::route('GET /pets', function(){
    $pet = Flight::db()->query('SELECT * FROM pets', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($pet);
});
Flight::route('GET /lost_pets', function(){
    $lostpet = Flight::db()->query('SELECT * FROM lost_pets', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($lostpet);
});

Flight::route('GET /appointments', function(){
    $appointment = Flight::db()->query('SELECT * FROM appointments', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($appointment);
});

Flight::route('POST /pets', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['id']) && $request['id'] !=''){
        $update = "UPDATE pets SET Name= :name, Age = :age, Adopttime = :adopttime, Type = :type, City = :city, Gender = :gender, Sterilized = :sterilized WHERE id=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Pet ".$request['name']." has been updated successfully"]);
    }else{
        unset($request['id']);
        $insert = "INSERT INTO pets (Name, Age, Adopttime, Type, City, Gender, Sterilized) VALUES(:name, :age, :adopttime, :type, :city, :gender, :sterilized)";
        $stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "Pet ".$request['name']." has been added successfully"]);
    }
});

Flight::route('POST /services', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['serviceID']) && $request['serviceID'] !=''){
        $update = "UPDATE services SET Name = :Name, City = :City, Address = :Address, Type = :Type, WorkingTime = :WorkingTime WHERE idServices=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Service ".$request['Name']." has been updated successfully"]);
    }else{
        unset($request['serviceID']);
        $insert = "INSERT INTO services (Name, City, Address, Type, WorkingTime) VALUES(:idServices, :Name, :City, :Address, :Type, :WorkingTime)";
        $stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "Service ".$request['Name']." has been added successfully"]);
    }
});

Flight::route('POST /appointments', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['idappointments']) && $request['idappointments'] !=''){
        $update = "UPDATE appointments SET serviceID = :serviceID, City = :city, userID = :userID, Time = :time, RequestApproved = :requestApproved WHERE id=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Appointment ".$request['idappointments']." has been updated successfully"]);
    }else{
        unset($request['idappointments']);
        $insert = "INSERT INTO appointments (serviceID, City, userID, Time, RequestApproved) VALUES( :serviceID, :city, :userID, :time, :requestApproved)";
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
Flight::route('DELETE /lost_pets/@id', function($id){
    $query = "DELETE FROM lost_pets WHERE idlost_pets = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['idlost_pets' => $id]);
    Flight::json(['message' => "Lost pet has been deleted successfully"]);
});
Flight::route('GET /lost_pets/@id', function($id){
    $query = "SELECT * FROM lost_pets WHERE idlost_pets = :id";
    $stmt= Flight::db()->prepare($query);
    $stmt->execute(['idlost_pets' => $id]);
    $lostpet = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($lostpet);
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
Flight::route('POST /lost_pets', function(){
    $request = Flight::request()->data->getData();
    if (isset($request['idlost_pets']) && $request['idlost_pets'] !=''){
        $update = "UPDATE lost_pets SET Name = :name, Type = :type, Ownername = :ownername, City = :city, Contact = :contact, Reward = :reward WHERE idlost_pets=:id";
        $stmt= Flight::db()->prepare($update);
        $stmt->execute($request);
        Flight::json(['message' => "Lost pet ".$request['name']." has been updated successfully"]);
    }else{
        unset($request['idlost_pets']);
        $insert = "INSERT INTO lost_pets (Name, Type,Ownername, City, Contact, Reward) VALUES(:name, :type, :ownername, :city, :contact, :reward)";
        $stmt= Flight::db()->prepare($insert);
        $stmt->execute($request);
        Flight::json(['message' => "Lost pet ".$request['name']." has been added successfully"]);
    }
});
Flight::start();
?>