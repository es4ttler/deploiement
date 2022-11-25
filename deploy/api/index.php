<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;
use \Firebase\JWT\JWT;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
require __DIR__ . '/../vendor/autoload.php';
 
const JWT_SECRET = "makey1234567";

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

function  addHeaders (Response $response) : Response {
    $response = $response
    ->withHeader("Content-Type", "application/json")
    ->withHeader('Access-Control-Allow-Origin', ('https://sattler-emma.onrender.com'))
    ->withHeader('Access-Control-Allow-Headers', 'Content-Type,  Authorization')
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
    ->withHeader('Access-Control-Expose-Headers', 'Authorization');

    return $response;
}



/*
=================================================HELLO=================================================
*/
$app->get('/api/hello/{name}', function (Request $request, Response $response, $args) {
    $array = [];
    $array ["nom"] = $args ['name'];
    $response->getBody()->write(json_encode ($array));
    return $response;
});



/*
=================================================CLIENT=================================================
*/

// GET - GET ALL CLIENTS
$app->get('/api/clients', function (Request $request, Response $response, $args) {
    $json = file_get_contents("../mock/client.json");
    $response = addHeaders($response);
    $response->getBody()->write($json);
    return $response;
});

// GET - GET CLIENT BY ID
$app->get('/api/client/{id}', function (Request $request, Response $response, $args) {
    $json = file_get_contents("../mock/client.json");
    $array = json_decode($json, true);
    $id = $args ['id'];
    $array = $array[$id];
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($array));
    return $response;
});

// POST - CREATE CLIENT
$app->post('/api/client', function (Request $request, Response $response, $args) {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array
    $civility=$body['civility'] ?? "";
    $firstName = $body ['firstName'] ?? "";
    $name = $body ['name'] ?? ""; 
    $tel = $body ['tel'] ?? "";
    $email = $body ['email'] ?? "";
    $address = $body ['address'] ?? "";
    $city = $body ['city'] ?? "";
    $cp = $body ['cp'] ?? "";
    $country = $body ['country'] ?? "";
    $login = $body ['login'] ?? "";
    $password = $body ['password'] ?? "";
    $err=false;

    //check format name, email and password
    if (empty($lastName) || empty($firstName) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($codeCity) || empty($country) || empty($login) || empty($password) || empty($civility) || 
        !preg_match("/^[a-zA-Z0-9]+$/", $lastName) || !preg_match("/^[a-zA-Z0-9]+$/", $firstName) ||  
        !preg_match("/^[a-zA-Z0-9]+$/", $city) || 
        !preg_match("/^[0-9]+$/", $codeCity) || !preg_match("/^[a-zA-Z0-9]+$/", $country) || !preg_match("/^[a-zA-Z0-9]+$/", $civility)) {
        $err=true;
    }

    if (!$err) {
        // $json = file_get_contents("../mock/clients.json");
        // $array = json_decode($json, true);
        // $id = count($array);

        //Create a new client in an array
        $array = array('id' => $id, 'name' => $name, 'firstName' => $firstName, 'email' => $email, 'tel' => $tel, 'address' => $address, 'city' => $city, 'cp' => $cp, 'country' => $country, 'login' => $login, 'password' => $password, 'civility' => $civility);
        $json = json_encode($array);
        // file_put_contents("../mock/clients.json", $json);
        $response = addHeaders($response);
        $response->getBody()->write($json);
    }
    else{          
        $response = $response->withStatus(401);
    }
    return $response;
});

//PUT - UPDATE CLIENT
$app->put('/api/client/{id}', function (Request $request, Response $response, $args) {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array 
    $civility=$body['civility'] ?? "";
    $firstName = $body ['firstName'] ?? "";
    $name = $body ['name'] ?? ""; 
    $tel = $body ['tel'] ?? "";
    $email = $body ['email'] ?? "";
    $address = $body ['address'] ?? "";
    $city = $body ['city'] ?? "";
    $cp = $body ['cp'] ?? "";
    $country = $body ['country'] ?? "";
    $login = $body ['login'] ?? "";
    $password = $body ['password'] ?? "";
    $err=false;

    //check format name, email and password
    if (empty($name) || empty($firstName) || empty($email) || empty($tel) || empty($address) || empty($city) || empty($cp) || empty($country) || empty($login) || empty($password) || empty($civility) || 
        !preg_match("/^[a-zA-Z0-9]+$/", $name) || !preg_match("/^[a-zA-Z0-9]+$/", $firstName) ||  
        !preg_match("/^[a-zA-Z0-9]+$/", $city) || 
        !preg_match("/^[0-9]+$/", $cp) || !preg_match("/^[a-zA-Z0-9]+$/", $country) || !preg_match("/^[a-zA-Z0-9]+$/", $civility)) {
        $err=true;
    }

    if (!$err) {
        $json = file_get_contents("../mock/client.json");
        $array = json_decode($json, true);
        $id = $args ['id'];
        $array[$id] = array('id' => $id, 'name' => $name, 'firstName' => $firstName, 'email' => $email, 'tel' => $tel, 'address' => $address, 'city' => $city, 'cp' => $cp, 'country' => $country, 'login' => $login, 'password' => $password, 'civility' => $civility);
        $json = json_encode($array);
        file_put_contents("../mock/client.json", $json);
        $response = addHeaders($response);
        $response->getBody()->write($json);
    }
    else{          
        $response = $response->withStatus(401);
    }
    return $response;
});

//DELETE - DELETE CLIENT
$app->delete('/api/client/{id}', function (Request $request, Response $response, $args) {
    $json = file_get_contents("../mock/client.json");
    $array = json_decode($json, true);
    $id = $args ['id'];
    unset($array[$id]);
    $json = json_encode($array);
    file_put_contents("../mock/client.json", $json);
    $response = addHeaders($response);
    $response->getBody()->write($json);
    return $response;
});

// POST - LOGIN
$app->post('/api/login', function (Request $request, Response $response, $args) {  
    $err=false; 
    $inputJSON = file_get_contents('php://input');
    $response = addHeaders($response);
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array 
    $login = $body['login'] ?? ""; 
    $password = $body['password'] ?? "";

    if (!preg_match("/[a-zA-Z0-9]{1,20}/",$login)|| !preg_match("/[a-zA-Z0-9]{1,20}/",$password))  {
        $err=true;
    }

    if (!$err) {
            $response = createJwT ($response);
            $data = array('login' => $login);
            $response->getBody()->write(json_encode($data));
     } else {          
            $response = $response->withStatus(401);
     }
    return $response;
});


/*
=================================================CATALOG=================================================
*/

//GET - GET ALL FRUITS
$app->get('/api/fruits', function (Request $request, Response $response, $args) {
    $json = file_get_contents("../mock/catalog.json");
    $array = json_decode($json, true);
    $response = addHeaders($response);
    $response->getBody()->write($json);
    return $response;
});

//GET - GET FRUIT BY ID
$app->get('/api/fruit/{id}', function (Request $request, Response $response, $args) {
    $json = file_get_contents("../mock/catalog.json");
    $array = json_decode($json, true);
    $id = $args ['id'];
    $array = $array[$id];
    $response = addHeaders($response);
    $response->getBody()->write(json_encode ($array));
    return $response;
});

//POST - ADD FRUIT
$app->post('/api/fruit', function (Request $request, Response $response, $args) {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array 
    $name = $body ['name'] ?? ""; 
    $price = $body ['price'] ?? "";
    $color = $body ['color'] ?? "";
    $err=false;

    //check format name, price, color and image
    if (empty($name) || empty($price) || empty($color) ||
    !preg_match("/^[a-zA-Z]+$/", $name) || !preg_match("/^[0-9]+$/", $price) || !preg_match("/^[a-zA-Z0-9]+$/", $color)) {
        $err=true;
    }

    if (!$err) {
        $json = file_get_contents("../mock/catalog.json");
        $array = json_decode($json, true);
        $id = count($array);
        $array[] = array('id' => $id, 'name' => $name, 'price' => $price, 'color' => $color);
        $json = json_encode($array);
        file_put_contents("../mock/catalog.json", $json);
        $response = addHeaders($response);
        $response->getBody()->write($json);
    }
    else{          
        $response = $response->withStatus(401);
    }
    return $response;
});

//UPDATE - UPDATE FRUIT
$app->put('/api/fruit/{id}', function (Request $request, Response $response, $args) {
    $inputJSON = file_get_contents('php://input');
    $body = json_decode( $inputJSON, TRUE ); //convert JSON into array 
    $name = $body ['name'] ?? ""; 
    $price = $body ['price'] ?? "";
    $color = $body ['color'] ?? "";
    $err=false;

    //check format name, price, color and image
    if (empty($name) || empty($price) || empty($color) ||
    !preg_match("/^[a-zA-Z]+$/", $name) || !preg_match("/^[0-9]+$/", $price) || !preg_match("/^[a-zA-Z0-9]+$/", $color)) {
        $err=true;
    }

    if (!$err) {
        $json = file_get_contents("../mock/catalog.json");
        $array = json_decode($json, true);
        $id = $args ['id'];
        $array[$id] = array('id' => $id, 'name' => $name, 'price' => $price, 'color' => $color);
        $json = json_encode($array);
        file_put_contents("../mock/catalog.json", $json);
        $response = addHeaders($response);
        $response->getBody()->write($json);
    }
    else{          
        $response = $response->withStatus(401);
    }
    return $response;
});

//DELETE - DELETE FRUIT
$app->delete('/api/fruit/{id}', function (Request $request, Response $response, $args) {
    $json = file_get_contents("../mock/catalog.json");
    $array = json_decode($json, true);
    $id = $args ['id'];
    unset($array[$id]);
    $json = json_encode($array);
    file_put_contents("../mock/catalog.json", $json);
    $response->getBody()->write($json);
    $response = addHeaders($response);
    return $response;
});

/*
=================================================JWT=================================================
*/
$options = [
    "attribute" => "token",
    "header" => "Authorization",
    "regexp" => "/Bearer\s+(.*)$/i",
    "secure" => false,
    "algorithm" => ["HS256"],
    "secret" => JWT_SECRET,
    "path" => ["/api"],
    "ignore" => ["/api/hello","/api/login","/api/createUser"],
    "error" => function ($response, $arguments) {
        $data = array('ERREUR' => 'Connexion', 'ERREUR' => 'JWT Non valide');
        $response = $response->withStatus(401);
        return $response->withHeader("Content-Type", "application/json")->getBody()->write(json_encode($data));
    }
]; 
function createJwT (Response $response) : Response {

    $issuedAt = time();
    $expirationTime = $issuedAt + 60;
    
    $payload = array(
    'userid' => 'sattlee',
    'email' => 'sattlee@gmail.com',
    'pseudo' => 'esat',
    'iat' => $issuedAt,
    'exp' => $expirationTime
    );

    $token_jwt = JWT::encode($payload,JWT_SECRET, "HS256");
    $response = $response->withHeader("Authorization", "Bearer {$token_jwt}");

    return $response;
}


$app->add(new Tuupola\Middleware\JwtAuthentication($options));
$app->run ();