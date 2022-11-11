@extends('layouts.admin')
@section('content')
    <h1>Refill:</h1>
    @if($refillActive)
        <p class="error">"index:refill" is already running!</p>
    @else
        <a class="button"
           onclick="btcClick('/api/v1/admin/run-refill');"
           href="javascript:void(0);"
        >Run index:refill</a>
    @endif

    <hr>

    <h1>Indices:</h1>
    @if(!empty($goodsIndices) && !empty($goodsIndices[0]))
        <div class="table-wr">
            <table>
                <thead>
                <tr class="uppercase">
                    @foreach ($goodsIndices[0] as $key => $value)
                        <th>{{ $key }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach ($goodsIndices as $index)
                    <tr>
                        @foreach ($index as $key => $value)
                            @switch($key)
                                @case('health')
                                    <td class="center">
                                        <div class="circle" style="background-color: {{ $value }}"></div>
                                    </td>
                                    @break

                                @case('db_status')
                                    <td class="center uppercase bold"
                                        style="color: {{ $value === 'active' ? 'green' : 'red' }};">{{ $value }}</td>
                                    @break

                                @case('actions')
                                    @if($index['actions'])
                                        <td class="center uppercase bold" style="color: red;">
                                            Active
                                        </td>
                                    @else
                                        <td class="center" style="white-space: nowrap;">
                                            <a class="button"
                                               onclick="btcClick('/api/v1/admin/switch?name={{ $index['index'] }}');"
                                            >switch</a>
                                            <a class="button red"
                                                onclick="btcClick('/api/v1/admin/delete?name={{ $index['index'] }}');"
{{--                                                onclick="document.getElementById('overlay').style.display = 'block';"--}}
{{--                                                href="/api/v1/admin/delete?name={{ $index['index'] }}"--}}
                                            >delete</a>
                                        </td>
                                    @endif
                                    @break

                                @default
                                    <td>{{ $value }}</td>
                            @endswitch
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="error">There are no indexes!</p>
    @endif
@endsection


