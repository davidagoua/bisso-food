@extends('layouts.app',['activePage' => 'faq'])

@section('title','FAQ')

@section('content')
    <section class="section">
        @if (Session::has('msg'))
            @include('layouts.msg')
        @endif
        <div class="section-header">
            <h1>{{__('FAQ')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('admin/home') }}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('FAQ')}}</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">{{__('FAQ')}}</h2>
            <p class="section-lead">{{__('FAQs Management')}}</p>
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <a href="{{ url('admin/faq/create') }}" class="btn btn-primary float-right">{{__('Add New')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    <input name="select_all" value="1" id="master" type="checkbox" />
                                    <label for="master"></label>
                                </th>
                                <th>#</th>
                                <th>{{__('Question')}}</th>
                                <th>{{__('Answer')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($faqs as $faq)
                        <tr>
                            <td>
                                <input name="id[]" value="{{$faq->id}}" id="{{$faq->id}}" data-id="{{ $faq->id }}" class="sub_chk" type="checkbox" />
                                <label for="{{$faq->id}}"></label>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$faq->question}}</td>
                            <td>{{$faq->answer}}</td>
                            <td>
                                <a href="{{ url('admin/faq/'.$faq->id.'/edit') }}" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                <a href="javascript:void(0);" class="table-action ml-2 btn btn-primary btn-action" onclick="deleteData('admin/faq',{{ $faq->id }},'Faq')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <input type="button" value="Delete selected" onclick="deleteAll('faq_multi_delete','Faq')" class="btn btn-primary">
                </div>
            </div>
        </div>
    </section>
@endsection
