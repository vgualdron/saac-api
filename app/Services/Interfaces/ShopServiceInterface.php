<?php
    namespace App\Services\Interfaces;

    interface ShopServiceInterface
    {
        function list();
        function listBySatus(string $status);
    }
?>
