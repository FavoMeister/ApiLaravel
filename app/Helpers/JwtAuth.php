<?php
namespace App\Helpers;

use Tymon\JWTAuth\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth {

    protected $key;

    public function __construct()
    {
        $this->key = 'AskdaJ@hy*SD7-taSDg*Asa8s7d';
    }

    public function signUp($email, $password, $getToken = null)
    {
        $user = User::where(
            array(
                'email' => $email,
                'password' => $password,
            ))->first();

        $signup = false;
        
        if (is_object($user)) {
            $signup = true;
        }

        if ($signup) {
            
            // Generate Token
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => now(),
                'exp' => now() + (7 * 24 * 60 * 60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));

            if (!is_null($getToken)) {
                return $jwt;
            } else {
                return $decoded;
            }

        } else {
            return array('status' => 'error', 'message' => 'Login ha fallado');
        }
        
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
        } catch (\Throwable $th) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }
        
        return $auth;
    }
}
