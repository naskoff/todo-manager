/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';

// start the Stimulus application
import './bootstrap';

const Swal = require('sweetalert2');

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
