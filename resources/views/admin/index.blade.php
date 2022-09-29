<html>
    <body>
        <script type="text/javascript">
            function btcClick(href) {
                if (window.confirm("Точно?")) {
                    document.getElementById('overlay').style.display = 'block';
                    window.location.href = href;
                }
            }
        </script>

        <h1>Refill:</h1>
        @if($refillActive)
            <p class="error">"index:refill" is already running!</p>
        @else
            <a class="button"
               onclick="btcClick('/api/v1/admin/run-refill');"
{{--               onclick="document.getElementById('overlay').style.display = 'block';"--}}
{{--               href="/api/v1/admin/run-refill"--}}
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
{{--                                                onclick="document.getElementById('overlay').style.display = 'block';"--}}
{{--                                                href="/api/v1/admin/switch?name={{ $index['index'] }}"--}}
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

        <div id="overlay" style="display: none;">
            <div class="loader">Loading...</div>
        </div>

        <style>
            body {padding: 20px 50px; margin: 0; position: relative;}
            .table-wr {width: 100%; overflow-x: scroll; padding-bottom: 20px;}
            table {width: 100%;}
            table, th, td {border: 1px solid #424242;border-collapse: collapse;padding: 5px;}
            table thead tr {background-color: #bebebe;}
            tr {height: 45px;}
            .button {text-decoration: none;background-color: #00a046;color: #fff;border: none;border-radius: 4px;box-sizing: border-box;display: inline-block;margin: 0;outline: none;padding-left: 16px;padding-right: 16px;position: relative;text-align: center;transition-duration: .2s;transition-property: color,background-color,border-color;transition-timing-function: ease-in-out;font: 62.5%/1.4 BlinkMacSystemFont, -apple-system, Arial, "Segoe UI", Roboto, Helvetica, sans-serif;font-family: BlinkMacSystemFont,-apple-system,Arial,Segoe UI,Roboto,Helvetica,sans-serif;font-size: 16px;height: 40px;line-height: 40px;}
            .button.red {background-color: red;}
            .button:hover {background-color: #00bc52;}
            .button.red:hover {background-color: indianred;}
            .error {color: red;}
            .circle {width: 20px; height: 20px;border: 1px solid #424242;border-radius: 11px;margin: 0 auto;}
            .center {text-align:center;}
            .uppercase {text-transform: uppercase;}
            .bold {font-weight: bold;}
            #overlay {position: absolute;top: 0;left: 0;width: 100%;height: 100%;background-color: #fff;}
            .loader, .loader:before, .loader:after {border-radius: 50%;width: 2.5em;height: 2.5em;-webkit-animation-fill-mode: both;animation-fill-mode: both;-webkit-animation: load7 1.8s infinite ease-in-out;animation: load7 1.8s infinite ease-in-out;}
            .loader {top: 50%;color: #000;font-size: 10px;margin: -50px auto 0;position: relative;text-indent: -9999em;-webkit-transform: translateZ(0);-ms-transform: translateZ(0);transform: translateZ(0);-webkit-animation-delay: -0.16s;animation-delay: -0.16s;}
            .loader:before, .loader:after {content: '';position: absolute;top: 0;}
            .loader:before {left: -3.5em;-webkit-animation-delay: -0.32s;animation-delay: -0.32s;}
            .loader:after {left: 3.5em;}
            @-webkit-keyframes load7 {0%, 80%, 100% {box-shadow: 0 2.5em 0 -1.3em;} 40% {box-shadow: 0 2.5em 0 0;}}
            @keyframes load7 {0%, 80%, 100% {box-shadow: 0 2.5em 0 -1.3em;} 40% {box-shadow: 0 2.5em 0 0;}}
        </style>

    </body>
</html>

