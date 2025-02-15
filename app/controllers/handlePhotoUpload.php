<?php
    /**
     * Gère le téléversement de la photo de profil.
     * @param array $file Données du fichier uploadé
     * @return string | false Chemin de la photo ou false en cas d'erreur
     */
    function handlePicture($file) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return 'default.png'; // Aucun fichier téléversé
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false; // Erreur lors du téléversement
        }

        // Vérifier le type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false; // Type de fichier non autorisé
        }

        // Vérifier la taille du fichier (par exemple, 2 Mo maximum)
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        if ($file['size'] > $maxSize) {
            return false; // Fichier trop volumineux
        }

        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        return uniqid() . '.' . $extension;
    }

    function uploadPictureTo($file, $path) {
        $fileName = handlePicture($file);
        if (!$fileName) {
            return $fileName; // Erreur lors du téléversement
        }

        move_uploaded_file($file['tmp_name'], BASE_PATH . $path . $fileName);

        return $path . $fileName;
    }

?>