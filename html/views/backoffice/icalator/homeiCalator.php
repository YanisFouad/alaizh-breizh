<?php

require_once("models/ICalatorModel.php");

$calendar = ICalatorModel::findById(UserSession::get()->get("id_compte"));
require_once(__DIR__ . "/../layout/header.php");

ScriptLoader::load("icalator/iCalatorHome.js");
ScriptLoader::load("icalator/calendarLink.js");

?>

<main id="icalator-home-page">
    <section class="icalator-container-home">
        <div class="icalator-title">
            <h1>Mes Calendriers</h1>
            <a href="/backoffice/calendrier/nouveau">
                <button class="backoffice tertiary">Créer un calendrier</button>
            </a>
        </div>
        <?php
        if (count($calendar) == 0) {
            echo '<p>Vous n\'avez pas encore de calendrier</p>';
        } else { ?>
            <div class="icalator-home-table-container">
                <table class="icalator-home-table">
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calendar as $cal) { ?>
                            <tr>
                                <td><a class="calendar-link" href="https://<?= $_SERVER["HTTP_HOST"] . '/icalator?key=' . $cal->get("cle_api") ?>"><?= $_SERVER["HTTP_HOST"] . '/icalator?key=' . $cal->get("cle_api") ?></a></td>
                                <td><?= date("d/m/Y", strtotime($cal->get("start_date"))) ?></td>
                                <td><?= date("d/m/Y", strtotime($cal->get("end_date"))) ?></td>
                                <td class="actions-cell">
                                    <a href="/backoffice/calendrier/voir?key=<?= $cal->get("cle_api") ?>">
                                        <button class="calendar-btn read-btn">
                                            <span class="mdi mdi-eye-circle-outline"></span>
                                            Voir
                                        </button>
                                    </a>

                                    <a href="/backoffice/calendrier/editer?key=<?= $cal->get("cle_api") ?>">
                                        <button class="calendar-btn edit-btn">
                                            <span class="mdi mdi-pencil-outline"></span>
                                            Editer
                                        </button>
                                    </a>

                                    <button class="calendar-btn delete-btn" data-key="<?= $cal->get("cle_api") ?>">
                                        <span class="mdi mdi-trash-can-outline"></span>
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
    </section>
    <section id="modal-calendar" style="display: none;">
        <div class="modal-content">
            <h2>Supprimer le calendrier</h2>
            <p>Êtes-vous sûr de vouloir supprimer ce calendrier ?</p>
            <div class="modal-btn-container">
                <button id="modal-calendar-close" class="backoffice secondary">Annuler</button>
                <button id="modal-calendar-delete" class="backoffice primary">Supprimer</button>
            </div>
        </div>
    </section>
    <div id="modal-calendar-background" style="display: none;"></div>
</main>
<?php require_once(__DIR__ . "/../../layout/footer.php");
