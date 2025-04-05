@extends('pdfs.layout', ['title' => 'Resume - ' . $user->first_name . ' ' . $user->last_name])

@section('content')
    <div class="header">
        <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="contact-info">
            {{ $user->email }} | {{ $user->phone_number ?? '' }} | {{ $user->location ?? '' }}
            @if($user->linkedin_url)
                | LinkedIn: {{ $user->linkedin_url }}
            @endif
            @if($user->github_url)
                | GitHub: {{ $user->github_url }}
            @endif
            @if($user->personal_website_url)
                | Website: {{ $user->personal_website_url }}
            @endif
        </div>
    </div>
    
    <div class="content">
        {!! $content !!}
    </div>
@endsection
