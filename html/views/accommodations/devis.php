<?php
    require_once(__DIR__."/../../models/AccommodationModel.php");
    ScriptLoader::load("acccommodations/devis.js");


?>


<div id="modal">
    <section id="modal-devis-content">
        <div>
        
            <span class="close">&times;</span>
    
            <article>
                <h2>
                    <span>Mon séjour</span>
                    <span></span>
                </h2>
        
                <div>
                    <div>
                        <h4>Dates : </h4>
                        <h4 id="dates"></h4>
                    </div>
                    <div>
                        <h4>Voyageur(s) : </h4>
                        <h4 class="voyageurs"></h4>
                    </div>
                    <div>
                        <h4>Nombre de nuit(s) : </h4>
                        <h4 class="nuits"></h4>
                    </div>
                    <div>
                        <h4>Prix nuitée : </h4>
                        <h4 id="prixHT"><?php echo price_format($accomodation->get("prix_ht_logement"))?>€</h4>
                    </div>
                </div>  
            </article>

            <article>
                <h2>
                    <span>Détails du prix</span>
                    <span></span>
                </h2>

                <div>    
                    <div>
                        <div>
                            <h4>Prix du séjour HT : </h4>
                            <h4><?php echo price_format($accomodation->get("prix_ht_logement"))?>€ x <span class="nuits"></span> nuits</h4>
                        </div>
                        <h4 class="prixHTCalcul"></h4>
                    </div>
    
                    <div>
                        <div>
                            <h4>TVA du séjour : </h4>
                            <h4><span class="prixHTCalcul"></span> x 10%</h4> <!-- TODO: remplacer le 10% par la valeur en base -->
                        </div>
                        <h4 class="prixSejourTVA"></h4>
                    </div>
    
                    <div>
                        <div>
                            <h4>Frais de service : </h4>
                            <h4><span class="prixHTCalcul"></span> x 1%</h4>
                        </div>
                        <h4 class="fraisService"></h4>
                    </div>

                        
                    <div>
                        <div>
                            <h4>TVA frais de service : </h4>
                            <h4><span class="fraisService"></span> x 20%</h4>
                        </div>
                        <h4 id="tvaFraisService"></h4>
                    </div>
                    
                    <div>
                        <div>
                            <h4>Taxe de séjour : </h4>
                            <h4>1€ x <span class="voyageurs"></span> pers x <span class="nuits"></span> nuits</h4>
                        </div>
                        <h4 id="taxeSejour"></h4>
                    </div>
                </div>
                
                <div id="PrixTTC">
                    <h4>Prix à payer : </h4>
                    <h4 id="prixTotal"></h4>
                </div>
            </article>
        </div>
        
        <button onclick="handleDevis()" class="primary devis">
            Accepter le devis
            <span class="mdi mdi-chevron-right"></span>

            <input type="hidden" value="<?=$_GET["id_logement"]?>" id="id_logement">
        </button>
        
    </section>
</div>