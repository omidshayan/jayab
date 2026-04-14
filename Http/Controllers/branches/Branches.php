<?php

namespace App;

class Branches extends App
{
    // manage branches
    public function manageBranches()
    {
        $this->middleware(true, true, 'general', true);
        $branches = $this->db->select('SELECT * FROM branches ORDER BY id ASC')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/branches/branches.php');
    }

    // branch store
    public function branchStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if ($request['branch_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }
        $request = $this->validateInputs($request);

        $branch_name = $this->db->select('SELECT branch_name FROM branches WHERE `branch_name` = ?', [$request['branch_name']])->fetch();

        if (!empty($branch_name['branch_name'])) {
            $this->flashMessage('error', _repeat);
        } else {

            $this->db->insert('branches', array_keys($request), $request);

            $branch_id = $this->db->lastInsertId();

            $branch_info = [
                'name'      => $request['branch_name'],
                'who_it'    => $request['who_it'],
                'branch_id' => $branch_id
            ];
            $this->flashMessage('success', _success);
        }
    }

    // edit branch page
    public function editBranch($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM branches WHERE `id` = ?', [$id])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/branches/edit-branche.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // editBranchStore
    public function editBranchStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        // check empty form
        if ($request['branch_name'] == '') {
            $this->flashMessage('error', _emptyInputs);
        }

        $item = $this->db->select('SELECT * FROM branches WHERE `branch_name` = ?', [$request['branch_name']])->fetch();

        if ($item) {
            if ($item['id'] != $id) {
                $this->flashMessage('error', 'نام وارد شده تکراری است.');
            }
        }
        $this->db->update('branches', $id, array_keys($request), $request);
        $this->flashMessageTo('success', _success, url('manage-branches'));
    }

    // branch Details detiles page
    public function branchDetails($id)
    {
        $this->middleware(true, true, 'general');
        $item = $this->db->select('SELECT * FROM branches WHERE `id` = ?', [$id])->fetch();
        if ($item != null) {
            require_once(BASE_PATH . '/resources/views/app/branches/branche-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status branches
    public function changeStatusBranch($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM branches WHERE id = ?', [$id])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;
        $this->db->update('branches', $item['id'], ['status'], [$newState]);
        $this->send_json_response(true, _success, $newState);
    }
}
