<?php declare(strict_types = 1);

namespace App\Library;

use PDO;

/**
 *----------------------------------------------------
 * Paginate Class
 *----------------------------------------------------
 * Simple paginator for long listings
 *
 */
class Paginate
{
    
    private static $pgtotal; // Total number of entries
    private static $pgnow;   // Current page
    private static $perpage; // Entries per page
    private static $pgall;   // Total number of pages
    private static $pgX;     // Starting entry number, also used for SQL LIMIT X
    private static $pgY;     // Ending entry number
    
    /* [PAGINATION - render] */
    private static $pgurl;   // URL for anchor link or JS function
    private static $pgmode;  // "A" for anchor link, "J" for JS onclick
    private static $pgadj;   // Number of adjacent pages for pagination squares
    
    private static $initialized = false; // already created
    private static $paginate = false;  // do we need to paginate
    
    /**
     * Initialize - create object only once
     *
     * @param  total   - total items in query result - get from query in Model
     * @param  pgnow   - current page - place hidden in form or use 1 for first page
     * @param  perpage - listings per page - place hidden field in form or use
     *                   Config::$get['listing_limit'] for first page
     * @return boolean - Initialized object
     */
    public static function initialize($db, $query, $data=[], $pgnow=null, $perpage=null)
    {
        if (self::$initialized)
            return;
        // Do We Need to Paginate?
        $stmt = $db->prepare($query);
        $stmt->execute($data);
        $row = $stmt->fetch(PDO::FETCH_NAMED);
        if ($row['count'] > $perpage) {
            self::setup($row['count'], $pgnow,$perpage);
            self::$paginate = true;
        }
        self::$initialized = true;
        return true;
    }
    
    public static function setup($total=null, $pgnow=null, $perpage=null)
    {
        if (self::$initialized)
            return;
        if (!is_numeric($total)) { $total = 0; }
        if (!is_numeric($pgnow)) { $pgnow = 1; }
        if (!is_numeric($perpage)) { $perpage = 20; }
        self::$pgtotal = $total<0 ? 0 : $total ;
        self::$pgnow   = $pgnow<1 ? 1 : $pgnow ;
        self::$perpage = $perpage<=0 ? 20 : $perpage ;
        self::$pgall   = CEIL(self::$pgtotal / self::$perpage);
        self::$pgX     = self::$perpage * (self::$pgnow-1);
        self::$pgY     = self::$pgX + (self::$perpage - 1);
        self::$initialized = true;
        return true;
    }
    
    public static function limit()
    {
        // do nothing if pagination not needed.
        if (!self::$paginate) return '';
        return ' LIMIT ' . self::$pgX . ',' . self::$perpage;
    }
    
    public static function render($url="", $mode="", $adj=null)
    {
        // do nothing if pagination not needed.
        if (!self::$paginate) return '';
        
        // SETUP
        self::$pgurl  = $url;
        self::$pgmode = $mode!="A" && $mode!="J" ? "A" : $mode ;
        self::$pgadj  = is_numeric($adj) ? $adj : 2;
        if (self::$pgadj<=0) { self::$pgadj = 2; }
        
        // DRAW
        echo '<nav aria-label="page navigation" class="d-flex justify-content-center mb-5 mt-3"><ul class="pagination">';
        // ENOUGH PAGES TO HIDE
        if (self::$pgall>5 + (self::$pgadj*2)) {
            // CURRENT PAGE IS CLOSE TO BEGINNING - HIDE LATER PAGES
            if (self::$pgnow < 2 + (self::$pgadj*2)) {
                for ($i=1; $i<3 + (self::$pgadj*2); $i++) { self::cell($i); }
                echo '<li class="page-item"><span class="page-link">...</span></li>';
                for ($i=self::$pgall-1; $i<=self::$pgall; $i++) { self::cell($i); }
            }
            
            // CURRENT PAGE SOMEWHERE IN THE MIDDLE
            elseif (self::$pgall - (self::$pgadj*2) > self::$pgnow && self::$pgnow > (self::$pgadj*2)) {
                for ($i=1; $i<3; $i++) { self::cell($i); }
                echo '<li class="page-item"><span class="page-link">...</span></li>';
                for ($i=self::$pgnow-self::$pgadj; $i<=self::$pgnow+self::$pgadj; $i++) { self::cell($i); }
                echo '<li class="page-item"><span class="page-link">...</span></li>';
                for ($i=self::$pgall-1; $i<=self::$pgall; $i++) { self::cell($i); }
            }
            
            // CURRENT PAGE SOMEWHERE IN THE MIDDLE - HIDE EARLY PAGES
            else {
                for ($i=1; $i<3; $i++) { self::cell($i); }
                echo '<li class="page-item"><span class="page-link">...</span></li>';
                for ($i=self::$pgall - (2+(self::$pgadj * 2)); $i<=self::$pgall; $i++) { self::cell($i); }
            }
        }
        
        // NOT ENOUGH PAGES TO BOTHER HIDING
        else {
            for ($i=1; $i<=self::$pgall; $i++) { self::cell($i); }
        }
        echo '</ul></nav>';
    }
    
    private static function cell($pg)
    {
        // cell() : helper function for pgdraw - draws pagination cells
        // PARAM $pg : page number
        $class = $pg==self::$pgnow ? ' class="page-item active"' : ' class="page-item"';
        if (self::$pgmode == 'A') {
            $link = '<a href="'.self::$pgurl.'/'.$pg.'/'.self::$perpage.'" class="page-link">';
        } else {
            $link = '<div onclick="'.self::$pgurl.'('.$pg.')">';
        }
        $end = self::$pgmode=='A' ? '</a>' : '</div>';
        printf('<li%s>%s%u%s</li>',$class, $link, $pg, $end);
    }
}