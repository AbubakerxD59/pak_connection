@extends('admin.layouts.secure')
@section('page_title', 'Chats')
@section('page_content')
    @can('view_user')
        <div class="page-content">
            <div class="content-header clearfix">
                <h1 class="float-left">Chats</h1>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-list">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered" id="dataTable">
                                                        <thead>
                                                            <th>User</th>
                                                            <th>Status</th>
                                                            <th>Agent</th>
                                                            <th>Action</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endcan
    @include('admin.chats.modals.view_messages')
@endsection

@push('styles')
    <style>
        .chat-container {
            /* max-width: 600px; */
            /* margin: 20px auto; */
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* border-radius: 15px; */
            position: relative;
        }

        .message {
            display: flex;
            align-items: flex-end;
            margin-bottom: 20px;
            position: relative;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .message.sent .avatar {
            margin-left: 10px;
        }

        .message.received .avatar {
            margin-right: 10px;
        }

        .message-content {
            padding: 12px 18px;
            border-radius: 20px;
            max-width: 80%;
            word-wrap: break-word;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
        }

        .message.sent .message-content {
            background-color: #fff;
            border: 1px solid #f0f0f0;
            border-top-right-radius: 5px;
        }

        .message.received .message-content {
            background-color: #e0ffe0;
            border-top-left-radius: 5px;
        }

        .timestamp {
            font-size: 0.75rem;
            color: #888;
            margin: 0 8px;
            white-space: nowrap;
            align-self: center;
        }

        .checkmark {
            color: #a8a8a8;
            font-size: 0.8rem;
        }

        .message.sent .timestamp {
            margin-left: 0;
            order: -1;
        }

        .message.received .timestamp {
            order: 1;
        }

        .message.sent .checkmark {
            order: -2;
            margin-right: 5px;
        }

        .message-box {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        /* Custom caret for sent message box */
        .message.sent .message-content::before {
            content: '';
            position: absolute;
            top: 0;
            right: -10px;
            width: 10px;
            height: 10px;
            background: transparent;
            box-shadow: 5px 0 0 0 #fff;
            border-top-right-radius: 5px;
            z-index: 0;
        }

        .message.received .message-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: -10px;
            width: 10px;
            height: 10px;
            background: transparent;
            box-shadow: -5px 0 0 0 #e0ffe0;
            border-top-left-radius: 5px;
            z-index: 0;
        }

        .message-box-user {
            justify-content: end;
        }

        .message-input-container {
            display: flex;
            align-items: center;
            margin-top: 20px;
            padding: 10px;
            border-top: 1px solid #ccc;
        }

        .message-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-right: 10px;
        }

        .send-button {
            background-color: #0d6efd;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        chatDatatable = $('#dataTable').DataTable({
            "paging": true,
            'iDisplayLength': 10,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ route('chats.dataTable') }}",
            },
            columns: [{
                    data: 'user_name'
                },
                {
                    data: 'status_view'
                },
                {
                    data: 'agent_name'
                },
                {
                    data: 'action'
                },
            ],
        });
        // view messages
        $(document).on("click", ".view_chat", function() {
            var id = $(this).data("id");
            if (id) {
                viewChat(id);
            }
        });
        var viewChat = function(id) {
            var modal = $("#viewChatModal");
            modal.find(".modal-body").empty();
            modal.modal("show");
            $.ajax({
                url: "{{ route('chats.view.messages') }}",
                type: "GET",
                data: {
                    "id": id
                },
                success: function(response) {
                    if (response.success) {
                        modal.find(".modal-body").append(response.data);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }
        // new message
        $(document).on("click", ".send-button", function() {
            var id = $(this).data("id");
            var message = $("#new_message").val();
            console.log(id);
            console.log(message);
            if (id != '' && message != '') {
                $.ajax({
                    url: "{{ route('chats.new.message') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "message": message,
                    },
                    success: function(response) {
                        if (response.success) {
                            viewChat(id);
                            chatDatatable.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    </script>
@endpush
