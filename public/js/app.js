/*
|--------------------------------------------------------------------------
| Mobile Sidebar
|--------------------------------------------------------------------------
*/

const mobileMenuButton =
    document.getElementById('mobileMenuButton');

const sidebar =
    document.getElementById('sidebar');


if (mobileMenuButton && sidebar) {

    mobileMenuButton.addEventListener('click', function () {

        sidebar.classList.toggle('mobile-open');

    });

}


/*
|--------------------------------------------------------------------------
| Close Alert
|--------------------------------------------------------------------------
*/

window.closeAlert = function (alertId) {

    const alert = document.getElementById(alertId);

    if (alert) {

        alert.remove();

    }

};


/*
|--------------------------------------------------------------------------
| Delete Confirmation
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const deleteForms =
        document.querySelectorAll('.delete-form');


    deleteForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Are you sure you want to delete this client?'
            );


            if (!confirmed) {

                event.preventDefault();

            }

        });

    });

});


