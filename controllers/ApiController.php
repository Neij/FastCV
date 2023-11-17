<?php

namespace Controllers;

use helpers\Request;

class ApiController extends AppController
{
    private $rapidApiKey = '01c45b4255mshe451c206960209bp14b394jsn04aef4b0d397'; 

    public function __construct()
    {
        parent::__construct("api", "api.phtml");
    }

    public function displayTranslationForm()
    {
        if (!$this->isLoggedIn) {

            header("Location: index.php?route=home");
            exit();
        }
    
        $this->displayPage();
    }
    
    public function translateUserInput()
    {

        $textToTranslate = Request::post('user_input', '');

        if (!empty($textToTranslate)) {

            $sourceLanguage = "fr";

            $targetLanguage = "en";

            if (Request::post('source_language') && Request::post('target_language')) {
                $sourceLanguage = Request::post('source_language');
                $targetLanguage = Request::post('target_language');
            }


            if ($sourceLanguage === $targetLanguage) {
                $this->viewData['error'] = "Veuillez sélectionner deux langues différentes.";
                $this->viewData['textToTranslate'] = $textToTranslate;
                $this->displayPage();
                return;
            }

            $translation = $this->callTranslationApi($sourceLanguage, $targetLanguage, $textToTranslate);

            if ($translation && isset($translation['status']) && $translation['status'] === 'success') {
                if (isset($translation['data']['translatedText'])) {

                    $translatedText = $translation['data']['translatedText'];
                } else {

                    $translatedText = 'No translation available.';
                }

                $this->viewData['textToTranslate'] = $textToTranslate;
                $this->viewData['translatedText'] = $translatedText;
            } else {

                $error = 'Error in translation.';


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

        $this->view = "api.phtml"; 
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

