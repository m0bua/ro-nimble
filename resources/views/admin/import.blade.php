@extends('layouts.admin')
@section('content')
    <h1>Import:</h1>
    @if($mark4DeleteActive)
        <p class="error">"db:mark-for-delete" is already running!</p>
    @endif
    @if($deleteFromDBActive)
        <p class="error">"db:delete-from-db" is already running!</p>
    @endif
    @if(!empty($tables))
        <div style="width: 400px;">
            <div style="padding-bottom: 10px;">
                <input type="text" id="tables_filter" class="fl_left" placeholder="фільтранути..." oninput="filterList();" />
                <select id="tables_filter_select" class="fl_right" onchange="filterList()">
                    <option value="0" selected>All</option>
                    <option value="1">Marked</option>
                    <option value="2">!Marked</option>
                </select>
                <div class="fl_clear"></div>
            </div>
            <form action="" id="tables_form" method="post" enctype="multipart/form-data">
                <div>
                    <select id="tables_select" name="tables_select[]" multiple size="12" onchange="changeTablesSelect();">
                        @foreach($tables as $table)
                            <option value="{{ $table['table_name'] }}" data-count="{{ $table['count'] }}">
                                {{ $table['table_name'] }} ({{ $table['count'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="tables_selected" style="display: none;"></div>
                <div id="tables_actions" style="display: none;">
                    <a class="button fl_left" id="tables_actions_mark"
                       onclick="formSubmit('/api/v1/admin/mark-delete', 'tables_form');"
                       href="javascript:void(0);"
                    >Mark for delete (<span>0</span>)</a>

                    <a class="button red fl_right" id="tables_actions_delete"
                       onclick="formSubmit('/api/v1/admin/delete-from-db', 'tables_form');"
                       href="javascript:void(0);"
                    >Delete from DB (<span>0</span>)</a>
                    <div class="fl_clear"></div>
                </div>
            </form>
        </div>

        <script type="text/javascript">
            const select = document.getElementById('tables_select');
            const actions = document.getElementById('tables_actions');
            const selectedTables = document.getElementById('tables_selected');
            const filter = document.getElementById('tables_filter');
            const filterMarked = document.getElementById('tables_filter_select');

            function changeTablesSelect() {
                let selected = getSelected(select);
                showCounts(selected.length);
                showList(selected);
            }

            function getSelected(select) {
                let selected = [];
                for (let counter = 0; counter < select.options.length; counter++) {
                    if (select.options[counter].selected) {
                        selected.push(select.options[counter].value);
                    }
                }

                return selected;
            }

            function showList(selected) {
                selectedTables.innerHTML = selected.join(', ');
                selectedTables.style.display = selected.length > 0 ? 'block' : 'none';
            }

            function showCounts(count) {
                document.getElementById('tables_actions_mark').children[0].innerHTML = count;
                document.getElementById('tables_actions_delete').children[0].innerHTML = count;
                actions.style.display = count > 0 ? 'block' : 'none';
            }

            function filterList() {
                let selected = parseInt(getSelected(filterMarked)[0]);
                for (let counter = 0; counter < select.options.length; counter++) {
                    select.options[counter].setAttribute("disabled", "disabled");
                    if (
                        (0 < parseInt(select.options[counter].dataset.count) && 1 === selected)
                        || (0 === parseInt(select.options[counter].dataset.count) && 2 === selected)
                        || 0 === selected
                    ) {
                        select.options[counter].removeAttribute("disabled");
                    }

                    if (-1 === select.options[counter].value.indexOf(filter.value)) {
                        select.options[counter].setAttribute("disabled", "disabled");
                    }
                }
            }
        </script>
    @endif
@endsection
<style>
    #tables_filter {float: left; width: 300px; height: 20px;}
    #tables_filter_select {float: right; width: 90px; height: 20px;}
    #tables_select {width: 400px;}
    #tables_select option:disabled {display: none;}
    #tables_selected {padding-top: 10px; width: 400px;}
    #tables_actions {padding-top: 10px; width: 400px;}
</style>

