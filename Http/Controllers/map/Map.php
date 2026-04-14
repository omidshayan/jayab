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
        $street = $this->db->select(
            "SELECT id FROM streets WHERE name = ?",
            [$request['name']]
        )->fetch();

        if ($street) {
            $street_id = $street['id'];
        } else {
            $this->db->insert('streets', ['name'], [
                'name' => $request['name']
            ]);
            $street_id = $this->db->lastInsertId();
        }

        $request['street_id'] = $street_id;

        $this->db->insert('places', array_keys($request), $request);
        $this->flashMessage('success', 'ok');
    }
    // getPlaces
    public function getPlaces()
    {
        $places = $this->db->select('SELECT * FROM places')->fetchAll();

        $this->send_json_response(true, '', $places);
    }
}
