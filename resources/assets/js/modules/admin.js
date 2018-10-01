require('../bootstrap');

const ADMIN = 'Admin';
const SUPER_ADMIN = 'Super Admin';

window.onload = function() {
    loadAdminManagement();

    // Add Admin button
    $('.btn-add-admin').on('click', function() {
        addAdmin();
    });

    // Logout button
    $('#logout-a').on('click', function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to logout?') === true) {
            logout();
        }
    });
}

function loadAdminManagement() {
    getCurrentAdmin().then(getListOfAdmins());

    // Gets current user
    function getCurrentAdmin() {
        return axios.get('/getCurrentAdmin')
            .then(function (response) {
                addButtonsFunctionality(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    // Add functionality to dynamically added buttons
    function addButtonsFunctionality(sCurrentAdmin) {
        // Change Admin Role
        $('div.container.modal-body-list').on('click', '.btn-role-toggle', function(event) {
            let targetRoleButton = $(event.target);
            let relativeRoleButton = targetRoleButton.siblings('.btn-role-toggle');
            let sCurrentRole = relativeRoleButton.text().trim();
            let sNewRole = targetRoleButton.text().trim();
            let bSelected = targetRoleButton.hasClass('active');
            let sGroupwareId = targetRoleButton.parents('.admin-list-item').find('.admin-groupware-id').text().trim();
            if (bSelected === false) {
                let sConfirmationMsg = 'Are you sure you want to change the role of ' + sGroupwareId + ' from ' + sCurrentRole + ' to ' + sNewRole + '?';
                if (sGroupwareId === sCurrentAdmin) {
                    sConfirmationMsg = 'Are you sure you want to change your role from ' + sCurrentRole + ' to ' + sNewRole + '? \n\nNote: You will lose access to user management.';
                }
                if (confirm(sConfirmationMsg) === true ) {
                    updateAdminRole(sGroupwareId, sNewRole)
                    .then( function() {
                        if (sGroupwareId === sCurrentAdmin) {
                            // window.location.reload()
                        }
                    });
                } else {
                    targetRoleButton.removeClass('active');
                    relativeRoleButton.addClass('active');
                }
            }
        });

        // Delete Admin
        $('div.container.modal-body-list').on('click', '.btn-delete-admin', function(event) {
            let sGroupwareId = $(event.target).parents('.admin-list-item').find('.admin-groupware-id').text().trim();
            let sConfirmationMsg = 'Are you sure you want to remove ' + sGroupwareId + '?';
            if (sGroupwareId === sCurrentAdmin) {
                sConfirmationMsg = 'Are you sure you want to delete your own account? \n\nNote: You\'ll be logged out and lose access to the admin page.';
            }

            if (confirm(sConfirmationMsg) === true) {
                deleteAdmin(sGroupwareId)
                    .then( function() {
                        if (sGroupwareId === sCurrentAdmin) {
                            logout();
                        }
                    });
            }
        });
    }

    // Get list of admins
    function getListOfAdmins() {
        axios.get('/getListOfAdmins')
            .then(function (response) {
                $('.modal-body-list').empty();
                $('.admin-list-header').remove();
                displayListOfAdmins(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    // Display list of admins in user management
    function displayListOfAdmins(aAdmins) {
        let iAdminCount = aAdmins.length;
        let listContainer = $('.modal-body-list');
        let panelContainer = $('.admin-panel-body');
        let sHeader =
            `<div class="admin-list-header container row text-center">
                <div class="col-1"> # </div>
                <div class="col-5"> Groupware ID </div>
                <div class="col-4"> Role </div>
                <div class="col-2"></div>
            </div>`;
        panelContainer.before(sHeader);

        for (let iCounter = 0; iCounter < iAdminCount; iCounter++) {
            let aActive = ((aAdmins[iCounter]['role']).toUpperCase() === SUPER_ADMIN.toUpperCase()) ? [null, 'active'] : ['active', null]
            let sItem =
                `<div class="row admin-list-item text-center col-1">
                     <div class="col-1 admin-number"> ${iCounter + 1} </div>
                     <div class="col-5 admin-groupware-id"> ${aAdmins[iCounter]['name']} </div>
                     <div class="col-4 admin-toggle toggle-div">
                         <div class="btn-group btn-group-toggle" data-toggle="buttons">
                             <label class="btn btn-role-toggle ${aActive[0]}">
                                 <input type="radio" name="options" autocomplete="off"> Admin
                             </label>
                             <label class="btn btn-role-toggle ${aActive[1]}">
                                 <input type="radio" name="options" autocomplete="off"> Super Admin
                             </label>
                         </div>
                     </div>
                     <div class="col-2 admin-delete">
                         <button class="btn btn-danger btn-delete-admin">Delete</button>
                     </div>
                </div>`;
            listContainer.append(sItem);
        }
    }
}

// Add admin by Groupware ID with role
function addAdmin() {
    let inputGroupwareID = $('#admin-groupware-id');
    let inputRole = $('#admin-role').hasClass('active') ? ADMIN : SUPER_ADMIN;

    if (isValidString(inputGroupwareID.val()) === false) {
        return;
    }

    axios.post('/addAdmin', {
        groupwareId: inputGroupwareID.val(),
        role: inputRole
    })
        .then(function (response) {
            if (response.data['bResult'] === true) {
                inputGroupwareID.val('');
                loadAdminManagement();
            }
            displayMessage(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

// Change admin role
function updateAdminRole(sGroupwareId, sRole) {
    return axios.post('/updateAdminRole', {
        groupwareId: sGroupwareId,
        role: sRole
    })
        .then(function (response) {
            displayMessage(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

// Delete admin by Groupware ID
function deleteAdmin(sGroupwareId) {
    return axios.post('/deleteAdmin', {
        groupwareId: sGroupwareId
    })
        .then(function (response) {
            if (response.data['bResult'] === true) {
                loadAdminManagement();
            }
            displayMessage(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

// Checks if string is valid
function isValidString(sString) {
    if (sString === undefined || sString.trim().length === 0) {
        return false;
    }
    return true;
}

// Sweetalert message display
function displayMessage(aMessage) {
    if (aMessage['bResult'] === true) {
        // swal('Success!', aMessage['sMsg'], 'success');
    } else {
        // swal('Error!', aMessage['sMsg'], 'error');
    }
}

// // Sweetalert confirmation dialog
// function showConfirm(sText, mYesFunction, mNoFunction = function(){}) {
//     return swal({
//         text: sText,
//         buttons: {
//             yes: {
//                 text: 'Yes',
//                 value: true,
//                 className: 'green-bg'
//             },
//             no: {
//                 text: 'No',
//                 value: false,
//                 className: 'red-bg'
//             }
//         }
//     }).then(function (sValue) {
//         if (sValue === true) {
//             mYesFunction();
//         } else {
//             mNoFunction();
//         }
//     });
// }

// Admin logout
function logout() {
    axios.post('/admin/logout')
        .then(function () {
            window.location = '/admin/login';
        })
        .catch(function (error) {
            console.log(error);
        });
}