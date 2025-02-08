<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

<<<<<<< HEAD
=======
//pour envoi de mail
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;
///////////////////////



>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
<<<<<<< HEAD
=======
    public function testMail(){
        Mail::to(["moiseayola4@gmail.com"])
        ->send(new Contact([
            'nom' => 'Durand',
            'subject' => 'test02022025',
            'email' => "saintraphael2@gmail.com",
            'message' =>"test"
            ]));
    }
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
}
