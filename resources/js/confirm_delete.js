export function confirmDelete(event, type) {
    event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: `Delete this ${type}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }   