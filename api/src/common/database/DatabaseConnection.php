<?php
namespace OpenDiscard\api\common\database;

class DatabaseConnection {
    public static function startEloquent($file_path) {
        $config = parse_ini_file($file_path);

        $db = new \Illuminate\Database\Capsule\Manager();
        $db->addConnection($config);
        $db->setAsGlobal();
        $db->bootEloquent();
    }
}