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
        $streetName = $request['street_name'];

        $street = $this->db->select(
            "SELECT id FROM streets WHERE name = ?",
            [$streetName]
        )->fetch();

        if ($street) {
            $street_id = $street['id'];
        } else {
            $this->db->insert('streets', ['name'], [
                'name' => $streetName
            ]);
            $street_id = $this->db->lastInsertId();
        }

        unset($request['street_name']); // مهم
        $request['street_id'] = $street_id;

        $this->db->insert('places', array_keys($request), $request);

        $this->flashMessage('success', 'ok');
    }

    // getPlaces
    public function getMap()
    {
        $places = $this->db->select("SELECT * FROM places")->fetchAll();

        $this->send_json_response(true, '', $places);
    }

    // getPlaces
    public function getPlaces()
    {
        $places = $this->db->select("
        SELECT p.*, s.name AS street_name
        FROM places p
        LEFT JOIN streets s ON p.street_id = s.id
    ")->fetchAll();

        $this->send_json_response(true, '', $places);
    }
}
