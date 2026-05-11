<?php
namespace App\Services;

use App\DataTransferObjects\SearchResult;

class DataService
{   
private function compressResult(SearchResult $result): string
{
    return gzcompress(serialize($result), 9); // 9 = maximum compression
}

private function decompressResult(string $compressed): SearchResult
{
    return unserialize(gzuncompress($compressed));
}

}