<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jwt {
    private $key;
    private $expire;
    private $iss;

    public function __construct() {
        $CI =& get_instance();
        $CI->config->load('jwt', TRUE);
        $this->key = $CI->config->item('jwt_key', 'jwt');
        if (!$this->key) {
            $this->key = $CI->config->item('encryption_key');
        }
        $this->expire = $CI->config->item('jwt_expire', 'jwt') ?: 86400;
        $this->iss = $CI->config->item('jwt_iss', 'jwt') ?: 'adminto';
    }

    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data) {
        $pad = strlen($data) % 4;
        if ($pad) $data .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Generate JWT
     * @param array $payload data user (id, email, etc)
     * @param int|null $expire detik, null pakai config
     * @return string
     */
    public function encode($payload, $expire = null) {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $iat = time();
        $exp = $iat + ($expire !== null ? $expire : $this->expire);
        $body = array_merge(['iss' => $this->iss, 'iat' => $iat, 'exp' => $exp], $payload);

        $headerEnc = $this->base64UrlEncode(json_encode($header));
        $payloadEnc = $this->base64UrlEncode(json_encode($body));
        $signature = hash_hmac('sha256', $headerEnc . "." . $payloadEnc, $this->key, true);
        $sigEnc = $this->base64UrlEncode($signature);

        return $headerEnc . "." . $payloadEnc . "." . $sigEnc;
    }

    /**
     * Decode & verify JWT
     * @return array|false payload jika valid, false jika tidak
     */
    public function decode($token) {
        if (!$token) return false;
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;
        list($headerEnc, $payloadEnc, $sigEnc) = $parts;

        $header = json_decode($this->base64UrlDecode($headerEnc), true);
        if (!isset($header['alg']) || $header['alg'] !== 'HS256') return false;

        $expectedSig = $this->base64UrlEncode(hash_hmac('sha256', $headerEnc . "." . $payloadEnc, $this->key, true));
        if (!hash_equals($expectedSig, $sigEnc)) return false;

        $payload = json_decode($this->base64UrlDecode($payloadEnc), true);
        if (!$payload) return false;
        if (isset($payload['exp']) && $payload['exp'] < time()) return false;

        return $payload;
    }

    /**
     * Ambil token dari header Authorization: Bearer <token>
     */
    public function getBearerToken() {
        $auth = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) $auth = $headers['Authorization'];
            elseif (isset($headers['authorization'])) $auth = $headers['authorization'];
        }
        if (!$auth) return null;
        if (preg_match('/Bearer\s+(.*)$/i', $auth, $m)) return trim($m[1]);
        return null;
    }

    /**
     * Validasi request, return payload atau kirim 401
     */
    public function requireAuth() {
        $token = $this->getBearerToken();
        if (!$token) return false;
        return $this->decode($token);
    }
}
