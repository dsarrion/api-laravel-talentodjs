<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Track;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(){


        $categories = Category::All();
        foreach($categories as $category){
            echo "<h1>".$category->name."</h1>";
            foreach($category->tracks as $track){
                echo "<h2>".$track->title." | ".$track->dj."</h2>";
                echo "<p>".$track->description."</p>";
                echo "<span>"."Categoria: ".$track->category->name."</span>";
                echo "<hr>";
            }
        }

        die();
    }
    
}
