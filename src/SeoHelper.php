<?php

namespace dynamikaweb\seo;

use yii\helpers\Inflector;

class SeoHelper
{
    private static $title;
    private static $image;
    private static $keywords = [];
    private static $site_name;
    private static $description;

    /**
     * Set or Replace metatags
     *
     * @param array $seo
     * @return void
     */
    public static function tags($seo)
    {
        self::$title = isset($seo['title']) && !empty($seo['title'])? $seo['title']: self::$title;
        self::$image = isset($seo['image']) && !empty($seo['image'])? $seo['image']: self::$image;
        self::$keywords = isset($seo['keywords']) && !empty($seo['keywords'])? $seo['keywords']: self::$keywords;
        self::$site_name = isset($seo['site_name']) && !empty($seo['site_name'])? $seo['site_name']: self::$site_name;
        self::$description = isset($seo['description']) && !empty($seo['description'])? $seo['description']: self::$description;
    }

    /**
     * Set metatag only when is undefined
     *
     * @param array $seo
     * @return void
     */
    public static function defaultTags($seo)
    {
        self::$title = empty(self::$title) && isset($seo['title'])? $seo['title']: self::$title;
        self::$image = empty(self::$image) && isset($seo['image'])? $seo['image']: self::$image;
        self::$keywords = empty(self::$keywords) && isset($seo['keywords'])? $seo['keywords']: self::$keywords;
        self::$site_name = empty(self::$site_name) && isset($seo['site_name'])? $seo['site_name']: self::$site_name;
        self::$description = empty(self::$description) && isset($seo['description'])? $seo['description']: self::$description;
    }

    /**
     * Register metags
     *
     * @param object $view
     * @return string
     */
    public static function register($view)
    {
        $image = self::$image;
        $site_name = self::$site_name;
        $description = self::$description;
        $keywords = self::generateKeyWords(self::$keywords);
        $title = empty(self::$title)? self::$site_name: self::$title.' | '.self::$site_name;
        $abstract = strlen(self::$description) >= 97? substr(self::$description, 0, 93).'...': self::$description;
        
        $view->registerMetaTag(['name' => 'title', 'content' => $title]);
        $view->registerMetaTag(['name' => 'og:title', 'content' => $title]);
        $view->registerMetaTag(['name' => 'og:site_name', 'content' => $site_name]);
        $view->registerMetaTag(['name' => 'image', 'content' => $image]);
        $view->registerMetaTag(['name' => 'og:image', 'content' => $image]);
        $view->registerMetaTag(['name' => 'og:image:url', 'content' => $image]);
        $view->registerMetaTag(['name' => 'og:image:secure_url', 'content' => $image]);
        $view->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);
        $view->registerMetaTag(['name' => 'abstract', 'content' => $abstract]);
        $view->registerMetaTag(['name' => 'description', 'content' => $description]);
        $view->registerMetaTag(['name' => 'og:description', 'content' => $description]);
        $view->registerMetaTag(['name' => 'og:type', 'content' => 'website']);
        $view->registerLinkTag(['rel' => 'image_src', 'href' => $image]);
    }

    /**
     * Separate the 16 most used words that contain more than 3 characters
     *
     * @param array|string $keywords
     * @return void
     */
    private static function generateKeyWords($keywords)
    {
        if (is_array($keywords) && !empty($keywords)) {
            $keywords = implode('-', $keywords);
        } 
        else if (!is_string($keywords) || empty($keywords)) {
            $keywords = self::$title.'-'.self::$description;
        }

        preg_match_all("/(\w{3,}+)/", Inflector::slug($keywords), $keywords);
        $keywords = array_count_values($keywords[0]);
        $keywords = array_slice(array_keys($keywords), 0, 16);
        return implode(', ', $keywords);
    }
}
