<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Studio;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Session;
use Illuminate\Support\Facades\DB;
use Mail;
use Validator;
use Illuminate\Support\Str;
use Image;

class BlogController extends Controller
{

    public function index(Request $request)
    {
        $blogs = \App\Blog::all();
        return view('webadmin.blogs.index', compact('blogs'));
    }

    public function create(Request $request)
    {
        return view('webadmin.blogs.add');
    }


    public function store(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:blogs|max:255',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        
        $blog = new \App\Blog();
        $blog->title = $request->title;
        $blog->slug = str_slug($request->title,'-');
        $descriptions = isset($request->description) ? $request->description : null;

        $allDesc = [];
        if($descriptions)
        {
            foreach($descriptions as $ky => $description)
            {
                if($description)
                {
                    $dom = new \DomDocument();
                    $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                    $imageFile = $dom->getElementsByTagName('img');

                    foreach($imageFile as $item => $img){
                        $data = $img->getAttribute('src');
                        list($type, $data) = explode(';', $data);
                        list(, $data)      = explode(',', $data);
                        $imgeData = base64_decode($data);
                        $image_name= "/uploads/" . time().$item.'.png';
                        $path = public_path() . $image_name;
                        file_put_contents($path, $imgeData);
                        
                        $img->removeAttribute('src');
                        $img->setAttribute('src', $image_name);
                    }
                    $description = $dom->saveHTML();
                }
                $allDesc[$ky] =  $description;
            }
        }
        $blog->description = $allDesc;
        if($request->has('feature_image'))
        {
            $feature_image = '';
            $image = $request->file('feature_image');

            $feature_image = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/blogs');

            $filePath = public_path('/uploads/blogs/thumbnails');
            $img = Image::make($image->path());
            $img->resize(360, 240, function ($const) {
                $const->aspectRatio();
            })->save($filePath.'/'.$feature_image);


            $image->move($destinationPath, $feature_image);
            $blog->feature_image = $feature_image;
        }
        $blog->status = $request->status;
        $blog->sort_order = isset($request->sort_order) ? $request->sort_order : null;
        $blog->main_featured = isset($request->main_featured) ? $request->main_featured : false;
        $blog->is_featured = $request->is_featured ? $request->is_featured : false ;

        $blog->save();

        return redirect()->route('edit-blog', ['id' => $blog->id])->with('message', 'Information has been Added Successfully'); 

    }


    public function edit(Request $request,$id)
    {
        $blog = \App\Blog::findOrFail($id);
        //dd($blog);
        // print "<pre>";
        // print_r($blog->description['befor_image']);
        // die;
        return view('webadmin.blogs.edit',compact('blog'));
    }

    public function update(Request $request,$id)
    {     
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $blog = \App\Blog::findOrFail($id);
        $blog->title = $request->title;
        //$blog->slug = str_slug($request->title,'-');
        //$blog->description = $request->description;

        $descriptions = isset($request->description) ? $request->description : null;

        $allDesc = [];
        if($descriptions)
        {
            foreach($descriptions as $ky => $description)
            {
                //$description = trim($description,'<p></p>');
                if($description)
                {
                    $dom = new \DomDocument();
                    @$dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                    @$imageFile = $dom->getElementsByTagName('img');
                    if($imageFile)
                    {
                        foreach($imageFile as $item => $img){
                            $data = $img->getAttribute('src');
                            if ( !str_contains($data, '/uploads/')) {
                                list($type, $data) = explode(';', $data);
                                list(, $data)      = explode(',', $data);
                                $imgeData = base64_decode($data);
                                $image_name= "/uploads/" . time().$item.'.png';
                                $path = public_path() . $image_name;
                                file_put_contents($path, $imgeData);
                                
                                $img->removeAttribute('src');
                                $img->setAttribute('src', $image_name);
                            }
                        }
                        $description = $dom->saveHTML();
                    }
                }
                $allDesc[$ky] =  $description;
            }
        }
        $blog->description = $allDesc;

        if($request->has('feature_image'))
        {
            $feature_image = '';
            $image = $request->file('feature_image');
      
            $feature_image = time().'.'.$image->getClientOriginalExtension();
            
            $filePath = public_path('/uploads/blogs/thumbnails');
            $img = Image::make($image->path());
            $img->resize(460, 350, function ($const) {
                $const->aspectRatio();
            })->save($filePath.'/'.$feature_image);


            $filePath1 = public_path('/uploads/blogs/medium');
            $img = Image::make($image->path());
            $img->resize(760, 440, function ($const) {
                $const->aspectRatio();
            })->save($filePath1.'/'.$feature_image);


            $destinationPath = public_path('/uploads/blogs');
      
            $image->move($destinationPath, $feature_image);
            $blog->feature_image = $feature_image;
        }
        $blog->status = $request->status;
        $blog->sort_order = isset($request->sort_order) ? $request->sort_order : null;
        $blog->main_featured = isset($request->main_featured) && $request->main_featured == true ? true : false;
        $blog->is_featured = $request->is_featured ? $request->is_featured : false ;

        $blog->update();

      return redirect()->back()->with('message', 'Information has been Updated Successfully'); 

    }

}