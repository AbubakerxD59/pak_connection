@extends('admin.layouts.secure')
@section('page_title', 'Edit Chat')
@section('page_content')
    @can('edit_chats')
        <div class="page-content">
            <form method="POST" action="{{ route('chats.update', $chat->id) }}" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="content-header clearfix">
                    <h1 class="float-left"> Edit Chat
                        <small>
                            <i class="fas fa-arrow-circle-left"></i>
                            <a href="{{ route('chats.index') }}">back to Chats list</a>
                        </small>
                    </h1>
                    <div class="float-right">
                        <button type="submit" name="action" value="save" class="btn btn-primary">
                            <i class="far fa-save"></i>
                            Save
                        </button>
                    </div>
                </div>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <i class="fas fa-info"></i>
                                            Info
                                        </div>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" name="chat_id" id="chat_id" value="{{ $chat->id }}">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="user_name" class="form-label">User</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="user_name"
                                                    value="{{ $chat->user_name }}" readonly disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="created_at" class="form-label">Created At</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" id="created_at"
                                                    value="{{ \Carbon\Carbon::parse($chat->created_at)->format(setting('date_format', 'Y-m-d')) }}" readonly disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                            </div>
                                            <div class="col-md-9">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    @foreach (get_options('chat_status') as $status)
                                                        <option value="{{ $status }}"
                                                            {{ $chat->status == $status ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 text-right">
                                            <button type="submit"
                                                class="btn btn-outline-success">{{ __('users.btn_submit_text') }}</button>
                                            <a href="{{ route('users.index') }}"
                                                class="btn btn-outline-dark">{{ __('users.btn_cancel_text') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            {{-- Messages --}}
                            @can('response_chat')
                                <div class="card">
                                    <div class="card-header with-border clearfix">
                                        <div class="card-title">
                                            <i class="fas fa-star"></i>
                                            Messages
                                        </div>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-list">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" id="messages_dataTable">
                                                            <thead>
                                                                <th>Sender</th>
                                                                <th>Sender Type</th>
                                                                <th>Content</th>
                                                                <th>Created At</th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($messages as $message)
                                                                    <tr>
                                                                        <td>{{ $message->sender_name }}</td>
                                                                        <td>{{ ucfirst($message->sender_type) }}</td>
                                                                        <td>{!! $message->content !!}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($message->created_at)->format(setting('date_time_format', 'Y-m-d h:i:s')) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endcan
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endpush
