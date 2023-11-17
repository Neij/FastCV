<?php

namespace Controllers;

use Models\Users;
use helpers\Request;
use helpers\SessionManager;

class CreateCVController extends FormController
{
    private $usersModel;
    private $userId;
    public $jobs;
    public $educations;
    public $personalInfo;

    public function __construct(Users $usersModel)
    {
        parent::__construct('create-cv', 'views/create-cv.phtml');
        $this->usersModel = $usersModel;
        $this->userId = SessionManager::get('user_id');
        if (!$this->userId) {
            
            header("Location: index.php?route=login");
            exit;
        }
    }

    public function displayCreateCV()
    {
        $jobs = $this->usersModel->getJobsByUserId($this->userId);
        $educations = $this->usersModel->getEducationsByUserId($this->userId);
        $personalInfo = $this->usersModel->getPersonalInfoByUserId($this->userId);

        $this->viewData['jobs'] = $jobs;
        $this->jobs = $jobs;

        $this->viewData['educations'] = $educations;
        $this->educations = $educations;

        $this->viewData['personalInfo'] = $personalInfo;
        $this->personalInfo = $personalInfo;
        $this->displayPage();
    }

    public function createJob()
    {
        $jobTitle = htmlspecialchars(Request::post("jobTitle"));
        $jobDate = htmlspecialchars(Request::post("jobDate"));
        $jobDescription = htmlspecialchars(Request::post("jobDescription"));

        if (!empty($jobTitle) && !empty($jobDate) && !empty($jobDescription)) {
            $result = $this->usersModel->addJob($this->userId, $jobTitle, $jobDate, $jobDescription);

            if ($result) {
                SessionManager::set('success_message', 'Le métier a été ajouté avec succès.');
            } else {
                SessionManager::set('error_message', 'Une erreur s\'est produite lors de l\'ajout du métier.');
            }
        } else {
            SessionManager::set('error_message', 'Veuillez remplir tous les champs pour ajouter un métier.');
        }

        header("Location: index.php?route=create-cv");
        exit;
    }

    public function updateJob()
    {
        
        $jobId = (int) Request::post('jobId');
        $jobTitle = htmlspecialchars(Request::post('editJobTitle'));
        $jobDate = htmlspecialchars(Request::post('editJobDate'));
        $jobDescription = htmlspecialchars(Request::post('editJobDescription'));

        if (!empty($jobTitle) && !empty($jobDescription)) {
            
            $result = $this->usersModel->updateJob($jobId, $jobTitle, $jobDate, $jobDescription, $this->userId);
            if ($result) {
                SessionManager::set('success_message', 'Le métier a été mis à jour avec succès.');
            } else {
                SessionManager::set('error_message', 'Une erreur s\'est produite lors de la mise à jour du métier.');
            }
        } else {
            SessionManager::set('error_message', 'Veuillez remplir tous les champs pour mettre à jour le métier.');
        }

        header("Location: index.php?route=create-cv");
        exit;

    }

    public function deleteJob()
    {
        $jobId = (int) Request::post('id');

        $result = $this->usersModel->deleteJob($jobId, $this->userId);
        if ($result) {
            SessionManager::set('success_message', 'Le métier a été supprimé avec succès.');
        } else {
            SessionManager::set('error_message', 'Une erreur s\'est produite lors de la suppression du métier.');
        }

        header("Location: index.php?route=create-cv");
    }

    public function createEducation()
    {
        $institution = htmlspecialchars(Request::post("institution"));
        $degree = htmlspecialchars(Request::post("degree"));
        $graduationYear = htmlspecialchars(Request::post("graduationYear"));

        if (!empty($institution) && !empty($degree) && !empty($graduationYear)) {
            $result = $this->usersModel->addEducation($this->userId, $institution, $degree, $graduationYear);

            if ($result) {
                SessionManager::set('success_message', 'L\'éducation a été ajoutée avec succès.');
            } else {
                SessionManager::set('error_message', 'Une erreur s\'est produite lors de l\'ajout de l\'éducation.');
            }
        } else {
            SessionManager::set('error_message', 'Veuillez remplir tous les champs pour ajouter une éducation.');
        }

        header("Location: index.php?route=create-cv");
        exit;
    }

    public function updateEducation()
    {

        $educationId = (int) Request::post('educationId');
        $institution = htmlspecialchars(Request::post('editEducationInstitution'));
        $degree = htmlspecialchars(Request::post('editEducationDegree'));
        $graduationYear = htmlspecialchars(Request::post('editEducationGraduationYear'));


        if (!empty($institution) && !empty($degree) && !empty($graduationYear)) {

            $result = $this->usersModel->updateEducation($educationId, $this->userId, $institution, $degree, $graduationYear);
            if ($result) {
                SessionManager::set('success_message', 'L\'éducation a été mise à jour avec succès.');
            } else {
                SessionManager::set('error_message', 'Une erreur s\'est produite lors de la mise à jour de l\'éducation.');
            }
        } else {
            SessionManager::set('error_message', 'Veuillez remplir tous les champs pour mettre à jour l\'éducation.');
        }

        header("Location: index.php?route=create-cv");
        exit;
        
    }
    public function deleteEducation()
    {
        $educationId = (int) Request::post('id');

        $result = $this->usersModel->deleteEducation($educationId, $this->userId);
        if ($result) {
            SessionManager::set('success_message', 'La formation a été supprimée avec succès.');
        } else {
            SessionManager::set('error_message', 'Une erreur s\'est produite lors de la suppression de la formation.');
        }

        header("Location: index.php?route=create-cv");
    }

    public function createPersonalInfo()
    {
        $firstName = htmlspecialchars(Request::post("firstName"));
        $lastName = htmlspecialchars(Request::post("lastName"));
        $address = htmlspecialchars(Request::post("address"));
        $description = htmlspecialchars(Request::post("description"));

        if (!empty($firstName) && !empty($lastName) && !empty($address) && !empty($description)) {
            $result = $this->usersModel->addPersonalInfo($this->userId, $firstName, $lastName, $address, $description);

            if ($result) {
                SessionManager::set('success_message', 'Les informations personnelles ont été ajoutées avec succès.');
            } else {
                SessionManager::set('error_message', 'Une erreur s\'est produite lors de l\'ajout des informations personnelles.');
            }
        } else {
            SessionManager::set('error_message', 'Veuillez remplir tous les champs pour ajouter des informations personnelles.');
        }

        header("Location: index.php?route=create-cv");
        exit;
    }

    public function updatePersonalInfo()
    {
        $personalInfoId = (int) Request::post('personalInfoId');
        $firstName = htmlspecialchars(Request::post('editFirstName'));
        $lastName = htmlspecialchars(Request::post('editLastName'));
        $address = htmlspecialchars(Request::post('editAddress'));
        $description = htmlspecialchars(Request::post('editDescription'));

        if (!empty($firstName) && !empty($lastName) && !empty($address) && !empty($description)) {
            $result = $this->usersModel->updatePersonalInfo($personalInfoId, $this->userId, $firstName, $lastName, $address, $description);
            if ($result) {
                SessionManager::set('success_message', 'Les informations personnelles ont été mises à jour avec succès.');
            } else {
                SessionManager::set('error_message', 'Une erreur s\'est produite lors de la mise à jour des informations personnelles.');
            }
        } else {
            SessionManager::set('error_message', 'Veuillez remplir tous les champs pour mettre à jour les informations personnelles.');
        }

        header("Location: index.php?route=create-cv");
        exit;
        
    }

    
    public function deletePersonalInfo()
    {
        $personalInfoId = (int) Request::post('id');
        $result = $this->usersModel->deletePersonalInfo($personalInfoId, $this->userId);

        if ($result) {
            SessionManager::set('success_message', 'Les informations personnelles ont été supprimées avec succès.');
        } else {
            SessionManager::set('error_message', 'Une erreur s\'est produite lors de la suppression des informations personnelles.');
        }

        header("Location: index.php?route=create-cv");
    }
}
