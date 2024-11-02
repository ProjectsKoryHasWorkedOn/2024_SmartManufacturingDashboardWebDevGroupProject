<?php
function getCurrentOrderingOfColumn($column) {
    $current_order = isset($_GET['order_by']) && $_GET['order_by'] === $column ? $_GET['sort'] : 'ASC';
    return $current_order === 'ASC' ? 'DESC' : 'ASC';
}