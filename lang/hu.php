<?php
/*
 * Created on 2020.02.13.
 *
 * Hungarian labels
 *
 */
trait language {
	public $lbl_websitename = "Adatbázis mentés manager";
	public $lbl_shortwebsitename = "DB BackupMan";
	public $lbl_databasebackupsettings = "Adatbázis mentések beállítása";
	public $lbl_readmore = "Tovább";
	public $lbl_search = "Keresés";
	public $lbl_email = "Email";
	public $lbl_loginname = "Belépési név";
	public $lbl_password = "Jelszó";
	public $lbl_somethingwrong = "Fejlesztés alatt";
	public $lbl_themodeldoesnotexist = "A 'Model' nem található";
	public $lbl_thecontrollerdoesnotexist = "A 'Vezérlő' nem található";
	public $lbl_theviewdoesnotexist = "A 'Nézet' nem található";
	public $lbl_theactiondoesnotexist = "A 'Művelet' nem található";
	public $lbl_send = "Küldés";


	public $lbl_settings = "Beállítások";
	public $lbl_emailsettings = "Email beállítások";
	public $lbl_databasesettings = "Adatbázis beállítások";

	public $lbl_login = "Belépés";
	public $lbl_logout = "Kilépés";
	public $lbl_loggedout = "Kilépett";
	public $lbl_save = "Mentés";
	public $lbl_filter = "Szűrés";
	public $lbl_all = "Mindegyik";
	public $lbl_active = "Aktív";
	public $lbl_inactive = "Inaktív";
	public $lbl_previous = "Előző";
	public $lbl_next = "Következő";
	public $lbl_first = "Első";
	public $lbl_last = "Utolsó";
	public $lbl_page = "Oldal";
	public $lbl_close = "Bezár";
	public $lbl_cancel = "Mégsem";
	public $lbl_reset = "Kiürít";
	public $lbl_edit = "Szerkeszt";
	public $lbl_back = "<<- Vissza";
	public $lbl_operations = "Műveletek";
	public $lbl_pieces = "darab";
	public $lbl_piecesshort = "db";
	public $lbl_details = "Részletek";

	//ERROR MESSAGES
	public $lbl_error = "Hiba!";
	public $lbl_sessionexpired = "Lejárt a munkamenet. Kérlek jelentkezz be újra!";
	public $lbl_oldpasswordinvalid = "Hibás a megadott jelszó";
	public $lbl_emailsettingsfilenotfound = "Az email beállítások file nem található!";
	public $lbl_erroronmailsendingprocess = "Hiba a levélküldés során, kérlek próbáld meg újra!";
	public $lbl_incorrectemailformat = "Érvénytelen az 'Email' mező formátuma!";
	public $lbl_passwordrequired = "A 'Jelszó' mező kitöltése kötelező!";
	public $lbl_loginfailed = "Hibásak a megadott belépési adatok!";
	public $lbl_tokenerror = "Érvénytelen vagy lejárt token.";
	public $lbl_tokenerrorrefreshpage = "Érvénytelen vagy lejárt token. Frissítsd az oldalt!";
	public $lbl_expiredtoken = "Lejárt token";
	public $lbl_invalidtoken = "Érvénytelen token";
	public $lbl_missingtoken = "Hiányzó token";
	public $lbl_pleaserefreshthepage = "Kérlek frissítsd az oldalt";
	public $lbl_filenotfound = "A fájl nem található";
	// ne változtasd meg a változóneveket!!
	// _MENU_ sorok az oldalon,
	// _START_ első bejegyzés a listában,
	// _END_ utolsó bejegyzés a listában,
	// _TOTAL_ összes bejegyzés száma
	public $lbl_recordonpage = "_MENU_ sor egy oldalon";
	public $lbl_showingofentries = "Lista _START_ - _END_-ig a _TOTAL_ bejegyzésből";
	public $lbl_emptytable = "0 sor a 0 találatból";

	//in settings
	public $lbl_dbHost = "DB Host";
	public $lbl_dbName = "DB Name";
	public $lbl_dbUser = "DB User";
	public $lbl_dbPassword = "DB Password";
	public $lbl_dbCharset = "DB Charset";

	public $lbl_smtpIsSmtp = "SMTP szervert használsz";
	public $lbl_smtpHost = "SMTP host";
	public $lbl_smtpAuth = "SMTP autentikáció";
	public $lbl_smtpUser = "SMTP felhasználó";
	public $lbl_smtpPassword = "SMTP jelszó";
	public $lbl_smtpEncryption = "SMTP titkosítás";
	public $lbl_smtpPort = "SMTP port";
	public $lbl_mailerFrom = "Feladó email címe";
	public $lbl_mailerFromName = "Feladó neve";
	public $lbl_mailerIsHtml = "HTML levelt küldjünk?";
	public $lbl_mailerCharset = "Email karakter kódolás";
	public $lbl_yes = "Igen";
	public $lbl_no = "Nem";
	public $lbl_backupfile = "Backup fájl";
//messages
	public $lbl_sender = "Feladó";
}// end class hu
?>