@include('frontend.include.header')
<section class="cs-app-page">
    <div class="container">
        <div class="cs-app-shell">
            @include('frontend.include.aside')
            <div class="cs-app-main">
                <div class="cs-app-head">
                    <h1>Notes</h1>
                    <p>Keep your ideas and reminders organized.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>All Notes</h3>
                        <a href="{{ url('user/add-note') }}" class="cs-btn cs-btn--primary">Add Note</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table cs-app-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notes as $idx => $note)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>{{ $note->created_at }}</td>
                                        <td>{{ $note->title }}</td>
                                        <td>
                                            <a href="{{ url('user/edit-note/'.$note->id) }}" class="cs-btn cs-btn--ghost-white cs-btn--sm">Edit</a>
                                            <a href="{{ url('user/delete-note/'.$note->id) }}" class="cs-btn cs-btn--primary cs-btn--sm">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="cs-app-empty">No notes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.include.footer')
</body>



</html>