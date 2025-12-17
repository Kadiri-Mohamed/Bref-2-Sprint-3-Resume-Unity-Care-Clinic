<?php
class Language {
    private static $currentLang = 'fr';
    private static $translations = [];
    
    public static function init() {
        session_start();
        
        if (isset($_GET['lang'])) {
            $_SESSION['lang'] = $_GET['lang'];
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
        
        if (isset($_SESSION['lang'])) {
            self::$currentLang = $_SESSION['lang'];
        }
        
        // Charger les traductions
        self::loadTranslations();
    }
    
    public static function loadTranslations() {
        $langFile = __DIR__ . '/../lang/' . self::$currentLang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require($langFile);
        }
    }
    
    public static function getCurrent() {
        return self::$currentLang;
    }
    
    public static function translate($key) {
        return self::$translations[$key] ?? $key;
    }
    
    public static function t($key) {
        return self::translate($key);
    }
}

// Initialiser la langue
Language::init();
$lang = Language::getCurrent();
$t = function($key) {
    return Language::translate($key);
};
?>