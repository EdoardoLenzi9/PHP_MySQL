<?php
class Db{
    private $pdo;

    public function __construct(){
        $host = "127.0.0.1";
        $port = "3306";
        $user = "root";
        $password = "root";
        $dsn = "mysql:host=$host;port=$port;charset=utf8";
        $this->pdo = new PDO($dsn, $user, $password);
    }

    public function init(){
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS social");
        $this->pdo->exec("USE social");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS user(
            id INT AUTO_INCREMENT,
            user VARCHAR(50),
            pw VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS post(
            id INT AUTO_INCREMENT,
            topic VARCHAR(255) NOT NULL,
            body VARCHAR(1024) NOT NULL,
            likes INT DEFAULT 0,
            user_id INT NOT NULL,

            FOREIGN KEY (user_id)
                REFERENCES user(id)
                ON DELETE CASCADE,

            PRIMARY KEY(Id)
        )");

        $this->pdo->exec("INSERT IGNORE INTO `user` (`user`, `pw`) 
            VALUES ('user1', 'user1');");
    }

    public function login($user, $pw){
        $this->pdo->exec("USE social");
        $stmt = $this->pdo->prepare("
            SELECT id 
            FROM user 
            WHERE user = :user 
            AND pw = :pw
            LIMIT 1
        ");

        $stmt->execute([
            ':user' => $user,
            ':pw'   => $pw
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result["id"];
        }
        return -1;
    }
}

$db = new Db();
$db->init();
?>