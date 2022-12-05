<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DownloadFileController extends Controller
{
    /**
     * Получение файла по ссылке
     * @param $file_name
     * @return Response
     */
    function downloadFile($file_name): Response
    {
        $file = Storage::disk('public')->get($file_name);

        return (new Response($file, 200))
            ->header('Content-Type', 'multipart/form-data');
    }
}
