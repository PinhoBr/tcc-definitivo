<?php

    $hostname = "localhost:3306";
    $dbname = "linstravelbao";
    $username = "root";


    try {
        $pdo = new PDO('mysql:host=' .$hostname.';dbname='.$dbname, $username);
        //  echo "Conexão com o banco de dados: $dbname, foi realizado com sucesso!"; // está em comentario para nao ficar aparecendo toda vez que rodar o site
        
    } catch (PDOexception $e) {
        echo "Error: ".$e->getMessage();

    }

    ?>