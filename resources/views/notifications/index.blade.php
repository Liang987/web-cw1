@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Notifications</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.markAll') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-primary">Mark all as read</button>
            </form>
        @endif
    </div>

    <div class="list-group">
        @forelse($notifications as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}" 
               class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-light border-primary' }}">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                        <span class="fw-bold">{{ $notification->data['user_name'] }}</span> 
                        {{ $notification->data['message'] }}
                    </h5>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1 text-muted">
                    Post: "{{ \Illuminate\Support\Str::limit($notification->data['post_title'], 60) }}"
                </p>
                @if(!$notification->read_at)
                    <span class="badge bg-primary rounded-pill">New</span>
                @endif
            </a>
        @empty
            <div class="alert alert-info">You have no notifications.</div>
        @endforelse
    </div>
</div>
@endsection