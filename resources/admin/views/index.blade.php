<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    {!! HTML::style('admin-public/css/app.css') !!}
    {!! HTML::style('admin-public/css/materialIcon.css') !!}
    {!! HTML::style('admin-public/css/select2.min.css') !!}
    {!! HTML::style('admin-public/css/Material_Symbols.css') !!}
    {!! HTML::style('plugin/toastr.min.css') !!}
    {!! HTML::style('admin-public/css/multiple-select.css') !!}

    {!! HTML::script('admin-public/js/app.js') !!}
    {!! HTML::script('plugin/toastr.min.js') !!}
    {!! HTML::script('admin-public/js/tinymce/tinymce.min.js') !!}
    {!! HTML::script('admin-public/js/jQuery.print.min.js') !!}
    {!! HTML::script('admin-public/js/feather.min.js') !!}
    {!! HTML::script('admin-public/js/select2.min.js') !!}
    {!! HTML::script('admin-public/js/jqueryUi.js') !!}
    {!! HTML::script('admin-public/js/icheck.min.js') !!}
    {{-- {!! HTML::script('admin-public/js/printJS.min.js') !!} --}}

</head>

<body>
    @yield('index')
    @include('admin::components.toast')
    <script lang="ts">
        $(document).ready(function() {
            @if (Session::has('success'))
                Toast({
                    message: '{!! Session::get('success') !!}',
                    status: 'success',
                    size: 'small',
                });
            @elseif (Session::has('error'))
                Toast({
                    title: '{!! Session::get('success') !!}',
                    status: 'danger',
                    duration: 5000,
                });
            @elseif (Session::has('warning'))
                Toast({
                    title: '{!! Session::get('success') !!}',
                    status: 'warning',
                    duration: 5000,
                });
            @endif
        });
    </script>
    @yield('script')

    {!! HTML::script('admin-public/js/body.js') !!}
</body>

</html>
