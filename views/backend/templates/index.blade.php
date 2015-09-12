@extends('backend::_layouts.application')

@section('title'){{ "Whole CMS Şablonlar" }}@endsection

@section('page_title')
    <h1>Şablonlar<small>Tüm Şablonlar</small></h1>
@endsection


@section('page_breadcrumb')
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ route('admin.index') }}">Yönetim Paneli</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('admin.template.index') }}">Şablonlar</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="#">Tüm Şablonlar</a>
        </li>
    </ul>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-green-haze" style="width: 100%;">
                        <i class="icon-layers font-green-haze"></i>
                        <span class="caption-subject bold uppercase"> Şablonlar</span>
                        <a class="btn green pull-right" href="{{ route('admin.template.create') }}">
                            <i class="fa fa-plus"></i> Yeni Ekle
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    @include('backend::_errors.error')
                    <table class="table table-striped table-bordered table-hover" id="sample_2">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Şablon Adı</th>
                            <th>Şablon Açıklaması</th>
                            <th>Yayın</th>
                            <th>İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $template)
                            <tr class="odd gradeX">
                                <td>{{ $template->id }}</td>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->description }}</td>
                                <td>
                                    @if($setting->template!==null)
                                        @if($setting->template_id == $template->id)
                                            Etkin Şablon
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.template.destroy',$template->id) }}" class="btn btn-danger btn-sm" data-method="delete"> <i class="fa fa-trash"></i> Sil</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('include_css')
    @include('backend::_layouts._table_css')
@endsection

@section('include_js')
    @include('backend::_layouts._table_js')
@endsection