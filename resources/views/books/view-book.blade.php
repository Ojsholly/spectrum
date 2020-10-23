@extends('layouts.layout')
@section('content')
<main class="content-wrapper">
    <div class="mdc-layout-grid">
        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-card">
                    <div class="template-demo typography-demo">
                        <div class="mdc-layout-grid__inner align-items-center">
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1 text-muted tx-14">
                                Title
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-5">
                                <h1 class="mdc-typography--headline1">{{ $book->title }}</h1>
                            </div>
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1 text-muted tx-14">
                                Author
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-5">
                                <h1 class="mdc-typography--headline3">{{ $book->author }}</h1>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">

                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                                <a href="{{ $book->get_download_link() }}"><img src="{{ $book->get_front_cover() }}"
                                        class="img img-responsive" style="width:60%"></a>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">

                            </div>
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1 text-muted tx-14">
                                Description
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
                                <h1 class="mdc-typography--headline3">{{ $book->description }}</h1>
                            </div>
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2 text-muted tx-14">
                                Publisher
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                                <h1 class="mdc-typography--headline3">{{ $book->publisher }}</h1>
                            </div>
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1 text-muted tx-14">
                                Date Published
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                                <h1 class="mdc-typography--headline3">
                                    {{ date('l jS F Y', strtotime($book->date_published)) }}</h1>
                            </div>
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1 text-muted tx-14">
                                Date Created
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                                <h1 class="mdc-typography--headline3">
                                    {{ date('l jS F Y \a\t g:ia', strtotime($book->created_at)) }}</h1>
                            </div>
                            <div
                                class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1 text-muted tx-14">
                                Last Update
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3">
                                <h1 class="mdc-typography--headline3">
                                    {{ date('l jS F Y \a\t g:ia', strtotime($book->updated_at)) }}</h1>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">

                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <a href="{{ url('admin/books/edit-book/'.$book->id) }}" class="mdc-button mdc-button--raised filled-button--success">
                                        Edit Book
                                    </a>
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                </div>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2">
                                    <button type="button" book_id="{{ $book->id }}" class="mdc-button mdc-button--raised filled-button--secondary delete_btn">
                                        Delete Book
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ripple Enabled Buttons Ends -->
        </div>
    </div>
</main>
@endsection