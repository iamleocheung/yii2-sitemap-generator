<?php
/**
 * @copyright Copyright (c) 2023 Leo Cheung
 * @license   https://github.com/iamleocheung/yii2-sitemap-generator/blob/master/LICENSE
 * @link      https://github.com/iamleocheung/yii2-sitemap-generator#readme
 * @author    Leo Cheung <hello@leocheu.ng>
 */

namespace demi\sitemap\interfaces;

/**
 * Interface GoogleVideo
 *
 * @url https://developers.google.com/search/docs/appearance/video#provide-a-high-quality-thumbnail
 *
 * @package demi\sitemap\interfaces
 */
interface GoogleVideo
{
    /**
     * Get list on videos assigned to material
     *
     * @param static|self|mixed $material
     * @param string|null $lang Required language of items content
     *
     * @return array
     */
    public function getSitemapMaterialVideos($material, $lang = null);

    /**
     * [REQIRED] The URL of the video.
     *
     * @param mixed $video      Video element from [[getSitemapMaterialVideos]]
     * @param string|null $lang Required language of item content
     *
     * @return string
     */
    public function getSitemapVideoPlayerLoc($video, $lang = null);

    /**
     * [OPTIONAL] The thumbnail location of the video.
     *
     * @example <video:geo_location>Limerick, Ireland</video:geo_location>.
     *
     * @param mixed $video      Video element from [[getSitemapMaterialVideos]]
     * @param string|null $lang Required language of item content
     *
     * @return string
     */
    public function getSitemapVideoThumbnailLoc($video, $lang = null);

    /**
     * [OPTIONAL] The description of the video.
     *
     * @param mixed $video      Video element from [[getSitemapMaterialVideos]]
     * @param string|null $lang Required language of item content
     *
     * @return string
     */
    public function getSitemapVideoDescription($video, $lang = null);

    /**
     * [OPTIONAL] The title of the video.
     *
     * @param mixed $video      Video element from [[getSitemapMaterialVideos]]
     * @param string|null $lang Required language of item content
     *
     * @return string
     */
    public function getSitemapVideoTitle($video, $lang = null);
}
