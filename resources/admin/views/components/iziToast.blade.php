<script>
    @if(session('success'))
        iziToast.success({
            title: 'Success',
            message: '{{ session('success') }}',
            position: 'bottomCenter',
            timeout: 2500
        });
    @endif

    @if(session('error'))
        iziToast.error({
            title: 'Error',
            message: '{{ session('error') }}',
            position: 'bottomCenter',
            timeout: 2500
        });
    @endif

    @if(session('info'))
        iziToast.info({
            title: 'Info',
            message: '{{ session('info') }}',
            position: 'bottomCenter',
            timeout: 2500
        });
    @endif

    @if(session('warning'))
        iziToast.warning({
            title: 'Warning',
            message: '{{ session('warning') }}',
            position: 'bottomCenter',
            timeout: 2500
        });
    @endif
</script>