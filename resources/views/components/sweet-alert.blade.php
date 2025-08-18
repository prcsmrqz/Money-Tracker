@if (session('success') || session('errors'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    confirmButtonColor: '#10B981',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @elseif (session('errors'))
                @php
                    $firstError = session('errors')->first() ?: 'Please check your form input.';
                @endphp
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json($firstError),
                    timer: 1000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('custom-close-modal'));
                }, 2100);
            @endif
        });
    </script>
@endif
