@extends('layouts.default')
@section('content')
    <div class="row">
        <h1 class="titleh1">Create Project</h1>
        <div class="col-sm-12 select-box create-playlist">
            <div class="row edit-playlist-section">
                <div class="col-sm-9 col-md-9">
                    <input type="text" id="title" name="title" placeholder="Project title" class="input" value="">
                </div><!--col-5-->
                <div class="col-sm-3 col-md-3 project-save-btn">
                    <a class="activate-playlist-button" onclick="saveProject()">
                        <span>Save</span>
                    </a>
                </div><!--col-3-->
            </div><!--row | edit-playlist-section-->
        </div><!--col-12-->
    </div><!--row-->
    <div class="col-sm-12 col-md-12">
        <div class="row">
            <div class="table-section project-list-section">
                <div class="table-responsive">
                    <table id="tbl_playlist1" class="table">
                        <thead>
                            <tr>
                                <th style="width: 35px;">ID</th>
                                <th>Playlist</th>
                                <th>Month</th>
                                <th>Week Day</th>
                                <th>Start</th>
                                <th>Message</th>
                                <th>Endless</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!--table-responsive-->
            </div><!--table-section-->
        </div><!--row-->
    </div><!--col-12-->
    <div class="bottom-btns project-list-btns edit-playlist-section">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <a class="activate-playlist-button add-playlist" data-toggle="modal" data-target="#modal_playlist"><span>Add Playlist</span></a>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <a class="stop-playlist-button remove-playlist"><span>Remove Playlist</span></a>
        </div>
    </div>


    <!------------------------  Select Video Clip Dialog -------------------------------------->
    <div class="modal fade" id="modal_playlist" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Select Playlist</h4>
                </div>
                <div class="modal-body">
                    <table id="tbl_playlist2" class="table">
                        <thead>
                        <tr>
                            <th style="width: 35px;">ID</th>
                            <th>Playlist</th>
                            <th>Month</th>
                            <th>Week Day</th>
                            <th>Start</th>
                            <th>Message</th>
                            <th>Endless</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($playlists as $item)
                                @php
                                    if(isset($item->schedule)) {
                                        $months = explode(',', $item->schedule->months);
                                        $weekdays = explode(',', $item->schedule->days);
                                    }
                                @endphp
                                <tr class="tbl-row" data-id="{{ $item->id }}">
                                    <td style="width: 35px;">{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @if(isset($months))
                                            @foreach($months as $month)
                                                @if(is_numeric($month))
                                                    {{ Config::get('constants.months')[$month] }},
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($weekdays))
                                            @foreach($weekdays as $weekday)
                                                @if(is_numeric($weekday))
                                                    {{ Config::get('constants.weekdays')[$weekday] }},
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($item->schedule))
                                            {{ $item->schedule->start_time }}
                                        @endif
                                    </td>
                                    <td>
                                        <span>
                                            @if(isset($item->message))
                                                {{ $item->message->text }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if(isset($item->schedule))
                                            {{ $item->schedule->endless }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('script')
    <script>
        function saveProject() {
            playlists = [];
            $('#tbl_playlist1 .tbl-row').each(function(index, value) {
                playlists.push($(this).data().id);
            });

            $.post('/project/store', {
                '_token' : '{{ csrf_token() }}',
                'title' : $('#title').val(),
                'playlists' : playlists,
            }, function (response) {
                if (response.result == '<?= Config::get('constants.status.success') ?>') {
                    swal("Project", "New project successfully saved", "success");
                } else if (response.result == '<?= Config::get('constants.status.validation') ?>') {
                    swal("Project", "Validation failed", "error");
                } else {
                    swal("Project", "Saving project failed", "error");
                }
            });
        }

        $(function() {
            $('.remove-playlist').click(function() {
                if ($('#tbl_playlist1>tbody>tr').hasClass('active-tr')) {
                    $('#tbl_playlist1 .tbl-row.active-tr').each(function(index, value) {
                        $('#tbl_playlist2>tbody').append($(this).clone());
                        $(this).remove();
                    });
                } else {
                    swal("Please select playlist to remove",{
                        icon:"error",
                    });
                }
            });

            $('#tbl_playlist2 .tbl-row').click(function() {
                $('#tbl_playlist1>tbody').append($(this).clone());
                $(this).remove();

                $('#tbl_playlist1 .tbl-row').click(function() {
                    $('.tbl-row').removeClass('active-tr');
                    $(this).addClass('active-tr');
                });

                $('#modal_playlist').modal('hide');
            });
        });
    </script>
@stop