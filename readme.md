# SpinTEK proovitöö

Tegemist on SpinTEK ASi jaoks tehtud testülesandega, kus on vaja ettekujutatud raamatupidajale väljasta veebipäringus mainitud aasta kõikide kuude töötasu maksmise kuupäevad. Lisaks ka kuupäev mis oleks raamatupidajale teate saatmis kuupäev, mis on vaikimisi 3 päeva enne. Süsteem arvestab et nädalavahetustele ja riigipühadele langevate palgapäevade töötasu tuleb maksta eelneval tööpäeval. 

# Tehniline kirjeldus
Projekt on üles ehitatud kerge PHP veebiteenusena ja on võõrustud apache veebiserveris. Projektil puudub andmebaas, sest ettenähtud tööks pole see vajalik. 
Projekti faili hierarhia:

 - apache -> sisaldab veebiserveri konfiguratsiooni faile ning ka dockeri jaoks Dockerfile'i
- src-> sisaldab php veebiteenust, see sisaldab:
	- controller -> juhtiv teenuse loogika ja vasutste koostamine
	- helper -> süsteemi loogikat abistavad funktsioonid (loodud trait'idena)
	- inc -> süsteemi seadistuseks olulised failid
	- tests -> süsteemi testid
	- composer.json -> testimise jaoks vajalikud PHP paketid
	- composer.lock -> testimise jaoks vajalikud PHP paketid lukustatud
	- index.php -> veebiteenuse entry point
	- index.test.php -> veebiteenuse testide entry point
	- htaccess -> apache serveri loogika jaoks oluline suunamine. Suuname ükskõik millised selle veebiteenuse poole suunatud päringud index.php-sse

## Väljund
Süsteem vastab päringutele JSON vastustena foraadis:
Õnnestunud päring:
```json
 {"status":"success","data":
 {"year":"xxxx","dates":
	 [{"kuunimi":{"paymentDate":"YYYY-MM-DD","notificationDate":"YYYY-MM-DD"}},
	 ...]
 }}
```
ja vea korral 
```json
 {"status":"error","error": "error message"}
```

# Installatsioon

## Süsteemi eeldused

 - Docker https://www.docker.com/

## Projekti käivitamine
 
Dockeri olemas olul buildida projekti image jooksutades projekti juur kaustas:
```console
 docker-compose build
```
Käsu õnnestunud lõppemise järgi on projekt valmis käivitamiseks
```console
 docker-compose up
```

## Projekti katsetamine
Peale projekti käivitamist on võimalik kontrollida projekti tööle minnest läbi veebilehitseja sisestades urli localhost. Projektil on lisa serveri seadistus, mis loob virtuaal hosti domeenile www.bookkeeping.local. Selle urli toimiseks on vaja suunata windowsi arvutidel www.bookkeeping.local ip'le 127.0.0.1. Seda on võimalik teha vajutades windowsi nupp + R ja sisestada %SystemRoot%\System32\drivers\etc ja lisades hosts faili
```console
 127.0.0.1	www.bookkeeping.local
```
## Projekti testimine
Enne projekti testimist tuleb seada ülesse testimise keskkond. Selleks tuleb konteinerisse tõmmata composeri abil PHPUnit. Composeri käivitamiseks tuleb jooksutada käsureal käsklus:
```console
 docker exec -it spinbookkeeping-custom-apache-1 composer install
```
Projekti testimiseks tuleb käivitada dockeri imageis käsklus;
```console
 docker exec -it spinbookkeeping-custom-apache-1 ./vendor/bin/phpunit --testdox tests
```
Kui millegi pärast ei suuda süsteem konteinerit leida siis jooksutada käsklus 
```console
 docker ps
```

