<?php

namespace App;

class Map extends App
{
    // profile page
    public function showMap()
    {
        $this->middleware(true, true, 'general', true);
        // $userId = $this->currentUser();
        // $profile = $this->db->select('SELECT * FROM employees WHERE id = ?', [$userId['id']])->fetch();
        require_once(BASE_PATH . '/resources/views/app/map/map.php');
    }

    // mapInfoStore
    public function mapInfoStore($request)
    {
        // $this->middleware(true, true, 'general', true, $request);
        $this->db->insert('places', array_keys($request), $request);
        $this->flashMessage('success', _success);
    }
}
