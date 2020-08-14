<?php

namespace Inc;

/**
 * Class Api
 * @package Inc
 */
class Api
{
    /**
     * Twilio API key
     */
    const API_KEY = 'FHABnsQgveUiDdrrBbxgb7ZFRby31r7f';
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param $email
     * @param $phone
     * @param $code
     * @return mixed
     */
    public function registerUser($email, $phone, $code)
    {
        $url = 'https://api.authy.com/protected/json/users/new';
        $data = [
            'user[email]' => $email,
            'user[cellphone]' => $code,
            'user[country_code]' => $phone
        ];
        try {
            $user = $this->callAPI($url, 'POST', $data);
        } catch (AuthyException $e) {
            $this->errors[] = $e->getMessage();

            return false;
        }


        $authyId = $user->user->id;
        $this->sendPasswordViaSms($authyId);

        return $authyId;
    }

    /**
     * @param $authyId
     */
    public function sendPasswordViaSms($authyId)
    {
        $url = "https://api.authy.com/protected/json/sms/{$authyId}";
        try {
            $this->callAPI($url);
        } catch (AuthyException $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     * @param $authyId
     * @param $token
     * @return bool
     */
    public function verifyUserViaSms($authyId, $token)
    {
        $url = "https://api.authy.com/protected/json/verify/{$token}/{$authyId}";
        try {
            return $this->callAPI($url)->token;
        } catch (AuthyException $e) {
            $this->errors[] = $e->getMessage();
        }
        return false;
    }

    /**
     * @param $url
     * @param string $method
     * @param array $data
     * @return mixed
     * @throws AuthyException
     */
    public function callAPI($url, $method = 'GET', $data = []){
        $curl = curl_init();

        switch ($method){
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        $headers[] = 'X-Authy-Api-Key: ' . self::API_KEY;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        if($result = curl_exec($curl)){
            $result = json_decode($result);
        } else {
            throw new AuthyException('Api call undefined error!');
        }
        if (!$result->success) {
            foreach ($result->errors as $error_key => $error){
                if($error_key == 'message')
                    continue;
                $this->errors[] = $error_key .' '. $error;
            }
            throw new AuthyException($result->errors->message);
        }
        curl_close($curl);

        return $result;
    }

    /**
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }
}