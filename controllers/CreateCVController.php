<?php

namespace Controllers;

use Models\Users;
use helpers\Request;
use helpers\SessionManager;

class CreateCVController extends FormController
{
    private $usersModel;

    public function __construct(Users $usersModel)
    {
        parent::__construct('create-cv', 'views/create-cv.phtml');
        $this->usersModel = $usersModel;
        SessionManager::startSession();
    }

    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userId = SessionManager::get('user_id');

            if ($userId) {
                // Vérifiez si la requête est pour la suppression d'un métier
                if (isset($_POST['deleteJobId'])) {
                    $jobId = (int) Request::post('deleteJobId');
                    $userId = SessionManager::get('user_id');
                    $this->deleteJob($jobId, $userId);
                } else {
                    // Ajout d'un nouveau métier
                    $jobTitle = htmlspecialchars(Request::post("jobTitle"));
                    $jobDate = htmlspecialchars(Request::post("jobDate"));
                    $jobDescription = htmlspecialchars(Request::post("jobDescription"));

                    // Vérifiez si les champs sont remplis
                    if (!empty($jobTitle) && !empty($jobDate) && !empty($jobDescription)) {
                        // Ajoutez le nouveau métier dans la table jobs dans la base de données
                        $result = $this->usersModel->addJob($userId, $jobTitle, $jobDate, $jobDescription);

                        // Vérifiez si l'ajout du métier a réussi
                        if ($result) {
                            SessionManager::set('success_message', 'Le métier a été ajouté avec succès.');
                        } else {
                            SessionManager::set('error_message', 'Une erreur s\'est produite lors de l\'ajout du métier.');
                        }
                    } else {
                        // Les champs ne sont pas remplis
                        SessionManager::set('error_message', 'Veuillez remplir tous les champs.');
                    }
                }
                // Redirigez vers la même page après l'ajout ou la suppression
                header("Location: index.php?route=create-cv");
                exit;
            } else {
                // ID utilisateur non disponible
                SessionManager::set('error_message', 'Utilisateur introuvable.');
            }
        }

        $userId = SessionManager::get('user_id');

        if ($userId) {
            // Récupérez les métiers de l'utilisateur depuis la table `jobs` dans la base de données
            $jobs = $this->usersModel->getJobsByUserId($userId);

            // Assignez les métiers à la vueData pour les rendre accessibles dans la vue
            $this->viewData['jobs'] = $jobs;
        } else {
            // Gérez le cas où `user_id` n'est pas disponible dans la session
            SessionManager::set('error_message', 'Utilisateur introuvable.');
        }

        // Appelez la méthode `displayPage()` de `AppController` pour afficher la page
        $this->displayPage();
    }

    // Méthode pour supprimer un métier
    private function deleteJob($jobId, $userId)
    {
        // Votre logique de suppression de métier ici
        $result = $this->usersModel->deleteJob($jobId, $userId); // Passez les deux arguments
        if ($result) {
            SessionManager::set('success_message', 'Le métier a été supprimé avec succès.');
        } else {
            SessionManager::set('error_message', 'Une erreur s\'est produite lors de la suppression du métier.');
        }
    }
}
