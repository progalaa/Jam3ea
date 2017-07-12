<?php

/**
 * Description of JsonParser
 *
 * @author MrAbdelrahman10
 */
class JsonParser {

    public $json = null;
    public $data = null;
    public $success = true;
    public $error = null;
    public $pages = null;
    public $cache = null;

    public function Response() {
        header('Content-type: application/json; charset=utf-8');
        $this->json = array(
            'success' => $this->success,
            'error' => $this->error,
            'data' => $this->data
        );
        if ($this->pages) {
            $this->json['pages'] = $this->pages;
        }
        if ($this->cache && $this->data) {
            Cache::Set($this->cache, $this->json);
        }
        echo json_encode($this->json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    public function Request() {
        $json = file_get_contents('php://input');
        return json_decode($json);
    }

}
