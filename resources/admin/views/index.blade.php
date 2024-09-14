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
    {!! HTML::style('css/iziToast.css') !!}
    {{-- {!! HTML::style('admin-public/css/multiple-select.css') !!} --}}

    {!! HTML::script('admin-public/js/app.js') !!}

    {!! HTML::script('plugin/toastr.min.js') !!}
    {!! HTML::script('admin-public/js/tinymce/tinymce.min.js') !!}
    {{-- {!! HTML::script('admin-public/js/jQuery.print.min.js') !!} --}}
    {!! HTML::script('admin-public/js/feather.min.js') !!}
    {!! HTML::script('admin-public/js/select2.min.js') !!}
    {!! HTML::script('admin-public/js/jqueryUi.js') !!}
    {!! HTML::script('admin-public/js/icheck.min.js') !!}
    {!! HTML::script('js/iziToast.js') !!}

</head>

<body>
    @yield('index')
    @include('admin::components.iziToast')
    @yield('script')
    {!! HTML::script('admin-public/js/body.js') !!}

</body>

<script>
    // if ('serviceWorker' in navigator) {
    //     window.addEventListener('load', () => {
    //         navigator.serviceWorker.register('/service-worker.js').then((registration) => {
    //             console.log('ServiceWorker registration successful with scope: ', registration.scope);
    //         }, (error) => {
    //             console.log('ServiceWorker registration failed: ', error);
    //         });
    //     });
    // }
    // function clearLocalStorage() {
    //       localStorage.clear();
    //       console.log('Local storage cleared');
    //     }

    //     function clearSessionStorage() {
    //       sessionStorage.clear();
    //       console.log('Session storage cleared');
    //     }

    //     function clearIndexedDB() {
    //       if (indexedDB && indexedDB.databases) {
    //         indexedDB.databases().then((databases) => {
    //           databases.forEach((dbInfo) => {
    //             var request = indexedDB.deleteDatabase(dbInfo.name);
    //             request.onsuccess = function() {
    //               console.log(`IndexedDB database ${dbInfo.name} deleted`);
    //             };
    //             request.onerror = function() {
    //               console.log(`Error deleting IndexedDB database ${dbInfo.name}`);
    //             };
    //           });
    //         });
    //       }
    //     }

    //     function clearCookies() {
    //       var cookies = document.cookie.split(";");
    //       for (var i = 0; i < cookies.length; i++) {
    //         var cookie = cookies[i];
    //         var eqPos = cookie.indexOf("=");
    //         var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    //         document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    //       }
    //       console.log('Cookies cleared');
    //     }

    //     function clearAllClientStorage() {
    //       clearLocalStorage();
    //       clearSessionStorage();
    //       clearIndexedDB();
    //       clearCookies();
    //       if ('serviceWorker' in navigator) {
    //         navigator.serviceWorker.getRegistrations().then((registrations) => {
    //           for (let registration of registrations) {
    //             registration.unregister();
    //           }
    //         });
    //       }
    //       console.log('All client storage cleared');
    //     }
    //     clearAllClientStorage();
</script>

</html>
