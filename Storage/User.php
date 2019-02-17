<?php

namespace Api\Storage;

use Api\Config\ResponseInterface;

class User
{
    protected $db;
    protected $userGroup;
    protected $userRegion;
    protected $userCountry;
    protected $groups;
    protected $username;
    protected $table;
    protected $userId;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = array(
            'user_table' => 'sf_guard_user',
            'user_group' => 'sf_guard_user_group',
            'country' => 'country');
        $this->groups = array(
            'certified_facilitator' => '2',
        );
    }

    public function checkUserCredentials($username, $password = null)
    {
        $this->username = $username;
        $stmt = $this->db->prepare(sprintf("SELECT * FROM %s WHERE username = '" . $this->username . "'", $this->table['user_table']));
        $stmt->execute(compact('username'));
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $this->userId = $result['id'];
        $this->userCountry = $result['country_id'];
        if (password_verify($password, $result['password'])) {
            if ($this->getUserGroup()) {
                $this->userRegion = $this->getUserRegion();
                $userInfo = array("userId" => $this->userId, "userRegion" => $this->userRegion, "userGroup" => $this->userGroup, "countryID" => $this->userCountry,
                    "UserName" => $this->username, "FirstName" => $this->$result['first_name'], "LastName" => $result['last_name']);
                return $userInfo;
            }
        } else {
            return false;
        }
    }

    protected function getUserGroup()
    {
        $stmt = $this->db->prepare(sprintf("SELECT * FROM %s WHERE user_id = '" . $this->userId . "'", $this->table['user_group']));
        $stmt->execute(compact('user_id'));
        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (in_array($result['group_id'], $this->groups)) {
                $this->userGroup = $result['group_id'];
                return true;
            }
        }
        return false;
    }

    protected function getUserRegion()
    {
        $stmt = $this->db->prepare(sprintf("SELECT region_id FROM %s WHERE id = '" . $this->userCountry . "'", $this->table['country']));
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['region_id'];

    }
}

