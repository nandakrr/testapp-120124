<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller; // ON live remove 

use Illuminate\Http\Request;
use App\Studio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Session;
use Illuminate\Support\Facades\DB;
use Mail;
use Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{

    public function frontBlogList()
    {
        $featuredBlogs = \App\Blog::whereRaw('status != 0 AND is_featured = 1')->limit(5)->get()->all();
        $allBlogs = \App\Blog::whereRaw('status != 0 ')->orderByRaw('ISNULL(sort_order), sort_order DESC')->orderBy('id','DESC')->paginate(6);

        return view('blogs.list',compact('featuredBlogs','allBlogs'));
    }

    public function singleBlog(Request $request,$slug)
    {
        $blog = \App\Blog::where('slug', $slug)->firstOrFail();
        $allBlogs = \App\Blog::whereRaw('status != 0 ')->limit(3)->orderByRaw('RAND()')->get()->all();
        return view('blogs.detail',compact('blog','allBlogs'));
    }

}