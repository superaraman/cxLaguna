<?php

namespace App\Http\BusinessLogic;

use App\Http\Models\User;
//use Lib\libCurl;

use Illuminate\Support\Facades\Hash;

/**
 * Class blPosts
 * @package App\Http\BusinessLogic
 */
class blUser
{
    /**
     * Initialize Model instance
     *
     * @param $oModelUser
     * @return void
     */
    public function __construct(User $oModelUser)
    {
        $this->oModelUser = $oModelUser;
    }

    public function createUser($data)
    {
        $sFileNameToStore = $this->handleImageToStore($data);

        $aData = array(
            'username'      => $data['username'],
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'profile_image' => $sFileNameToStore,
        );

        return $this->oModelUser->createUser($aData);
    }

    public function updateUser($data, $sId)
    {
        $sFileNameToStore = $this->handleImageToStore($data);
        $aData = array(
            'username'      => $data['username'],
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password'])
        );

        if (array_key_exists('profile_image', $data) === true) {
            $aData['profile_image'] = $sFileNameToStore;
        }

        $this->oModelUser->updateUser($aData, $sId);

        return array(
            'bResult'   => true,
            'sMsg'      => 'Sucessful Updating Profile',
            'sOutCome'  => 'success'
        );
    }

    /**
     * Handle What Image Path to Store in DB
     *
     * @param array $aPosts
     * @return string $sFileNameToStore;
     */
    private function handleImageToStore($aPosts)
    {
        if (array_key_exists('profile_image', $aPosts) === true) {
            $sFileNameToStore = $this->getFileName($aPosts['profile_image']);
            $aPosts['profile_image']
                ->storeAs('public/profile_images', $sFileNameToStore);

            return $sFileNameToStore;
        }

        return 'customer.png';
    }

    /**
     * Gets the filename to be stored
     *
     * @param object $oImageFile
     * @return int
     */
    private function getFileName($oImageFile)
    {
        $sFileNameWithExt = $oImageFile->getClientOriginalName();
        $sFileName = pathinfo($sFileNameWithExt, PATHINFO_FILENAME);
        $sExtension = $oImageFile->getClientOriginalExtension();
        $sFileNameToStore = $sFileName . '_' . time() . '.' . $sExtension;

        return $sFileNameToStore;
    }

    /**
     * Convert Name to Unique Username (GOOGLE/FB/GROUPWARE LOGIN)
     * @param string $sName
     * @return string
     */
    private function processUsername($sName)
    {
        $iExist = count($this->oModelUser->getUserByUsername($sName));
        if ($iExist !== 0) {
            if (ctype_alpha($sName) === true) {
                $sName = $sName . '0';
            } else {
                $sName = ++$sName;
            }
            $this->processUsername($sName);
        }

        return $sName;
    }

    /**
     * Get User By Id
     *
     * @param $sId
     * @return mixed
     */
    public function getUserById($sId)
    {
        return $this->oModelUser->getUserById($sId);
    }

}
