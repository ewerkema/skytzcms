@extends('templates.admin.modals.modal', ['target' => 'menuModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Menu instellingen</strong></h4>
@overwrite

@section('modal-body')
    <menu-manager></menu-manager>
@overwrite

@section('modal-footer')

@overwrite

@section('javascript')
    <script type="text/javascript">
        $('.sortable').nestedSortable({
            listType: 'ul',
            handle: 'div',
            items: 'li',
            toleranceElement: '> div',
            maxLevels: 2,
            placeholder: 'placeholder',
            forcePlaceholderSize: true
        });

        function saveLayoutMenu() {
            var array = $('.sortable').nestedSortable('toArray');
            $.ajax({
                url: '/cms/pages/order',
                type: 'POST',
                data: {
                    _method: 'PATCH',
                    pages: array
                },
                success: function() {
                    location.reload();
                }
            });
        }
    </script>
@overwrite