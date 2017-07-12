<?php
/**
 * Static Pages
 */
class pageController extends Controller {

    public function index() {
        RedirectNotFound();
    }

    public function i($ID) {
        $d = &$this->Data;

        $Page = Cache::Get($this->CacheFile);
        if (!$Page) {
            $this->API->url = GetAPIUrl('page/i/' . $ID);
            $Page = $this->API->Process();
        }
        if ($Page['data']) {
            $d['dResults'] = $Page['data'];
            $d['dTitle'] = $Page['data']['Title'];
        } else {
            RedirectNotFound();
        }
        $this->View->Render();
    }
}