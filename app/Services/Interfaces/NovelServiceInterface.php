<?php
    namespace App\Services\Interfaces;
    use Illuminate\Http\Request;

    interface NovelServiceInterface
    {
        function list(string $status);
        function create(array $novel);
        function completeDataSaac(Request $request);
        function update(array $novel, int $id);
        function delete(int $id);
        function get(int $id);
        function getByPhone(string $phone);
    }
?>
