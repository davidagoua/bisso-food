@extends('layouts.app',['activePage' => 'feedback'])

@section('title','Feedback And Support')

@section('content')
<section class="section">
    @if (Session::has('msg'))
        @include('layouts.msg')
    @endif
    <div class="section-header">
        <h1>{{__('Feedback & support')}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
            <div class="breadcrumb-item">{{__('Feedback & support')}}</div>
        </div>
    </div>

    <div class="section-body">
        <h2 class="section-title">{{__('Feedback & support management system')}}</h2>
        <p class="section-lead">{{__('Show users feedback.')}}</p>
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('User name')}}</th>
                            <th>{{__('Image')}}</th>
                            <th>{{__('Rate')}}</th>
                            <th>{{__('Comment')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $feedback)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $feedback->user }}</td>
                            <td>
                                @if ($feedback->image != null)
                                @php
                                    $feedback_images = json_decode($feedback->image)
                                @endphp
                                    @foreach ($feedback_images as $feedback_image)
                                        <img alt="image" src="{{ url('images/upload/'.$feedback_image) }}" class="rounded-circle" width="35" height="35">
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @for ($i = 1; $i < 6; $i++)
                                    @if ($feedback->rate >= $i)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </td>
                            <td>{{ $feedback->comment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
