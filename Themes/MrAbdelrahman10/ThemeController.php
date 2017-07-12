<?php

/**
 * Description of ThemeController
 *
 * @author abduo
 */
class ThemeController extends Controller implements ITheme {

    public function __construct() {
        parent::__construct();
        $this->GetBanners();
        $this->GetSideCategories();
        $this->GetMainMenu();
//        $this->GetMostCommented();
        $this->GetPages();
    }

    public function GetBanners() {
        //Banner
        $Banners = Cache::Get('BannersHTML');
        if (!$Banners) {
            //GetBannerPositions
            $BannerPositions = $this->GetBannerPositions();
            $allBannerData = Cache::Get('Banners');
            if (!$allBannerData) {
                $this->API->url = GetAPIUrl('general/allbanners');
                $allBannerData = $this->API->Process();
                Cache::Set('Banners', $allBannerData);
            }
            $allBanners = $allBannerData['data'];
            if ($allBanners) {
                foreach ($BannerPositions as $p) {
                    $Bnrs = array();
                    foreach ($allBanners as $b) {
                        if ($b['BannerPosition'] == $p['ID']) {
                            $Bnrs[] = $b;
                        }
                    }
                    $Ads = array();
                    foreach ($Bnrs as $Item) {
                        if ($Item['BannerType'] == BannerType::Code) {
                            $Ads[] = $Item['BannerCode'];
                        } else {
                            $Ads[] = Anchor($Item['Url'], Img(GetImageThumbnail($Item['Picture'], $p['Width'], $p['Height']), 'class="img-responsive"'), 'target="_blank"', false);
                        }
                    }

                    $Banners[$p['BannerPositionAlias']] = $Ads;
                }
            }
            Cache::Set('BannersHTML', $Banners);
        }
        $this->Data['dBanners'] = $Banners;
    }

    /*
      <div class="panel panel-default">
      <div class="panel-heading">
      <h4 class="panel-title">
      <a data-toggle="collapse" data-parent="#accordian" href="#womens">
      <span class="badge pull-right"><i class="fa fa-plus"></i></span>
      Womens
      </a>
      </h4>
      </div>
      <div id="womens" class="panel-collapse collapse">
      <div class="panel-body">
      <ul>
      <li><a href="#">Fendi</a></li>
      </ul>
      </div>
      </div>
      </div>
      <div class="panel panel-default">
      <div class="panel-heading">
      <h4 class="panel-title"><a href="#">Shoes</a></h4>
      </div>
      </div>

     */

    private function BuildHTMLCategories($Data, $ID = null) {
        $Output = '';
        if ($Data) {
            foreach ($Data as $Item) {
                if ($Item['ParentID'] == $ID) {
                    if ($Item['IsParent'] == true /*&& !$Item['ParentID']*/) {
                        $Output .= '
 <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordian" href="#cat' . $Item['ID'] . '">
                                                <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                                ' . $Item['Name'] . '
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="cat' . $Item['ID'] . '" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <ul>
                        ';
                        $Output .= $this->BuildHTMLCategories($Data, $Item['ID']);
                        $Output .= '
                        </ul>
                                        </div>
                                    </div>
                                </div>
                        ';
                    } else {
                        $Output .= '
                        <div class="panel panel-default">
      <div class="panel-heading">
      <h4 class="panel-title">' .
                                Anchor(GetRewriteUrl('product/c/' . $Item['ID']), $Item['Name']) .
                                '</h4>
      </div>
      </div>
                        ';
                    }
                }
            }
        }
        return $Output;
    }

    public function GetSideCategories() {
//Category
        $html = Cache::Get('HTMLCategory.' . $this->Lang['Code']);
        if (!$html) {
            $html = $this->BuildHTMLCategories($this->GetCategories());
            Cache::Set('HTMLCategory.' . $this->Lang['Code'], $html);
        }
        $this->Data['dSideCategories'] = $html;
    }

    public function GetFooterLinks() {
        //Categories
        $fCategories = Cache::Get('Categories-Footer');
        if (!$fCategories) {
            $fCategories = $this->BuildFooterCatgories($this->Model->GetFooterCategories(), $this->Model->GetAllPages());
            Cache::Set('Categories-Footer', $fCategories);
        }
        $this->Data['dFooterCategories'] = $fCategories;
    }

    public function GetMainMenu() {
        //Menu
        $Menus = Cache::Get('MainMenu.HTML.' . $this->Lang['Code']);
        if (!$Menus) {
            $MenuData = $this->BuildHTMLMenu($this->BuildMenu());
            if ($MenuData) {
                $Menus = '<ul class="nav navbar-nav collapse navbar-collapse">';
                $Menus .= $MenuData;
                $Menus .= '</ul>';
                Cache::Set('MainMenu.HTML.' . $this->Lang['Code'], $Menus);
            }
        }
        $this->Data['dMainMenu'] = $Menus;
    }

    public function GetMostViewed() {
//MostViewedPosts
        $MostViewedPosts = Cache::Get('MostViewedPosts');
        if (!$MostViewedPosts) {
            $MostViewedPosts = $this->Model->GetMostViewedArticles();
            Cache::Set('MostViewedPosts', $MostViewedPosts);
        }
        $this->Data['dMostViewed'] = $MostViewedPosts;
    }

    public function GetPages() {
//Page
        $Page = Cache::Get($this->Lang['Code'] . '.page');
        if (!$Page) {
            $this->API->url = GetAPIUrl('page');
            $Page = $this->API->Process();
            if ($Page['data']) {
                Cache::Set($this->Lang['Code'] . '.page', $Page);
            }
        }
        $this->Data['dPages'] = $Page['data'];
    }

    public function BuildFooterCatgories($Categories, $Pages) {
        $Output = '';
        $Cats = array_chunk($Categories, 9);
        $j = 1;
        foreach ($Cats as $Cs) {
            $Output .= '<div class="cat-ftr-cont-sngl lefty cat-brd-5"><ul>';
            foreach ($Cs as $i) {
                $Output .= '<li>' . Anchor(BASE_URL . 'news/c' . $i['ID'], $i['Name']) . '</li>';
            }
            $Output .= '</ul>
                    </div>';
            $j++;
        }
        return $Output;
    }

    private function BuildHTMLMenu($Menus, $ID = null) {
        $Output = '';
        if ($Menus) {
            foreach ($Menus as $Item) {
                if ($Item['Name'] == 'allcats') {
                    $Output .= $this->BuildMenuCategories($this->GetCategories());
                } elseif ($Item['ParentID'] == $ID) {
                    if ($Item['IsParent'] == true && !$Item['ParentID']) {
                        $Output .= '<li class="dropdown">' .
                                Anchor($this->SetMenuLink($Item['Link']), $Item['Name'] . ' <i class="fa fa-angle-down"></i>') . '<ul role="menu" class="sub-menu">';
                        $Output .= $this->BuildHTMLMenu($Menus, $Item['ID']);
                        $Output .= '</ul></li>';
                    } else {
                        $Output .= '<li>' . Anchor($this->SetMenuLink($Item['Link']), $Item['Name']) . '</li>';
                    }
                }
            }
        }
        return $Output;
    }

    private function BuildMenuCategories($Categories, $ID = null) {
        $Output = '';
        foreach ($Categories as $Item) {
            if ($Item['ParentID'] == $ID && $Item['SortingOrder']) {
                if ($Item['IsParent'] == true) {
                    $Output .= '<li class="dropdown">' .
                            Anchor('javascript:void(0);', $Item['Name']);
                    $Output .= '<ul role="menu" class="sub-menu">';
                    $Output .= $this->BuildMenuCategories($Categories, $Item['ID']);
                    $Output .= '</ul></li>';
                } else {
                    $Output .= '<li>' . Anchor(BASE_URL . 'product/c/' . $Item['ID'], $Item['Name']) . '</li>';
                }
            }
        }
        return $Output;
    }

}

function renderQuantity($i) {
    echo '
        <div class="minicart-input">
            <input type="text" lang="en" data-id="' . $i['ID'] . '" class="form-control input-xs quantity txtQ" value="1" id="miniq-' . $i['ID'] . '" min="1" />
        </div>
        <div class="minicart-controls">
            <a href="javascript:void(0)" class="minipQ" data-id="' . $i['ID'] . '">
                <i class="fa fa-plus-circle fa-lg"></i>
            </a>
            <a href="javascript:void(0)" class="minimQ" data-id="' . $i['ID'] . '">
                <i class="fa fa-minus-circle fa-lg"></i>
            </a>
        </div>
        ';
}

function get_ajax_data_into_div($url, $div, $dot = '#') {
    echo "
        $.ajax({
                url: '{$url}',
                type: 'get',
                beforeSend: function () {
                }, success: function (json) {
                    $('{$dot}{$div}').html(json['IsResult']);
                }, complete: function () {
                }, error: function (xhr, ajaxOptions, thrownError) {
                }
            });
        ";
}
