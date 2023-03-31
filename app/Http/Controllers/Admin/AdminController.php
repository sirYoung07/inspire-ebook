<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Models\Book;
use App\Traits\userTrait;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use userTrait;

    public function createbook(Request $request, Book $book){
        $request->validate([
            'author_name' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:10'],
            'price' => ['required', ],
            'file' => ['required', 'mimes:pdf,docx', 'max:5120']
        ]);

        
        $book->author_name = $request->input('author_name');
        $book->description = $request->input('description');
        $book->price = $request->input('price');
        

        if($request->hasFile('file')){

            $file = $request->file('file');
            $file_name = $book->id . '_'.  time() . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('books', $file_name, 'public');
            $book->book_path = $file_path;
        }

        $this->getauth()->books()->save($book);
        return $this->success(['message' => 'You have successfully upload your book']);

    }

    public function viewbook(){
        return Book::where('bookable_id', auth()->id())->get();
    }

    public function show_single($id){
        $single_book = $this->getauth()->books()->find($id);

        if(!$single_book){
            return $this->failure(['message' => 'this particular book is not created by you'], 'You cannot view this book', 401);
        }
        return $single_book;
    }


    public function update(Request $request){

    }

}
