<?php

namespace App;

class BaseCrud extends App
{
    protected $table;
    protected $viewPath;
    protected $name; // location, cargo-type

    public function index()
    {
        $this->middleware(true, true, 'general', true);

        $items = $this->db->select("SELECT * FROM {$this->table}")->fetchAll();

        require_once(BASE_PATH . "/resources/views/{$this->viewPath}/{$this->name}s.php");
    }

    public function store($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request[$this->name . '_name'])) {
            $this->flashMessage('error', _emptyInputs);
        }

        $exists = $this->db->select(
            "SELECT {$this->name}_name FROM {$this->table} WHERE {$this->name}_name = ?",
            [$request[$this->name . '_name']]
        )->fetch();

        if ($exists) {
            $this->flashMessage('error', _repeat);
        }

        $this->db->insert($this->table, array_keys($request), $request);
        $this->flashMessage('success', _success);
    }

    public function edit($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select("SELECT * FROM {$this->table} WHERE id = ?", [$id])->fetch();

        if ($item) {
            require_once(BASE_PATH . "/resources/views/{$this->viewPath}/edit-{$this->name}.php");
            exit;
        }

        require_once(BASE_PATH . '/404.php');
        exit;
    }

    public function update($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        $this->db->update($this->table, $id, array_keys($request), $request);

        $this->flashMessageTo('success', _success, url($this->name . 's'));
    }

    public function details($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select("SELECT * FROM {$this->table} WHERE id = ?", [$id])->fetch();

        if ($item) {
            require_once(BASE_PATH . "/resources/views/{$this->viewPath}/{$this->name}-details.php");
            exit;
        }

        require_once(BASE_PATH . '/404.php');
        exit;
    }

    public function changeStatus($id)
    {
        $item = $this->db->select("SELECT * FROM {$this->table} WHERE id = ?", [$id])->fetch();

        if (!$item) {
            require BASE_PATH . '/404.php';
            exit;
        }

        $newState = $item['status'] == 1 ? 2 : 1;

        $this->db->update($this->table, $id, ['status'], [$newState]);

        $this->send_json_response(true, _success, $newState);
    }
}
