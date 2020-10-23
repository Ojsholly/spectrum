@extends('layouts.layout')
@section('content')
<!--Create Books-->
<main class="content-wrapper">
    <form method="POST" action="{{ url('admin/books/edit-book/'.$book->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <div class="mdc-card">
                @if ($errors->any())
                <flash-error message="{{$errors->first()}}"></flash-error>
                @endif
                <div class="d-flex justify-content-between">

                    <div class="mdc-card">
                        <h6 class="card-title">Edit Book</h6>

                        <div class="template-demo">
                            <div class="mdc-layout-grid__inner">
                                <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-4">
                                    <a href="{{ $book->get_download_link() }}"><img src="{{ $book->get_front_cover() }}"
                                            class="img img-responsive" style="width:60%;"></a>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                                    <div class="mdc-text-field">
                                        <input class="mdc-text-field__input" id="text-field-hero-input" name="title"
                                            value="{{ $book->title }}" type="text" required>
                                        <div class="mdc-line-ripple"></div>
                                        <label for="text-field-hero-input" class="mdc-floating-label">Book Title</label>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                                    <div class="mdc-text-field mdc-text-field--outlined">
                                        <input class="mdc-text-field__input" id="text-field-hero-input" name="author"
                                            type="text" required value="{{ $book->author }}">
                                        <div class="mdc-notched-outline">
                                            <div class="mdc-notched-outline__leading"></div>
                                            <div class="mdc-notched-outline__notch">
                                                <label for="text-field-hero-input" class="mdc-floating-label">Book
                                                    Author</label>
                                            </div>
                                            <div class="mdc-notched-outline__trailing"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                                    <div
                                        class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">

                                        <input class="mdc-text-field__input" id="text-field-hero-input" type="text"
                                            name="publisher" required value="{{ $book->publisher }}">
                                        <div class="mdc-notched-outline">
                                            <div class="mdc-notched-outline__leading"></div>
                                            <div class="mdc-notched-outline__notch">
                                                <label for="text-field-hero-input" class="mdc-floating-label">Book
                                                    Publisher</label>
                                            </div>
                                            <div class="mdc-notched-outline__trailing"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                                    <div
                                        class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">

                                        <input class="mdc-text-field__input" id="text-field-hero-input" type="date"
                                            name="date_published" required max="{{ date('Y-m-g') }}"
                                            value="{{ $book->date_published }}">
                                        <div class="mdc-notched-outline">
                                            <div class="mdc-notched-outline__leading"></div>
                                            <div class="mdc-notched-outline__notch">
                                                <label for="text-field-hero-input" class="mdc-floating-label">Date
                                                    Published</label>
                                            </div>
                                            <div class="mdc-notched-outline__trailing"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid__cell--span-12">
                                    <div class="mdc-card">
                                        <div class="template-demo">
                                            <div class="mdc-layout-grid__inner">
                                                <label>Book Description</label>
                                                <div
                                                    class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12-desktop">
                                                    <textarea name="description" min="10" max="500"
                                                        class="mdc-text-field__input" id="text-field-hero-input"
                                                        required>{{ $book->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                                    <div><label for="file-input" class=" form-control-label">Book Cover</label> &nbsp;
                                        &nbsp;
                                        <input type="file" id="file-input" name="front_cover" class="form-control-file">
                                    </div>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                                    <div><label for="file-input" class=" form-control-label">Upload Book</label> &nbsp;
                                        &nbsp;
                                        <input type="file" id="file-input" name="book" class="form-control-file">
                                    </div>
                                </div>
                            </div>
                            <p class="font-weight-bold text-center">Leave the File Upload Fields empty if you do not wish to make
                                changes to the already existing files.</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="mdc-card">
            {{-- <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12-desktop">
                <file-upload></file-upload>
            </div>

            <image-upload></image-upload> --}}
            <div class="text-center col-md-12">
                <button type="submit" class="mdc-button mdc-button--raised">
                    Submit
                </button>
            </div>
        </div>




    </form>
</main>
@endsection