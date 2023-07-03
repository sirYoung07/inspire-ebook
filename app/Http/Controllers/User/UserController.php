<?php

namespace App\Http\Controllers\User;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\userTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Psy\CodeCleaner\ReturnTypePass;
use Symfony\Component\Translation\Dumper\DumperInterface;

class UserController extends Controller
{
    use userTrait;

    public function manage_profile(Request $request){
        
        $user = User::find($this->getauth()->id);

        if(!$user){
            return $this->failure();
        }

        $request->validate([
            'first_name' => ['required', 'string','min:3','max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name =  $request->input('last_name');

        $user->save();

        return $this->success([], 'Profile Updated Successfully');

    }



    public function available_books()
    {
        return Book::where('available', true)->get();
    }


    

    public function rent_book(){
        return 'hey'; 
    }


}
