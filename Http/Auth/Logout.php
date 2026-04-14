<?php

namespace Auth;

use Auth\Auth;
use database\Database;

class Logout extends Auth
{
    // logout 
    public function logout()
    {
        $db = DataBase::getInstance();

        if (isset($_SESSION['trs_employee']['id'])) {
            $db->update('employees', $_SESSION['trs_employee']['id'], [
                'expire_remember_token',
                'remember_token'
            ], [0, null]);
        }

        if (isset($_SESSION['trs_admin']['id'])) {
            $db->update('employees', $_SESSION['trs_admin']['id'], [
                'expire_remember_token',
                'remember_token'
            ], [0, null]);
        }

        $sessionsToUnset = [
            'trs_employee',
            'trs_admin',
            'sk_em_name',
            'user_permissions',
            'csrf_token',
            'temporary_old',
            'old'
        ];

        foreach ($sessionsToUnset as $sessionKey) {
            unset($_SESSION[$sessionKey]);
        }

        session_destroy();

        if (isset($_COOKIE['trs_user'])) {
            setcookie("trs_user", '', time() - 3600, '/', '', true, true);
        }

        $this->redirect('login');
        exit();
    }
}
