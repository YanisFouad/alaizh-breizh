<?php
    require_once(__DIR__."/../../models/AccommodationModel.php");
    ScriptLoader::load("acccommodations/devis.js");
?>


<div id="modal">
    <section id="modal-devis-content">
        <div>
        
            <span class="close">&times;</span>
    
            <h2>
                <span>Mon séjour</span>
                <span></span>
            </h2>
    
            <article>
                <div>
                    <div>
                        <h4>Dates:</h4>
                        <h4>12 juillet 2024 - 15 juillet 2024</h4>
                    </div>
                    <div>
                        <h4>Voyageur(s):</h4>
                        <h4>2</h4>
                    </div>
                </div>
    
                <div>
                    <div>
                        <h4>Prix nuit:</h4>
                        <h4><?php echo price_format($accomodation->get("prix_ht_logement"))?>€</h4>
                    </div>
    
                    <div>
                        <div>
                            <h4>Prix HT:</h4>
                            <h4><?php echo price_format($accomodation->get("prix_ht_logement"))?>€ x 4 nuits</h4>
                        </div>
                        <h4><?php 
                            $prix = $accomodation->get("prix_ht_logement")*4;
                            echo price_format($prix);
                        ?>€</h4>
                    </div>
    
                    <div>
                        <div>
                            <h4>TVA:</h4>
                            <h4><?php echo price_format($prix)?>€ x 10%</h4>
                        </div>
                        <h4><?php 
                            $tva = $prix*0.1;
                            $prix = $prix + $tva;
                            echo price_format($tva);
                        ?>€</h4>
                    </div>
    
                    <div>
                        <div>
                            <h4>Frais de service:</h4>
                            <h4><?php echo price_format($prix)?>€ x 1%</h4>
                        </div>
                        <h4><?php 
                            $fraisService = $prix*0.01;
                            $prix = $prix + $fraisService;
                            echo price_format($fraisService);
                        ?>€</h4>
                    </div>
                    
                    <div>
                        <div>
                            <h4>Taxe de séjour:</h4>
                            <h4 id="taxeSejour">1€ x 3 pers x 4 nuits</h4>
                        </div>
                        <h4>12€<?php 
                            $prix = $prix + 12;
                        ?></h4>
                    </div>
                    
                    <div>
                        <h4>Prix TTC:</h4>
                        <h4><?php 
                            echo price_format($prix);
                        ?>€</h4>
                    </div>
                </div>
            </article>
        </div>
        
        <button id="accepterDevis" class="primary devis">
            Accepter le devis
            <span class="mdi mdi-chevron-right"></span>

                <input type="hidden" value="<?=$_GET["id_logement"]?>" id="id_logement">
            </button>
        </div>
        
    </section>
</div>