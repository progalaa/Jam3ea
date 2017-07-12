<?php

class NotificationController extends ControllerAdmin implements IControllerAdmin {

    protected function Validation() {
        $Data = $this->GetData();
        $Valid = array(
            new ErrorField('Title', 'Title', $Data['Title'], true),
            new ErrorField('Details', 'Details', $Data['Details'], true),
        );

        return $this->DoValidation($Valid);
    }

    protected function AfterAdd() {
        $this->SendNotification();
    }

    protected function SendNotification() {
        $POST = $this->GetData();
        if (@$POST['Title'] && @$POST['Details']) {
            include APP_LIB . 'MobilePush.php';
            $MobPush = new MobilePush();
            $dvs = $this->Model->GetDevices();
//            $Androids = array();
//            $IOSs = array();
            foreach ($dvs as $i) {
                if ($i['DeviceType'] == MobileDevice::Android) {
//                    $Androids[] = $i['DeviceToken'];
                    $MobPush->Android($i['DeviceToken'], 0, $POST['Title'], $POST['Details']);
                } else {
//                    $IOSs[] = $i['DeviceToken'];
                    $MobPush->IOS($i['DeviceToken'], 0, $POST['Title'], $POST['Details']);
                }
            }
        }
    }

}
