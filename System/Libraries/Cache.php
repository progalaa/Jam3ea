<?php

final class Cache {

    public static function Get($Key) {
        $Files = glob(APP_CACHE . 'Cache.' . $Key);
        if ($Files) {
            $Cache = file_get_contents($Files[0]);
            return unserialize($Cache);
        }
        return null;
    }

    public static function Set($Key, $Value) {
        if ($Value) {
            $File = APP_CACHE . 'Cache.' . $Key;
            $Handle = fopen($File, 'w');
            fwrite($Handle, serialize($Value));
            fclose($Handle);
        }
    }

    public static function Delete($Key = null) {
        $Files = array();
        if (is_array($Key)) {
            foreach ($Key as $v) {
                $Files = array_merge($Files, glob(APP_CACHE . 'Cache.*' . preg_replace('/[^A-Z0-9\._-]/i', '', $v) . '*'));
            }
        } elseif ($Key) {
            $Files = glob(APP_CACHE . 'Cache.*' . preg_replace('/[^A-Z0-9\._-]/i', '', $Key) . '*');
        } else {
            $Files = glob(APP_CACHE . 'Cache.*');
        }
        if ($Files) {
            foreach ($Files as $File) {
                if (file_exists($File)) {
                    unlink($File);
                    clearstatcache();
                }
            }
        }
    }

}