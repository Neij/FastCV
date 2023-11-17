<?php

namespace Models;

class UsersAdmin
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function deleteUserWithAllInfos($userId)
    {
        $sqlDeleteJobs = "DELETE FROM jobs WHERE user_id = ?";
        $paramsDeleteJobs = [$userId];
        $this->db->execute($sqlDeleteJobs, $paramsDeleteJobs);

        $sqlDeleteEducations = "DELETE FROM educations WHERE user_id = ?";
        $paramsDeleteEducations = [$userId];
        $this->db->execute($sqlDeleteEducations, $paramsDeleteEducations);

        $sqlDeletePersonalInfo = "DELETE FROM personal_info WHERE user_id = ?";
        $paramsDeletePersonalInfo = [$userId];
        $this->db->execute($sqlDeletePersonalInfo, $paramsDeletePersonalInfo);

        $sqlDeleteUser = "DELETE FROM users WHERE id = ?";
        $paramsDeleteUser = [$userId];
        return $this->db->execute($sqlDeleteUser, $paramsDeleteUser);
    }

    public function getAllUsersWithDetails()
    {
        $sql = "SELECT u.id AS user_id, u.username, u.email, u.date_creation, u.login_attempts, u.last_failed_login, 
        MAX(j.title) AS job_title, MAX(j.date) AS job_date, MAX(j.description) AS job_description, 
        MAX(e.institution) AS education_institution, MAX(e.degree) AS education_degree, MAX(e.graduation_year) AS education_graduation_year,
        MAX(p.first_name) AS first_name, MAX(p.last_name) AS last_name, MAX(p.address) AS address, MAX(p.description) AS description
        FROM users u
        LEFT JOIN jobs j ON u.id = j.user_id
        LEFT JOIN educations e ON u.id = e.user_id
        LEFT JOIN personal_info p ON u.id = p.user_id
        GROUP BY u.id";

        return $this->db->fetchAll($sql);
    }

}


