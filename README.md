# CRM für Kleinunternehmer/Freiberufler
Einfaches CRM für Kleinunternehmer/Freiberufler in PHP und MySQL. Responsive Design. 

Die Anwendung verwendet einen einfachen Kontenrahmen, den man auf eigene Bedürfnisse anpassen kann. Einfach zu verstehende Eingabedialoge ermöglichen die Erfassung von Belegen und die Änderung bzw. Erweiterung des Kontenrahmens.

Die Anwendung kann selbst gehostet werden.

Funktionen:
- Verwalten der Kunden
- Verwalten der Lieferanten
- Verwalten der Ein- und Ausgangsrechnungen
- Verwalten des Kontenrahmens
- Dashboard
- Diverse Auswertungen (Ein-/Ausgaben)

Die Datei include/config.inc.php muss angepasst werden mit den Zugangsdaten für MySQL.
Ein Unterverzeichnis uploads muss erstellt werden im Hauptverzeichnis der Webanwendung (hier: firma)

firma
  | - uploads
      | - rechnungen
      | - rechnungen_eingang

Getestet mit folgenden Systemvoraussetzungen:
- Apache 2.4 (Webserver)
- PHP 7.2 (Erweiterung: mysqli)
- MySQL Community Server 8.0.18 

Anmerkung zum Datenschutz:
In dieser Anwendung gespeicherte Daten werden nicht an den Entwickler weitergeschickt. 
Somit bleibt alles beim Anwender, soweit die Anwendung selbst gehostet wird. Alle Daten, die Sie eingeben, verlassen nicht Ihren Computer - außer Sie veranlassen das selbst. In jedem Fall hat der Autor des Programms keinen Zugang zu Ihren Daten und er verarbeitet diese auch nicht.
