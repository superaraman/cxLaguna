<?php
namespace App\Http\BusinessLogic;

use App\Http\Models\User;
use Illuminate\Http\Request;

/**
 * Class for admin login and user management
 *
 * @author  Mark Angelo Mariano <mark04@simplexi.com.ph>
 * @package App\Http\BusinessLogic
 * @since   2018.08.30
 */
class blAdmin
{
    const MSG_ADMIN_DOESNT_EXIST = 'Admin doesn\'t exist';
    const MSG_GROUPWARE_ID_DOESNT_EXIST = 'Groupware ID doesn\'t exist';
    const MSG_ENTER_VALID_GROUPWARE = 'Enter a valid Groupware ID';
    const MSG_ALREADY_ADMIN = 'User is already an admin';
    const MSG_CANT_ADD_ADMIN = 'Can\'t add as admin';
    const MSG_INVALID_ROLE = 'Invalid admin role';
    const ADMIN_ROLES = array(
        'ADMIN',
        'SUPER ADMIN'
    );

    /**
     * User model instance
     * @var User $oUserModel
     */
    private $oUserModel;

    /**
     * blAdmin constructor.
     * @param User $oUserModel
     */
    public function __construct(User $oUserModel)
    {
        $this->oUserModel = $oUserModel;
    }

    /**
     * Returns list of admins and super admins
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListofAdmins()
    {
        return $this->oUserModel::all();
    }

    /**
     * Changes the admin role
     *
     * @param Request $request
     * @return array
     */
    public function updateAdminRole(Request $request) : array
    {
        if ($this->isValidRole($request['role']) === false) {
            return array('bResult' => false, 'sMsg' => self::MSG_INVALID_ROLE);
        }
        $iResult = $this->oUserModel::where('username', $request['groupwareId'])->update(array('role' => $request['role']));
        if ($iResult === 0) {
            return array('bResult' => false, 'sMsg' => self::MSG_ADMIN_DOESNT_EXIST);
        }
        return array('bResult' => true, 'sMsg' => 'Role of ' . $request['groupwareId'] . ' has been updated');
    }

    /**
     * Deletes admin based on Groupware ID
     *
     * @param Request $request
     * @return array
     */
    public function deleteAdmin(Request $request) : array
    {
        if ($this->checkIfAdminExists($request['groupwareId']) === false) {
            return array('bResult' => false, 'sMsg' => self::MSG_ADMIN_DOESNT_EXIST);
        }
        $iResult = $this->oUserModel::where('username', $request['groupwareId'])->first()->delete();
        if (empty($iResult) === true) {
            return array('bResult' => false, 'sMsg' => self::MSG_ADMIN_DOESNT_EXIST);
        }
        return array('bResult' => true, 'sMsg' => 'Admin ' . $request['groupwareId'] . ' has been removed successfully');
    }

    /**
     * Checks if admin Groupware ID already exists in database
     *
     * @param string $sGroupwareId
     * @return bool
     */
    private function checkIfAdminExists(string $sGroupwareId) : bool
    {
        return $this->oUserModel::where('username', '=', $sGroupwareId)->exists();
    }

    /**
     * Checks if input role is valid
     *
     * @param string $sRole
     * @return bool
     */
    private function isValidRole($sRole) : bool
    {
        $sRole = strtoupper($sRole);
        return in_array($sRole, self::ADMIN_ROLES);
    }

    /**
     * Removes XSS from input strings
     *
     * @param $sString
     * @return string
     */
    private function cleanString(string $sString) : string
    {
        return trim(htmlspecialchars($sString));
    }
}
