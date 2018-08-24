# telemach-opozorilo
Telemach ne omogoča pošiljanja opozorila, ko prekoračiš zakupljene minute. Zato sem naredil program, ki gleda portal moj telemach in te opozori, ko minute pridejo k kraju.

## Uporaba programa
Za uporabo programa potrebujete strežnik z nameščenim PHP, ki ima omogočeno `mail()` funkcijo. 
Naredite datoteko __userInfo.php__ in v njej nastavite spremenljivke:
* `$username` vaše uporabniško ime za portal moj telemach
* `$password` vaše geslo
* `$email` email na katerege želite prejemati obvestila
* `$prag` pri koliko minutah vas program obvesti

V konfiguraciji __crontab__ nastavite, da se program __getData.php__ zažene vsaj enkrat na dan.

## Delovanje programa
Program deluje tako, da se na določen čas prijavi na portal Moj Telemach in pogleda koliko minut imate še na voljo. Ko so te minute manjše od nastavljene vrednosti, vam pošlje email.
