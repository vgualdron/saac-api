<?php
    namespace App\Services\Interfaces;

    interface CreditServiceInterface
    {
        function list(string $status);
        function create(array $point);
        function update(array $point, int $id);
        function delete(int $id);
        function get(int $id);
    }
?>
