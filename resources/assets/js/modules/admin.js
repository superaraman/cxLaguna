require('../bootstrap');

const ADMIN = 'Admin';
const SUPER_ADMIN = 'Super Admin';

window.onload = function() {
    getListOfAdmins();

    // Add Admin
    $('.btn-add-admin').on('click', function() {
        addAdmin();
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
             <div class="col-5 admin-groupware-id"> ${aAdmins[iCounter]['username']} </div>
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
    getCurrentAdmin();
}

function getCurrentAdmin() {
    axios.get('/getCurrentAdmin')
        .then(function (response) {
            addButtonsFunctionality(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
}

function addButtonsFunctionality(sCurrentAdmin) {
    // Change Admin Role
    $('.btn-role-toggle').on('click', function(event) {
        let targetRoleButton = $(event.target);
        let sGroupwareId = targetRoleButton.parents('.admin-list-item').find('.admin-groupware-id').text().trim();
        let sCurrentRole = targetRoleButton.siblings('label').text().trim();
        let sNewRole = targetRoleButton.text().trim();
        let bSelected = targetRoleButton.hasClass('active');
        if (sGroupwareId === sCurrentAdmin) {
            if (confirm('Are you sure you want to change your role from ' + sCurrentRole + ' to ' + sNewRole + '? \nNote: You will lose access to user management.') === false) {
                return false;
            }
            window.location.reload();
        } else if (bSelected === true || confirm('Are you sure you want to change the role of ' + sGroupwareId + ' from ' + sCurrentRole + ' to ' + sNewRole + '?') === false) {
            return false;
        }
        updateAdminRole(sGroupwareId, sNewRole);
    });

    // Delete Admin
    $('.btn-delete-admin').on('click', function(event) {
        let sGroupwareId = $(event.target).parents('.admin-list-item').find('.admin-groupware-id').text().trim();
        if (sGroupwareId === sCurrentAdmin) {
            if (confirm('Are you sure you want to delete your own account? \nNote: You\'ll be logged out and lose access to the admin page.') === false) {
                return false;
            }
            $('#logout-form').submit();
        } else if (confirm('Are you sure you want to remove ' + sGroupwareId + '?') === false) {
            return;
        }
        deleteAdmin(sGroupwareId);
    });
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
                getListOfAdmins();
            }
            alert(response.data['sMsg']);
        })
        .catch(function (error) {
            console.log(error);
        });
}

// Change admin role
function updateAdminRole(sGroupwareId, sRole) {
    axios.post('/updateAdminRole', {
        groupwareId: sGroupwareId,
        role: sRole
    })
        .then(function (response) {
            alert(response.data['sMsg']);
        })
        .catch(function (error) {
            console.log(error);
        });

}

// Delete admin by Groupware ID
function deleteAdmin(sGroupwareId) {
    axios.post('/deleteAdmin', {
        groupwareId: sGroupwareId
    })
        .then(function (response) {
            if (response.data['bResult'] === true) {
                getListOfAdmins();
            }
            alert(response.data['sMsg']);
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