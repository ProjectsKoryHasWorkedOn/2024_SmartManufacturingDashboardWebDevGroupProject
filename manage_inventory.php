<?php
session_start();
require_once("php_resource_paths.php");
require_once($sessionHandlingFilePath);
checkSameSession();
include_once($redirectUsersFilePath);
include_once($DBconnectionFilePath);
require_once($sortColumnFilePath);
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ((isset($_POST['stock_id'])) && (isset($_POST['stock_quantity']))) {
        $stock_id = $_POST['stock_id'];
        $stock_quantity = $_POST['stock_quantity'];
        $stmt = $mysqli->prepare("UPDATE factory_inventory SET stock_quantity = ? WHERE stock_id = ?");
        $stmt->bind_param('ii', $stock_quantity, $stock_id);
        $stmt->execute();
        $stmt->close();  
    }
}
?>
<!-- Manage inventory page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once($headFilePath); ?>
    <title>Manage inventory</title>
</head>
<body>
    <?php
    include_once($headerFilePath);
    include_once($sidebarFilePath);
    include_once($hotkeysFilePath);
    ?>
    <main>
        <h1>Oversee inventory</h1>
        <div class="filters_container">
            <p>Filter what's shown by the name or partial name of the stock:</p>
            <label for="sn">Stock search:</label>
            <input type="text" class="input_field" id="sn" placeholder="No filter applied">
        </div>
        <script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('filter_button').onclick = filterInventoryTable;
});
            function filterInventoryTable() {
                const inventoryName = document.getElementById('sn').value.toLowerCase();
                /* Select elements */
                var tbody = document.querySelector('#inventory_list_table tbody');
                var rows = tbody.getElementsByTagName('tr');
                // Hide all rows
                for (var i = 0; i < rows.length; i++) {
                    rows[i].style.display = 'none';
                }
                /* Show rows so long as conditions are met */
                for (var i = 0; i < rows.length; i++) {
                    /* Get inventory name of each column */
                    var inventoryNameColumn = rows[i].getElementsByTagName('td')[1].textContent.trim().toLowerCase();
                    // Check for correct inventory name
                    if (
                        (inventoryNameColumn.match(inventoryName)) ||
                        (inventoryName == "")
                    ) {
                        rows[i].style.display = 'table-row';
                    }
                }
            }
        </script>
        <button class='button' id="filter_button" onclick="filterInventoryTable()">Filter</button>
        <h3>Filtered inventory</h3>
        <div class="full_width_table_container">
      
        <table class="full_width_table" id="inventory_list_table">
    <thead>
        <tr>
            <th><a class="order_by_link" href="?order_by=stock_id&sort=<?= getCurrentOrderingOfColumn('stock_id') ?>">ID</a></th>
            <th><a class="order_by_link" href="?order_by=stock_name&sort=<?= getCurrentOrderingOfColumn('stock_name') ?>">Name</a></th>
            <th><a class="order_by_link" href="?order_by=stock_quantity&sort=<?= getCurrentOrderingOfColumn('stock_quantity') ?>">Quantity</a></th>
        </tr>
    </thead>
    <tbody>

    <?php
$order_by = $_GET['order_by'] ?? 'stock_name'; 
$sort = $_GET['sort'] ?? 'ASC'; 

$allowed_columns = ['stock_id', 'stock_name', 'stock_quantity'];

if (!in_array($order_by, $allowed_columns)) {
    $order_by = 'stock_name';
}

$sql = "SELECT 
    fi.stock_id,
    fi.stock_name,
    fi.stock_quantity
FROM factory_inventory fi
WHERE fi.stock_branch_id = ?
ORDER BY $order_by $sort;";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $_SESSION['branch_id']);
$stmt->execute();
$results = $stmt->get_result();

                while ($row = $results->fetch_assoc()) {
                    echo "<tr>
        <form method=\"post\" action=\"\">
            <input type=\"hidden\" name=\"stock_id\" value=\"" . htmlspecialchars($row['stock_id']) . "\">
            <td><span class=\"numbers_font\">" . htmlspecialchars($row['stock_id']) . "</span></td>
            <td>" . htmlspecialchars($row['stock_name']) . "</td>
            <td>
                <input class=\"input_field input_class_select_field\" type=\"text\" name=\"stock_quantity\" value=\"" . htmlspecialchars($row['stock_quantity']) . "\">
            </td>
            <td><button class=\"button\" type=\"submit\">Update</button></td>
        </form>
    </tr>";
                }
                $results->free(); 
                $stmt->close();  
                ?>
            </tbody>
        </table>
        </div>
    </main>
    <?php
    include_once($footerFilePath);
    ?>
</body>
</html>