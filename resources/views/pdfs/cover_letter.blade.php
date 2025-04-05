@extends('pdfs.layout', ['title' => 'Cover Letter - ' . $user->first_name . ' ' . $user->last_name])

@section('content')
    <div class="header">
        <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="contact-info">
            {{ $user->email }} | {{ $user->phone_number ?? '' }} | {{ $user->location ?? '' }}
        </div>
        
        <div style="margin-top: 1.5rem;">
            <div>{{ date('F j, Y') }}</div>
            <div>{{ $jobPost->company_name }}</div>
            <div>RE: {{ $jobPost->job_title }}</div>
        </div>
    </div>
    
    <div class="content">
        {!! $content !!}
        
        <div style="margin-top: 2rem;">
            <p>Sincerely,</p>
            <div style="margin-top: 1.5rem;">
                {{ $user->first_name }} {{ $user->last_name }}
            </div>
        </div>
    </div>
@endsection
