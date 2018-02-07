<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

/**
 * This is an object model class used to manage module data
 */
class ElegantalTinyPngImageCompressImagesClass extends ElegantalTinyPngImageCompressObjectModel
{

    public static $STATUS_NOT_COMPRESSED = 0;
    public static $STATUS_COMPRESSED = 1;
    public static $STATUS_FAILED = 2;
    public $tableName = 'elegantaltinypngimagecompress_images';
    public static $definition = array(
        'table' => 'elegantaltinypngimagecompress_images',
        'primary' => 'id_elegantaltinypngimagecompress_images',
        'fields' => array(
            'id_elegantaltinypngimagecompress' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true),
            'status' => array('type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'),
            'image_path' => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'image_size_before' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'image_size_after' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'modified_at' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function add($auto_date = true, $null_values = false)
    {
        // Check if the same image was already compressed or is pending compression
        $sql = new DbQuery();
        $sql->select("*");
        $sql->from($this->tableName, "t");
        $sql->where(
            "t.`image_path` = '" . pSQL($this->image_path) . "' AND " .
            "t.`modified_at` = '" . pSQL($this->modified_at) . "' AND " .
            "(t.`status` = " . (int) self::$STATUS_COMPRESSED . " OR " .
            "(t.`status` = " . (int) self::$STATUS_NOT_COMPRESSED . " AND t.`id_elegantaltinypngimagecompress` > 0))"
        );

        $model = Db::getInstance()->getRow($sql);

        if (!empty($model)) {
            return false;
        }

        return parent::add($auto_date, $null_values);
    }

    /**
     * Compress current image using TinyPNG API
     * @param string $api_key
     * @return boolean
     */
    public function compress($api_key)
    {
        if (!is_file($this->image_path) || !filesize($this->image_path)) {
            $this->status = self::$STATUS_FAILED;
            $this->update();
            return false;
        }

        self::requireTinify();

        ElegantalTinyPngImageCompressTinify::setKey($api_key);

        try {
            $source = ElegantalTinyPngImageCompressSource::fromFile($this->image_path);
            $compressResult = $source->toFile($this->image_path);

            if ($compressResult) {
                //Clear cache of old file
                clearstatcache(true, $this->image_path);
                $this->image_size_after = filesize($this->image_path);
                $this->modified_at = date('Y-m-d H:i:s', filemtime($this->image_path));
                $this->status = self::$STATUS_COMPRESSED;
                $this->update();
                return true;
            } else {
                $this->status = self::$STATUS_FAILED;
                $this->update();
                return false;
            }
        } catch (ElegantalTinyPngImageCompressAccountException $e) {
            return 'There was a problem with your API key or with your API account. Your request could not be authorized. ' . $e->getMessage();
        } catch (ElegantalTinyPngImageCompressClientException $e) {
            // return 'The request could not be completed because of a problem with the submitted data. ' . $e->getMessage();
            // If there is a problem with submitted data, it should not stop, but continue with the next image
            $this->status = self::$STATUS_FAILED;
            $this->update();
            return false;
        } catch (ElegantalTinyPngImageCompressServerException $e) {
            // return 'The request could not be completed because of a temporary problem with the Tinify API. ' . $e->getMessage();
            // It is safe to retry request, as this is a problem from Tinyfy API
            $this->status = self::$STATUS_FAILED;
            $this->update();
            return false;
        } catch (ElegantalTinyPngImageCompressConnectionException $e) {
            return 'The request could not be sent because there was an issue connecting to the Tinify API. You should verify your network connection. ' . $e->getMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Returns number of compressions used by the API Key in the current month
     * @param string $api_key
     * @return int
     */
    public static function getTinifyCompressionsCount($api_key)
    {
        $result = 0;

        if (!empty($api_key)) {
            try {
                self::requireTinify();
                ElegantalTinyPngImageCompressTinify::setKey($api_key);
                ElegantalTinyPngImageCompressTinify::validate();
                ElegantalTinyPngImageCompressTinify::getCompressionCount();
                $result = (int) ElegantalTinyPngImageCompressTinify::getCompressionCount();
            } catch (ElegantalTinyPngImageCompressException $e) {
                // Do nothing
            }
        }

        return $result;
    }

    /**
     * Requires Tinify Classes
     */
    public static function requireTinify()
    {
        $dir = _ELEGANTALTINYPNGIMAGECOMPRESS_DIR_ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'tinify' . DIRECTORY_SEPARATOR;
        require_once($dir . 'ElegantalTinyPngImageCompressTinify.php');
        require_once($dir . 'ElegantalTinyPngImageCompressClient.php');
        require_once($dir . 'ElegantalTinyPngImageCompressResultMeta.php');
        require_once($dir . 'ElegantalTinyPngImageCompressResult.php');
        require_once($dir . 'ElegantalTinyPngImageCompressSource.php');
        require_once($dir . 'ElegantalTinyPngImageCompressException.php');
        require_once($dir . 'ElegantalTinyPngImageCompressAccountException.php');
        require_once($dir . 'ElegantalTinyPngImageCompressClientException.php');
        require_once($dir . 'ElegantalTinyPngImageCompressServerException.php');
        require_once($dir . 'ElegantalTinyPngImageCompressConnectionException.php');
    }
}
