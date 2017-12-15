# Sample Manager

[Linkki sovellukseen](https://luupanu.users.cs.helsinki.fi/samplemanager/)

## Työn aihe

Sample Manager on www-sivulla toimiva [samplemanageri](https://en.wikipedia.org/wiki/Sampling_%28music%29). Sample Manager sisältää äänitiedostoja, joille voi antaa erilaisia tageja, kommentteja ja nimiä. Ohjelma auttaa käyttäjää löytämään isoistakin samplekirjastoista nopeasti kuhunkin tarkoitukseen tarvittavan samplen kuvaavien tagien avulla.

## Dokumentaatio

* [PDF](doc/dokumentaatio.pdf)

## Suunnitellut sivut

* [Etusivu](https://luupanu.users.cs.helsinki.fi/samplemanager/)
* [Kirjautumissivu](https://luupanu.users.cs.helsinki.fi/samplemanager/login)
* [Rekisteröitymissivu](https://luupanu.users.cs.helsinki.fi/samplemanager/register)
* [Listaus/muokkaussivu](https://luupanu.users.cs.helsinki.fi/samplemanager/library)
* [Lisäämissivu](https://luupanu.users.cs.helsinki.fi/samplemanager/add)
* [Käyttäjäsivu](https://luupanu.users.cs.helsinki.fi/samplemanager/profile)
* [Ylläpitäjän sivu](https://luupanu.users.cs.helsinki.fi/samplemanager/admin)

## Testaus

* Ohjelmaa voi testata käyttäjänimillä:
  * 'MikkoMies', salasana 'zalazana'
  * 'Maikkeli', salasana 'salasana'
  * 'root', salasana 'LyuxgyVHgnq068ofOIY5'
* Voit lisäksi tehdä oman käyttäjän rekisteröitymissivulla.

## Skriptit

* Ohjelmaan liittyvät vahvasti kaksi bash-skriptiä, jotka pitävät kirjaa paikallisella levyllä olevasta samplekirjastosta.
* Siirrä skriptit samplekirjastosi juurikansioon. Voit joko suorittaa ne manuaalisesti komennolla:

`./sampletracker.sh && ./jsonify.sh`

* tai automatisoida skriptien suoritus crontabissa, esim. joka tunti:

`0 * * * *       cd ~/Documents/Samples/ && ./sampletracker.sh && ./jsonify.sh`

* Skriptit on testattu OS X:llä (10.12.6), ja ne käyttävät komentoa [afinfo](https://developer.apple.com/legacy/library/documentation/Darwin/Reference/ManPages/man1/afinfo.1.html)