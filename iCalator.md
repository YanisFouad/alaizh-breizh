# Documentation des Événements iCalendar (EVENTS.md)

## Type d'Événement : Réservations Confirmées
Cet évènement représente les périodes pendant lesquelles un logement est réservé par un locataire. Chaque événement inclut des détails sur la réservation et le client.

### Propriétés
- **DTSTART**: La date et l'heure de début de l'événement.
- **DTEND**: La date et l'heure de fin de l'événement.
- **SUMMARY**: Nom du type d'évènement
- **DESCRIPTION**: Une description détaillée de l'événement, incluant les détails du client et les informations sur le bien réservé ainsi qu'un lien vert le site pour voir le détai.
- **STATUS**: Le statut de l'événement (`CONFIRMED`).

### Exemple
```plaintext
BEGIN:VEVENT
DTSTART:20240619T000000Z
DTEND:20240628T000000Z
SUMMARY:Reservation
Category:Réservations confirmées
LOCATION:12 Rue de la Paix \, Rennes 35000\, \nFrance
DESCRIPTION:Reservation du logement "Ferme rustique à la campagne"par <b> Pierre MOREL</b>\nEmail: <b>pierre.morel@gmail.com</b> Téléphone: <b>623456789</b>\n\n<a href=lav15.ventsdouest.dev/backoffice/reservation?id=15>Voir la réservation</a>
STATUS:CONFIRMED
END:VEVENT