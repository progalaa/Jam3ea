<?php

/**
 * Products
 */
class productController extends Controller {

    public function index() {
        $d = &$this->Data;

        $page = intval(GetValue($_GET['page'], 1));
        $this->API->url = GetAPIUrl('product' . "?page=".$page);
        $Data = $this->API->Process();

        $d['dTitle'] = $this->_['_Products'];
        $d['dResults'] = $Data['data'];
        $d['dRenderNav'] = RenderHTMLNavDB(GetValue($Data['pages']));

        $this->View->Render();
    }

    public function search() {
        $d = &$this->Data;

        $page = intval(GetValue($_GET['page'], 1));
        $this->API->url = GetAPIUrl('product/search?q=' . GetValue($_GET['q']) . "&page=".$page);
        $Data = $this->API->Process();

        $d['dTitle'] = $this->_['_Search'];
        $d['dResults'] = $Data['data'];
        $d['dRenderNav'] = RenderHTMLNavDB(GetValue($Data['pages']));

        $this->View->Render('product/index');
    }

    public function c($ID) {
        $d = &$this->Data;

        $this->API->url = GetAPIUrl('product/c/' . $ID);
        $Data = $this->API->Process();
        if ($Data['data']) {
            $d['dTitle'] = $Data['data'][0]['CategoryName'];
            $d['dResults'] = $Data['data'];
            $d['dRenderNav'] = RenderHTMLNavDB(GetValue($Data['pages']));
        } else {
            RedirectNotFound();
        }
        $this->View->Render('product/index');
    }

    public function related($ID) {
        $this->API->url = GetAPIUrl('product/related/' . $ID);
        $Data = $this->API->Process();
        $this->Data['dResults'] = $Data['data'];
        $this->View->RenderOnly('product/related');
    }

    public function i($ID) {
        $d = &$this->Data;

        $Item = Cache::Get($this->CacheFile);
        if (!$Item) {
            $this->API->url = GetAPIUrl('product/i/' . $ID);
            $Item = $this->API->Process();
        }
        if ($Item['data']) {
            $d['dResults'] = $Item['data'];
            $d['dTitle'] = $Item['data']['Name'];
        } else {
            RedirectNotFound();
        }
        $this->View->Render();
    }

}
