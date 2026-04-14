<?php

namespace App;

require_once 'Http/Controllers/App.php';

class User extends App
{
    // add User page
    public function addUser()
    {
        $this->middleware(true, true, 'general', true);
        require_once(BASE_PATH . '/resources/views/app/users/add-user.php');
        exit();
    }

    // store user
    public function userStore($request)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['user_name']) || empty($request['phone'])) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }

        $this->changekNumbersToEn($request, ['credit_past', 'remnants_past']);

        $existingEmployee = $this->db->select('SELECT * FROM users WHERE `phone` = ?', [$request['phone']])->fetch();
        if ($existingEmployee) {
            $this->flashMessage('error', _phone_repeat);
            return;
        }
        if (!isset($request['password'])) {
            $request['password'] = $request['phone'];
        }
        $request['password'] = $this->hash($request['password']);

        $this->validateInputs($request, ['user_image' => false]);

        // check image
        $request['image'] = $this->handleImageUpload($request['image'], 'images/users');

        $request['branch_id'] = $this->getBranchId();

        $note = !empty($request['note']) ? $request['note'] : 'باقیداری اولیه هنگام ثبت کاربر';
        unset($request['note']);

        try {
            $this->db->beginTransaction();

            $this->db->insert('users', array_keys($request), $request);
            $newUserId = $this->db->lastInsertId();

            if (!$newUserId) {
                $row = $this->db->select('SELECT id FROM users WHERE phone = ?', [$request['phone']])->fetch();
                $newUserId = $row ? $row->id : null;
            }

            if (!$newUserId) {
                throw new \Exception('Cannot get new user id after insert.');
            }

            $remnantsPast = isset($request['remnants_past']) && $request['remnants_past'] !== ''
                ? (float)$request['remnants_past']
                : 0;

            $creditPast = isset($request['credit_past']) && $request['credit_past'] !== ''
                ? (float)$request['credit_past']
                : 0;


            if ($remnantsPast > 0 && $creditPast > 0) {
                throw new \Exception('کاربر نمی‌تواند همزمان قرضدار و طلبکار باشد');
            }

            $now = time();

            $balance = 0;
            $type    = null;
            $amount  = 0;

            if ($remnantsPast > 0) {
                $balance = -abs($remnantsPast);
                $type    = 7;
                $amount  = $remnantsPast;
            } elseif ($creditPast > 0) {
                $balance = abs($creditPast);
                $type    = 8;
                $amount  = $creditPast;
            }

            $accountBalance = [
                'branch_id' => (int)$request['branch_id'],
                'user_id'   => (int)$newUserId,
                'balance'   => $balance,
            ];

            $this->db->insert('account_balances', array_keys($accountBalance), $accountBalance);
            if ($amount > 0) {
                $data = [
                    'branch_id'   => (int)$request['branch_id'],
                    'user_id'     => (int)$newUserId,
                    'amount'      => $amount,
                    'type'        => $type,
                    'date'        => $now,
                    'description'   => $note,
                    'who_it'      => $request['who_it'] ?? 'system',
                    'currency'    => $request['currency'] ?? 'af',
                ];

                $users_transactions = [
                    'branch_id'   => (int)$request['branch_id'],
                    'user_id'     => (int)$newUserId,
                    'total_amount'      => $amount,
                    'paid_amount'      => $amount,
                    'transaction_type'        => $type,
                    'transaction_date'        => $now,
                    'description'   => $note,
                    'who_it'      => $request['who_it'] ?? 'system',
                ];

                $this->db->insert('cash_transactions', array_keys($data), $data);

                $this->db->insert('users_transactions', array_keys($users_transactions), $users_transactions);
            }

            $this->db->commit();

            $this->flashMessage('success', _success);
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->flashMessage('error', 'خطا در ثبت کاربر: ');
        }
    }

    // show users
    public function showUsers()
    {
        $this->middleware(true, true, 'general');
        $users = $this->db->select('SELECT * FROM users ORDER BY id DESC')->fetchAll();
        require_once(BASE_PATH . '/resources/views/app/users/show-users.php');
        exit();
    }

    // edit user page
    public function editUser($id)
    {
        $this->middleware(true, true, 'general', true);

        $user = $this->db->select('SELECT * FROM users WHERE id = ?', [$id])->fetch();

        if ($user != null) {

            require_once(BASE_PATH . '/resources/views/app/users/edit-user.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // edit user store
    public function editUserStore($request, $id)
    {
        $this->middleware(true, true, 'general', true, $request, true);

        if (empty($request['user_name']) || empty($request['phone'])) {
            $this->flashMessage('error', _emptyInputs);
            return;
        }

        try {
            $this->db->beginTransaction();

            $this->changekNumbersToEn($request, ['credit_past', 'remnants_past']);

            $user = $this->db->select(
                'SELECT id, phone, branch_id FROM users WHERE id = ? FOR UPDATE',
                [$id]
            )->fetch();

            if (!$user) {
                $this->db->rollBack();
                require_once(BASE_PATH . '/404.php');
                exit();
            }

            $existing_user = $this->db->select(
                'SELECT id FROM users WHERE phone = ? FOR UPDATE',
                [$request['phone']]
            )->fetch();

            if ($existing_user && $existing_user['id'] != $id) {
                $this->db->rollBack();
                $this->flashMessage('error', 'این شماره موبایل قبلاً توسط کاربر دیگری ثبت شده است.');
                return;
            }

            if ($request['password'] = trim($request['password'])) {
                $request['password'] = $this->hash($request['password']);
            } else {
                unset($request['password']);
            }

            $note = !empty($request['note']) ? $request['note'] : 'باقیداری اولیه هنگام ویرایش کاربر';
            unset($request['note']);

            $remnantsPast = isset($request['remnants_past']) && $request['remnants_past'] !== ''
                ? (float)$request['remnants_past']
                : 0;

            $creditPast = isset($request['credit_past']) && $request['credit_past'] !== ''
                ? (float)$request['credit_past']
                : 0;

            if ($remnantsPast > 0 && $creditPast > 0) {
                throw new \Exception('کاربر نمی‌تواند همزمان قرضدار و طلبکار باشد');
            }

            $balance = 0;
            $type    = null;
            $amount  = 0;

            if ($remnantsPast > 0) {
                $balance = -abs($remnantsPast);
                $type    = 7;
                $amount  = $remnantsPast;
            } elseif ($creditPast > 0) {
                $balance = abs($creditPast);
                $type    = 8;
                $amount  = $creditPast;
            }


            $this->updateImageUpload($request, 'image', 'users', 'users', $id);

            $this->db->update('users', $id, array_keys($request), $request);

            $accountBalance = $this->db->select(
                'SELECT id FROM account_balances WHERE user_id = ? FOR UPDATE',
                [$id]
            )->fetch();

            if ($accountBalance) {
                $this->db->update(
                    'account_balances',
                    $accountBalance['id'],
                    ['balance'],
                    ['balance' => $balance]
                );
            } else {
                $this->db->insert(
                    'account_balances',
                    ['branch_id', 'user_id', 'balance'],
                    [
                        'branch_id' => (int)$user['branch_id'],
                        'user_id'   => (int)$id,
                        'balance'   => $balance
                    ]
                );
            }

            $cashTransaction = $this->db->select(
                'SELECT id FROM cash_transactions 
             WHERE user_id = ? AND type IN (7,8)
             ORDER BY id ASC
             LIMIT 1
             FOR UPDATE',
                [$id]
            )->fetch();

            $userTransaction = $this->db->select(
                'SELECT id FROM users_transactions 
             WHERE user_id = ? AND transaction_type IN (7,8)
             ORDER BY id ASC
             LIMIT 1
             FOR UPDATE',
                [$id]
            )->fetch();

            if ($amount > 0) {

                $cashData = [
                    'branch_id'   => (int)$user['branch_id'],
                    'user_id'     => (int)$id,
                    'amount'      => $amount,
                    'type'        => $type,
                    'description' => $note,
                    'who_it'      => $request['who_it'] ?? 'system',
                    'currency'    => $request['currency'] ?? 'af',
                ];

                $userTransactionData = [
                    'branch_id'         => (int)$user['branch_id'],
                    'user_id'           => (int)$id,
                    'total_amount'      => $amount,
                    'paid_amount'       => $amount,
                    'transaction_type'  => $type,
                    'description'       => $note,
                    'who_it'            => $request['who_it'] ?? 'system',
                ];

                if ($cashTransaction) {
                    $this->db->update(
                        'cash_transactions',
                        $cashTransaction['id'],
                        array_keys($cashData),
                        $cashData
                    );
                } else {
                    $cashData['date'] = time();

                    $this->db->insert(
                        'cash_transactions',
                        array_keys($cashData),
                        $cashData
                    );
                }

                if ($userTransaction) {
                    $this->db->update(
                        'users_transactions',
                        $userTransaction['id'],
                        array_keys($userTransactionData),
                        $userTransactionData
                    );
                } else {
                    $userTransactionData['transaction_date'] = time();

                    $this->db->insert(
                        'users_transactions',
                        array_keys($userTransactionData),
                        $userTransactionData
                    );
                }
            } else {

                if ($cashTransaction) {
                    $this->db->delete('cash_transactions', $cashTransaction['id']);
                }

                if ($userTransaction) {
                    $this->db->delete('users_transactions', $userTransaction['id']);
                }
            }

            $this->db->commit();

            $this->flashMessageTo(
                'success',
                'اطلاعات کاربر با موفقیت ویرایش شد.',
                url('users')
            );
        } catch (\Exception $e) {
            $this->db->rollBack();

            $this->flashMessage(
                'error',
                'خطایی در هنگام ویرایش رخ داد. لطفاً دوباره تلاش کنید.'
            );
        }
    }

    // user detiles page
    public function userDetails($id)
    {
        $this->middleware(true, true, 'general');

        $branchId = $this->getBranchId();

        $user = $this->db->select('SELECT * FROM users WHERE id = ? AND branch_id = ?', [$id, $branchId])->fetch();

        if ($user != null) {

            $account_balance = $this->db->select('SELECT * FROM account_balances WHERE user_id = ?  AND branch_id = ?', [$id, $branchId])->fetch();

            require_once(BASE_PATH . '/resources/views/app/users/user-details.php');
            exit();
        } else {
            require_once(BASE_PATH . '/404.php');
            exit();
        }
    }

    // change status user
    public function changeStatusUser($id)
    {
        $this->middleware(true, true, 'general');

        $item = $this->db->select('SELECT * FROM users WHERE id = ?', [$id])->fetch();

        if (!$item) {
            require_once BASE_PATH . '/404.php';
            exit;
        }

        $newStatus = $item['status'] == 1 ? 2 : 1;

        $this->db->update('users', $item['id'], ['status'], [$newStatus]);
        $this->send_json_response(true, _success, $newStatus);
    }

    // user search details
    public function searchUserDetails($request)
    {
        $this->middleware(true, true, 'general', true);

        $usre = $this->db->select("SELECT * FROM users WHERE user_name LIKE ?", ['%' . $request['customer_name'] . '%'])->fetchAll();

        $response = [
            'status' => 'success',
            'items' => $usre,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // search item
    public function searchItem($request)
    {
        $this->middleware(true, true, 'general');
        $infos = $this->db->select("SELECT * FROM users WHERE `user_name` LIKE ?", ['%' . $request['customer_name'] . '%'])->fetchAll();

        $response = [
            'status' => 'success',
            'items' => $infos,
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}
