import './bootstrap';

const Swal = require('sweetalert2');

document.querySelectorAll('.change-status-task').forEach((element) => {
    element.addEventListener('change', function (event) {
        event.preventDefault();
        const id = element.getAttribute('data-id');
        const row = document.querySelector(`#task-row-${id}`);
        element.setAttribute('disabled', 'disable');
        fetch('/todos/' + id + '/status', {
            method: 'put',
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'COMPLETE') {
                    row.classList.add('todo-completed');
                } else {
                    row.classList.remove('todo-completed');
                }
                element.removeAttribute('disabled');
            });
    })
});

document.querySelectorAll('.confirm-delete-task').forEach((element) => {
    element.addEventListener('click', function (event) {
        event.preventDefault();
        const id = event.target.getAttribute('data-id');
        if (!id) {
            return false;
        }
        Swal.fire({
            icon: 'warning',
            reverseButtons: true,
            title: 'Are you sure?',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/todos/${id}`, {
                    method: 'DELETE',
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            const row = document.querySelector(`#task-row-${id}`);
                            row.parentNode.removeChild(row);
                            Swal.fire('Success', 'Task deleted successfully', 'success');
                        } else {
                            Swal.fire('Error', data.error, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Error occurred while deleted task', 'error');
                    })
            }
        });
    });
});
