<?php

/**
 * Description of Restful
 *
 * @author mrabdelrahman10
 */
class Restful {

    public $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Content-type: multipart/form-data'
    );
    public $url = '';
    public $data = null;
    public $method = 0;
    public $code = null;

    public function Process() {
        $CURL = curl_init();

        curl_setopt($CURL, CURLOPT_URL, $this->url);
        curl_setopt($CURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($CURL, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($CURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($CURL, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        if ($this->method == ActionMethod::POST) {
            curl_setopt($CURL, CURLOPT_POST, true);
            $Data = array(
                'post_data' => serialize($this->data)
            );
            curl_setopt($CURL, CURLOPT_POSTFIELDS, $Data);
        }

        $output = curl_exec($CURL);
        $this->code = curl_getinfo($CURL, CURLINFO_HTTP_CODE);
        curl_close($CURL);

        return json_decode($output, true);
    }

    public function SendAuth($uData = null) {
        Session::Init();
        $u = $uData? : Session::Get(_UD);
        $this->data['AuthUserName'] = $u['AuthUserName'];
        $this->data['AuthPassword'] = $u['AuthPassword'];
    }

}
