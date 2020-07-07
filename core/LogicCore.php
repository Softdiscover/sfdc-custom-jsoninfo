<?php
declare(strict_types=1);
/**
 *
 * @category logicCore
 * @author   Raimundo Yabar <djyabar@gmail.com>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     example.com <https://example.com>
 */
namespace sfdc\wpJsonPlaceholder\core;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class logicCore
 *
 * @category logicCore
 * @author   Raimundo Yabar <djyabar@gmail.com>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     example.com <https://example.com>
 */
class LogicCore
{
    use \sfdc\wpJsonPlaceholder\core\Helper;
    /**
    * constructor.
    */
    public function __construct()
    {
    }

     
    public function userDetail()
    {
        check_ajax_referer('sfdc_ajax_nonce', 'sfdc_security');
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
        
        // return data to ajax callback
        $resp = [];
        if ($userId!==false && $userId!==null) {
            $resp['info']=$this->queryUsers(true, intval($userId));
        }
        
        wp_send_json_success($resp);
    }

    public function listUsers()
    {
        check_ajax_referer('sfdc_ajax_nonce', 'sfdc_security');
        
        // return data to ajax callback
        $resp = [];
        $resp['users']=$this->queryUsers();
        
        wp_send_json_success($resp);
    }

    public function changeTitle(string $title):?string
    {
        if ($this->validateSlug()) {
            $title = 'Showing Users';
        }
        return $title;
    }
   

    /**
     * createCustomSlug.
     *
     * @author  Unknown
     * @since   v0.0.1
     * @version v1.0.0  Friday, July 3rd, 2020.
     * @param   string    $template
     * @return  mixed
     */
    public function createCustomSlug(string $template) : ?string
    {
        if ($this->validateSlug()) {
            status_header(200);
            return dirname(__FILE__) . '/../public/partials/plugin-name-public-display.php';
        }
        return $template;
    }

    public function validateSlug():?bool
    {
        $urlPath = trim(parse_url(add_query_arg([]), PHP_URL_PATH), '/');
        $cusSlug='sfdc_show_users';
        $pos = strpos($urlPath, $cusSlug);
        if ($pos===false) {
            return false;
        }
        
        return true;
    }

    protected function cacheCompInstance():?object
    {
        return new FilesystemAdapter('', 0, "cache");
    }

    
    public function queryUsers(bool $isUserDetail = false, int $userId = 0):?array
    {
        $urlPath='https://jsonplaceholder.typicode.com/users';
        if ($isUserDetail) {
            $urlPath.='/'.$userId;
        }
        
        $result=[];
        $errorMsg = "";
        
        // init cache pool of file system adapter
        $cachePool = $this->cacheCompInstance();
        
        $cacheKey=$this->base64urlEncode($urlPath);
        // 1. store string values
        $demoString = $cachePool->getItem($cacheKey);
        if (!$demoString->isHit()) {
            $curlRes=$this->callApi($urlPath);

            if (!empty($curlRes['error'])) {
                return [];
            }
              
            if ($curlRes['httpres']===200) {
                $result = $curlRes['info'];
                $dataStore = $this->base64urlEncode(json_encode($result));
                $demoString->set($dataStore);
                //expires in one hour
                $demoString->expiresAfter(3600);
                $cachePool->save($demoString);
            }
        }
        
        if ($cachePool->hasItem($cacheKey)) {
            $demoString = $cachePool->getItem($cacheKey);
            $result = json_decode($this->base64urlDecode($demoString->get()), true);
        }
         
        return $result;
    }

    
    public function callApi(string $url):?array
    {
        $result=['error'=>'', 'info'=>[], 'httpres'=> ''];
        try {
            if (!function_exists("curl_init")) {
                throw new ErrorException("Curl module is not available on this system");
            }
        
            $process = curl_init($url); // API url
            curl_setopt($process, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            ]);
            curl_setopt($process, CURLOPT_TIMEOUT, 20);
            curl_setopt($process, CURLOPT_FAILONERROR, true);
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($process);
            if (curl_errno($process)) {
                throw new \Exception(curl_error($process));
            }
        
            $result['info'] = json_decode($res, true);
            $result['httpres'] = curl_getinfo($process, CURLINFO_HTTP_CODE);
            curl_close($process);
        } catch (\Exception $err) {
            $result['error'] = $err->getMessage();
            $result['info']=[];
        }
        return $result;
    }
}
