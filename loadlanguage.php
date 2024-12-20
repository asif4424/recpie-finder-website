<?php
function loadLanguage() {
    // Default to English if no language is set
    $language = $_SESSION['language'] ?? 'english';

    // Map language names to file paths
    $languageFile = __DIR__ . "/languages/" . strtolower($language) . ".php";

    // Check if the file exists
    if (file_exists($languageFile)) {
        return include $languageFile;
    }

    // Debugging: Output an error if the file doesn't exist
    error_log("Language file not found: " . $languageFile);

    // Fallback to English
    $fallbackFile = __DIR__ . "/languages/english.php";
    if (file_exists($fallbackFile)) {
        return include $fallbackFile;
    } else {
        error_log("Fallback language file not found: " . $fallbackFile);
        return [];
    }
}
?>
