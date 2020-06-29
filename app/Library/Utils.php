<?php

declare(strict_types=1);

namespace App\Library;
/**
 *----------------------------------------------------
 * Utils
 *----------------------------------------------------
 * Functions that maybe used in multiple locations
 *
 */

use App\Models\Account\Member;
use App\Models\Account\Store;
use Delight\Cookie\Cookie;
use Delight\Cookie\Session;
use PDO;

class Utils
{
    public function __construct()
    {
        //
    }

    public function sanitizeFileName($filename)
    {
        $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
        $file = mb_ereg_replace("([\.]{2,})", '', $file);
        $file = trim(preg_replace('/\s+/', '', $file));
        return $file;
    }
    /**
     * curlGet alias for request method
     *
     * @param $url
     * @param array $params
     * @param array $headers
     * @param array $userOptions
     * @return mixed
     */
    public function curlGet($url, $params = array(), $headers = array(), $userOptions = array())
    {
        return $this->curlRequest('GET', $url, $params, $headers, $userOptions);
    }
    /**
     * curlPost alis for request method
     *
     * @param $url
     * @param array $params
     * @param array $headers
     * @param array $userOptions
     * @return mixed
     */
    public function curlPost($url, $params = array(), $headers = array(), $userOptions = array())
    {
        return $this->curlRequest('POST', $url, $params, $headers, $userOptions);
    }
    /**
     * Curl run request
     *
     * @param $method
     * @param string $url
     * @param array $params
     * @param array $headers
     * @param array $userOptions
     * @return mixed
     * @throws Exception
     */
    private function curlRequest($method, $url, $params = array(), $headers = array(), $userOptions = array())
    {
        $ch = curl_init();
        $method = strtoupper($method);
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => $headers
        );
        array_merge($options, $userOptions);
        switch ($method) {
            case 'GET':
                if ($params) {
                    $url = $url . '?' . http_build_query($params);
                }
                break;
            case 'POST':
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                return false;
                break;
        }
        $options[CURLOPT_URL] = $url;
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        return $response;
    }
    // SyncLogin
    public function syncLoginSession(PDO $db, $userId)
    {
        $member = (new Member($db))->columns($userId, ['Id', 'FullName', 'Avatar', 'ActiveStore']);
        if ($member) {
            Session::set('member_id', $member['Id']);
            Session::set('member_avatar', $member['Avatar']);
            if ($member['FullName']) {
                Session::set('member_name', $member['FullName']);
            } else {
                Session::set('member_name', 'Tracksz User');
            }
            if ($member['ActiveStore']) {
                Cookie::setcookie('tracksz_active_store', $member['ActiveStore'], time() + 2419200, '/');
                $active = (new Store($db))->findId($member['ActiveStore'], $member['Id']);
                if ($active) {
                    Cookie::setcookie('tracksz_active_name', $active['DisplayName'], time() + 2419200, '/');
                }
            } else {
                Cookie::setcookie('tracksz_active_store', 0, time() + 2419200, '/');
                Cookie::setcookie('tracksz_active_name', '', time() + 2419200, '/');
            }
        } else {
            Session::delete('member_id');
            Session::delete('member_avatar');
            Session::delete('member_name');
        }
        return true;
    }
}
