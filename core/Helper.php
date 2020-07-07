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

/**
 * Class logicCore
 *
 * @category logicCore
 * @author   Raimundo Yabar <djyabar@gmail.com>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     example.com <https://example.com>
 */
trait Helper
{
    /**
     * Sanitize input
     *
     * @param string $string input
     *
     * @return array
     */
    public function sanitizeInput(string $string):?string
    {
        $string = filter_var($string, FILTER_SANITIZE_STRING);
        $string = stripslashes($string);
        $string = str_replace([ '‘', '’', '“', '”' ], [ "'", "'", '"', '"' ], $string);
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        $string = preg_replace('/[\n\r\t]/', ' ', $string);
        $string = trim($string, "\x00..\x1F");
        $string = sanitize_text_field($string);
        return $string;
    }

    
    public function base64urlEncode(string $text):?string
    {
        return str_replace([ '+', '/' ], [ '-', '_' ], base64_encode($text));
    }

    public function base64urlDecode(string $text):?string
    {
        return base64_decode(str_replace([ '-', '_' ], [ '+', '/' ], $text));
    }
}
