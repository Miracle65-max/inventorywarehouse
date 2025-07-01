@extends('layouts.app')
@section('content')
<div style="background:lime;color:black;padding:10px;">LAYOUT TEST WORKS</div>
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-avatar">👤</div>
        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
        </div>
    </div>
</div>
@endsection
