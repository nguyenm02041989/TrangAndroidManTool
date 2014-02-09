<?php
$AppConfig = AppConfig::GetInstance();
$AppConfig->set("m_DBHost", "localhost");
$AppConfig->set("mDBUser", "{PUT YOUR USERNAME HERE}");
$AppConfig->set("mDBPassword", "{DATABASE PASSWORD}");
$AppConfig->set("mDBDatabase", "gcm_users");
$AppConfig->set("mGoogleApiKey", "Your GOOGLE SERVER API KEY");

// Set encryption key
$_AppConfig->set("m_EncryptionKey", "ptOe0tFWB6Xq39O8YXUl4cFYjSnB24tm");

// Initialize the database singleton
$_Db = Database::GetInstance($_AppConfig);

// Initialize the front index controller
$_FrontCtrl = FrontIndexController::GetInstance();
$_FrontCtrl->FilterInput();


