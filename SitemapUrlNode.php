<?php
/**
 * @copyright Copyright (c) 2023 Leo Cheung
 * @license   https://github.com/iamleocheung/yii2-sitemap-generator/blob/master/LICENSE
 * @link      https://github.com/iamleocheung/yii2-sitemap-generator#readme
 * @author    Leo Cheung <hello@leocheu.ng>
 */

namespace demi\sitemap;

use demi\sitemap\interfaces\Basic;
use yii\base\BaseObject;

class SitemapUrlNode extends BaseObject
{
    public $loc;
    public $lastmod;
    public $changefreq;
    public $priority;
    public $images = [];
    public $videos = [];
    public $alternateLinks = [];

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * Convert this url node object to valid xml-string
     *
     * @return string
     */
    public function __toString()
    {
        if (empty($this->loc)) {
            // Empty loc is not allowed in sitemap.xml
            return '';
        }

        $url = ['<url>'];

        $url[] = "\t<loc>" . static::prepareUrl($this->loc) . '</loc>';

        // Basic data
        if ($this->lastmod !== null) {
            $lastmod = is_int($this->lastmod) ? $this->lastmod : strtotime($this->lastmod);
            $url[] = "\t<lastmod>" . date(DATE_W3C, $lastmod) . '</lastmod>';
        }

        if ($this->changefreq !== null) {
            $url[] = "\t<changefreq>{$this->changefreq}</changefreq>";
        }

        if ($this->priority !== null) {
            $url[] = "\t<priority>{$this->priority}</priority>";
        }

        // Google images data
        foreach ($this->images as $image) {
            $url[] = "\t<image:image>";
            $url[] = "\t\t<image:loc>" . static::prepareUrl($image['loc']) . '</image:loc>';

            if ($image['caption'] !== null) {
                $url[] = "\t\t<image:caption>" . htmlspecialchars($image['caption'], ENT_XML1, 'UTF-8') . '</image:caption>';
            }
            if ($image['geoLocation'] !== null) {
                $url[] = "\t\t<image:geo_location>" . htmlspecialchars($image['geoLocation'], ENT_XML1, 'UTF-8') . '</image:geo_location>';
            }
            if ($image['title'] !== null) {
                $url[] = "\t\t<image:title>" . htmlspecialchars($image['title'], ENT_XML1, 'UTF-8') . '</image:title>';
            }
            if ($image['license'] !== null) {
                $url[] = "\t\t<image:license>" . htmlspecialchars($image['license'], ENT_XML1, 'UTF-8') . '</image:license>';
            }

            $url[] = "\t</image:image>";
        }
        
        // Google videos data
        foreach ($this->videos as $video) {
            $url[] = "\t<video:video>";
            $url[] = "\t\t<video:player_loc>" . static::prepareUrl($video['player_loc']) . '</video:player_loc>';
        
            if ($video['thumbnail_loc'] !== null) {
                $url[] = "\t\t<video:thumbnail_loc>" . htmlspecialchars($video['thumbnail_loc'], ENT_XML1, 'UTF-8') . '</video:thumbnail_loc>';
            }
            if ($video['title'] !== null) {
                $url[] = "\t\t<video:title>" . htmlspecialchars($video['title'], ENT_XML1, 'UTF-8') . '</video:title>';
            }
            if ($video['description'] !== null) {
                $url[] = "\t\t<video:description>" . htmlspecialchars($video['description'], ENT_XML1, 'UTF-8') . '</video:description>';
            }
        
            $url[] = "\t</video:video>";
        }

        // Google alternate hreflang data
        foreach ($this->alternateLinks as $hreflang => $href) {
            $url[] = "\t" . '<xhtml:link rel="alternate" hreflang="' . $hreflang . '" ' . 'href="' .
                static::prepareUrl($href) . '" />';
        }

        $url[] = '</url>';

        return implode(PHP_EOL, $url);
    }

    /**
     * Set location value(url)
     *
     * @param string $loc
     *
     * @return $this
     */
    public function loc($loc)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * Set last modificated time value
     *
     * @param string $lastmod String applicable to strtotime() function
     *
     * @return $this
     */
    public function lastmod($lastmod)
    {
        $this->lastmod = $lastmod;

        return $this;
    }

    /**
     * Set the regularity of content changing for [[loc]]
     *
     * @param string $changefreq
     *
     * @return $this
     */
    public function changefreq($changefreq)
    {
        $this->changefreq = $changefreq;

        return $this;
    }

    /**
     * Set priority value
     * May be between 0.0 - 1.0
     *
     * @param string $priority
     *
     * @return $this
     */
    public function priority($priority = Basic::PRIORITY_5)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Add image to [[images]] set
     *
     * @param string $loc
     * @param string|null $geoLocation
     * @param string|null $caption
     * @param string|null $title
     * @param string|null $license
     *
     * @return $this
     */
    public function addImage($loc, $geoLocation = null, $caption = null, $title = null, $license = null)
    {
        if (empty($loc)) {
            return $this;
        }
    
        $image = [
            'loc' => $loc,
            'geoLocation' => $geoLocation,
            'caption' => $caption,
            'title' => $title,
            'license' => $license,
        ];
        $this->images[] = $image;
    
        return $this;
    }
    
    /**
     * Add video to [[videos]] set
     *
     * @param string $player_loc
     * @param string|null $thumbnail_loc
     * @param string|null $title
     * @param string|null $description
     *
     * @return $this
     */
    public function addVideo($player_loc, $thumbnail_loc = null, $title = null, $description = null)
    {
        if (empty($player_loc)) {
            return $this;
        }
    
        $video = [
            'player_loc' => $player_loc,
            'thumbnail_loc' => $thumbnail_loc,
            'title' => $title,
            'description' => $description,
        ];
        $this->videos[] = $video;
    
        return $this;
    }

    /**
     * Add alternate link to [[alternateLinks]] set
     *
     * @param string $hreflang Language code
     * @param string $href     Absolute url to view material on $hreflang language
     *
     * @return $this
     */
    public function addAlternateLink($hreflang, $href)
    {
        $this->alternateLinks[$hreflang] = $href;

        return $this;
    }

    /**
     * Prepare url to place in <loc> tag
     *
     * @param string $url original url
     *
     * @return string
     */
    public static function prepareUrl($url)
    {
        // $url = urlencode($url);

        $replacement = [
            '&' => '&amp;',
            "'" => '&apos;',
            '"' => '&quot;',
            '>' => '&gt;',
            '<' => '&lt;',
        ];

        $url = str_replace(array_keys($replacement), array_values($replacement), $url);

        return $url;
    }
}
