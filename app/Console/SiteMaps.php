<?php declare(strict_types = 1);

namespace App\Console;

use App\Library\Config;
use App\Library\SphinxPdo;
use PDO;

class SiteMaps
{
    private $sphinx;
    private $per_page;
    private $total_listings;
    private $featured_listings;
    private $browse_listings;
    private $mod_date;
    
    private $writer;
    private $domain;
    private $path;
    private $filename = 'sitemap';
    private $current_item = 0;
    private $current_sitemap = 0;
    const EXT = '.xml';
    const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const DEFAULT_PRIORITY = 0.5;
    const ITEM_PER_SITEMAP = 50000;
    const SEPERATOR = '-';
    const INDEX_SUFFIX = 'index';
    
    const CHOWN_USER = 'daemon'; // web user
    const CHOWN_GRP = 'daemon'; // web group
    const CHMOD_VAL = 0755;
    
    private $sorts = [
        '/authora', '/titlea', '/pricelow'
    ];
    
    
    public function __construct()
    {
        $this->sphinx = new SphinxPdo();
        $this->per_page = Config::get('listing_per_page');
        $this->mod_date = date('c', time());
        $this->browse_listings = Config::get('browse_listings');
    
        $stmt = $this->sphinx->prepare('SELECT count(*) AS items FROM storeproducts');
        $stmt->execute();
        $this->total_listings = $stmt->fetch()['items'];
    
        $stmt = $this->sphinx->prepare('SELECT count(*) AS items FROM storeproducts WHERE featured=\'Y\' AND product_price > '.Config::get('featured_minprice'));
        $stmt->execute();
        $this->featured_listings = $stmt->fetch()['items'];
        
        $this->setDomain(Config::get('company_url'));
        $this->setPath(Config::get('web_dir').'/');
    }
    
    public function run ()
    {
        echo 'Running Command SiteMaps '."\n";
        $this->addItem('', '1.0', 'weekly', 'Today');
        $this->addItem('/stores', '1.0', 'weekly', 'Today');
        $this->addItem('/featured', '1.0', 'weekly', 'Today');
        $this->addItem('/browse', '1.0', 'weekly', 'Today');
   
        foreach ($this->sorts as $sort) {
            $this->addMultiPage('', $sort, $this->featured_listings);
        }

        foreach ($this->sorts as $sort) {
            $this->addMultiPage('/featured', $sort, $this->featured_listings);
        }
        foreach ($this->sorts as $sort) {
            $this->addMultiPage('/browse', $sort, $this->browse_listings);
        }

        $this->addStores();
        $this->addProducts();
        
        $this->createSitemapIndex($this->getDomain().'/', 'Today');
        
        $extra = $this->current_sitemap+1;
        while (file_exists($this->getPath().'/sitemap-'.$extra.'.xml')) {
            unlink($this->getPath().'/sitemap-'.$extra.'.xml');
            $extra++;
        }
        
        echo 'Command SiteMaps Complete'. "\n";
        return true;
    }
    
    public function addStores()
    {
        $stmt = $this->sphinx->prepare('SELECT id, store_name FROM stores LIMIT 0,5000 option max_matches=5000');
        $stmt->execute();
        $stores = $stmt->fetchAll();
        foreach ($stores as $store) {
            if ($store['store_name']) {
                $link_title = urlencode(str_replace(['/', '.'], '', substr($store['store_name'], 0, 180)));
                $url = '/storeitems/' . $store['id'] . '/' . $link_title;
                $this->addItem($url, '1.0', 'weekly', 'Today');
    
                $stmt = $this->sphinx->prepare('SELECT count(*) AS items FROM storeproducts WHERE store_id = :id');
                $stmt->bindParam(':id', $store['id'], PDO::PARAM_INT);
                $stmt->execute();
                $store_cnt = $stmt->fetch()['items'];
                foreach ($this->sorts as $sort) {
                    $this->addMultiPage($url, $sort, $store_cnt);
                }
            }
        }
        return true;
    }
    
    public function addMultiPage($route, $sort, $listings)
    {
        $total_pages = ($listings/$this->per_page);
        for ($i=1; $i<=$total_pages; $i++) {
            $url = $route.$sort.'/'.$i.'/'.$this->per_page;
            $this->addItem($url, '1.0', 'weekly', 'Today');
        }
        return true;
    }
    
    public function addProducts()
    {
        $stmt = $this->sphinx->prepare('SELECT id, product_title FROM storeproducts LIMIT 0,:end option max_matches='.$this->total_listings);
        $stmt->bindParam(':end', $this->total_listings, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();
        foreach ($products as $product) {
            if ($product['product_title']) {
                $link_title = urlencode(str_replace(['/', '.'], '', substr($product['product_title'], 0, 180)));
            } else {
                $link_title = '';
            }
            $url = '/details/' . $product['id'] . '/' . $link_title;
            $this->addItem($url, '1.0', 'weekly', 'Today');
        }
        return true;
    }
    
    /**
     * Sets root path of the website, starting with http:// or https://
     *
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
        return $this;
    }
    /**
     * Returns root path of the website
     *
     * @return string
     */
    private function getDomain() {
        return $this->domain;
    }
    /**
     * Returns XMLWriter object instance
     *
     * @return \XMLWriter
     */
    private function getWriter() {
        return $this->writer;
    }
    /**
     * Assigns XMLWriter object instance
     *
     * @param \XMLWriter $writer
     */
    private function setWriter(\XMLWriter $writer) {
        $this->writer = $writer;
    }
    /**
     * Returns path of sitemaps
     *
     * @return string
     */
    private function getPath() {
        return $this->path;
    }
    /**
     * Sets paths of sitemaps
     *
     * @param string $path
     * @return Sitemap
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }
    /**
     * Returns filename of sitemap file
     *
     * @return string
     */
    private function getFilename() {
        return $this->filename;
    }
    /**
     * Sets filename of sitemap file
     *
     * @param string $filename
     * @return Sitemap
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }
    /**
     * Returns current item count
     *
     * @return int
     */
    private function getCurrentItem() {
        return $this->current_item;
    }
    /**
     * Increases item counter
     *
     */
    private function incCurrentItem() {
        $this->current_item = $this->current_item + 1;
    }
    /**
     * Returns current sitemap file count
     *
     * @return int
     */
    private function getCurrentSitemap() {
        return $this->current_sitemap;
    }
    /**
     * Increases sitemap file count
     *
     */
    private function incCurrentSitemap() {
        $this->current_sitemap = $this->current_sitemap + 1;
    }
    
    /**
     * Increases sitemap file count
     *
     */
    private function chownChmod($filename) {
        chown($filename, self::CHOWN_USER);
        chgrp($filename, self::CHOWN_GRP);
        chmod($filename, self::CHMOD_VAL);
        return true;
    }
    /**
     * Prepares sitemap XML document
     *
     */
    private function startSitemap() {
        $this->setWriter(new \XMLWriter());
        if ($this->getCurrentSitemap()) {
            $this->getWriter()->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . $this->getCurrentSitemap() . self::EXT);
            $this->chownChmod($this->getPath() . $this->getFilename() . self::SEPERATOR . $this->getCurrentSitemap() . self::EXT);
            
        } else {
            $this->getWriter()->openURI($this->getPath() . $this->getFilename() . self::EXT);
            $this->chownChmod($this->getPath() . $this->getFilename() . self::EXT);
        }
        $this->getWriter()->startDocument('1.0', 'UTF-8');
        $this->getWriter()->setIndent(true);
        $this->getWriter()->startElement('urlset');
        $this->getWriter()->writeAttribute('xmlns', self::SCHEMA);
    }
    /**
     * Adds an item to sitemap
     *
     * @param string $loc URL of the page. This value must be less than 2,048 characters.
     * @param string|null $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * @param string|null $changefreq How frequently the page is likely to change. Valid values are always, hourly, daily, weekly, monthly, yearly and never.
     * @param string|int|null $lastmod The date of last modification of url. Unix timestamp or any English textual datetime description.
     * @return Sitemap
     */
    public function addItem($loc, $priority = self::DEFAULT_PRIORITY, $changefreq = NULL, $lastmod = NULL) {
        if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
            if ($this->getWriter() instanceof \XMLWriter) {
                $this->endSitemap();
            }
            $this->startSitemap();
            $this->incCurrentSitemap();
        }
        $this->incCurrentItem();
        $this->getWriter()->startElement('url');
        $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
        if($priority !== null)
            $this->getWriter()->writeElement('priority', $priority);
        if ($changefreq)
            $this->getWriter()->writeElement('changefreq', $changefreq);
        if ($lastmod)
            $this->getWriter()->writeElement('lastmod', $this->mod_date);
        $this->getWriter()->endElement();
        return $this;
    }
    
    /**
     * Finalizes tags of sitemap XML document.
     *
     */
    private function endSitemap() {
        if (!$this->getWriter()) {
            $this->startSitemap();
        }
        $this->getWriter()->endElement();
        $this->getWriter()->endDocument();
    }
    
    /**
     * Writes Google sitemap index for generated sitemap files
     *
     * @param string $loc Accessible URL path of sitemaps
     * @param string|int $lastmod The date of last modification of sitemap. Unix timestamp or any English textual datetime description.
     */
    public function createSitemapIndex($loc, $lastmod = 'Today') {
        $this->endSitemap();
        $indexwriter = new \XMLWriter();
        $indexwriter->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
        $this->chownChmod($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
        $indexwriter->startDocument('1.0', 'UTF-8');
        $indexwriter->setIndent(true);
        $indexwriter->startElement('sitemapindex');
        $indexwriter->writeAttribute('xmlns', self::SCHEMA);
        for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
            $indexwriter->startElement('sitemap');
            $indexwriter->writeElement('loc', $loc . $this->getFilename() . ($index ? self::SEPERATOR . $index : '') . self::EXT);
            $indexwriter->writeElement('lastmod', $this->mod_date);
            $indexwriter->endElement();
        }
        $indexwriter->endElement();
        $indexwriter->endDocument();
    }
}