<?php

namespace App\Http\Controllers\Admin\Books;

use App\Book;
use App\DeveloperApiKey;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class SpectrumBooksController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth.custom');
    }
    public function index()
    {
        $books = Book::all();

        return view('books.uploaded-books', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create_book');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:books',
            'author' => 'required',
            'publisher' => 'required',
            'description' => 'required',
            'date_published' => 'required|date|before_or_equal:' . date('Y-m-d')
            // 'front_cover' => 'required|mimes:jpg, jpeg, png, gif, bmp',
            // 'book' => 'required|file|mimes:pdf,html,epub,ocr,docx,doc'
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->publisher = $request->publisher;
        $book->date_published = $request->date_published;

        $book->front_cover = $request
            ->file('front_cover')
            ->store('public/books/front-covers');

        $book->path = $request
            ->file('book')
            ->storeAs(
                'public/books',
                $request->title .
                    '.' .
                    $request->book->getClientOriginalExtension()
            );

        if ($book->save()) {


            $auid = Auth::guard('admin')->user()->uuid;
            $title = "Book Upload";
            $action = "Upload of ".$book->title;
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, null, $ip_address);

            return redirect('admin/books/create-books')->with(
                'success',
                'New Book Was Successfully Saved'
            );
        } else {
            return redirect('admin/books/create-books')
                ->with('fail', 'Error Saving Book')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);

        return view('books.view-book', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);

        return view('books.edit-book', ['book' => $book]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'description' => 'required',
            'date_published' =>
                'required|date|before_or_equal:' . date('Y-m-d'),
            // 'front_cover' => 'mimes:jpg, jpeg, png, gif, bmp',
            // 'book' => 'mimes:pdf,html,epub,ocr,docx,doc'
        ]);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->publisher = $request->publisher;
        $book->description = $request->description;
        $book->date_published = $request->date_published;

        if ($request->hasFile('front_cover')) {
            Storage::delete($book->front_cover);

            $book->front_cover = $request
                ->file('front_cover')
                ->store('public/books/front-covers');
        }

        if ($request->hasFile('book')) {
            Storage::delete($book->path);
            $book->path = $request
                ->file('book')
                ->storeAs(
                    'public/books',
                    $request->title .
                        '.' .
                        $request->book->getClientOriginalExtension()
                );
        }

        if ($book->save()) {

            $auid = Auth::guard('admin')->user()->uuid;
            $title = "Book Editing";
            $action = "Editing of ".$book->title;
            $ip_address = $request->ip();

            log_activity($auid, $title, $action, null, $ip_address);

            return redirect()->back()->with(
                'success',
                'Book Successfully Edited'
            );
        } else {
            return redirect()->back()
                ->with('fail', 'Error Edited Book')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();


        $auid = Auth::guard('admin')->user()->uuid;
        $title = "Book Deletion";
        $action = "Deletion of ".$book->title;
        $ip_address = $request->ip();

        log_activity($auid, $title, $action, null, $ip_address);

        return redirect('admin/books/uploaded-books')->with(
            'success',
            'Book Deletion Successful.'
        );
    }

    public function show_trashed(Request $request)
    {
        $books = Book::onlyTrashed()->get();
        // dd($books);
        return view('books.deleted-books', ['books' => $books]);
    }

    public function restore($id)
    {
        $book = Book::withTrashed($id);
        $book->restore();

        return redirect('admin/books/deleted-books')->with(
            'success',
            'Book Successfully Restored.'
        );
    }
}
