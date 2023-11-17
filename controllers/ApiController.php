<?php

namespace Controllers;

use helpers\SessionManager;

class ApiController extends AppController
{
    private $rapidApiKey = '01c45b4255mshe451c206960209bp14b394jsn04aef4b0d397'; // Remplacez par votre propre clé API

    public function __construct()
    {
        parent::__construct("api", "api.phtml");
    }

    public function displayTranslationForm()
    {
        if (!$this->isLoggedIn) {
            // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté
            header("Location: index.php?route=home");
            exit();
        }
    
        // Afficher le formulaire de traduction
        $this->displayPage();
    }
    
    public function translateUserInput()
    {
        // Récupérer la phrase à traduire depuis le formulaire
        $textToTranslate = isset($_POST['user_input']) ? $_POST['user_input'] : '';

        if (!empty($textToTranslate)) {
            // Langue source par défaut
            $sourceLanguage = "fr";

            // Langue cible par défaut
            $targetLanguage = "en";

            // Vérifier si les langues source et cible sont spécifiées dans le formulaire
            if (isset($_POST['source_language']) && isset($_POST['target_language'])) {
                $sourceLanguage = $_POST['source_language'];
                $targetLanguage = $_POST['target_language'];
            }

            // Vérifier si les langues source et cible sont différentes
            if ($sourceLanguage === $targetLanguage) {
                $this->viewData['error'] = "Veuillez sélectionner deux langues différentes.";
                $this->viewData['textToTranslate'] = $textToTranslate;
                $this->displayPage();
                return;
            }

            // Appeler l'API de traduction
            $translation = $this->callTranslationApi($sourceLanguage, $targetLanguage, $textToTranslate);

            // Vérifier si la traduction a réussi
            if ($translation && isset($translation['status']) && $translation['status'] === 'success') {
                // Vérifier si la clé 'translatedText' est présente dans la structure de la réponse
                if (isset($translation['data']['translatedText'])) {
                    // Stocker le texte traduit dans la variable
                    $translatedText = $translation['data']['translatedText'];
                } else {
                    // En cas d'absence de 'translatedText', vous pouvez utiliser une valeur par défaut
                    $translatedText = 'No translation available.';
                }

                // Ajouter le texte source et traduit directement dans la vue
                $this->viewData['textToTranslate'] = $textToTranslate;
                $this->viewData['translatedText'] = $translatedText;
            } else {
                // En cas d'échec de la traduction
                $error = 'Error in translation.';

                // Stocker le texte source dans la vue même en cas d'échec
                $this->viewData['textToTranslate'] = $textToTranslate;

                // Afficher davantage d'informations sur l'erreur
                if ($translation) {
                    $errorDetails = json_encode($translation, JSON_PRETTY_PRINT);
                    // Stocker l'erreur et les détails dans la vue
                    $this->viewData['error'] = $error;
                    $this->viewData['errorDetails'] = $errorDetails;
                }
            }
        } else {
            // Stocker un message d'erreur dans la vue
            $this->viewData['error'] = "Veuillez entrer une phrase à traduire.";
        }

        $this->view = "api.phtml"; // Assurez-vous que $this->view est correctement défini sur "api.phtml"
        $this->displayPage();
    }

    private function callTranslationApi($sourceLanguage, $targetLanguage, $textToTranslate)
    {
        // Point d'accès de l'API de traduction
        $apiEndpoint = 'https://text-translator2.p.rapidapi.com/translate';

        // Paramètres de la requête POST
        $requestData = [
            "source_language" => $sourceLanguage,
            "target_language" => $targetLanguage,
            "text" => $textToTranslate,
        ];

        // Configuration de la requête cURL en tant que POST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-RapidAPI-Host: text-translator2.p.rapidapi.com',
            'X-RapidAPI-Key: ' . $this->rapidApiKey,
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        // Modifiez cette ligne avec le chemin complet
        curl_setopt($ch, CURLOPT_CAINFO, 'C:/wamp64/www/FastCV/certificates/Cacert.pem');

        // Exécution de la requête cURL
        $response = curl_exec($ch);

        // Gestion des erreurs
        if ($response === false) {
            echo 'Erreur cURL : ' . curl_error($ch);
            curl_close($ch);
            return null;
        }

        // Fermeture de la session cURL
        curl_close($ch);

        // Décodez les données JSON avec gestion des erreurs
        $translationData = json_decode($response, true);
        if ($translationData === null && json_last_error() !== JSON_ERROR_NONE) {
            echo 'Erreur lors du décodage des données JSON : ' . json_last_error_msg();
            return null;
        }

        return $translationData;
    }

    public function displayPage()
    {
        include('views/layout-web.phtml');
    }
    
}
