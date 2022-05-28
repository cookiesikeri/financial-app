@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../knuckleswtf/pastel/pastel
php "%BIN_TARGET%" %*
