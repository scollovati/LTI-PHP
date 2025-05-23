<?php
declare(strict_types=1);

namespace ceLTIc\LTI\Content;

use ceLTIc\LTI\Util;

/**
 * Class to represent a content-item image object
 *
 * @author  Stephen P Vickers <stephen@spvsoftwareproducts.com>
 * @copyright  SPV Software Products
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3
 */
class Image
{

    /**
     * URL of image.
     *
     * @var string|null $url
     */
    private ?string $url = null;

    /**
     * Width of image.
     *
     * @var int|null $width
     */
    private ?int $width = null;

    /**
     * Height of image.
     *
     * @var int|null $height
     */
    private ?int $height = null;

    /**
     * Class constructor.
     *
     * @param string   $url     URL of image
     * @param int|null $width   Width of image in pixels (optional)
     * @param int|null $height  Height of image in pixels (optional)
     */
    function __construct(string $url, ?int $width = null, ?int $height = null)
    {
        $this->url = $url;
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * Generate the JSON-LD object representation of the image.
     *
     * @return object  JSON object
     */
    public function toJsonldObject(): object
    {
        $image = new \stdClass();
        $image->{'@id'} = $this->url;
        if (!is_null($this->width)) {
            $image->width = $this->width;
        }
        if (!is_null($this->height)) {
            $image->height = $this->height;
        }

        return $image;
    }

    /**
     * Generate the JSON object representation of the image.
     *
     * @return object  JSON object
     */
    public function toJsonObject(): object
    {
        $image = new \stdClass();
        $image->url = $this->url;
        if (!is_null($this->width)) {
            $image->width = $this->width;
        }
        if (!is_null($this->height)) {
            $image->height = $this->height;
        }

        return $image;
    }

    /**
     * Generate an Image object from its JSON or JSON-LD representation.
     *
     * @param object|string $item  A JSON or JSON-LD object representing an image or an image URL
     *
     * @return Image|null  The Image object
     */
    public static function fromJsonObject(object|string $item): ?Image
    {
        $ok = true;
        $obj = null;
        $width = null;
        $height = null;
        if (is_object($item)) {
            $url = null;
            foreach (get_object_vars($item) as $name => $value) {
                switch ($name) {
                    case '@id':
                    case 'url':
                        $url = Util::checkUrl($item, "Image/{$name}", true);
                        if (is_null($url)) {
                            $ok = false;
                        }
                        break;
                    case 'width':
                        $width = Util::checkInteger($item, "Image/width", false, 0, true);
                        if (is_null($width) && Util::$strictMode) {
                            $ok = false;
                        }
                        break;
                    case 'height':
                        $height = Util::checkInteger($item, "Image/height", false, 0, true);
                        if (is_null($height) && Util::$strictMode) {
                            $ok = false;
                        }
                        break;
                }
            }
        } else {
            $url = $item;
        }
        if ($ok && is_string($url)) {
            $obj = new Image($url, $width, $height);
        }

        return $obj;
    }

}
