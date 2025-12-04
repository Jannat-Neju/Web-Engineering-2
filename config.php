<?php

$db_host = "dpg-d4onnlnpm1nc73ec2f40-a.oregon-postgres.render.com";
$db_port = "5432";
$db_name = "shop_db_og56";
$db_user = "shop_db_og56_user";
$db_pass = "S4Uo4J8czOGMP1ut0qjkY1uWfQzKNCyx";

// PostgreSQL connection (not MySQL)
$conn = pg_connect("
    host=$db_host 
    port=$db_port 
    dbname=$db_name 
    user=$db_user 
    password=$db_pass
");

if (!$conn) {
    die('Connection failed: ' . pg_last_error());
}
