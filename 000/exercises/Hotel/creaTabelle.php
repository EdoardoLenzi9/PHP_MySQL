<?php

$user = "root";
$password = "";
$host = "localhost";
$dsn = "mysql:host=$host;port:3306;charset=utf8";

$pdo = new PDO($dsn, $user, $password);

$pdo->exec("CREATE DATABASE IF NOT EXISTS hotel");

$pdo->exec("USE hotel");

$pdo->exec("CREATE TABLE IF NOT EXISTS CAMERA(
    CodCamera INT AUTO_INCREMENT PRIMARY KEY,
    Piano INT NOT NULL,
    Prezzo DECIMAL(10,2) NOT NULL,
    Metratura INT NOT NULL,
    NLetti INT NOT NULL
    )
");

$pdo->exec("CREATE TABLE IF NOT EXISTS CLIENTE(
    CodCliente INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    Indirizzo VARCHAR(100),
    Telefono VARCHAR(20)
    )
");

$pdo->exec("CREATE TABLE IF NOT EXISTS HOTEL(
    CodHotel INT AUTO_INCREMENT PRIMARY KEY,
    RagioneSociale VARCHAR(100) NOT NULL,
    Indirizzo VARCHAR(100),
    Telefono VARCHAR(20),
    CCa INT NOT NULL,
    foreign key (CCa) references CAMERA(CodCamera)
    )
");

$pdo->exec("CREATE TABLE IF NOT EXISTS PRENOTAZIONE(
    ID INT AUTO_INCREMENT PRIMARY KEY,
    DataInizio DATE NOT NULL,
    DataFine DATE NOT NULL,
    CCa INT NOT NULL,
    CCli INT NOT NULL,
    foreign key (CCa) references CAMERA(CodCamera),
    foreign key (CCli) references CLIENTE(CodCliente)
    )
");
?>
