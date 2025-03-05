<?php
    namespace App\Services\Interfaces;

    interface ShopServiceInterface
    {
        function list();
        function listByStatus(string $status);
    }
?>
