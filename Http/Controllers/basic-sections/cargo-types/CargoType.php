<?php

namespace App;

class CargoType extends App
{
    // CargoType page
    public function cargoTypes()
    {
        $this->middleware(true, true, 'general', true);

        $cargo_types = $this->db->select('SELECT * FROM cargo_types')->fetchAll();

        require_once(BASE_PATH . '/resources/views/app/basic-sections/cargo-types/cargo-types.php');
    }

    // store cargo_types
    public function cargoTypeStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['type_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $item = $this->db->select('SELECT type_name FROM cargo_types WHERE `type_name` = ?', [$request['type_name']])->fetch();

        if (!empty($item['type_name'])) {
            $this->flashMessage('error', _repeat);
        } else {
            $this->db->insert('cargo_types', array_keys($request), $request);
            $this->flashMessage('success', _success);
        }
    }

    // edit cargo_types page
    public function editcargoType($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM cargo_types WHERE `id` = ?', [$id])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/basic-sections/cargo-types/edit-cargo-type.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit company Store
    public function editcargoTypeStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['type_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $item = $this->db->select('SELECT * FROM cargo_types WHERE `type_name` = ?', [$request['type_name']])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessage('error', 'نام کمپانی وارد شده تکراری است.');
            }
        }
        $this->db->update('cargo_types', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('cargo-types'));
    }

    // locations detiles page
    public function cargoTypeDetails($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM cargo_types WHERE `id` = ?', [$id])->fetch();

        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/basic-sections/cargo-types/cargo-type-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status locations
    public function changeStatuscargoType($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM cargo_types WHERE id = ?', [$id])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('cargo_types', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
