<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

class ElegantalTinyPngImageCompressClient
{

    const API_ENDPOINT = "https://api.tinify.com";

    private $options;

    public static function userAgent()
    {
        $curl = curl_version();
        return "Tinify/" . ElegantalTinyPngImageCompressTinify::VERSION . " PHP/" . PHP_VERSION . " curl/" . $curl["version"];
    }

    private static function caBundle()
    {
        return dirname(__FILE__) . "/cacert.pem";
    }

    public function __construct($key, $app_identifier = null)
    {
        $this->options = array(
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_USERPWD => "api:" . $key,
            CURLOPT_CAINFO => self::caBundle(),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => join(" ", array_filter(array(self::userAgent(), $app_identifier))),
        );
    }

    public function request($method, $url, $body = null, $header = array())
    {
        if (is_array($body)) {
            if (!empty($body)) {
                $body = Tools::jsonEncode($body);
                array_push($header, "Content-Type: application/json");
            } else {
                $body = null;
            }
        }

        $request = curl_init();
        curl_setopt_array($request, $this->options);

        $url = Tools::strtolower(Tools::substr($url, 0, 6)) == "https:" ? $url : ElegantalTinyPngImageCompressClient::API_ENDPOINT . $url;
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_HTTPHEADER, $header);
        curl_setopt($request, CURLOPT_CUSTOMREQUEST, Tools::strtoupper($method));

        if ($body) {
            curl_setopt($request, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($request);

        if (is_string($response)) {
            $status = curl_getinfo($request, CURLINFO_HTTP_CODE);
            $headerSize = curl_getinfo($request, CURLINFO_HEADER_SIZE);
            curl_close($request);

            $headers = self::parseHeaders(Tools::substr($response, 0, $headerSize));
            $body = Tools::substr($response, $headerSize);

            if (isset($headers["compression-count"])) {
                ElegantalTinyPngImageCompressTinify::setCompressionCount((int) $headers["compression-count"]);
            }

            if ($status >= 200 && $status <= 299) {
                return array("body" => $body, "headers" => $headers);
            }

            $details = Tools::jsonDecode($body);
            if (!$details) {
                $message = sprintf("Error while parsing response: %s (#%d)", PHP_VERSION_ID >= 50500 ? json_last_error_msg() : "Error", json_last_error());
                $details = (object) array(
                        "message" => $message,
                        "error" => "ParseError"
                );
            }

            throw ElegantalTinyPngImageCompressException::create($details->message, $details->error, $status);
        } else {
            $message = sprintf("%s (#%d)", curl_error($request), curl_errno($request));
            curl_close($request);
            throw new ElegantalTinyPngImageCompressConnectionException("Error while connecting: " . $message);
        }
    }

    protected static function parseHeaders($headers)
    {
        if (!is_array($headers)) {
            $headers = explode("\r\n", $headers);
        }

        $res = array();
        foreach ($headers as $header) {
            if (empty($header)) {
                continue;
            }
            $split = explode(":", $header, 2);
            if (count($split) === 2) {
                $res[Tools::strtolower($split[0])] = trim($split[1]);
            }
        }
        return $res;
    }
}
