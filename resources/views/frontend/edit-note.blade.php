@include('frontend.include.header')
<section class="cs-app-page">
    <div class="container">
        <div class="cs-app-shell">
            @include('frontend.include.aside')
            <div class="cs-app-main">
                <div class="cs-app-head">
                    <h1>Edit Note</h1>
                    <p>Update your note information.</p>
                </div>

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>Note details</h3>
                        <a href="{{ url('user/notes') }}" class="cs-btn cs-btn--ghost-white">View All</a>
                    </div>

                    <form action="{{ url('user/update-note/'.$note->id) }}" method="POST" class="cs-app-form">
                        @csrf
                        <div class="cs-field">
                            <label for="noteTitle">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ $note->title }}" id="noteTitle" required>
                        </div>
                        <div class="cs-field">
                            <label for="noteDesc">Description</label>
                            <textarea class="form-control" id="noteDesc" name="description" placeholder="Enter note description" required>{{ $note->description }}</textarea>
                        </div>
                        <button type="submit" class="cs-btn cs-btn--primary">Update Note</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.include.footer')
</body>



</html>