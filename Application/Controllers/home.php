<?php

/**
 * home page
 */
class homeController extends Controller {

    /**
     *
     */
    public function index() {
        $d = &$this->Data;

        $d['dTitle'] = $this->Settings['sSiteName'];

        $SlideShow = Cache::Get('SlideShow.' . $this->Lang['Code']);
        if (!$SlideShow) {
            $this->API->url = GetAPIUrl('home/slideshow');
            $SlideShow = $this->API->Process();
            Cache::Set('SlideShow.' . $this->Lang['Code'], $SlideShow);
        }
        $d['dSlideShow'] = $SlideShow['data'];

        $Featured = Cache::Get('Featured.' . $this->Lang['Code']);
        if (!$Featured) {
            $this->API->url = GetAPIUrl('home/featured');
            $Featured = $this->API->Process();
            Cache::Set('Featured.' . $this->Lang['Code'], $Featured);
        }
        $d['dFeatured'] = $Featured['data'];

        $Last = Cache::Get('Last.' . $this->Lang['Code']);
        if (!$Last) {
            $this->API->url = GetAPIUrl('home/last');
            $Last = $this->API->Process();
            Cache::Set('Last.' . $this->Lang['Code'], $Last);
        }
        $d['dLast'] = $Last['data'];

        /*
          $Featuredcats = Cache::Get('Featuredcats-' . $this->Lang['Code']);
          if (!$Featuredcats) {
          $this->API->url = GetAPIUrl('home/featuredcats');
          $Featuredcats = $this->API->Process();
          }
          $d['dFeaturedcats'] = $Featuredcats['data'];
         */
        $this->View->Render();
    }

    /**
     *
     */
    public function error() {
        $this->Data['dTitle'] = $this->_['_PageNotFound'];
        $this->View->RenderOnly();
    }

    /**
     *
     */
    public function contactus() {
        $_ = $this->_;
        $this->Data['dTitle'] = $this->_['_Contact'];
        if (Request::IsPost()) {
            $s = $_POST['name'];
            $m = $_POST['message'];
            $e = $_POST['email'];
            $mo = $_POST['mobile'];
            $Subject = $this->Filter($s);
            $Message = $this->Filter($m);
            $Body = "
                <table dir='{$_['_Direction']}'>
                    <tr>
                        <td>{$_['_URName']}</td>
                        <td>{$s}</td>
                    </tr>
                    <tr>
                        <td>{$_['_Mobile']}</td>
                        <td>{$mo}</td>
                    </tr>
                    <tr>
                        <td>{$_['_Email']}</td>
                        <td>{$e}</td>
                    </tr>
                    <tr>
                        <td>{$_['_URMsg']}</td>
                        <td>{$m}</td>
                    </tr>
                </table>
                ";
            $Mail = new Mail();
            $Mail->Subject = $Subject . ' From: ' . $e;
            $Mail->Body = $Body;
            $Mail->From = $e;
            $Mail->FromName = $this->Settings['sSiteName'];
            $Mail->To = $this->Settings['sEmail'];
            $Mail->Send();
            $this->Data['dMsg'] = $this->_['_SendedSuccessfully'];
        }
        $this->View->Render();
    }

}
