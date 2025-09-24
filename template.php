<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Тестовое Задание</title>
   <link rel="stylesheet" href="style.css">
</head>

<body>
   <h1>Список пользователей ATT.by</h1>
   <input type="text" id="filter" placeholder="Фильтрация по имени...">
   <table id="ordersTable">
      <thead>
         <tr>
            <th><a href="<?= getSortLink('orders.id', $sortColumn, $sortOrder) ?>">ID Заказа</a></th>
            <th><a href="<?= getSortLink('title', $sortColumn, $sortOrder) ?>">Заголовок</a></th>
            <th><a href="<?= getSortLink('cost', $sortColumn, $sortOrder) ?>">Цена</a></th>
            <th><a href="<?= getSortLink('users.name', $sortColumn, $sortOrder) ?>">Имя пользователя</a></th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($rows as $row): ?>
            <tr>
               <td>
                  <?= htmlspecialchars($row['order_id']) ?>
               </td>
               <td>
                  <?= htmlspecialchars($row['title']) ?>
               </td>
               <td>
                  <?= htmlspecialchars($row['cost']) ?>
               </td>
               <td class="user-name">
                  <?= htmlspecialchars($row['user_name']) ?>
               </td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>

   <div class="copyright">Тестовое сделано <div class="name">Порфирием Романовским</div>
   </div>

   <script>
      const filterInput = document.getElementById('filter');
      const table = document.getElementById('ordersTable');
      const rows = table.querySelectorAll('tbody tr');

      filterInput.addEventListener('keyup', function () {
         const filterValue = this.value.toLowerCase();
         rows.forEach(row => {
            const userName = row.querySelector('.user-name').textContent.toLowerCase();
            row.style.display = userName.includes(filterValue) ? '' : 'none';
         });
      });
   </script>
</body>

</html>