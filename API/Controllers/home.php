<?php

/**
 * home page
 */
class homeController extends ControllerAPI {

    /**
     * @link http://mrabdelrahman10.com/home/index_m
     * @return array return array of SlideShow News And Featured News <br />
     * Featured News are array of array contain Category And news in this Category
     * @example http://mrabdelrahman10.com/home/index_m
     */
    public function index() {

    }

    /**
     * @link http://mrabdelrahman10.com/home/featuredcats
     * @return array return array of Featuredcats Products <br />
     * @example http://mrabdelrahman10.com/home/featuredcats
     */
    public function featuredcats() {
        $this->CacheFile = 'Featuredcats-' . $this->Language['Code'];
        $Data = array();
        $Cats = $this->Model->GetFeaturedCategories();
        foreach ($Cats as $c) {
            $ps = $this->Model->GetProductsByCategory($c['ID']);
            if ($ps) {
                $Data[] = array(
                    'Category' => $c,
                    'Products' => $ps
                );
            }
        }
        $this->setJsonParser($Data);
    }

    /**
     * @link http://mrabdelrahman10.com/home/featured
     * @return array return array of Featured Products <br />
     * @example http://mrabdelrahman10.com/home/featured
     */
    public function featured() {
        $this->CacheFile = 'Featured.' . $this->Language['Code'];
        $this->setJsonParser($this->Model->GetFeatured(), false, $this->Model->db->RenderFullNav());
    }

    /**
     * @link http://mrabdelrahman10.com/home/featured
     * @return array return array of Featured Products <br />
     * @example http://mrabdelrahman10.com/home/featured
     */
    public function last() {
        $this->CacheFile = 'Last.' . $this->Language['Code'];
        $this->setJsonParser($this->Model->GetLast());
    }

    /**
     * @link http://mrabdelrahman10.com/home/slideshow
     * @return array return array of SlideShow News <br />
     * @example http://mrabdelrahman10.com/home/slideshow
     */
    public function slideshow() {
        $this->CacheFile = 'SlideShow.' . $this->Language['Code'];
        $this->setJsonParser($this->Model->GetSliders());
    }

}
