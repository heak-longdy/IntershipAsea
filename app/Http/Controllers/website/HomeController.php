<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobApplicationReceived;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\Job;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    protected $layout = 'website::pages.home.';
    protected $layouts = 'website::pages.';
    public function index(Request $req)
    {
        $data['jobs'] = Job::limit(5)->orderBy('id','desc')->get();
        return view($this->layout . 'index', $data);
    }
    public function job(Request $req)
    {
        $data['data'] = Blog::limit(5)->orderBy('id','desc')->get();
        return view($this->layouts . 'blogs', $data);
    }
    public function jobDetail($id="65541"){
        $data['data'] = Job::find($id);
        $data['blogRelates'] = Job::limit(3)->orderBy('id','desc')->get();
        return view($this->layouts . 'jobDetail', $data);
    }
    public function applyForm(){
        return view($this->layouts . 'applyForm');
    }
    public function apply(){
        $application = [
            "id"=>'001',
            "email"=>'longdyheak9999@gmail.com'
        ];
        Mail::to('ld99.lh@gmail.com')->send(new JobApplicationReceived($application));
        return 'hi';
    }
    public function upload(Request $request)
    {
        $request->validate([
            'imageURL' => 'required|url'
        ]);

        $imageUrl = $request->input('imageURL');

        // Fetch the image content while following redirects and ignoring SSL certificate errors
        $response = Http::withOptions(['verify' => false, 'allow_redirects' => true])->get($imageUrl);

        if ($response->successful() && $this->isImage($response)) {
            $imageContents = $response->body();
            $imageName = basename(parse_url($imageUrl, PHP_URL_PATH));

            $path = public_path('ImageLink');
            
            // Ensure the directory exists
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            file_put_contents($path . '/' . $imageName, $imageContents);

            return back()->with('success', 'Image successfully saved to ' . $path . '/' . $imageName);
        }

        return back()->with('error', 'Failed to download the image.');
    }

    private function isImage($response)
    {
        $contentType = $response->header('Content-Type');
        return strpos($contentType, 'image/') === 0;
    }

    public function contact(){
        return view($this->layouts . 'contact');
    }
}
