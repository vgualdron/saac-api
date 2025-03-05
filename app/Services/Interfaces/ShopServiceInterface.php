<?php
    namespace App\Services\Interfaces;

    interface ShopServiceInterface
    {
        function list();
        function listByStatus(string $status);
        function create(array $shop);
        function update(array $shop, int $id);
        function delete(int $id);
    }
?>
