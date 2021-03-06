# CRM/Faktura für Kleinunternehmer/Freiberufler
Einfaches CRM und Faktura für Kleinunternehmer/Freiberufler in PHP und MySQL. Responsive Design. 

Die Anwendung verwendet einen einfachen Kontenrahmen, den man auf eigene Bedürfnisse anpassen kann. Einfach zu verstehende Eingabedialoge ermöglichen die Erfassung von Belegen und die Änderung bzw. Erweiterung des Kontenrahmens.

Achtung: Alle Preise ohne MwSt., also als Brutto-Preise!

Die Anwendung kann selbst gehostet werden.

Funktionen:
- Verwalten der Kontakte (Kunden, Lieferanten)
- Verwalten von Ein- und Ausgangsrechnungen 
- Erstellen von Angeboten
- Exportieren als Word-Dokument mit frei anpassbarer und individualisierbarer Vorlage
- Verwalten des Kontenrahmens für die EÜR
- Dashboard
- Diverse Auswertungen (Ein-/Ausgaben/EÜR)
- Exportieren als XLS-Datei (MS Excel, LibreOffice Calc)

Die Datei include/config.inc.php muss angepasst werden mit den Zugangsdaten für MySQL.

Für die Verbindung der PDF-Dokumente mit der Datenbank, muss ein Unterverzeichnis 'uploads' erstellt werden im Hauptverzeichnis der Webanwendung (hier: firma) mit 2 Unterverzeichnissen:
- firma / uploads / rechnungen
- firma / uploads / rechnungen_eingang

Die exportierten Angebote befinden sich im Unterverzeichnis 
- firma / word_export

Getestet mit folgenden Systemvoraussetzungen:
- Apache 2.4 (Webserver)
- PHP 7.2 (Erweiterung: mysqli)
- MySQL Community Server 8.0.18 

Anmerkung zum Datenschutz:
In dieser Anwendung gespeicherte Daten werden nicht an den Entwickler weitergeschickt. 
Somit bleibt alles beim Anwender, soweit die Anwendung selbst gehostet wird. Alle Daten, die Sie eingeben, verlassen nicht Ihren Computer - außer Sie veranlassen das selbst. In jedem Fall hat der Autor des Programms keinen Zugang zu Ihren Daten und er verarbeitet diese auch nicht.

Eine Google-Gruppe für Diskussionen und Anregungen wurde eingerichtet unter: https://groups.google.com/d/forum/crm-und-faktura-fr-kleinunternehmer
