<?php

//Моя базовая логика для странички
$host = 'localhost';
$dbname = 'test_db';
$username = 'root';
$password = '';

try {
   $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   die("Database connection failed: " . $e->getMessage());
}


//Базовые настройки моих таблиц
$createUsersTable = "
CREATE TABLE IF NOT EXISTS users (
   id int AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(255) NOT NULL
)";
$pdo->exec($createUsersTable);

$createOrdersTable = "
CREATE TABLE IF NOT EXISTS orders (
   id INT AUTO_INCREMENT PRIMARY KEY,
   title VARCHAR(255) NOT NULL,
   cost DECIMAL(10, 2) NOT NULL,
   user_id INT,
   FOREIGN KEY (user_id) REFERENCES users(id)
)";
$pdo->exec($createOrdersTable);


//Вставлю Наши тестовые данные с пользователями

$checkUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
if ($checkUsers == 0) {
   $usersData = [
      ['name' => 'Порфирий Романовский'],
      ['name' => 'Алексей Мороз'],
      ['name' => 'Василий Андрейчук'],
      ['name' => 'Давид Коперник'],
      ['name' => 'Борис Конофальский']
   ];
   $stmt = $pdo->prepare("INSERT INTO users (name) VALUES (:name)");
   foreach ($usersData as $user) {
      $stmt->execute($user);
   }
}

$checkOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
if ($checkOrders == 0) {
   $ordersData = [
      ['title' => 'Заказ 1', 'cost' => 100.50, 'user_id' => 1],
      ['title' => 'Заказ 2', 'cost' => 200.00, 'user_id' => 2],
      ['title' => 'Заказ 3', 'cost' => 150.75, 'user_id' => 1],
      ['title' => 'Заказ 4', 'cost' => 300.00, 'user_id' => 3],
      ['title' => 'Заказ 5', 'cost' => 50.25, 'user_id' => 4],
      ['title' => 'Заказ 6', 'cost' => 400.00, 'user_id' => 5],
      ['title' => 'Заказ 7', 'cost' => 75.00, 'user_id' => 2]
   ];
   $stmt = $pdo->prepare("INSERT INTO orders (title, cost, user_id) VALUES (:title, :cost, :user_id)");
   foreach ($ordersData as $order) {
      $stmt->execute($order);
   }
}


// отсортирую 
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'orders.id';
$sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';
$allowedColumns = ['orders.id', 'title', 'cost', 'users.name'];
if (!in_array($sortColumn, $allowedColumns)) {
   $sortColumn = 'orders.id';
}

// создаю запрос к базе
$query = "
SELECT orders.id AS order_id, orders.title, orders.cost, users.name AS user_name
FROM orders
JOIN users ON orders.user_id = users.id
ORDER BY $sortColumn $sortOrder
";
$stmt = $pdo->query($query);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//определяю порядок сортировки тута
function getSortLink($column, $currentSort, $currentOrder)
{
   $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'desc' : 'asc';
   return "?sort=$column&order=$newOrder";
}

// тут комментарии излишни 
include 'template.php';