<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function showHomepage()
    {
        $pageTitle = "Chào mừng bạn đến với PHT Chương 7";
        $pageDescription = "Đây là bài thực hành về Blade Layout và truyền dữ liệu trong Laravel.";
        $tasks = [
            "Học cấu trúc thư mục Laravel",
            "Cài đặt môi trường XAMPP/Composer",
            "Xây dựng Route và Controller",
            "Sử dụng Blade Template Engine"
        ];

        // Truyền dữ liệu sang View bằng mảng
        return view('homepage', [
            'page_title' => $pageTitle,
            'page_description' => $pageDescription,
            'tasks' => $tasks
        ]);
    }
}